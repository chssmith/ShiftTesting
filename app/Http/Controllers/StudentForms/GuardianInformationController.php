<?php

namespace App\Http\Controllers\StudentForms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use RCAuth;
use App\Students;
use App\User;
use App\CompletedSections;

use App\GuardianInfo;
use App\GuardianRelationshipTypes;
use App\GenericAddress;
use App\Education;
use App\MaritalStatuses;
use App\States;
use App\Countries;
use App\EmploymentInfo;

class GuardianInformationController extends SectionController
{
  /**
   * Display the form necessary for this section
   *
   * @return View The view of this form section to display
   **/
  public function show (Students $student, User $vpb_user, CompletedSections $completed_sections) {
    $user      = RCAuth::user();
		$guardians  = GuardianInfo::where('student_rcid', $user->rcid)->with("guardian_type")->get();
		return view('parent_guardian_info', compact('guardians'));
  }

  public function deleteGuardian(Students $student, CompletedSections $completed_sections, $id){ //TODO
		$user     = RCAuth::user();

		$guardian = GuardianInfo::where('student_rcid', $user->rcid)->where('id', $id)->first();
		if(!empty($guardian)){
			$user = RCAuth::user();
			$guardian->updated_by = $user->rcid;
			$guardian->deleted_by = $user->rcid;
			$guardian->save();
			$guardian->delete();
		}

		self::completedParentInfo($student, $completed_sections);

		return redirect()->action('StudentForms\GuardianInformationController@show');
	}

  public function guardianPersonalShow (Students $student, $id = NULL) {
		$user     = RCAuth::user();

		$guardian           = GuardianInfo::where('id', $id)->where('student_rcid', $user->rcid)->firstOrNew([]);
		$relationship_types = GuardianRelationshipTypes::all();
		$address            = GenericAddress::fromGuardianInfo($guardian);
		$education 					= Education::orderBy("id")->get();
		$marital   					= MaritalStatuses::all();
		$states    					= States::all();
		$countries 					= Countries::all();

		return view('guardian_verification', compact('guardian', 'relationship_types', 'address', 'marital',
                                                 'states', 'id', 'education', 'countries'));
  }

  public function guardianPersonalStore (Request $request, Students $student, CompletedSections $completed_sections, $id = null) {
		$user     = RCAuth::user();

		$guardian = GuardianInfo::where("id", $id)->firstOrNew(['student_rcid' => $student->RCID], ['created_by' => $student->RCID]);

		$guardian->first_name     	   	 = $request->first_name;
		$guardian->nick_name      	   	 = $request->nick_name;
		$guardian->middle_name    	   	 = $request->middle_name;
		$guardian->last_name      	   	 = $request->last_name;
		$guardian->fkey_marital_status 	 = $request->marital_status;
		$guardian->email          	   	 = $request->email;
		$guardian->home_phone     	   	 = $request->home_phone;
		$guardian->cell_phone     	   	 = $request->cell_phone;
		$guardian->fkey_CountryId	     	 = $request->country;
		if ($request->country == GenericAddress::US_ID) {
			$guardian->Address1      	     	 = $request->address[0];
			$guardian->Address2       	   	 = $request->address[1];
			$guardian->City           	   	 = $request->city;
			$guardian->fkey_StateCode	     	 = $request->state;
			$guardian->PostalCode     	   	 = $request->zip;
			$guardian->international_address = null;
		} else {
			$guardian->Address1      	     	 = null;
			$guardian->Address2       	   	 = null;
			$guardian->City           	   	 = null;
			$guardian->fkey_StateCode	     	 = null;
			$guardian->PostalCode     	   	 = null;
			$guardian->international_address = $request->input("international_address", "");
		}
		$guardian->joint_mail1    	   	 = $request->joint1;
		$guardian->joint_mail2    	   	 = $request->joint2;
		$guardian->fkey_education_id   	 = $request->education;
		$guardian->updated_by          	 = $user->rcid;
		$guardian->reside_with         	 = isset($request->reside_with);
		$guardian->claimed_dependent   	 = isset($request->dependent);

		$guardian->relationship   	   	          = $request->relationship;
		$guardian->relationship_other_description = NULL;
		if ($guardian->relationship == "O") {
			$guardian->relationship_other_description = $request->input("relationship_other", NULL);
		}

		$guardian->save();

		self::completedParentInfo($student, $completed_sections);

		return redirect()->action('StudentForms\GuardianInformationController@guardianEmploymentShow', ['id' => $guardian->id]);
	}

  public function guardianEmploymentShow (Students $student, $id = NULL) {
		$user	     = RCAuth::user();
		$guardian  = GuardianInfo::where('id', $id)->where('student_rcid', $user->rcid)->first();
		$states		 = States::all();
		$countries = Countries::all();

		if(!empty($guardian)){
			$employment = EmploymentInfo::where('fkey_guardian_id', $guardian->id)->firstOrNew([]);
			$address    = GenericAddress::fromEmploymentInfo($employment);
			return view('employment_info', compact('student','guardian','employment', 'address', 'states', 'countries'));
		}else{
			return redirect()->action('StudentInformationController@index');
		}
	}

	public function guardianEmploymentStore (Request $request, Students $student, CompletedSections $completed_sections, $id) {
		$user 			 = RCAuth::user();

		$employment_info = EmploymentInfo::firstOrNew(['fkey_guardian_id' => $id], ['created_by' => $user->rcid]);

		$employment_info->employer_name   = $request->employer_name;
		$employment_info->position        = $request->position;
		$employment_info->business_number = $request->business_number;
		$employment_info->business_email  = $request->business_email;
		$employment_info->fkey_CountryId  = $request->country_business;

		if ($employment_info->fkey_CountryId == GenericAddress::US_ID) {
			$employment_info->Street1         			= $request->input("address_business", ["", ""])[0];
			$employment_info->Street2         			= $request->input("address_business", ["", ""])[1];
			$employment_info->city 			      			= $request->city_business;
			$employment_info->fkey_StateCode  			= $request->state_business;
			$employment_info->postal_code     			= $request->zip_business;
			$employment_info->international_address = null;
		} else {
			$employment_info->Street1         			= null;
			$employment_info->Street2         			= null;
			$employment_info->city 			      			= null;
			$employment_info->fkey_StateCode  			= null;
			$employment_info->postal_code     			= null;
			$employment_info->international_address = $request->international_address_business;
		}
		$employment_info->updated_by	    = $user->rcid;

		$employment_info->save();

		self::completedParentInfo ($student, $completed_sections);

		return redirect()->action('StudentForms\GuardianInformationControllerr@show');
	}

  public static function completedParentInfo (Students $student, CompletedSections $completed_sections){
		$user       = RCAuth::user();
		$guardians  = GuardianInfo::where('student_rcid', $student->RCID)->get();
		$complete   = $guardians->filter(function ($item) {
										return $item->complete();
									}, true)->count();
		$incomplete = $guardians->filter(function ($item) {
										return !$item->complete();
									})->count();

		$completed_sections->parent_and_guardian_information = $incomplete == 0 && ($student->independent_student || $complete > 0);
		$completed_sections->updated_by = $user->rcid;
		$completed_sections->save();
	}

  /**
   * Store the user submission for this section.
   *
   * @return Redirect A redirect to the next page in the form sequence
   **/
  public function store (Request $request, Students $student, CompletedSections $completed_sections) {}

  /**
   * Get details pertaining to missing data for this section.
   *
   * @param Students $student, The student to check the missing information for.
   * @return Collection List of strings pertaining to missing data.
   **/
  public function getMissingInformation (Students $student) {
    $guardians  = GuardianInfo::where('student_rcid', $student->RCID)->get();

    $messages   = collect();
    if ($guardians->count() == 0 && !$student->independent_student) {
      $messages[] = "At least one parent/guardian is required for non-independent students";
    }

    $messages   = $guardians->reduce(function ($collector, $item) {
      return $collector->merge($item->getMissingInformation());
    }, $messages);

    return $messages;
  }

  public function getGuardianVerification (Students $student, $id) {
		$guardian           = GuardianInfo::where('id', $id)->with(["employment.country", "employment.state", "education", "marital_status", "state", "country"])->first();
		$guardian_address   = GenericAddress::fromGuardianInfo($guardian);
		$employment_address = !empty($guardian->employment) ? GenericAddress::fromEmploymentInfo($guardian->employment) : new GenericAddress;
		return view()->make("partials.guardian_confirm_modal_contents", compact("guardian", "guardian_address", "employment_address"));
	}
}
