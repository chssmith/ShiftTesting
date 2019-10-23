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

class StudentInformationController extends Controller
{

	public function __construct(){
      $this->middleware("force_login");
			$this->middleware("populate_dependencies");
	}

	public function index(){
		$user     = RCAuth::user();

		$completed_sections = CompletedSections::where('fkey_rcid', $user->rcid)->first();

		$sections['Personal Information']     	 = ['status' => $completed_sections->personal_information,			      'link' => action("StudentInformationController@personalInfo")];
		$sections['Address Information']      	 = ['status' => $completed_sections->address_information,			        'link' => action("StudentInformationController@addressInfo")];
		$sections['Residence Information']   	   = ['status' => $completed_sections->residence_information,			      'link' => action("StudentInformationController@residenceInfo")];
		$sections['Citizenship Information'] 	   = ['status' => $completed_sections->citizenship_information,		      'link' => action("StudentInformationController@citizenInfo")];
		$sections['Allergy Information']   	   	 = ['status' => $completed_sections->allergy_information,			        'link' => "allergy_info"];
		$sections['Medical Information']   	   	 = ['status' => $completed_sections->medical_information,			        'link' => "medical_info"];
		$sections['Missing Person']			 	       = ['status' => $completed_sections->missing_person,				          'link' => "missing_person"];
		$sections['Emergency Contact']		 	     = ['status' => $completed_sections->emergency_information,			      'link' => "emergency_contact"];
		$sections['Non Emergency Contact']   	   = ['status' => $completed_sections->non_emergency_contact,			      'link' => "non_emergency"];
		$sections['Independent Student']     	   = ['status' => $completed_sections->independent_student,			        'link' => "independent_student"];
		$sections['Parent/Guardian Information'] = ['status' => $completed_sections->parent_and_guardian_information, 'link' => "parent_info"];

		return view('index', compact('sections'));
	}

	public function personalInfo(Students $student, \App\User $vpb_user){
		$user       = RCAuth::user();
		$phones     = PhoneMap::where("RCID", $user->rcid)->whereIn("fkey_PhoneTypeId", [1, 3])->get()->keyBy("fkey_PhoneTypeId");
		$cell_phone = $phones->get(3);
		$home_phone = $phones->get(1);
		$user_races = RaceMap::where('fkey_rcid', $user->rcid)->pluck('fkey_race_code')->toArray();
		$datamart_student = DatamartStudent::where('rcid', $user->rcid)->first();
		if (empty($cell_phone) && empty($home_phone) && empty($user_races) && is_null($student->first_name) &&
			  is_null($student->middle_name) && is_null($student->last_name) && is_null($student->maiden_name) &&
			  is_null($student->ethnics) && is_null($student->fkey_marital_status) && is_null($student->fkey_military_id)){
			// User has not filled out anything on the page and needs their information pulled from datamart
			$phones     = DatamartPhones::where("RCID", $user->rcid)->whereIn("fkey_PhoneTypeId", [1, 3])->get()->keyBy("fkey_PhoneTypeId");
			$cell_phone = $phones->get(3);
			$home_phone = $phones->get(1);
		}

		$all_races  = Races::orderBy('sortOrder')->get();
		$military_options = MilitaryOptions::all();
		$marital_statuses = MaritalStatuses::all();

		// Need to figure out how to check their SSN
		$have_SSN   = 0;

		return view('personal', compact('user', 'student','cell_phone','home_phone', 'user_races', 'all_races', 'have_SSN','marital_statuses','military_options', 'datamart_student', 'vpb_user'));
	}

	public function personalInfoUpdate(Request $request, Students $student, CompletedSections $completed_sections){
		$user    = RCAuth::user();

		// Updating all the student information
		$student->first_name 		      = $request->first_name;
		$student->middle_name 		    = $request->middle_name;
		$student->last_name   		    = $request->last_name;
		$student->maiden_name		      = $request->maiden_name;
		$student->fkey_marital_status = $request->MaritalStatus;
		$student->ethnics     		    = $request->ethnics;
		$student->fkey_military_id    = $request->MilitaryStatus;
		$student->updated_by          = $user->rcid;
		$student->save();

		$cell_phone = PhoneMap::firstOrNew(['RCID' => $user->rcid, 'fkey_PhoneTypeId' => 3]);//where('RCID', $user->rcid)->where('fkey_PhoneTypeId',3)->first();
		if(empty($cell_phone->created_by)){
			$cell_phone->created_by = $user->rcid;
			$cell_phone->updated_by = $user->rcid;
		}

		// updating the cell phone information
		$cell_phone->PhoneNumber = $request->input("cell_phone", null);
		$cell_phone->save();

		$home_phone = PhoneMap::firstOrNew(['RCID' => $user->rcid, 'fkey_PhoneTypeId' => 1]);//where('RCID', $user->rcid)->where('fkey_PhoneTypeId',1)->first();
		if(empty($home_phone->created_by)){
			// Creating a new phone object for a home phone
			$home_phone->created_by = $user->rcid;
			$home_phone->updated_by = $user->rcid;
		}

		// Updating the home phone information
		$home_phone->PhoneNumber = $request->input("home_phone", null);
		$home_phone->save();

		$races     = $request->races;
		$old_races = RaceMap::where('fkey_rcid', $user->rcid)->update(['deleted_at' => \Carbon\Carbon::now(), 'deleted_by' => $user->rcid]);

		if(!empty($races)){
			// ASSERT: We have input races to adjust
			foreach($races as $race){
				// Creating new race map connections for the student
				$new_race 			  	      = new RaceMap;
				$new_race->fkey_rcid      = $user->rcid;
				$new_race->created_by 	  = $user->rcid;
				$new_race->updated_by 	  = $user->rcid;
				$new_race->fkey_race_code = $race;
				$new_race->save();
			}
		}

		if( !empty($student->first_name)          && !empty($student->last_name) &&
				!empty($student->fkey_marital_status) && !empty($student->fkey_military_id) &&
				!empty($student->ethnics)             && !empty($cell_phone->PhoneNumber) &&
				!empty($home_phone->PhoneNumber)      && !empty($races)){
			// Assert: All Required information is filled out
			$completed_sections->personal_information = 1;
		}else{
			// Assert: Missing Required information
			$completed_sections->personal_information = 0;
		}
		$completed_sections->updated_by = $user->rcid;
		$completed_sections->save();

		return redirect()->action('StudentInformationController@addressInfo');
	}

	public function addressInfo(Students $student){
		$user            = RCAuth::user();
		$addresses       = Address::where('RCID', $user->rcid)->whereIn('fkey_AddressTypeId', [1, 3])->get()->keyBy("fkey_AddressTypeId");
		$dm_addresses    = DatamartAddress::where('RCID', $user->rcid)->whereIn('fkey_AddressTypeId', [1, 3])->get()->keyBy("fkey_AddressTypeId");
		$home_address    = $addresses->get(1, $dm_addresses->get(1));
		$billing_address = $addresses->get(3, $dm_addresses->get(3));

		$states          = States::all();
		$countries       = Countries::all();
		return view('address', compact('student','home_address', 'billing_address','states', 'countries'));
	}

	public function addressInfoUpdate(Request $request, Students $student, CompletedSections $completed_sections){
		$user 			 = RCAuth::user();

		$home_address    = Address::firstOrNew(['RCID' => $user->rcid, 'fkey_AddressTypeId' => 1, 'created_by' => $user->rcid]);

		// updating the home address information
		$home_address->Address1 	    = $request->Address1;
		$home_address->Address2 	    = $request->Address2;
		$home_address->City     	    = $request->city;
		$home_address->fkey_StateId   = $request->state;
		$home_address->PostalCode     = $request->zip;
		$home_address->fkey_CountryId = $request->country;
		$home_address->updated_by     = $user->rcid;
		$home_address->save();

		if ($request->home_as_billing){
			// ASSERT: Billing Address is same as Home Address
			$student->home_as_billing = true;
			Address::where('RCID', $user->rcid)->where("fkey_AddressTypeId", 3)->update(["deleted_by" => $user->rcid, "deleted_at" => \Carbon\Carbon::now()]);
		}else{
			// Assert: Need a Billing Address Added
			$student->home_as_billing = false;
			$billing_address = Address::firstOrNew(['RCID' => $user->rcid, 'fkey_AddressTypeId' => 3, 'created_by' => $user->rcid]);

			// Updating the billing address
			$billing_address->Address1	     = $request->billing_Address1;
			$billing_address->Address2	     = $request->billing_Address2;
			$billing_address->City     	     = $request->billing_city;
			$billing_address->fkey_StateId   = $request->billing_state;
			$billing_address->PostalCode     = $request->billing_zip;
			$billing_address->fkey_CountryId = $request->billingCountry;
			$billing_address->updated_by     = $user->rcid;
			$billing_address->save();
		}
		$student->save();

		$has_home_address    = !empty($home_address)  && !empty($home_address->Address1) && !empty($home_address->City) && !empty($home_address->fkey_StateId) && !empty($home_address->PostalCode);
		$has_billing_address = $student->home_as_billing || (!empty($billing_address) && !empty($billing_address->Address1) && !empty($billing_address->City) && !empty($billing_address->fkey_StateId) && !empty($billing_address->PostalCode));

		$completed_sections->address_information = $has_home_address && $has_billing_address;
		$completed_sections->updated_by          = $user->rcid;
		$completed_sections->save();

		return redirect()->action('StudentInformationController@residenceInfo');
	}

	public function residenceInfo (Students $student){
		$user          = RCAuth::user();
		$local_address = Address::where('RCID', $user->rcid)->where('fkey_AddressTypeId', 4)->first();
		$states        = States::all();

		return view('residence_hall', compact('local_address', 'student', 'states'));
	}

	public function residenceInfoUpdate(Request $request, Students $student, CompletedSections $completed_sections){
		$user = RCAuth::user();

		if (in_array($request->residence, ["hall", "home"])){
			// Need to delete all local info
			Address::where("RCID", $user->rcid)->where("fkey_AddressTypeId", 4)->update(["deleted_by" => $user->rcid, "deleted_at" => \Carbon\Carbon::now()]);

			$student->home_as_local = $request->residence == "home";
			// need to delete local address and update student bit
		}else{
			// need to update student bit and update local address
			$student->home_as_local = 0;
			$local_address = Address::firstOrNew(['RCID' => $user->rcid, 'fkey_AddressTypeId' => 4, 'created_by' => $user->rcid]);

			// updating local address information
			$local_address->Address1	   = $request->local_Address1;
			$local_address->Address2	   = $request->local_Address2;
			$local_address->City     	   = $request->local_city;
			$local_address->fkey_StateId = $request->local_state;
			$local_address->PostalCode   = $request->local_zip;
			$local_address->updated_by   = $user->rcid;
			$local_address->save();
		}

		$student->save();

		$completed_sections->residence_information = !is_null($student->home_as_local);
		$completed_sections->updated_by = $user->rcid;
		$completed_sections->save();

		return redirect()->action('StudentInformationController@citizenInfo');
	}

	public function citizenInfo(Students $student){
		$user          = RCAuth::user();

		$us_resident   = USResidence::where('RCID', $user->rcid)->first();
		$foreign       = CitizenshipInformation::where('RCID', $user->rcid)->get();

		$visa_types    = VisaTypes::all();
		$visa          = VisaTypeMap::where('RCID', $user->rcid)->first();

		$states        = States::all();
		$countries     = Countries::all();
		$counties      = Counties::all()->keyBy("county_id")->map(
			function ($item) {
				$display = $item->description;

				if(strpos($display, 'Co:') !== false){
	    			$display = str_replace("Co: ", "", $display);
	    			$display .= " County";
	  		}

				if(strpos($display, 'Ct:') !== false){
					$display = str_replace("Ct: ", "", $display);
				}

				$item->display = $display;
				return $item;
		})->sortBy("display");

		return view('citizen_info', compact('countries', 'student', 'us_resident', 'foreign', 'visa_types', 'visa', 'counties', 'states'));
	}

	public function citizenInfoUpdate(Request $request, Students $student, CompletedSections $completed_sections){
		$user          = RCAuth::user();
		$visa          = VisaTypeMap::where('RCID', $user->rcid)->first();
		$foreign       = CitizenshipInformation::orderBy('ID')->where('RCID', $user->rcid)->get();

		$student->us_citizen = (bool)$request->US_citizen;

		if ($request->US_citizen){
			// Student is a US Citizen
			$us_resident   = USResidence::firstOrNew(['RCID' => $user->rcid, 'created_by' => $user->rcid, 'updated_by' => $user->rcid]);

			$us_resident->fkey_StateCode = $request->state;
			if(!empty($request->county) && $request->state == "VA"){
				// ASSERT: Student lives in VA
				$us_resident->fkey_CityCode = $request->county;
			}else{
				$us_resident->fkey_CityCode = NULL;
			}
			$us_resident->save();
		}else{
			// Student is not a US Citizen
			$student->us_citizen = 0;
			USResidence::where('RCID', $user->rcid)->update(["deleted_by" => $user->rcid, "deleted_at" => \Carbon\Carbon::now()]);
		}

		$student->other_citizen = (bool)$request->other_citizen;
		if ($request->other_citizen){
			// Student has a non-US Citizenship
			if($foreign->isEmpty()){
				// Assert: No Previous non-US citizenship information
				$country1             = new CitizenshipInformation;
				$country1->RCID       = $user->rcid;
				$country1->created_by = $user->rcid;

				$country2             = new CitizenshipInformation;
				$country2->RCID       = $user->rcid;
				$country2->created_by = $user->rcid;
			}else{
				// Assert: Set up required both country 1 and country 2 to be created
				$country1 = $foreign[0];
				$country2 = $foreign[1];
			}

			// Updating foreign country information
			$country1->updated_by = $user->rcid;
			$country1->BirthCountry       = $request->BirthCountry[0];
			$country1->CitizenshipCountry = $request->CitizenshipCountry[0];
			$country1->PermanentCountry   = $request->PermanentCountry[0];
			$country2->updated_by = $user->rcid;
			$country2->BirthCountry       = $request->BirthCountry[1];
			$country2->CitizenshipCountry = $request->CitizenshipCountry[1];
			$country2->PermanentCountry   = $request->PermanentCountry[1];

			$country1->save();
			$country2->save();

			if(!empty($request->GreenCard) && ($request->GreenCard == "GreenCard")){
				// ASSERT:  Has Green Card
				$student->green_card = 1;
			}else{
				$student->green_card = 0;
			}

			if(!empty($request->GreenCard) && ($request->GreenCard == "Visa")){
				// ASSERT: has a Visa
				if(empty($visa)){
					$visa 		= New VisaTypeMap;
					$visa->RCID = $user->rcid;
					$visa->created_by = $user->rcid;
					$visa->updated_by = $user->rcid;
				}
				$visa->fkey_code = $request->VisaTypes;
				$visa->save();
			}else{
				// ASSERT: NO Visa
				if(!empty($visa)){
					// ASSERT: Has visa in database that needs to be removed
					self::deleteObject($visa);
				}
			}
		}else{
			// Assert: Student does not have a foreign citizenship
			$student->other_citizen = 0;
			// deleting any foreign records if there are any
			self::emptyForeignCitizenship($user->rcid);
		}
		$student->save();
		self::completedCitizenShipInfo();

		return redirect()->action('StudentInformationController@allergyInfo');
	}

	public function allergyInfo(){
		$user           = RCAuth::user();
		$student 	    = self::getStudent($user->rcid);
		$medications    = Medications::where('rcid', $user->rcid)->first();
		$med_allergy    = MedicationAllergies::where('rcid', $user->rcid)->first();
		$insect_allergy = InsectAllergies::where('rcid', $user->rcid)->first();

		return view('allergy', compact('user', 'medications', 'med_allergy', 'insect_allergy'));
	}

	public function allergyInfoUpdate(Request $request){
		$user    = RCAuth::user();
		$student = self::getStudent($user->rcid);

		$medications = Medications::firstOrNew(['rcid' => $user->rcid, "created_by" => $user->rcid]);
		$medications->take_medications = !empty($request->medications);
		$medications->medications 	   = $request->input("medications_text", "");
		$medications->updated_by       = $user->rcid;
		$medications->save();

		$med_allergy = MedicationAllergies::firstOrNew(['rcid' => $user->rcid, "created_by" => $user->rcid]);
		$med_allergy->have_medication_allergies = !empty($request->med_allergies);
		$med_allergy->medication_allergies      = $request->input("med_allergy_text", "");
		$med_allergy->updated_by                = $user->rcid;
		$med_allergy->save();

		$insect_allergy = InsectAllergies::firstOrNew(['rcid' => $user->rcid, "created_by" => $user->rcid]);
		$insect_allergy->have_insect_allergies = !empty($request->insect_allergies);
		$insect_allergy->insect_allergies      = $request->input("insect_allergy_text", "");
		$insect_allergy->updated_by            = $user->rcid;
		$insect_allergy->save();

		self::completedAllergyInfo();

		// UPDATE TO CORRECT PATH
		return redirect()->action('StudentInformationController@medicalInfo');

	}

	public function medicalInfo(Students $student){
		$user          = RCAuth::user();

		$health_concerns = HealthConcerns::with(['student_concerns' => function($query) use ($user){
			$query->where('rcid', $user->rcid);
		}])->get();

		$other_concern   = OtherConcerns::where('rcid', $user->rcid)->first();

		return view('medical', compact('user', 'health_concerns','other_concern'));
	}

	public function medicalInfoUpdate(Request $request, Students $student, CompletedSections $completed_sections){
		$user          = RCAuth::user();

		// Call to delete all previous medical concerns
		StudentConcerns::where("rcid", $user->rcid)->update(["deleted_by" => $user->rcid,
																												 "deleted_at" => \Carbon\Carbon::now()]);
		OtherConcerns::where("rcid", $user->rcid)->update(["deleted_by" => $user->rcid,
																											 "deleted_at" => \Carbon\Carbon::now()]);

		$student_concerns = $request->concerns;
		if(!empty($student_concerns)){
			// Assert: We need to add new medical concerns
			foreach($student_concerns as $concern){
				// creating new medical concern objects
				$new_concern = new StudentConcerns;
				$new_concern->rcid 			  = $user->rcid;
				$new_concern->fkey_concern_id = $concern;
				$new_concern->created_by 	  = $user->rcid;
				$new_concern->updated_by 	  = $user->rcid;
				$new_concern->save();
			}
		}
		$other_concern = OtherConcerns::where('rcid', $user->rcid)->first();
		if($request->other == "other"){
			// We have other medical concern information to have
			if(empty($other_concern)){
				// ASSERT: No previous other concern information
				// Creating new otherconern object
				$other_concern 			   = new OtherConcerns;
				$other_concern->rcid       = $user->rcid;
				$other_concern->created_by = $user->rcid;
				$other_concern->updated_by = $user->rcid;
			}
			$other_concern->other_concern = $request->other_concerns;
			$other_concern->save();
		}

		// update that the student submitted this page
		$student->submitted_health_concerns = 1;
		$student->save();

		$completed_sections->medical_information = 1;
		$completed_sections->save();

		return redirect()->action('StudentInformationController@missingPersonContact');
	}

	public function nonEmergency(Students $student){
		$user          = RCAuth::user();

		return view('non_emergency', compact('student'));
	}

	public function nonEmergencyUpdate(Request $request, Students $student, CompletedSections $completed_sections){
		$user          = RCAuth::user();

		$student->non_emergency = !empty($request->non_emergency);
		$student->save();

		$completed_sections->non_emergency_contact = 1;
		$completed_sections->save();

		return redirect()->action('StudentInformationController@independentStudent');
	}

	public function independentStudent(){
		$user          = RCAuth::user();
		$student 	   = self::getStudent($user->rcid);

		return view('independent_student', compact('student'));
	}

	public function independentStudentUpdate(Request $request){
		$user          = RCAuth::user();
		$student 	   = self::getStudent($user->rcid);

		if(!empty($request->independent_student)){
			// Assert: Student declares themselves as independent
			$student->independent_student = 1;
		}else{
			// Assert: Student declared non-independent
			$student->independent_student = 0;
		}
		$student->save();

		self::completedIndependentInfo();

		return redirect()->action('StudentInformationController@parentAndGuardianInfo');
	}

	public function infoRelease($id = NULL){
		$user          = RCAuth::user();
		$student 	   = self::getStudent($user->rcid);

		$guardian      = GuardianInfo::where('id', $id)->where('student_rcid', $user->rcid)->first();
		if(!empty($guardian)){
			return view('info_release', compact('student','guardian'));
		}else{
			return redirect()->action('StudentInformationController@index');
		}
	}

	public function infoReleaseUpdate(Request $request, $id){
		$user          = RCAuth::user();
		$student 	   = self::getStudent($user->rcid);

		$guardian      = GuardianInfo::where('id', $id)->where('student_rcid', $user->rcid)->first();

		if(!empty($request->info_release)){
			// Student gives information release permission
			$guardian->info_release = 1;
		}else{
			$guardian->info_release = 0;
		}
		$guardian->updated_by = $user->rcid;
		$guardian->save();

		self::completedParentInfo();

		return redirect()->action('StudentInformationController@employmentInfo', ['id'=> $id]);
	}

	public function employmentInfo($id = NULL){
		$user 			 = RCAuth::user();
		$student 		 = self::getStudent($user->rcid);

		$guardian        = GuardianInfo::where('id', $id)->where('student_rcid', $user->rcid)->first();
		$states          = States::all();
		$countries 		 = Countries::all();

		if(!empty($guardian)){
			$employment = EmploymentInfo::where('fkey_guardian_id', $guardian->id)->first();
			return view('employment_info', compact('student','guardian','employment', 'states', 'countries'));
		}else{
			return redirect()->action('StudentInformationController@index');
		}
	}

	public function employmentInfoUpdate(Request $request, $id){
		$user 			 = RCAuth::user();
		$student 		 = self::getStudent($user->rcid);

		$employment_info = EmploymentInfo::where('fkey_guardian_id', $id)->first();
		if(empty($employment_info)){
			$employment_info = new EmploymentInfo;
			$employment_info->fkey_guardian_id = $id;
			$employment_info->created_by = $user->rcid;
		}
		$employment_info->employer_name   = $request->employer_name;
		$employment_info->position        = $request->position;
		$employment_info->business_number = $request->business_number;
		$employment_info->business_email  = $request->business_email;
		$employment_info->Street1         = $request->Address1;
		$employment_info->Street2         = $request->Address2;
		$employment_info->city 			  = $request->city;
		$employment_info->fkey_StateCode  = $request->state;
		$employment_info->postal_code     = $request->zip;
		$employment_info->updated_by	  = $user->rcid;
		$employment_info->fkey_CountryId  = $request->Country;

		$employment_info->save();

		self::completedParentInfo();

		return redirect()->action('StudentInformationController@parentAndGuardianInfo');
	}


	public function parentAndGuardianInfo(){
		$user          = RCAuth::user();
		$student 	   = self::getStudent($user->rcid);

		$guardians     = GuardianInfo::where('student_rcid', $user->rcid)->get();
		return view('parent_guardian_info', compact('guardians'));
	}

	public function individualGuardian($id = NULL){
		$user     = RCAuth::user();
		$student  = self::getStudent($user->rcid);

		$guardian  = GuardianInfo::where('id', $id)->where('student_rcid', $user->rcid)->first();
		$education = Education::all();
		$marital   = MaritalStatuses::all();
		$states    = States::all();
		$countries = Countries::all();

		return view('guardian_verification', compact('guardian','marital','states', 'id', 'education', 'countries'));
	}

	public function parentAndGuardianInfoUpdate(Request $request, $id = null){
		$user     = RCAuth::user();
		$student  = self::getStudent($user->rcid);

		$guardian = GuardianInfo::where('id', $id)->where('student_rcid',$user->rcid)->first();
		if(empty($guardian)){
			$guardian = new GuardianInfo;
			$guardian->student_rcid    = $user->rcid;
			$guardian->created_by 	   = $user->rcid;
		}
		$guardian->first_name     	   = $request->first_name;
		$guardian->nick_name      	   = $request->nick_name;
		$guardian->middle_name    	   = $request->middle_name;
		$guardian->last_name      	   = $request->last_name;
		$guardian->fkey_marital_status = $request->MaritalStatus;
		$guardian->relationship   	   = $request->relationship;
		$guardian->email          	   = $request->email;
		$guardian->home_phone     	   = $request->home_phone;
		$guardian->cell_phone     	   = $request->cell_phone;
		$guardian->Address1      	   = $request->Address1;
		$guardian->Address2       	   = $request->Address2;
		$guardian->City           	   = $request->city;
		$guardian->fkey_StateCode	   = $request->state;
		$guardian->PostalCode     	   = $request->zip;
		$guardian->fkey_CountryId	   = $request->Country;
		$guardian->joint_mail1    	   = $request->joint1;
		$guardian->joint_mail2    	   = $request->joint2;
		$guardian->fkey_education_id   = $request->education;
		$guardian->updated_by          = $user->rcid;
		if(isset($request->reside_with)){
			$guardian->reside_with  = 1;
		}else{
			$guardian->reside_with  = 0;
		}

		if(isset($request->dependent)){
			$guardian->claimed_dependent = 1;
		}else{
			$guardian->claimed_dependent = 0;
		}

		$guardian->save();
		self::completedParentInfo();

		return redirect()->action('StudentInformationController@infoRelease', ['id' => $guardian->id]);
	}

	public function emergencyContact(){
		$user     = RCAuth::user();
		$student  = self::getStudent($user->rcid);

		$contacts = EmergencyContact::where('student_rcid', $user->rcid)->where('emergency_contact',1)->get();

		return view('emergency_contacts', compact('contacts'));
	}

	public function individualEmergencyContact($id = null){
		$user     = RCAuth::user();
		$student  = self::getStudent($user->rcid);

		$contact = EmergencyContact::where('id', $id)->where('emergency_contact', 1)->first();

		if(empty($contact) || $contact->student_rcid == $user->rcid){
			$redirect = view('individual_emergency_contact', compact('contact', 'id'));
		}else{
			$redirect = redirect()->action('StudentInformationController@index');
		}

		return $redirect;
	}

	public function emergencyContactUpdate(Request $request, $id = null){
		$user     = RCAuth::user();
		$student  = self::getStudent($user->rcid);

		$emergency_contact = EmergencyContact::where('student_rcid', $user->rcid)->where('id',$id)->where('emergency_contact', 1)->first();

		if(empty($emergency_contact)){
			$emergency_contact = new EmergencyContact;
			$emergency_contact->student_rcid   = $user->rcid;
			$emergency_contact->missing_person = 0;
			$emergency_contact->created_by 	 = $user->rcid;
		}
		$emergency_contact->emergency_contact = 1;
		$emergency_contact->name = $request->contact_name;
		$emergency_contact->relationship = $request->relationship;
		$emergency_contact->day_phone = $request->daytime_phone;
		$emergency_contact->evening_phone = $request->evening_phone;
		$emergency_contact->cell_phone = $request->cell_phone;
		$emergency_contact->updated_by = $user->rcid;
		$emergency_contact->save();

		self::completedEmergency();
		// UPDATE TO CORRECT PATH
		return redirect()->action('StudentInformationController@emergencyContact');
	}

	public function missingPersonContact(){
		$user     = RCAuth::user();
		$student  = self::getStudent($user->rcid);

		$contact = EmergencyContact::where('student_rcid', $user->rcid)->where('missing_person', 1)->first();

		return view('missing_person', compact('contact'));


	}

	public function missingPersonContactUpdate(Request $request){
		$user     = RCAuth::user();
		$student  = self::getStudent($user->rcid);

		$missing_contact = EmergencyContact::where('student_rcid', $user->rcid)->where('missing_person', 1)->first();

		if(empty($missing_contact)){
			$missing_contact = new EmergencyContact;
			$missing_contact->student_rcid   = $user->rcid;
			$missing_contact->missing_person = 1;
			$missing_contact->created_by 	 = $user->rcid;
		}
		$missing_contact->name = $request->contact_name;
		$missing_contact->relationship = $request->relationship;
		$missing_contact->day_phone = $request->daytime_phone;
		$missing_contact->evening_phone = $request->evening_phone;
		$missing_contact->cell_phone = $request->cell_phone;
		if($request->emergency == "emergency"){
			$missing_contact->emergency_contact = 1;
		}else{
			$missing_contact->emergency_contact = 0;
		}
		$missing_contact->updated_by = $user->rcid;
		$missing_contact->save();

		self::completedMissingPerson();

		return redirect()->action('StudentInformationController@emergencyContact');
	}

	public function deleteContact($id){
		$user     = RCAuth::user();
		$student  = self::getStudent($user->rcid);

		$contact = EmergencyContact::where('student_rcid', $user->rcid)->where('id', $id)->first();
		if(!empty($contact)){
			if($contact->missing_person){
				$contact->emergency_contact = 0;
				$contact->updated_by = $user->rcid;
				$contact->save();
			}else{
				self::deleteObject($contact);
			}
		}

		return redirect()->action('StudentInformationController@emergencyContact');

	}

	public function deleteGuardian($id){
		$user     = RCAuth::user();
		$student  = self::getStudent($user->rcid);

		$guardian = GuardianInfo::where('student_rcid', $user->rcid)->where('id', $id)->first();
		if(!empty($guardian)){
			self::deleteObject($guardian);
		}

		return redirect()->action('StudentInformationController@parentAndGuardianInfo');

	}

	public function confirmation(){
		return view('confirmation');
	}

	public function confirmationUpdate(){
		dd('completed');
	}

	// Pre :
	// Post:
	private function emptyForeignCitizenship($rcid){
		$student 	   = self::getStudent($rcid);
		$student->green_card = 0;
		$student->save();

		$foreign       = CitizenshipInformation::orderBy('ID')->where('RCID', $rcid)->get();
		foreach($foreign as $individual_country){
			self::deleteObject($individual_country);
		}

		$visa_map = VisaTypeMap::where('RCID', $rcid)->first();
		if(!empty($visa_map)){
			self::deleteObject($visa_map);
		}

	}

	// Pre : rcid is the student's rcid we want to delete all concerns for
	// Post: deletes all items from StudentConcerns for the given RCID
	private function emptyConcerns($rcid){
		$all_current_concerns = StudentConcerns::where('rcid', $rcid)->get();
		foreach($all_current_concerns as $concern){
			self::deleteObject($concern);
		}
	}

	// Pre : object is a Eloquent object that uses soft deleting
	// Post: We updated the updated_by and deleted_by fields and before deleting the object
	private function deleteObject($object){
		$user = RCAuth::user();
		$object->updated_by = $user->rcid;
		$object->deleted_by = $user->rcid;
		$object->save();
		$object->delete();
	}

	// Pre : rcid is the students rcid we are trying to recieve
	// Post: Returns the Student Object for the given RCID
	private function getStudent($rcid){
		// Attempts to pull the given student
		$student    = Students::where('RCID', $rcid)->first();
		if(empty($student)){
			// ASSERT: Student does not currently exist
			// Creating the student
			$student = new Students;
			$student->RCID       = $rcid;
			$student->created_by = $rcid;
			$student->save();

			// Create the completed section table
			$new_completion = new CompletedSections;
			$new_completion->fkey_rcid  = $rcid;
			$new_completion->created_by = $rcid;
			$new_completion->updated_by = $rcid;
			$new_completion->save();
		}

		return $student;
	}

	// Pre:
	// Post: Checks that the logged in user has filled out all required sections for the Personal Info Section
	private function completedPersonalInfo(){
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);
		$cell_phone = PhoneMap::where('RCID', $user->rcid)->where('fkey_PhoneTypeId',3)->first();
		$home_phone = PhoneMap::where('RCID', $user->rcid)->where('fkey_PhoneTypeId',1)->first();
		$user_races = RaceMap::where('fkey_rcid', $user->rcid)->pluck('fkey_race_code')->toArray();
	}

	// Pre:
	// Post: Checks that the logged in user has filled out all required sections for the Address Section
	private function completedAddressInfo(){
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);
		$home_address    = Address::where('RCID', $user->rcid)->where('fkey_AddressTypeId', 1)->first();
		$billing_address = Address::where('RCID', $user->rcid)->where('fkey_AddressTypeId', 3)->first();
		$completed_sections = CompletedSections::where('fkey_rcid', $user->rcid)->first();

	}

	// Pre :
	// Post: Checks that the medical fields were completed
	private function completedAllergyInfo(){
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);

		$medications    = Medications::where('rcid', $user->rcid)->first();
		$med_allergy    = MedicationAllergies::where('rcid', $user->rcid)->first();
		$insect_allergy = InsectAllergies::where('rcid', $user->rcid)->first();
		$completed_sections = CompletedSections::where('fkey_rcid', $user->rcid)->first();

		if(
			(!empty($medications)    && (!empty($medications->medications) || !$medications->take_medications)) &&
			(!empty($med_allergy)    && (!empty($med_allergy->medication_allergies) || !$med_allergy->have_medication_allergies)) &&
			(!empty($insect_allergy) && (!empty($insect_allergy->insect_allergies) || !$insect_allergy->have_insect_allergies))
		){
			// Assert: All Required information is filled out
			$completed_sections->allergy_information = 1;
		}else{
			// Assert: Missing Required information
			$completed_sections->allergy_information = 0;
		}
		$completed_sections->updated_by = $user->rcid;
		$completed_sections->save();
	}

	// Pre :
	// Post: Checks that the form is completed
	private function completedHealthInfo(){
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);
		$completed_sections = CompletedSections::where('fkey_rcid', $user->rcid)->first();

		if(!is_null($student->submitted_health_concerns)){
			// Assert: All Required information is filled out
			$completed_sections->medical_information = 1;
		}else{
			// Assert: Missing Required information
			$completed_sections->medical_information = 0;
		}
		$completed_sections->updated_by = $user->rcid;
		$completed_sections->save();
	}

	// Pre :
	// Post: Checks that the form is completed
	private function completedResidenceInfo(){
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);
		$completed_sections = CompletedSections::where('fkey_rcid', $user->rcid)->first();

		if(!is_null($student->home_as_local)){
			// Assert: If NULL than we have not completed the form
			// Assert: All Required information is filled out
			$completed_sections->residence_information = 1;
		}else{
			// Assert: Missing Required information
			$completed_sections->residence_information = 0;
		}
		$completed_sections->updated_by = $user->rcid;
		$completed_sections->save();

	}

	// Pre :
	// Post: Checks that the form is completed
	private function completedIndependentInfo(){
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);
		$completed_sections = CompletedSections::where('fkey_rcid', $user->rcid)->first();

		if(!is_null($student->independent_student)){
			$completed_sections->independent_student = 1;
		}else{
			// Assert: Missing Required information
			$completed_sections->independent_student = 0;
		}

		$completed_sections->updated_by = $user->rcid;
		$completed_sections->save();

	}

	// Pre :
	// Post: Checks that the form is completed
	private function completedNonEmergency(){
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);
		$completed_sections = CompletedSections::where('fkey_rcid', $user->rcid)->first();

		if(!is_null($student->non_emergency)){
			// Assert: Submitted the form
			$completed_sections->non_emergency_contact = 1;
		}else{
			// Assert: Missing Required information
			$completed_sections->non_emergency_contact = 0;
		}
		$completed_sections->updated_by = $user->rcid;
		$completed_sections->save();
	}

	// Pre :
	// Post: Checks that the form is completed
	private function completedCitizenShipInfo(){
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);
		$completed_sections = CompletedSections::where('fkey_rcid', $user->rcid)->first();

		if(!is_null($student->us_citizen)){
			// Assert: If NULL than we have not completed the form
			$completed_sections->citizenship_information = 1;
		}else{
			// Assert: Missing Required information
			$completed_sections->citizenship_information = 0;
		}
		$completed_sections->updated_by = $user->rcid;
		$completed_sections->save();
	}

	// Pre :
	// Post: checks that the emergency forms are completed
	private function completedEmergency(){
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);
		$contacts   = EmergencyContact::where('student_rcid', $user->rcid)->where('emergency_contact',1)->get();
		$completed_sections = CompletedSections::where('fkey_rcid', $user->rcid)->first();

		// On Page guardian_verificationon
		// If we have at least one contact it is complete
		if(count($contacts) > 0){
			$completed_sections->emergency_information = 1;
		}else{
			$completed_sections->emergency_information = 0;
		}

		$completed_sections->updated_by = $user->rcid;
		$completed_sections->save();
	}

	// Pre :
	// Post: checks that the emergency forms are completed
	private function completedMissingPerson(){
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);

		$missing_person = EmergencyContact::where('student_rcid', $user->rcid)->where('missing_person',1)->first();
		$completed_sections = CompletedSections::where('fkey_rcid', $user->rcid)->first();

		$completed_sections->missing_person = self::completedContact($missing_person);
		$completed_sections->updated_by = $user->rcid;

		$completed_sections->save();
	}

	private function completedContact($contact){
		if( !empty($contact) && !empty($contact->name) && !empty($contact->relationship) &&
		   (!empty($contact->day_phone) || !empty($contact->evening_phone) ||
		    !empty($contact->cell_phone)))	{
		   	// Fully filled out the Form
		   	$return = 1;
		}else{
			// Assert: Missing Required information
			$return = 0;
		}
		return $return;
	}

	// Pre :
	// Post: checks that the emergency forms are completed
	private function completedParentInfo(){
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);

		$parents    = GuardianInfo::where('student_rcid', $user->rcid)->with('employment')->get();

		$complete = true;
		foreach($parents as $parent){
			if(empty($parent->employment) || is_null($parent->info_release)){
				// Submitting employment page at least makes an entry
				// Info Release always marks a 0 or 1 if completed and stays NULL otherwise
				$complete = false;
			}
		}

		$completed_sections = CompletedSections::where('fkey_rcid', $user->rcid)->first();
		if($complete){
			$completed_sections->parent_and_guardian_information = 1;
		}else{
			$completed_sections->parent_and_guardian_information = 0;
		}
		$completed_sections->updated_by = $user->rcid;

		$completed_sections->save();
	}

}
