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
use App\CitizenshipCountryMap;
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
use App\GenericAddress;
use App\GuardianRelationshipTypes;
use RCAuth;

use App\ODS\CitizenshipInformation as ODSCitizenshipInformation;
use App\ODS\VisaTypeMap as ODSVisaTypeMap;
use App\ODS\USResidence as ODSUSResidence;
use App\ODS\MedicalData as ODSMedicalData;

class StudentInformationController extends Controller
{
	public const NUM_COUNTRIES = 2;

	public function __construct(){
      $this->middleware("force_login");
			$this->middleware("populate_dependencies");
	}

	public function index(Students $student, CompletedSections $completed_sections){
		$user              = RCAuth::user();
		$student           = $student->load("admit_status");

		$returning_student = !empty($student->admit_status) && $student->admit_status->X_APP_NEW != "NEW";
		$student_type      = ((!$returning_student && !empty($student->admit_status)) ? $student->admit_status->X_APP_ADMIT_STATUS : "other");

		$percs             = PERC::where("rcid", $student->RCID)->get();
		$submitted         = !$percs->where("perc", sprintf("RSI%s", \Carbon\Carbon::now()->format("y")))->isEmpty();

		$sections['Personal Information']     	 = ['status' => $completed_sections->personal_information,			      'link' => action("StudentInformationController@personalInfo")];
		$sections['Address Information']      	 = ['status' => $completed_sections->address_information,			        'link' => action("StudentInformationController@addressInfo")];
		$sections['Residence Information']   	   = ['status' => $completed_sections->residence_information,			      'link' => action("StudentInformationController@residenceInfo")];
		$sections['Citizenship Information'] 	   = ['status' => $completed_sections->citizenship_information,		      'link' => action("StudentInformationController@citizenInfo")];
		$sections['Allergy Information']   	   	 = ['status' => $completed_sections->allergy_information,			        'link' => action("StudentInformationController@allergyInfo")];
		$sections['Medical Information']   	   	 = ['status' => $completed_sections->medical_information,			        'link' => action("StudentInformationController@medicalInfo")];
		$sections['Missing Person']			 	       = ['status' => $completed_sections->missing_person,				          'link' => action("StudentInformationController@missingPersonContact")];
		$sections['Emergency Contact']		 	     = ['status' => $completed_sections->emergency_information,			      'link' => action("StudentInformationController@emergencyContact")];
		$sections['Non-Emergency Contact']   	   = ['status' => $completed_sections->non_emergency_contact,			      'link' => action("StudentInformationController@nonEmergency")];
		$sections['Independent Student']     	   = ['status' => $completed_sections->independent_student,			        'link' => action("StudentInformationController@independentStudent")];
		$sections['Parent/Guardian Information'] = ['status' => $completed_sections->parent_and_guardian_information, 'link' => action("StudentInformationController@parentAndGuardianInfo")];

		$additional_forms = AdditionalForms::orderBy("due_date")->orderBy("title")->get()->filter(function ($item) use ($student_type) {
			return $item->$student_type;
		});

		return view('index', compact('sections', "student", "completed_sections", "additional_forms", "percs", "submitted"));
	}

	//*************************************************************************************************************
	// BEGIN Personal Information FORMS
	//*************************************************************************************************************
	public function personalInfo(Students $student, \App\User $vpb_user){
		$user       = RCAuth::user();
		$phones     = PhoneMap::where("RCID", $user->rcid)->whereIn("fkey_PhoneTypeId", [1, 3])->get()->keyBy("fkey_PhoneTypeId");
		$cell_phone = $phones->get(3);
		$home_phone = $phones->get(1);
		$user_races = RaceMap::where('fkey_rcid', $user->rcid)->pluck('fkey_race_code')->toArray();
		$student     = $student->load("ssn");

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

		return view('personal', compact('user', 'student','cell_phone','home_phone', 'user_races', 'all_races','marital_statuses','military_options', 'vpb_user'));
	}

	public function personalInfoUpdate(Request $request, Students $student, CompletedSections $completed_sections){
		$user    = RCAuth::user();
		$student = $student->load('ssn');

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

		$cell_phone = PhoneMap::firstOrNew(['RCID' => $user->rcid, 'fkey_PhoneTypeId' => 3], ["created_by" => $user->rcid]);
		$cell_phone->PhoneNumber = $request->input("cell_phone", null);
		$cell_phone->updated_by = $user->rcid;
		$cell_phone->save();

		$home_phone = PhoneMap::firstOrNew(['RCID' => $user->rcid, 'fkey_PhoneTypeId' => 1], ["created_by" => $user->rcid]);
		$home_phone->PhoneNumber = $request->input("home_phone", null);
		$home_phone->updated_by  = $user->rcid;
		$home_phone->save();

		$races     = $request->input("races", []);
		$old_races = RaceMap::where('fkey_rcid', $user->rcid)->update(['deleted_at' => \Carbon\Carbon::now(), 'deleted_by' => $user->rcid]);

		foreach($races as $race){
			// Creating new race map connections for the student
			$new_race 			  	      = new RaceMap;
			$new_race->fkey_rcid      = $user->rcid;
			$new_race->created_by 	  = $user->rcid;
			$new_race->updated_by 	  = $user->rcid;
			$new_race->fkey_race_code = $race;
			$new_race->save();
		}

		$personal_completed = !empty($student->first_name) && !empty($student->last_name) &&
													!empty($student->fkey_marital_status) && !is_null($student->fkey_military_id) &&
													!is_null($student->ethnics) && !empty($races) &&
													!(empty($cell_phone->PhoneNumber) && empty($home_phone->PhoneNumber)) &&
													!empty($student->ssn);

		$completed_sections->personal_information = $personal_completed;
		$completed_sections->updated_by = $user->rcid;
		$completed_sections->save();

		return redirect()->action('StudentInformationController@addressInfo');
	}
	//*************************************************************************************************************
	// END Personal Information FORMS
	//*************************************************************************************************************

	//*************************************************************************************************************
	// BEGIN Address Information FORMS
	//*************************************************************************************************************
	public function addressInfo(Students $student){
		$user            = RCAuth::user();
		$addresses       = Address::where('RCID', $user->rcid)->whereIn('fkey_AddressTypeId', [1, 3])->get()->keyBy("fkey_AddressTypeId");

		$dm_addresses    = DatamartAddress::where('RCID', $user->rcid)->whereIn('fkey_AddressTypeId', [1, 3])->get()->keyBy("fkey_AddressTypeId");
		$home_address    = $addresses->get(1, $dm_addresses->get(1));
		$billing_address = $addresses->get(3, $dm_addresses->get(3));

		$home_address    = GenericAddress::fromMixedAddress($addresses->get(1, $dm_addresses->get(1)));
		$billing_address = GenericAddress::fromMixedAddress($addresses->get(3, $dm_addresses->get(3)));

		$states          = States::all();
		$countries       = Countries::all();
		return view('address', compact('student','home_address', 'billing_address','states', 'countries'));
	}

	public function addressInfoUpdate(Request $request, Students $student, CompletedSections $completed_sections){
		$user 			  = RCAuth::user();

		$home_address   = Address::firstOrNew(['RCID' => $user->rcid, 'fkey_AddressTypeId' => 1, 'created_by' => $user->rcid]);
		$home_addresses = $request->input("address_home", ["", ""]);

		// updating the home address information
		$home_address->Address1 	    			 = $home_addresses[0];
		$home_address->Address2 	    			 = $home_addresses[1];
		$home_address->City     	    			 = $request->input("city_home", NULL);
		$home_address->fkey_StateId   			 = $request->input("state_home", NULL);
		$home_address->PostalCode            = $request->input("zip_home", NULL);
		$home_address->fkey_CountryId        = $request->input("country_home", NULL);
		$home_address->international_address = $request->input("international_address_home", NULL);
		$home_address->updated_by            = $user->rcid;
		$home_address->save();

		if ($request->home_as_billing){
			// ASSERT: Billing Address is same as Home Address
			$student->home_as_billing = true;
			Address::where('RCID', $user->rcid)->where("fkey_AddressTypeId", 3)->update(["deleted_by" => $user->rcid, "deleted_at" => \Carbon\Carbon::now()]);
		}else{
			// Assert: Need a Billing Address Added
			$student->home_as_billing = false;
			$billing_address = Address::firstOrNew(['RCID' => $user->rcid, 'fkey_AddressTypeId' => 3, 'created_by' => $user->rcid]);

			$billing_addresses = $request->input("address_billing", ["", ""]);

			// Updating the billing address
			$billing_address->Address1	            = $billing_addresses[0];
			$billing_address->Address2	            = $billing_addresses[1];
			$billing_address->City     	            = $request->input("city_billing", NULL);
			$billing_address->fkey_StateId          = $request->input("state_billing", NULL);
			$billing_address->PostalCode            = $request->input("zip_billing", NULL);
			$billing_address->fkey_CountryId        = $request->input("country_billing", NULL);
			$billing_address->international_address = $request->input("international_address_billing", NULL);
			$billing_address->updated_by            = $user->rcid;
			$billing_address->save();
		}
		$student->save();

		$has_home_address    = GenericAddress::fromAddress($home_address)->complete();
		$has_billing_address = $student->home_as_billing || (!empty($billing_address) && GenericAddress::fromAddress($billing_address)->complete());

		$completed_sections->address_information = $has_home_address && $has_billing_address;
		$completed_sections->updated_by          = $user->rcid;
		$completed_sections->save();

		return redirect()->action('StudentInformationController@residenceInfo');
	}
	//*************************************************************************************************************
	// END Address Information FORMS
	//*************************************************************************************************************

	//*************************************************************************************************************
	// BEGIN Residence Information FORMS
	//*************************************************************************************************************
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
			$local_address->Address1	     = $request->local_Address1;
			$local_address->Address2	     = $request->local_Address2;
			$local_address->City     	     = $request->local_city;
			$local_address->fkey_StateId   = $request->local_state;
			$local_address->PostalCode     = $request->local_zip;
			$local_address->fkey_CountryId = GenericAddress::US_ID;
			$local_address->updated_by     = $user->rcid;
			$local_address->save();
		}

		$student->save();
		$completed_sections->residence_information = $student->home_as_local || $request->residence == "hall" || GenericAddress::fromAddress($local_address)->complete();
		$completed_sections->updated_by            = $user->rcid;
		$completed_sections->save();

		return redirect()->action('StudentInformationController@citizenInfo');
	}
	//*************************************************************************************************************
	// END Residence Information FORMS
	//*************************************************************************************************************

	//*************************************************************************************************************
	// BEGIN Citizenship Information FORMS
	//*************************************************************************************************************
	public function citizenInfo(Students $student){
		$user          = RCAuth::user();

		$us_resident     = USResidence::where('RCID', $user->rcid)->first();
		if (empty($us_resident)) {
			$ods_resident  = ODSUSResidence::where('RCID', $user->rcid)->first();
		} else {
			$ods_resident  = NULL;
		}

		$citizenship       = CitizenshipInformation::where('fkey_rcid', $user->rcid)->first();
		if (empty($citizenship)) {
			$ods_citizenship = ODSCitizenshipInformation::where('fkey_rcid', $user->rcid)->first();
		} else {
			$ods_citizenship = NULL;
		}

		$visa       = VisaTypeMap::where('RCID', $user->rcid)->first();
		if (empty($visa)) {
			$ods_visa = ODSVisaTypeMap::find($user->rcid);
		} else {
			$ods_visa = NULL;
		}

		$visa_types    = VisaTypes::all();

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

		return view('citizen_info', compact('countries', 'student', 'us_resident', 'ods_resident', 'citizenship', 'ods_citizenship', 'visa_types', 'visa', 'ods_visa', 'counties', 'states'));
	}

	public function citizenInfoUpdate(Request $request, Students $student, CompletedSections $completed_sections){
		$user              = RCAuth::user();

		$green_card_input  = $request->input("GreenCard", []);
		$us_resident       = USResidence::firstOrNew(['RCID' => $student->RCID, 'created_by' => $user->rcid], ['updated_by' => $user->rcid]);

		$citizenship                      = CitizenshipInformation::firstOrNew(["fkey_rcid" => $student->RCID], ["created_by" => $user->rcid]);//$student->load("citizenship");

		$citizenship->country_of_birth    = $request->input("BirthCountry", NULL);
		$citizenship->updated_by          = $user->rcid;

		$citizenship->us = (bool)$request->US_citizen;
		if ($request->US_citizen){
			$us_resident->fkey_StateCode = $request->state;
			$us_resident->fkey_CityCode  = $request->state == "VA" ? $request->input("county", NULL) : NULL;
			$us_resident->save();
		}else{
			USResidence::where('RCID', $user->rcid)->update(["deleted_by" => $user->rcid, "deleted_at" => \Carbon\Carbon::now()]);
		}

		$citizenship->another = (bool)$request->another_citizen;
		if ($citizenship->another) {
			$citizenship->permanent_residence = $request->input("PermanentCountry", NULL);
			$foreign = CitizenshipCountryMap::orderBy('ID')->where('RCID', $user->rcid)->get();
			for ($i = 0; $i < self::NUM_COUNTRIES; $i++ ){
				if (!isset($foreign[$i])) {
					$foreign[$i]             = new CitizenshipCountryMap;
					$foreign[$i]->RCID       = $student->RCID;
					$foreign[$i]->created_by = $user->rcid;
				}
				$foreign[$i]->CitizenshipCountry = $request->CitizenshipCountry[$i];
				$foreign[$i]->updated_by         = $user->rcid;
				$foreign[$i]->save();
			}
			$citizenship->green_card = in_array("GreenCard", $green_card_input);
		} else {
			//Delete all foreign information, because they are not listed as a citizen of another country
			$citizenship->permanent_residence = $citizenship->green_card = NULL;
			CitizenshipCountryMap::orderBy('ID')->where('RCID', $user->rcid)->update(['deleted_by' => $user->rcid, 'deleted_at' => \Carbon\Carbon::now()]);
			$foreign = collect();
		}

		if($citizenship->another && in_array("Visa", $green_card_input) && !empty($request->get("VisaTypes", NULL))) {
			$visa             = VisaTypeMap::firstOrNew(["RCID" => $student->RCID, "created_by" => $user->rcid]);
			$visa->updated_by = $user->rcid;
			$visa->fkey_code  = $request->VisaTypes;
			$visa->save();
		}else{
			VisaTypeMap::where("RCID", $user->rcid)->update(['deleted_by' => $user->rcid, 'deleted_at' => \Carbon\Carbon::now()]);
		}

		$citizenship->other = (bool)$request->other_citizen;
		$citizenship->save();
		self::completedCitizenshipInfo($student, $completed_sections, $citizenship, $us_resident, $foreign, !empty($citizenship) && $citizenship->green_card, isset($visa) && !empty($visa));

		return redirect()->action('StudentInformationController@allergyInfo');
	}

	// Pre :
	// Post: Checks that the form is completed
	private function completedCitizenshipInfo(Students $student, CompletedSections $completed_sections, CitizenshipInformation $citizenship,
																						USResidence $us_resident, $foreign, $permanent_residence, $visa){
		$user = RCAuth::user();

		$basic_citizenship   = !empty($citizenship) && !empty($citizenship->country_of_birth) && ($citizenship->us || $citizenship->another || $citizenship->other);
		$us_citizenship      = $basic_citizenship && (!$citizenship->us || (!empty($us_resident) && !empty($us_resident->fkey_StateCode) &&
																																			  ($us_resident->fkey_StateCode != "VA" || !empty($us_resident->fkey_CityCode))));
		$another_citizenship = $basic_citizenship && (!$citizenship->another || (!empty($citizenship->permanent_residence) &&
																																						 $foreign->reduce(function ($collector, $item) {
																																							 	return $collector || !empty($item->CitizenshipCountry);
																																						 	}, false) && ($permanent_residence || $visa)));

		$completed_sections->citizenship_information = $basic_citizenship && $us_citizenship && $another_citizenship;
		$completed_sections->updated_by              = $user->rcid;
		$completed_sections->save();
	}
	//*************************************************************************************************************
	// END Citizenship Information FORMS
	//*************************************************************************************************************



	//*************************************************************************************************************
	// BEGIN Allergy Information FORMS
	//*************************************************************************************************************
	public function allergyInfo(Students $student){
		$user           = RCAuth::user();
		$medications    = Medications::where('rcid', $user->rcid)->first();
		$med_allergy    = MedicationAllergies::where('rcid', $user->rcid)->first();
		$insect_allergy = InsectAllergies::where('rcid', $user->rcid)->first();
		$ods_data       = ODSMedicalData::find($user->rcid);

		return view('allergy', compact('user', 'medications', 'med_allergy', 'insect_allergy', "ods_data"));
	}

	public function allergyInfoUpdate(Request $request, Students $student, CompletedSections $completed_sections){
		$user = RCAuth::user();

		$medications = Medications::firstOrNew(['rcid' => $user->rcid, "created_by" => $user->rcid]);
		$medications->take_medications = !empty($request->medications);
		$medications->medications 	   = !empty($request->medications) ? $request->input("medications_text", "") : NULL;
		$medications->updated_by       = $user->rcid;
		$medications->save();

		$med_allergy = MedicationAllergies::firstOrNew(['rcid' => $user->rcid, "created_by" => $user->rcid]);
		$med_allergy->have_medication_allergies = !empty($request->med_allergies);
		$med_allergy->medication_allergies      = !empty($request->med_allergies) ? $request->input("med_allergy_text", "") : NULL;
		$med_allergy->updated_by                = $user->rcid;
		$med_allergy->save();

		$insect_allergy = InsectAllergies::firstOrNew(['rcid' => $user->rcid, "created_by" => $user->rcid]);
		$insect_allergy->have_insect_allergies = !empty($request->insect_allergies);
		$insect_allergy->insect_allergies      = !empty($request->insect_allergies) ? $request->input("insect_allergy_text", "") : NULL;
		$insect_allergy->updated_by            = $user->rcid;
		$insect_allergy->save();

		// Checks if the user has completed the allergy section
		$completed_allergy = (!$medications->take_medications          || !empty($medications->medications)) &&
												 (!$med_allergy->have_medication_allergies || !empty($med_allergy->medication_allergies)) &&
												 (!$insect_allergy->have_insect_allergies  || !empty($insect_allergy->insect_allergies));

		if ($completed_allergy != $completed_sections->allergy_information) {
			$completed_sections->allergy_information = $completed_allergy;
			$completed_sections->updated_by = $user->rcid;
			$completed_sections->save();
		}

		// UPDATE TO CORRECT PATH
		return redirect()->action('StudentInformationController@medicalInfo');
	}
	//*************************************************************************************************************
	// END Allergy Information FORMS
	//*************************************************************************************************************


	//*************************************************************************************************************
	// BEGIN Medical Information FORMS
	//*************************************************************************************************************
	public function medicalInfo(Students $student, CompletedSections $completed_sections){
		$user          = RCAuth::user();

		$health_concerns = HealthConcerns::with(['student_concerns' => function($query) use ($user){
			$query->where('rcid', $user->rcid);
		}])->get();

		$other_concern   = OtherConcerns::where('rcid', $user->rcid)->first();
		$ods_other       = ODSMedicalData::find($user->rcid);

		$ods_other_concerns = "";
		if(!empty($ods_other) && !$completed_sections->medical_information) {
			$ods_other_concerns = $ods_other->pull_data("other");
		}

		return view('medical', compact('user', 'health_concerns','other_concern', "ods_other_concerns"));
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
		} else if (!empty($other_concern)) {
			$other_concern->deleted_by = $user->rcid;
			$other_concern->save();
			$other_concern->delete();
		}

		// update that the student submitted this page
		$student->submitted_health_concerns = 1;
		$student->save();

		$completed_sections->medical_information = $request->other != "other" || (!empty($other_concern) && !empty($other_concern->other_concern));
		$completed_sections->save();

		return redirect()->action('StudentInformationController@missingPersonContact');
	}
	//*************************************************************************************************************
	// END Medical Information FORMS
	//*************************************************************************************************************


	//*************************************************************************************************************
	// BEGIN Cleary Act Missing Persons Contact FORMS
	//*************************************************************************************************************
	public function missingPersonContact(Students $student){
		$user    = RCAuth::user();
		$contact = EmergencyContact::where('student_rcid', $student->RCID)->where('missing_person', 1)->first();

		return view('missing_person', compact('contact'));
	}

	public function missingPersonContactUpdate(Request $request, Students $student, CompletedSections $completed_sections){
		$user     = RCAuth::user();

		$missing_contact = EmergencyContact::firstOrNew(["student_rcid" => $student->RCID, "missing_person" => 1, 'deleted_at' => NULL], ["created_by" => $student->RCID]);
		$missing_contact->name              = $request->contact_name;
		$missing_contact->relationship      = $request->relationship;
		$missing_contact->day_phone         = $request->daytime_phone;
		$missing_contact->evening_phone     = $request->evening_phone;
		$missing_contact->cell_phone        = $request->cell_phone;
		$missing_contact->emergency_contact = $request->emergency == "emergency";
		$missing_contact->updated_by        = $student->RCID;
		$missing_contact->save();

		$completed_sections->missing_person = $missing_contact->completed();
		if ($missing_contact->emergency_contact) {
			$completed_sections->emergency_information = 1;
		}
		$completed_sections->updated_by     = $student->RCID;
		$completed_sections->save();

		return redirect()->action('StudentInformationController@emergencyContact');
	}
	//*************************************************************************************************************
	// END Cleary Act Missing Persons Contact FORMS
	//*************************************************************************************************************



	//*************************************************************************************************************
	// BEGIN Emergency Contact FORMS
	//*************************************************************************************************************
	public function emergencyContact (Students $student){
		$contacts = EmergencyContact::where('student_rcid', $student->RCID)->where('emergency_contact',1)->get();

		return view('emergency_contacts', compact('contacts'));
	}

	public function individualEmergencyContact(Students $student, $id = null){
		$user     = RCAuth::user();

		$contact = EmergencyContact::where('id', $id)->where("student_rcid", $student->RCID)->where('emergency_contact', 1)->first();

		if(empty($id) || !empty($contact)){
			$redirect = view('individual_emergency_contact', compact('contact', 'id'));
		}else{
			$redirect = redirect()->action('StudentInformationController@index');
		}

		return $redirect;
	}

	public function emergencyContactUpdate(Request $request, Students $student, CompletedSections $completed_sections, $id = null){
		$user     = RCAuth::user();

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

		self::completedEmergency($student, $completed_sections);

		// UPDATE TO CORRECT PATH
		return redirect()->action('StudentInformationController@emergencyContact');
	}

	public function deleteContact(Students $student, CompletedSections $completed_sections, $id){
		$user     = RCAuth::user();

		$contact = EmergencyContact::where('student_rcid', $user->rcid)->where('id', $id)->first();
		if(!empty($contact)){
			if($contact->missing_person){
				$contact->emergency_contact = 0;
				$contact->updated_by = $user->rcid;
				$contact->save();
			}else{
				$contact->deleted_by = $user->rcid;
				$contact->save();
				$contact->delete();
			}
		}

		self::completedEmergency($student, $completed_sections);

		return redirect()->action('StudentInformationController@emergencyContact');
	}

	// Pre :
	// Post: checks that the emergency forms are completed
	private function completedEmergency(Students $student, CompletedSections $completed_sections){
		$contacts = EmergencyContact::where("student_rcid", $student->RCID)
																			->where("emergency_contact", 1)
																		 	->get();
		$completed_sections->emergency_information = $contacts->count() > 0 && $contacts->reduce(function ($collector, $item) {
																																							return $collector && $item->completed();
																																						}, true);
		$completed_sections->updated_by            = RCAuth::user()->rcid;
		$completed_sections->save();
	}

	public function emergencyDoubleCheck (Students $student, CompletedSections $completed_sections) {
		self::completedEmergency($student, $completed_sections);

		return redirect()->action("StudentInformationController@nonEmergency");
	}
	//*************************************************************************************************************
	// END Emergency Contact FORMS
	//*************************************************************************************************************


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

		self::completedParentInfo($student, $completed_sections);

		$completed_sections->independent_student = !is_null($student->independent_student);
		$completed_sections->updated_by          = $user->rcid;
		$completed_sections->save();

		return redirect()->action('StudentInformationController@parentAndGuardianInfo');
	}
	//*************************************************************************************************************
	// END Independent Student FORMS
	//*************************************************************************************************************



	//*************************************************************************************************************
	// BEGIN Parent/Guardian FORMS
	//*************************************************************************************************************
	public function getGuardianVerification (Students $student, $id) {
		$guardian           = GuardianInfo::where('id', $id)->with(["employment.country", "employment.state", "education", "marital_status", "state", "country"])->first();
		$guardian_address   = GenericAddress::fromGuardianInfo($guardian);
		$employment_address = !empty($guardian->employment) ? GenericAddress::fromEmploymentInfo($guardian->employment) : new GenericAddress;
		return view()->make("partials.guardian_confirm_modal_contents", compact("guardian", "guardian_address", "employment_address"));
	}

	public function parentAndGuardianInfo(Students $student){
		$user      = RCAuth::user();
		$guardians  = GuardianInfo::where('student_rcid', $user->rcid)->with("guardian_type")->get();
		return view('parent_guardian_info', compact('guardians'));
	}

	public function individualGuardian(Students $student, $id = NULL){
		$user     = RCAuth::user();

		$guardian           = GuardianInfo::where('id', $id)->where('student_rcid', $user->rcid)->firstOrNew([]);
		$relationship_types = GuardianRelationshipTypes::all();
		$address            = GenericAddress::fromGuardianInfo($guardian);
		$education 					= Education::orderBy("id")->get();
		$marital   					= MaritalStatuses::all();
		$states    					= States::all();
		$countries 					= Countries::all();

		return view('guardian_verification', compact('guardian', 'relationship_types', 'address', 'marital','states', 'id', 'education', 'countries'));
	}

	public function parentAndGuardianInfoUpdate(Request $request, Students $student, CompletedSections $completed_sections, $id = null){
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

		return redirect()->action('StudentInformationController@employmentInfo', ['id' => $guardian->id]);
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

		return redirect()->action('StudentInformationController@parentAndGuardianInfo');

	}

	public function infoRelease(Students $student, $id=NULL){
		$user     = RCAuth::user();
		$guardian = GuardianInfo::where('id', $id)->where('student_rcid', $user->rcid)->first();

		if(!empty($guardian)){
			return view('info_release', compact('student','guardian'));
		}else{
			return redirect()->action('StudentInformationController@index');
		}
	}

	public function infoReleaseUpdate(Request $request, Students $student, CompletedSections $completed_sections, $id){
		$user     = RCAuth::user();

		$guardian = GuardianInfo::where('id', $id)->where('student_rcid', $user->rcid)->first();

		if (empty($guardian)) {
			return redirect()->action("StudentInformationController@index");
		}

		$guardian->info_release = !empty($request->info_release) && $request->info_release;
		$guardian->updated_by   = $user->rcid;
		$guardian->save();

		self::completedParentInfo($student, $completed_sections);

		return redirect()->action('StudentInformationController@employmentInfo', ['id' => $id]);
	}

	public function employmentInfo(Students $student, $id = NULL){
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

	public function employmentInfoUpdate(Request $request, Students $student, CompletedSections $completed_sections, $id){
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

		self::completedParentInfo($student, $completed_sections);

		return redirect()->action('StudentInformationController@parentAndGuardianInfo');
	}

	private function completedParentInfo(Students $student, CompletedSections $completed_sections){
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

	//*************************************************************************************************************
	//END Parent/Guardian FORMS
	//*************************************************************************************************************

	//*************************************************************************************************************
	// BEGIN Financial Acceptance
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

		$perc = PERC::firstOrNew(['rcid' => $student->RCID, 'perc' => sprintf('TIX', \Carbon\Carbon::now()->format("y"))],
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
			$perc    = PERC::firstOrCreate(['rcid' => $student->RCID, 'perc' => sprintf('RSI%s', \Carbon\Carbon::now()->format("y"))],
															 			 ['created_by' => \RCAuth::user()->rcid, 'created_at' => \Carbon\Carbon::now(),
																	 	  'updated_by' => \RCAuth::user()->rcid]);

			$perc->save();
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
