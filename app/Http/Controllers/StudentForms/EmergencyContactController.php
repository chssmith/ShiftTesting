<?php

namespace App\Http\Controllers\StudentForms;

use App\CompletedSections;
use App\EmergencyContact;
use App\Http\Controllers\Controller;
use App\Students;
use App\User;
use Illuminate\Http\Request;
use RCAuth;

class EmergencyContactController extends SectionController
{
    public function show(Students $student, User $vpb_user, CompletedSections $completed_sections)
    {
        $contacts = EmergencyContact::where('student_rcid', $student->RCID)->where('emergency_contact', 1)->get();

        return view('emergency_contacts', compact('contacts'));
    }

    public function store(Request $request, Students $student, CompletedSections $completed_sections)
    {
    }

    public function showEmergencyContact(Students $student, $id = null)
    {
        $user = RCAuth::user();

        $contact = EmergencyContact::where('id', $id)->where('student_rcid', $student->RCID)->where('emergency_contact', 1)->first();

        if (empty($id) || ! empty($contact)) {
            $redirect = view('individual_emergency_contact', compact('contact', 'id'));
        } else {
            $redirect = redirect()->action('StudentInformationController@index');
        }

        return $redirect;
    }

    public function storeEmergencyContact(Request $request, Students $student, CompletedSections $completed_sections, $id = null)
    {
        $user = RCAuth::user();

        $emergency_contact = EmergencyContact::where('student_rcid', $user->rcid)->where('id', $id)->where('emergency_contact', 1)->first();

        if (empty($emergency_contact)) {
            $emergency_contact = new EmergencyContact;
            $emergency_contact->student_rcid = $user->rcid;
            $emergency_contact->missing_person = 0;
            $emergency_contact->created_by = $user->rcid;
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
        return redirect()->action('StudentForms\EmergencyContactController@show');
    }

    public function deleteContact(Students $student, CompletedSections $completed_sections, $id)
    {
        $user = RCAuth::user();

        $contact = EmergencyContact::where('student_rcid', $user->rcid)->where('id', $id)->first();
        if (! empty($contact)) {
            if ($contact->missing_person) {
                $contact->emergency_contact = 0;
                $contact->updated_by = $user->rcid;
                $contact->save();
            } else {
                $contact->deleted_by = $user->rcid;
                $contact->save();
                $contact->delete();
            }
        }

        self::completedEmergency($student, $completed_sections);

        return redirect()->action('StudentForms\EmergencyContactController@show');
    }

    // Pre :
    // Post: checks that the emergency forms are completed
    private function completedEmergency(Students $student, CompletedSections $completed_sections)
    {
        $contacts = EmergencyContact::where('student_rcid', $student->RCID)
                                                                            ->where('emergency_contact', 1)
                                                                            ->get();
        $completed_sections->emergency_information = $contacts->count() > 0 && $contacts->reduce(function ($collector, $item) {
            return $collector && $item->completed();
        }, true);
        $completed_sections->updated_by = RCAuth::user()->rcid;
        $completed_sections->save();
    }

    public function emergencyDoubleCheck(Students $student, CompletedSections $completed_sections)
    {
        self::completedEmergency($student, $completed_sections);

        return redirect()->action('StudentInformationController@nonEmergency');
    }

    public function getMissingInformation(Students $student)
    {
        $contacts = EmergencyContact::where('student_rcid', $student->RCID)
                                                                            ->where('emergency_contact', 1)
                                                                            ->get();

        $messages = collect();
        if ($contacts->count() == 0) {
            $messages[] = 'At least one emergency contact is required.';
        }

        $messages = $contacts->reduce(function ($collector, $item) {
            return $collector->merge($item->getMissingInformation());
        }, $messages);

        return $messages;
    }
}
