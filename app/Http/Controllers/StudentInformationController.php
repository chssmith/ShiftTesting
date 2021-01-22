<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\APMap;
use App\APExams;
use App\IBMap;
use App\IBExams;
use App\DEMap;
use App\DualEnrollmentCourses;
use App\PERC;
use App\AdditionalForms;
use App\Students;
use App\CompletedSections;
use App\Education;
use App\DatamartStudent;
use App\DatamartPhones;
use App\DatamartAddress;
use App\GenericAddress;
use RCAuth;

use App\ODS\CitizenshipInformation as ODSCitizenshipInformation;
use App\ODS\VisaTypeMap as ODSVisaTypeMap;
use App\ODS\USResidence as ODSUSResidence;
use App\ODS\MedicalData as ODSMedicalData;

class StudentInformationController extends Controller
{
	public function __construct(){
      $this->middleware("force_login");
			$this->middleware("populate_dependencies");
	}

	public function index(Students $student, CompletedSections $completed_sections){
		$user              = RCAuth::user();
		$student           = $student->load("admit_status");

		$returning_student = !empty($student->admit_status) && $student->admit_status->X_APP_NEW != "NEW";
		$student_type      = ((!$returning_student && !empty($student->admit_status)) ? $student->admit_status->X_APP_ADMIT_STATUS : "other");

		$form_perc         = sprintf("RSI%s", \Carbon\Carbon::now()->format("y"));

		$percs             = PERC::where("rcid", $student->RCID)->get();
		$ods_percs         = \App\ODS\PERC::where("fkey_rcid", $student->RCID)->get();
		$cleared_percs     = $ods_percs->filter(function ($item) { return !empty($item->end_date); });
		$open_percs        = $ods_percs->filter(function ($item) { return empty($item->end_date); })->pluck("perc")->unique();

		$submitted         = !$percs->where("perc", $form_perc)->isEmpty() && $ods_percs->where("perc", $form_perc)->isEmpty();
		$completed         = !$cleared_percs->where("perc", $form_perc)->isEmpty();

		$percs             = $percs->pluck("perc")->union($cleared_percs->pluck("perc"))->unique();

		$sections['Personal Information']     	 = ['status' => $completed_sections->personal_information,			      'link' => action("StudentForms\PersonalInformationController@show")];
		$sections['Address Information']      	 = ['status' => $completed_sections->address_information,			        'link' => action("StudentForms\AddressInformationController@show")];
		$sections['Residence Information']   	   = ['status' => $completed_sections->residence_information,			      'link' => action("StudentForms\ResidenceInformationController@show")];
		$sections['Citizenship Information'] 	   = ['status' => $completed_sections->citizenship_information,		      'link' => action("StudentForms\CitizenshipInformationController@show")];
		$sections['Allergy Information']   	   	 = ['status' => $completed_sections->allergy_information,			        'link' => action("StudentForms\AllergyInformationController@show")];
		$sections['Medical Information']   	   	 = ['status' => $completed_sections->medical_information,			        'link' => action("StudentForms\MedicalInformationController@show")];
		$sections['Missing Person']			 	       = ['status' => $completed_sections->missing_person,				          'link' => action("StudentForms\MissingPersonController@show")];
		$sections['Emergency Contact']		 	     = ['status' => $completed_sections->emergency_information,			      'link' => action("StudentForms\EmergencyContactController@show")];
		$sections['Non-Emergency Contact']   	   = ['status' => $completed_sections->non_emergency_contact,			      'link' => action("StudentInformationController@nonEmergency")];
		$sections['Independent Student']     	   = ['status' => $completed_sections->independent_student,			        'link' => action("StudentInformationController@independentStudent")];
		$sections['Parent/Guardian Information'] = ['status' => $completed_sections->parent_and_guardian_information, 'link' => action("StudentForms\GuardianInformationController@show")];

		$codes = $ods_percs->pluck("perc");

		$additional_forms = AdditionalForms::orderBy("due_date")->orderBy("title")->get()->filter(function ($item) use ($student_type, $codes) {
			return $item->$student_type && ($codes->contains($item->getPerc()));
		});

		return view('index', compact('sections', "student", "completed_sections", "additional_forms", "percs", "submitted", "completed"));
	}

	public function getMissingMessages (Students $student) {
		$messages = collect();

		$personal_information    = new StudentForms\PersonalInformationController;
		$address_information     = new StudentForms\AddressInformationController;
		$residence_information   = new StudentForms\ResidenceInformationController;
		$citizenship_information = new StudentForms\CitizenshipInformationController;
		$allergy_information     = new StudentForms\AllergyInformationController;
		$medical_information     = new StudentForms\MedicalInformationController;
		$missing_person          = new StudentForms\MissingPersonController;
		$emergency_contact       = new StudentForms\EmergencyContactController;
		$guardian_information    = new StudentForms\GuardianInformationController;

		$messages['Personal Information']     	 = $personal_information->getMissingInformation($student);
		$messages['Address Information']      	 = $address_information->getMissingInformation($student);
		$messages['Residence Information']   	   = $residence_information->getMissingInformation($student);
		$messages['Citizenship Information'] 	   = $citizenship_information->getMissingInformation($student);
		$messages['Allergy Information']   	   	 = $allergy_information->getMissingInformation($student);
		$messages['Medical Information']   	   	 = $medical_information->getMissingInformation($student);
		$messages['Missing Person']			 	       = $missing_person->getMissingInformation($student);
		$messages['Emergency Contact']		 	     = $emergency_contact->getMissingInformation($student);
		$messages['Parent/Guardian Information'] = $guardian_information->getMissingInformation($student);

		return view()->make("partials.missing_data", compact("messages"));
	}

	//*************************************************************************************************************
	// BEGIN Non-Emergency FORMS
	//*************************************************************************************************************
	public function nonEmergency(Students $student){
		$user          = RCAuth::user();

		return view('non_emergency', compact('student'));
	}

	public function nonEmergencyUpdate(Request $request, Students $student, CompletedSections $completed_sections){
		$user          = RCAuth::user();

		$student->non_emergency = !empty($request->non_emergency);
		$student->photo_consent = $request->has("photo_consent");
		$student->save();

		$completed_sections->non_emergency_contact = 1;
		$completed_sections->save();

		return redirect()->action('StudentInformationController@independentStudent');
	}
	//*************************************************************************************************************
	// END Non-Emergency FORMS
	//*************************************************************************************************************


	//*************************************************************************************************************
	// BEGIN Independent Student FORMS
	//*************************************************************************************************************
	public function independentStudent(Students $student){
		return view('independent_student', compact('student'));
	}

	public function independentStudentUpdate(Request $request, Students $student, CompletedSections $completed_sections){
		$user          = RCAuth::user();

		$student->independent_student = !empty($request->independent_student);
		$student->updated_by          = $user->rcid;
		$student->save();

		StudentForms\GuardianInformationController::completedParentInfo($student, $completed_sections);

		$completed_sections->independent_student = !is_null($student->independent_student);
		$completed_sections->updated_by          = $user->rcid;
		$completed_sections->save();

		return redirect()->action('StudentForms\GuardianInformationController@show');
	}
	//*************************************************************************************************************
	// END Independent Student FORMS
	//*************************************************************************************************************

	// Pre :
	// Post: checks that the emergency forms are completed
	public function showFinancialAcceptance (Students $student) {
		return view()->make("financial", compact("student"));
	}

	public function completeFinancialAcceptance (Request $request, Students $student) {
		$student->financial_acceptance = $request->has("acknowledge");
		$student->updated_by = \RCAuth::user()->rcid;
		$student->save();

		$perc = PERC::firstOrNew(['rcid' => $student->RCID, 'perc' => sprintf('BFA%s', \Carbon\Carbon::now()->format("y"))],
														 ['created_by' => \RCAuth::user()->rcid, 'created_at' => \Carbon\Carbon::now(),
														 	'updated_by' => \RCAuth::user()->rcid]);

		if ($student->financial_acceptance) {
			$perc->save();
		} else if (!empty($perc->id)) {
			$perc->deleted_by = \RCAuth::user()->rcid;
			$perc->save();
			$perc->delete();
		}

		return redirect()->action("StudentInformationController@index");
	}

	//*************************************************************************************************************
	// END Financial Acceptance
	//*************************************************************************************************************

	//*************************************************************************************************************
	// BEGIN Academic Integrity & Student Conduct
	//*************************************************************************************************************
	public function showAcademicIntegrityStatement (Students $student) {
		return view()->make("AI", compact("student"));
	}

	public function completeAcademicIntegrityStatement (Request $request, Students $student) {
		$student->ai_and_student_conduct = $request->has("acknowledge");
		$student->updated_by             = \RCAuth::user()->rcid;
		$student->save();

		$perc = PERC::firstOrNew(['rcid' => $student->RCID, 'perc' => sprintf('AIC%s', \Carbon\Carbon::now()->format("y"))],
														 ['created_by' => \RCAuth::user()->rcid, 'created_at' => \Carbon\Carbon::now(),
															'updated_by' => \RCAuth::user()->rcid]);

		if ($student->ai_and_student_conduct) {
			$perc->save();
		} else if (!empty($perc->id)) {
			$perc->deleted_by = \RCAuth::user()->rcid;
			$perc->save();
			$perc->delete();
		}

		return redirect()->action("StudentInformationController@index");
	}

	private function checkCompletion (Request $request, Students $student, CompletedSections $completed_sections) {
		return true;
	}
	//*************************************************************************************************************
	// END Academic Integrity & Student Conduct
	//*************************************************************************************************************

	//*************************************************************************************************************
	// BEGIN Title IX Acceptance
	//*************************************************************************************************************
	public function showTitleIXAcceptance (Students $student) {
		return view()->make("title_ix", compact("student"));
	}

	public function completeTitleIXAcceptance (Request $request, Students $student) {
		$student->title_ix_acceptance = $request->has("acknowledge");
		$student->updated_by = \RCAuth::user()->rcid;
		$student->save();

		$perc = PERC::firstOrNew(['rcid' => $student->RCID, 'perc' => sprintf('SMP%s', \Carbon\Carbon::now()->format("y"))],
														 ['created_by' => \RCAuth::user()->rcid, 'created_at' => \Carbon\Carbon::now(),
														 	'updated_by' => \RCAuth::user()->rcid]);

		if ($student->title_ix_acceptance) {
			$perc->save();
		} else if (!empty($perc->id)) {
			$perc->deleted_by = \RCAuth::user()->rcid;
			$perc->save();
			$perc->delete();
		}

		return redirect()->action("StudentInformationController@index");
	}

	//*************************************************************************************************************
	// END Title IX Acceptance
	//*************************************************************************************************************

	//*************************************************************************************************************
	// BEGIN COVID-19 Acceptance
	//*************************************************************************************************************
	public function showCovidForm (Students $student) {
		return view()->make("covid_pledge", compact("student"));
	}

	public function completeCovidForm (Request $request, Students $student) {
		$student->covid_acceptance = $request->has("acknowledge");
		$student->updated_by       = \RCAuth::user()->rcid;
		$student->save();

		$perc = PERC::firstOrNew(['rcid' => $student->RCID, 'perc' => sprintf('RCCCP')],
														 ['created_by' => \RCAuth::user()->rcid, 'created_at' => \Carbon\Carbon::now(),
														 	'updated_by' => \RCAuth::user()->rcid]);

		if ($student->covid_acceptance) {
			$perc->save();
		} else if (!empty($perc->id)) {
			$perc->deleted_by = \RCAuth::user()->rcid;
			$perc->save();
			$perc->delete();
		}

		return redirect()->action("StudentInformationController@index");
	}

	//*************************************************************************************************************
	// END Title IX Acceptance
	//*************************************************************************************************************

	//*************************************************************************************************************
	// BEGIN Academic Achievement
	//*************************************************************************************************************
	public function showAcademicAchievement (Request $request, Students $student) {
		$ap_exams = APExams::orderBy("name")->with(["map" => function ($query) use ($student) {
			$query->where("rcid", $student->RCID);
		}])->get();

		$ib_exams = IBExams::orderBy("name")->with(["map" => function ($query) use ($student) {
			$query->where("rcid", $student->RCID);
		}])->get();

		$de_courses = DualEnrollmentCourses::orderBy("name")->with(["map" => function ($query) use ($student) {
			$query->where("rcid", $student->RCID);
		}])->get();

		return view()->make("academic_achievement.index", compact("ap_exams", "ib_exams", "de_courses"));
	}

	public function storeAcademicAchievement (Request $request, Students $student) {
		APMap::where("rcid", $student->RCID)->update(["deleted_at" => Carbon::now()]);
		IBMap::where("rcid", $student->RCID)->update(["deleted_at" => Carbon::now()]);
		DEMap::where("rcid", $student->RCID)->update(["deleted_at" => Carbon::now()]);

		foreach ($request->input("ap_exams", []) as $exam) {
			$map = new APMap;
			$map->rcid         = $student->RCID;
			$map->fkey_ap_exam = $exam;
			$map->save();
		}

		foreach ($request->input("ib_exams", []) as $exam) {
			$map = new IBMap;
			$map->rcid         = $student->RCID;
			$map->fkey_ib_exam = $exam;
			$map->save();
		}

		foreach ($request->input("de_courses", []) as $course) {
			$map = new DEMap;
			$map->rcid                        = $student->RCID;
			$map->fkey_dual_enrollment_course = $course;
			$map->save();
		}

		$perc = PERC::firstOrNew(['rcid' => $student->RCID, 'perc' => sprintf('ACADF', \Carbon\Carbon::now()->format("y"))],
														 ['created_by' => \RCAuth::user()->rcid, 'created_at' => \Carbon\Carbon::now(),
															'updated_by' => \RCAuth::user()->rcid]);
		$perc->save();

		return redirect()->action("StudentInformationController@index");
	}
	//*************************************************************************************************************
	// END Academic Achievement
	//*************************************************************************************************************


	public function confirmationUpdate(Request $request, Students $student, CompletedSections $completed_sections){
		$actually_done = (
				$completed_sections->personal_information &&
				$completed_sections->address_information &&
				$completed_sections->residence_information &&
				$completed_sections->citizenship_information &&
				$completed_sections->allergy_information &&
				$completed_sections->medical_information &&
				$completed_sections->missing_person &&
				$completed_sections->emergency_information &&
				$completed_sections->non_emergency_contact &&
				$completed_sections->independent_student &&
				$completed_sections->parent_and_guardian_information
			);

			$ods_percs = \App\ODS\PERC::where("fkey_rcid", $student->RCID)->whereNull("end_date")->where("perc", "LIKE", "%RSI%")->get()->pluck("perc");

			foreach($ods_percs as $code) {
				$perc      = PERC::firstOrCreate(['rcid' => $student->RCID,
																					'perc' => $code],
																 			 	 ['created_by' => \RCAuth::user()->rcid,
																				 	'created_at' => \Carbon\Carbon::now(),
																		 	  	'updated_by' => \RCAuth::user()->rcid]);
				$perc->save();
			}
			$message = "Submitted successfully.  Please check your Roanoke College email account for confirmation.";
			if (!$actually_done) {
				$perc->deleted_by = \RCAuth::user()->rcid;
				$perc->save();
				$perc->delete();
				$message = "Missing information";
			}

			//TODO: Send Email
			try {
				$vpb_student = \App\User::find($student->RCID);
				\App\EmailQueue::sendEmail($vpb_student->CampusEmail, "Student Information Form has been submitted", view()->make("email.success")->render());
			} catch (\Exception $e) {
				$message = "We cannot locate your email address.  Please contact the Registrar's office at (540) 375-2211 for confirmation of your submission";
			}

			return redirect()->action("StudentInformationController@index")->with("message", $message);
	}
}
