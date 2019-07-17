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
use RCAuth;

class StudentInformationController extends Controller
{

	public function __construct(){
      $this->middleware("force_login");
	}

	public function index(){
		$sections = self::completedSections();
		// dd($completed_sections);
		return view('index', compact('sections'));
	}

	public function personalInfo(){
		$user       = RCAuth::user(); 
		$student 	= self::getStudent($user->rcid);
		
		$cell_phone = PhoneMap::where('RCID', $user->rcid)->where('fkey_PhoneTypeId',3)->first();
		$home_phone = PhoneMap::where('RCID', $user->rcid)->where('fkey_PhoneTypeId',1)->first();
		$user_races = RaceMap::where('fkey_rcid', $user->rcid)->pluck('fkey_race_code')->toArray();
		$all_races  = Races::orderBy('sortOrder')->get();
		$military_options = MilitaryOptions::all();
		$marital_statuses = MaritalStatuses::all();

		// Need to figure out how to check their SSN
		$have_SSN   = 0;

		return view('personal', compact('user', 'student','cell_phone','home_phone', 'user_races', 'all_races', 'have_SSN','marital_statuses','military_options'));
	}

	public function personalInfoUpdate(Request $request){
		$user    = RCAuth::user();
		$student = self::getStudent($user->rcid);
		
		// Updating all the student information
		$student->first_name 		  = $request->first_name;
		$student->middle_name 		  = $request->middle_name;
		$student->last_name   		  = $request->last_name;
		$student->maiden_name		  = $request->maiden_name;
		$student->fkey_marital_status = $request->MaritalStatus;
		$student->ethnics     		  = $request->ethnics;
		$student->fkey_military_id    = $request->MilitaryStatus;
		$student->updated_by          = $user->rcid;
		$student->save();

		$cell_phone = PhoneMap::where('RCID', $user->rcid)->where('fkey_PhoneTypeId',3)->first();
		if(empty($cell_phone)){
			// Creating new phone object for a cell phone
			$cell_phone 				  = new PhoneMap;
			$cell_phone->RCID			  = $user->rcid;
			$cell_phone->fkey_PhoneTypeId = 3;
			$cell_phone->created_by       = $user->rcid;
		}
		// updating the cell phone information
		$cell_phone->PhoneNumber = $request->cell_phone;
		$cell_phone->updated_by  = $user->rcid;
		$cell_phone->save();

		$home_phone = PhoneMap::where('RCID', $user->rcid)->where('fkey_PhoneTypeId',1)->first();
		
		if(empty($home_phone)){
			// Creating a new phone object for a home phone
			$home_phone 				  = new PhoneMap;
			$home_phone->RCID			  = $user->rcid;
			$home_phone->fkey_PhoneTypeId = 1;
			$home_phone->created_by       = $user->rcid;
		}
		// Updating the home phone information
		$home_phone->PhoneNumber = $request->home_phone;
		$cell_phone->updated_by  = $user->rcid;
		$home_phone->save();
		 
		$races     = $request->races;
		$old_races = RaceMap::where('fkey_rcid', $user->rcid)->get();
		foreach($old_races as $race){
			// Deleting all of the old race map connections
			self::deleteObject($race);
		}

		if(!empty($races)){
			// ASSERT: We have input races to adjust
			foreach($races as $race){
				// Creating new race map connections for the student
				$new_race 			  	  = new RaceMap;
				$new_race->fkey_rcid      = $user->rcid;
				$new_race->created_by 	  = $user->rcid;
				$new_race->updated_by 	  = $user->rcid;
				$new_race->fkey_race_code = $race;
				$new_race->save();
			}
		}

		return redirect()->action('StudentInformationController@addressInfo');
	}	

	public function addressInfo(){		
		$user            = RCAuth::user(); 
		$student 		 = Students::where('RCID', $user->rcid)->first();
		$home_address    = Address::where('RCID', $user->rcid)->where('fkey_AddressTypeId', 1)->first();
		$billing_address = Address::where('RCID', $user->rcid)->where('fkey_AddressTypeId', 3)->first();
		$states          = States::all();
		
		return view('address', compact('student','home_address', 'billing_address','states'));
	}	

	public function addressInfoUpdate(Request $request){
		$user 			 = RCAuth::user();
		$student 		 = self::getStudent($user->rcid);
		$home_address    = Address::where('RCID', $user->rcid)->where('fkey_AddressTypeId', 1)->first();		
		$billing_address = Address::where('RCID', $user->rcid)->where('fkey_AddressTypeId', 3)->first();

		if(empty($home_address)){
			// ASSERT: Home Address not found
			// need to create a fresh address
			$home_address 					  = new Address;
			$home_address->fkey_AddressTypeId = 1;
			$home_address->RCID 			  = $user->rcid;	
			$home_address->created_by 		  = $user->rcid;
		}
		// updating the home address information
		$home_address->Address1 	= $request->Address1;
		$home_address->Address2 	= $request->Address2;
		$home_address->City     	= $request->city;
		$home_address->fkey_StateId = $request->state;
		$home_address->PostalCode   = $request->zip;
		$home_address->updated_by   = $user->rcid;
		$home_address->save();

		if($request->home_as_billing){
			// ASSERT: Billing Address is same as Home Address
			$student->home_as_billing = 1;			
			if(!empty($billing_address)){
				// Need to delete existing billing address
				self::deleteObject($billing_address);
			}
		}else{
			// Assert: Need a Billing Address Added
			$student->home_as_billing = 0;
			if(empty($billing_address)){
				// Creating a new billing address object
				$billing_address 					 = new Address;
				$billing_address->fkey_AddressTypeId = 3;
				$billing_address->RCID 			  	 = $user->rcid;	
				$billing_address->created_by 		 = $user->rcid;
			}
			// Updating the billing address
			$billing_address->Address1	   = $request->billing_Address1;
			$billing_address->Address2	   = $request->billing_Address2;
			$billing_address->City     	   = $request->billing_city;
			$billing_address->fkey_StateId = $request->billing_state;
			$billing_address->PostalCode   = $request->billing_zip;
			$billing_address->updated_by   = $user->rcid;
			$billing_address->save();
		}
		$student->save();

		return redirect()->action('StudentInformationController@residenceInfo');
	}

	public function residenceInfo(){
		$user          = RCAuth::user(); 
		$student 	   = self::getStudent($user->rcid);
		$local_address = Address::where('RCID', $user->rcid)->where('fkey_AddressTypeId', 4)->first();
		$states        = States::all();

		return view('residence_hall', compact('local_address', 'student', 'states'));
	}

	public function residenceInfoUpdate(Request $request){
		$user          = RCAuth::user(); 
		$student 	   = self::getStudent($user->rcid);

		$local_address = Address::where('RCID', $user->rcid)->where('fkey_AddressTypeId', 4)->first();
		if($request->residence == "hall"){
			// Need to delete all local info
			$student->home_as_local = 0;
			if(!empty($local_address)){
				self::deleteObject($local_address);
			}
		}else if($request->residence == "home"){
			// need to delete local address and update student bit
			if(!empty($local_address)){
				self::deleteObject($local_address);
			}
			$student->home_as_local = 1;
		}else{
			// need to update student bit and update local address
			$student->home_as_local = 0;
			if(empty($local_address)){
				// Local Address is currently empty and needs to be created
				$local_address 					   = new Address;
				$local_address->fkey_AddressTypeId = 4;
				$local_address->RCID 			   = $user->rcid;	
				$local_address->created_by 		   = $user->rcid;
			}
			// updating local address information
			$local_address->Address1	 = $request->local_Address1;
			$local_address->Address2	 = $request->local_Address2;
			$local_address->City     	 = $request->local_city;
			$local_address->fkey_StateId = $request->local_state;
			$local_address->PostalCode   = $request->local_zip;
			$local_address->updated_by   = $user->rcid;
			$local_address->save();
		}
		$student->save();

		return redirect()->action('StudentInformationController@citizenInfo');
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
		$user          = RCAuth::user(); 
		$student 	   = self::getStudent($user->rcid);

		$medications    = Medications::where('rcid', $user->rcid)->first();
		$med_allergy    = MedicationAllergies::where('rcid', $user->rcid)->first();
		$insect_allergy = InsectAllergies::where('rcid', $user->rcid)->first();

		if(empty($medications)){
			// ASSERT: First time student is submitting this information
			// creating new medication information
			$medications = New Medications;
			$medications->rcid       = $user->rcid;
			$medications->created_by = $user->rcid;
		}
		if(!empty($request->medications)){	
			// User takes medications		
			$medications->take_medications = 1;
			$medications->medications 	   = $request->medications_text;
		}else{
			// User does not take medication
			$medications->take_medications = 0;
			$medications->medications      = '';
		}
		$medications->updated_by = $user->rcid;
		$medications->save();

		if(empty($med_allergy)){
			// ASSERT: First time student is submitting this information
			// Creating new allergy medication object
			$med_allergy = new MedicationAllergies;
			$med_allergy->rcid = $user->rcid;
			$med_allergy->created_by = $user->rcid;
		}
		if(!empty($request->med_allergies)){
			// User has medication allergies
			$med_allergy->have_medication_allergies = 1;
			$med_allergy->medication_allergies      = $request->med_allergy_text;
		}else{
			// User does not have medication allergies
			$med_allergy->have_medication_allergies = 0;
			$med_allergy->medication_allergies      = '';
		}		
		$med_allergy->updated_by = $user->rcid;
		$med_allergy->save();

		if(empty($insect_allergy)){
			// ASSERT: First time student is submitting this information
			// Creating new Insect allergy object
			$insect_allergy = new InsectAllergies;
			$insect_allergy->rcid = $user->rcid;
			$insect_allergy->created_by = $user->rcid;
		}
		if(!empty($request->insect_allergies)){
			// The user has insect allergies
			$insect_allergy->have_insect_allergies = 1;
			$insect_allergy->insect_allergies      = $request->insect_allergy_text;
		}else{
			// The user does not have insect allergies
			$insect_allergy->have_insect_allergies = 0;
			$insect_allergy->insect_allergies      = '';
		}
		$insect_allergy->updated_by = $user->rcid;
		$insect_allergy->save();

		// UPDATE TO CORRECT PATH
		return redirect()->action('StudentInformationController@medicalInfo');

	}

	public function medicalInfo(){
		$user          = RCAuth::user(); 
		$student 	   = self::getStudent($user->rcid);
		$health_concerns = HealthConcerns::with('student_concerns')->get();
		$other_concern   = OtherConcerns::where('rcid', $user->rcid)->first();

		return view('medical', compact('user', 'health_concerns','other_concern'));
	}

	public function medicalInfoUpdate(Request $request){
		$user          = RCAuth::user(); 
		$student 	   = self::getStudent($user->rcid);
		// Call to delete all previous medical concerns
		self::emptyConcerns($user->rcid);

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
		}else{
			// Assert: we have no other concerns
			if(!empty($other_concern)){
				// ASSERT: we have old concerns
				// deleting old concerns
				self::deleteObject($other_concern);
			}
		}

		// update that the student submitted this page
		$student->submitted_health_concerns = 1;
		$student->save();

		return redirect()->action('StudentInformationController@emergencyContact');
	}

	public function nonEmergency(){
		$user          = RCAuth::user(); 
		$student 	   = self::getStudent($user->rcid);

		return view('non_emergency', compact('student'));
	}

	public function nonEmergencyUpdate(Request $request){
		$user          = RCAuth::user(); 
		$student 	   = self::getStudent($user->rcid);

		if(!empty($request->non_emergency)){
			// ASSERT: Request for non_emergency contacts
			$student->non_emergency = 1;
		}else{
			// ASSERT: Refuse non emergency contacts
			$student->non_emergency = 0;
		}

		$student->save();

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

		// UPDATE TO CORRECT PATH
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

		if(!empty($request->info_release)){
			// Student gives information release permission
			$student->info_release = 1;
		}else{
			$student->info_release = 0;
		}
		$student->save();

		// Change to employement information
		return redirect()->action('StudentInformationController@index');
	}

	public function citizenInfo(){
		$user          = RCAuth::user(); 
		$student 	   = self::getStudent($user->rcid);		

		$us_resident   = USResidence::where('RCID', $user->rcid)->first();
		$foreign       = CitizenshipInformation::where('RCID', $user->rcid)->get();

		$visa_types    = VisaTypes::all();
		$visa          = VisaTypeMap::where('RCID', $user->rcid)->first();

		$states        = States::all();
		$countries     = Countries::all();
		$counties      = Counties::all();

		$updated_counties = collect();

		// Making counties and cities easier to read and sorting alphabetically
		foreach($counties as $county){
			$display = $county->description;
			if(strpos($display, 'Co:') !== false){
    			$display = str_replace("Co: ", "", $display);
    			$display .= " County";
    		}
			if(strpos($display, 'Ct:') !== false){
				$display = str_replace("Ct: ", "", $display);
			}
			$updated_counties[$county->county_id] = $display;
		}

		$updated_counties = $updated_counties->sort();

		return view('citizen_info', compact('countries', 'student', 'us_resident', 'foreign', 'visa_types', 'visa', 'counties', 'states', 'updated_counties'));
	}

	public function citizenInfoUpdate(Request $request){
		$user          = RCAuth::user(); 
		$student 	   = self::getStudent($user->rcid);	
		$visa          = VisaTypeMap::where('RCID', $user->rcid)->first();	
		$us_resident   = USResidence::where('RCID', $user->rcid)->first();
		$foreign       = CitizenshipInformation::orderBy('ID')->where('RCID', $user->rcid)->get();

		if($request->US_citizen == "US_citizen"){
			// Student is a US Citizen
			$student->us_citizen = 1;
			if(empty($us_resident)){
				// ASSERT: No previous US citizenship information
				$us_resident 			 = New USResidence;
				$us_resident->RCID 		 = $user->rcid;
				$us_resident->created_by = $user->rcid;
			}
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
			if(!empty($us_resident)){
				// Delete old Residence infomation
				self::deleteObject($us_resident); 
			}
		}


		if($request->other_citizen == "other_citizen"){
			// Student has a non-US Citizenship
			$student->other_citizen = 1;
			if(empty($foreign[0])){
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

		return redirect()->action('StudentInformationController@allergyInfo');
	}


	public function parentAndGuardianInfo(){
		$user          = RCAuth::user(); 
		$student 	   = self::getStudent($user->rcid);	

		$guardians     = GuardianInfo::where('student_rcid', $user->rcid)->get();	
		//dd($guardians);
		return view('parent_guardian_info', compact('guardians'));
	}

	public function individualGuardian($id = NULL){
		$user     = RCAuth::user(); 
		$student  = self::getStudent($user->rcid);	

		$guardian = GuardianInfo::where('id', $id)->where('student_rcid', $user->rcid)->first();
		$marital  = MaritalStatuses::all();
		$states   = States::all();

		return view('guardian_verification', compact('guardian','marital','states'));
	}

	public function parentAndGuardianInfoUpdate(Request $request, $id = null){
		$user     = RCAuth::user(); 
		$student  = self::getStudent($user->rcid);	

		$guardian = GuardianInfo::where('id', $id)->where('student_rcid',$user->rcid)->first();
		
		if(empty($guardian)){			
			$guardian = new GuardianInfo;
			$guardian->student_rcid   = $user->rcid;
			$guardian->created_by 	 = $user->rcid;
		}
		$guardian->first_name     = $request->first_name;
		$guardian->nick_name      = $request->nick_name;
		$guardian->middle_name    = $request->middle_name;
		$guardian->last_name      = $request->last_name;
		$guardian->relationship   = $request->relationship;
		$guardian->email          = $request->email;
		$guardian->home_phone     = $request->home_phone;
		$guardian->cell_phone     = $request->cell_phone;
		$guardian->Address1       = $request->Address1;
		$guardian->Address2       = $request->Address2;
		$guardian->City           = $request->city;
		$guardian->fkey_StateCode = $request->state;
		$guardian->PostalCode     = $request->zip;
		$guardian->joint_mail1    = $request->joint1;
		$guardian->joint_mail2    = $request->joint2;
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

		return redirect()->action('StudentInformationController@infoRelease', ['id' => $guardian->id]);
	}

	public function emergencyContact(){
		$user     = RCAuth::user(); 
		$student  = self::getStudent($user->rcid);

		$contacts = EmergencyContact::where('student_rcid', $user->rcid)->where('missing_person',0)->get();

		return view('emergency_contacts', compact('contacts'));
	}

	public function individualEmergencyContact($id = null){
		$user     = RCAuth::user(); 
		$student  = self::getStudent($user->rcid);

		$contact = EmergencyContact::where('id', $id)->where('missing_person',0)->first();

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

		$emergency_contact = EmergencyContact::where('student_rcid', $user->rcid)->where('id',$id)->where('missing_person', 0)->first();

		if(empty($emergency_contact)){
			$emergency_contact = new EmergencyContact;
			$emergency_contact->student_rcid   = $user->rcid;
			$emergency_contact->missing_person = 0;
			$emergency_contact->created_by 	 = $user->rcid;
		}
		$emergency_contact->name = $request->contact_name;
		$emergency_contact->relationship = $request->relationship;
		$emergency_contact->day_phone = $request->daytime_phone;
		$emergency_contact->evening_phone = $request->evening_phone;
		$emergency_contact->cell_phone = $request->cell_phone;
		$emergency_contact->updated_by = $user->rcid;
		$emergency_contact->save();


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
		$missing_contact->updated_by = $user->rcid;
		$missing_contact->save();

		return redirect()->action('StudentInformationController@nonEmergency');
	}

	public function deleteContact($id){	
		$user     = RCAuth::user(); 
		$student  = self::getStudent($user->rcid);

		$contact = EmergencyContact::where('student_rcid', $user->rcid)->where('id', $id)->first();
		if(!empty($contact)){
			self::deleteObject($contact);
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
		}
		return $student;
	}

	// Pre :
	// Post: Returns what sections are completed in an array
	private function completedSections(){
		$sections = [];
		$sections['Personal Information']     	 = ['status' => self::completedPersonalInfo(),    'link' => "personal_info"];
		$sections['Address Information']      	 = ['status' => self::completedAddressInfo(),	  'link' => "address_info"];
		$sections['Residence Information']   	 = ['status' => self::completedResidenceInfo(),   'link' => "residence_info"];
		$sections['Citizenship Information'] 	 = ['status' => self::completedCitizenShipInfo(), 'link' => "citizen_info"];
		$sections['Allergy Information']   	   	 = ['status' => self::completedAllergyInfo(),  	  'link' => "allergy_info"];
		$sections['Medical Information']   	   	 = ['status' => self::completedHealthInfo(),      'link' => "medical_info"];
		$sections['Emergency Contact']		 	 = ['status' => self::completedEmergency(),		  'link' => "emergency_contact"];
		$sections['Missing Person']			 	 = ['status' => self::completedMissingPerson(),	  'link' => "missing_person"];
		$sections['Non Emergency Contact']   	 = ['status' => self::completedNonEmergency(), 	  'link' => "non_emergency"];
		$sections['Independent Student']     	 = ['status' => self::completedIndependentInfo(), 'link' => "independent_student"];
		$sections['Parent/Guardian Information'] = ['status' => self::completedParentInfo(),      'link' => "parent_info"];
		return $sections;
	}

	// Pre:
	// Post: Checks that the logged in user has filled out all required sections for the Personal Info Section
	private function completedPersonalInfo(){
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);		
		$cell_phone = PhoneMap::where('RCID', $user->rcid)->where('fkey_PhoneTypeId',3)->first();
		$home_phone = PhoneMap::where('RCID', $user->rcid)->where('fkey_PhoneTypeId',1)->first();
		$user_races = RaceMap::where('fkey_rcid', $user->rcid)->pluck('fkey_race_code')->toArray();

		if( !empty($student) 				   && !empty($student->first_name) && 
		    !empty($student->last_name) 	   && !empty($student->fkey_marital_status) &&
		    !empty($student->fkey_military_id) && !empty($student->ethnics) &&
		    !empty($cell_phone) 			   && !empty($home_phone) &&
		    !empty($user_races)){
			// Assert: All Required information is filled out
			$return = "Pending";
		}else{
			// Assert: Missing Required information
			$return = false;
		}
		return $return;
	}

	// Pre:
	// Post: Checks that the logged in user has filled out all required sections for the Address Section
	private function completedAddressInfo(){
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);	
		$home_address    = Address::where('RCID', $user->rcid)->where('fkey_AddressTypeId', 1)->first();
		$billing_address = Address::where('RCID', $user->rcid)->where('fkey_AddressTypeId', 3)->first();
		if( !empty($student) && !empty($home_address)  &&
			!empty($home_address->Address1) && !empty($home_address->City) && !empty($home_address->fkey_StateId) && !empty($home_address->PostalCode) &&
		   (!empty($student->home_as_billing) || (!empty($billing_address) &&
			!empty($billing_address->Address1) && !empty($billing_address->City) && !empty($billing_address->fkey_StateId) && !empty($billing_address->PostalCode)))){
			// Assert: All Required information is filled out
			$return = "Pending";
		}else{
			// Assert: Missing Required information
			$return = false;
		}
		return $return;
	}

	// Pre :
	// Post: Checks that the medical fields were completed
	private function completedAllergyInfo(){
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);	

		$medications    = Medications::where('rcid', $user->rcid)->first();
		$med_allergy    = MedicationAllergies::where('rcid', $user->rcid)->first();
		$insect_allergy = InsectAllergies::where('rcid', $user->rcid)->first();

		if(!empty($medications) && !empty($med_allergy) && !empty($insect_allergy)){
			$return = "Pending";
		}else{
			$return = false;
		}

		return $return;
	}

	// Pre :
	// Post: Checks that the form is completed
	private function completedHealthInfo(){
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);	

		if(!is_null($student->submitted_health_concerns)){
			// Assert: If NULL than we have not completed the form
			$return = "Pending";
		}else{
			$return = false;
		}

		return $return;
	}

	// Pre :
	// Post: Checks that the form is completed
	private function completedResidenceInfo(){
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);	

		if(!is_null($student->home_as_local)){
			// Assert: If NULL than we have not completed the form
			$return = "Pending";
		}else{
			$return = false;
		}

		return $return;
	}

	// Pre :
	// Post: Checks that the form is completed
	private function completedIndependentInfo(){
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);	

		if(!is_null($student->independent_student)){
			// Assert: If NULL than we have not completed the form
			$return = "Pending";
		}else{
			$return = false;
		}

		return $return;
	}

	// Pre :
	// Post: Checks that the form is completed
	private function completedInfoRelease(){
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);	

		if(!is_null($student->info_release)){
			// Assert: If NULL than we have not completed the form
			$return = "Pending";
		}else{
			$return = false;
		}

		return $return;
	}

	// Pre :
	// Post: Checks that the form is completed
	private function completedNonEmergency(){
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);	

		if(!is_null($student->non_emergency)){
			// Assert: If NULL than we have not completed the form
			$return = "Pending";
		}else{
			$return = false;
		}

		return $return;
	}

	// Pre :
	// Post: Checks that the form is completed
	private function completedCitizenShipInfo(){
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);	

		if(!is_null($student->us_citizen)){
			// Assert: If NULL than we have not completed the form
			$return = "Pending";
		}else{
			$return = false;
		}

		return $return;
	}

	// Pre :
	// Post: checks that the emergency forms are completed
	private function completedEmergency(){		
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);	
		return false;
	}

	// Pre :
	// Post: checks that the emergency forms are completed
	private function completedMissingPerson(){
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);	

		$missing_person = EmergencyContact::where('student_rcid', $user->rcid)->where('missing_person',1)->first();

		if(!empty($missing_person)){
			// We have the object and they have submitted a missing person contact
			$return = "Pending";
		}else{
			$return = false;
		}
		return $return;
	}

	// Pre :
	// Post: checks that the emergency forms are completed
	private function completedParentInfo(){
		$user       = RCAuth::user();
		$student 	= self::getStudent($user->rcid);	
		return "Pending";
	}
	
}

?>	