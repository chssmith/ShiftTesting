<?php

namespace App\Http\Controllers\StudentForms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use RCAuth;

use App\User;
use App\Students;
use App\CompletedSections;

use App\EmergencyContact;

class MissingPersonController extends SectionController
{
  public function show (Students $student, User $vpb_user, CompletedSections $completed_sections){
		$user    = RCAuth::user();
		$contact = EmergencyContact::where('student_rcid', $student->RCID)->where('missing_person', 1)->first();

		return view('missing_person', compact('contact'));
	}

	public function store (Request $request, Students $student, CompletedSections $completed_sections) {
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

		return redirect()->action('StudentForms\EmergencyContactController@show');
	}

  public function getMissingInformation (Students $student) {
    $missing_contact = EmergencyContact::where("student_rcid", $student->RCID)->where("missing_person", 1)->first();

    $scope = [
      '$missing_contact' => $missing_contact
    ];

    $requirements = [
      '$missing_contact->completed()' => "Please make sure you provide at least one contact number for your missing person contact"
    ];

    return self::getMessages($requirements, $scope);
  }
}
