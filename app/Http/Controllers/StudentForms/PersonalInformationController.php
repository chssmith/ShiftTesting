<?php

namespace App\Http\Controllers\StudentForms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use RCAuth;

use App\CompletedSections;
use App\Students;
use App\User;

use App\PhoneMap;
use App\RaceMap;
use App\DatamartPhones;
use App\Races;
use App\MilitaryOptions;
use App\MaritalStatuses;

class PersonalInformationController extends SectionController
{
  public function show (Students $student, User $vpb_user, CompletedSections $completed_sections){
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

	public function store (Request $request, Students $student, CompletedSections $completed_sections){
		$user    = RCAuth::user();
		$student = $student->load('ssn');

		// Updating all the student information
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
													!(empty($cell_phone->PhoneNumber) && empty($home_phone->PhoneNumber));

		$completed_sections->personal_information = $personal_completed;
		$completed_sections->updated_by = $user->rcid;
		$completed_sections->save();

		return redirect()->action('StudentForms\AddressInformationController@show');
	}

  public function getMissingInformation (Students $student) {
		$races       = RaceMap::where('fkey_rcid', $student->RCID)->count();
		$phone_map   = PhoneMap::where("RCID", $student->RCID)->whereNotNull("PhoneNumber")->whereIn("fkey_PhoneTypeId", [3, 1])->count();

		$requirements = [
			'!empty($student->first_name)'          => "Missing First Name",
			'!empty($student->last_name)'           => "Missing Last Name",
			'!empty($student->fkey_marital_status)' => "Missing Marital Status",
			'!is_null($student->fkey_military_id)'  => "Missing Military Status",
			'!is_null($student->ethnics)'           => "Missing Ethnicity",
			'$races > 0'                            => 'Missing Race Information',
			'$phone_map > 0'                        => "Missing Phone Number"
		];

		return self::getMessages($requirements, ['$student' => $student, '$races' => $races, '$phone_map' => $phone_map]);
	}
}
