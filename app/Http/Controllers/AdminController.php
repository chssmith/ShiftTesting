<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Students;
use App\PhoneMap;
use App\RaceMap;
use App\Races;
use App\MaritalStatuses;
use App\Address;
use App\States;
use App\HealthConcerns;
use App\OtherConcerns;
use App\StudentConcerns;
use App\MilitaryOptions;
use App\Medications;
use App\MedicationAllergies;
use App\InsectAllergies;
use App\Countries;
use App\CitizenshipInformation;
use App\Counties;
use App\USResidence;
use App\VisaTypes;
use App\VisaTypeMap;
use App\GuardianInfo;
use App\EmergencyContact;
use App\EmploymentInfo;
use App\CompletedSections;
use App\Education;
use App\DatamartStudent;
use App\DatamartPhones;
use App\DatamartAddress;
use RCAuth;

class AdminController extends Controller
{

	public function __construct(){
      $this->middleware("force_login");
			if (!\Storage::exists("pdfs")) {
				\Storage::makeDirectory("pdfs");
			}
	}

	public function index(){
		return view('admin');
	}

	public function markStudentsProcessed() {
		Students::where("student_processed", 0)->where("student_printed", 1)
							->finished()->update(["student_processed" => 1,
																		"updated_by" => RCAuth::user()->rcid]);
		return redirect()->action("AdminController@index");
	}

	public function markParentsProcessed() {
		Students::where("parents_processed", 0)->where("parents_printed", 1)
							->finished()->update(["parents_processed" => 1,
																		"updated_by" => RCAuth::user()->rcid]);
		return redirect()->action("AdminController@index");
	}

	public function changedStudents(){
		ini_set('max_execution_time', 300);
		$user = RCAuth::user();

		Students::finished()->where('student_processed', "0")->update(["student_printed" => 1, 'updated_by' => $user->rcid]);

		$all_changed = Students::with(['visa', 'home_address', 'billing_address', 'local_address', 'ods_student.visa', 'ods_citizenship',
																	 'datamart_home_address' => function ($query) {	$query->with(["state", "country"]);	}, 'datamart_local_address' => function ($query) {	$query->with(["state", "country"]);	}, 'datamart_billing_address.state' => function ($query) {	$query->with(["state", "country"]);	}])->
														 finished()->where("student_processed", "0")->get()->keyBy("RCID");
		$storage_path = storage_path();

		$count = 1;
		foreach($all_changed as $student){

			$report_string = view()->make("reports.student_report", ['student'=>$student])->render();
			$pdf = \PDF::loadHtml($report_string);
			$new_page = $pdf->output();
			\Storage::put("pdfs/page" . $count . ".pdf", $new_page);
			// file_put_contents($storage_path.'/pdfs/page'.$count, $new_page);
			$count++;
		}

		$merger = \PDFMerger::init();

		for($index = 1; $index < $count; $index++){
			$merger->addPathToPDF(\Storage::path('/pdfs/page'.$index.".pdf"), 'all', 'P');
		}

			$merger->setFileName('StudentChanges.pdf');
			$merger->merge();
			$merger->inline();


		//Switch to PDF MERGER

		 //return view('reports.student_report', compact('all_changed'));
	}

	public function lookupMissingInfo (Request $request) {
		$request->validate([
			'student' => "required|numeric"
		]);

		$student  = Students::find($request->student);
		$sic      = new StudentInformationController;
		$messages = $sic->getMissingMessages($student);

		return view()->make("admin.missing_lookup", compact("messages", "student"));
	}

	public function changedParentInfo(){
		set_time_limit(0);
		$user = RCAuth::user();
		Students::finished()->where("parents_processed", "0")->update(['parents_printed' => 1, 'updated_by' => $user->rcid]);
		$all_changed = Students::with(['parents.employment', 'parents.guardian_type', 'parents.country',
																	 'parents.ods_guardian.employment.country', 'parents.ods_guardian.country'])
													   ->finished()->where("parents_processed", "0")->get();
		$count = 1;
		foreach($all_changed as $student){
			$report_string = view()->make("reports.parent_report", ['student'=>$student])->render();
			$pdf = \PDF::loadHtml($report_string)->setPaper('a4');
			$new_page = $pdf->output();
			\Storage::put('pdfs/page' . $count . '.pdf', $new_page);
			$count++;
		}

		$merger = \PDFMerger::init();

		for($index = 1; $index < $count; $index++){
			$merger->addPathToPDF(\Storage::path('/pdfs/page'.$index.".pdf"), 'all', 'P');
		}
		try {
			$merger->setFileName('ParentChanges.pdf');
			$merger->merge();
			$merger->inline();
		} catch (\Exception $e) {
			return "No records to export";
		}
	}


	public function exportAcademicAchievementCSV (Request $request) {
		$ap_exams   = \App\APExams::orderBy("name")->pluck("colleague_code");
		$ib_exams   = \App\IBExams::orderBy("name")->pluck("colleague_code");
		$de_courses = \App\DualEnrollmentCourses::orderBy("name")->pluck("colleague_code");

		$headings   = $ap_exams->merge($ib_exams)->merge($de_courses);

		$students   = Students::with(["ap_exams", "ib_exams", "de_courses"])->get()
										->map(function ($item) {
												$item->courses   = $item->ap_exams->merge($item->ib_exams)->merge($item->de_courses)->keyBy("colleague_code");
												return $item;
										});
		$filename   = sprintf("StudentInformationForms_AcademicAchievement_%s.csv", \Carbon\Carbon::now()->format("Ydm"));
		return response(view()->make("academic_achievement.csv", compact("students", "headings"))->render(), 200)
							->header("Content-Type",        "application/csv")
							->header("Content-Disposition", "attachment; filename=\"$filename\"");
	}

}
