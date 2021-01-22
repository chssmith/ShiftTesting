<?php

namespace App\Http\Controllers\StudentForms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use RCAuth;

use App\User;
use App\Students;
use App\CompletedSections;

use App\HealthConcerns;
use App\OtherConcerns;
use App\StudentConcerns;

use App\ODS\MedicalData as ODSMedicalData;

class MedicalInformationController extends SectionController
{
  public function show(Students $student, User $vpb_user, CompletedSections $completed_sections){
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

	public function store(Request $request, Students $student, CompletedSections $completed_sections){
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

		return redirect()->action('StudentForms\MissingPersonController@show');
	}

  public function getMissingInformation (Students $student) {
    $scope        = [
        '$student'          => $student,
        '$other_concern'    => OtherConcerns::where("rcid", $student->RCID)->first()
    ];

    $requirements = [
      '$student->submitted_health_concerns' => "Please Verify Medical Information",
      '(empty($other_concern) || !empty($other_concern->other_concern))' => "Missing Other Concerns"
    ];

    return self::getMessages($requirements, $scope);
  }
}
