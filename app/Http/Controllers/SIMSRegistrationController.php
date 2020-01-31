<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Students;
use App\SIMSSessions;
use App\SIMSRegistrations;
use Carbon\Carbon;

class SIMSRegistrationController extends Controller
{
    public function index (Students $student) {
      $messages      = collect();

      $sessions      = SIMSSessions::orderBy("start_date")->get();
      $registrations = SIMSRegistrations::select(\DB::raw("sims_sessions.id AS id"), \DB::raw("COUNT(rcid) AS num_registrations"))
                                        ->rightJoin("student_forms.sims_sessions", "sims_sessions.id", "fkey_sims_session_id")
                                        ->groupBy("sims_sessions.id")
                                        ->get()
                                        ->keyBy("id");

      return view()->make("sims.index", compact("sessions", "registrations", "messages"));
    }

    public function store (Students $student, Request $request) {
      $request->validate([
        "orientation_session" => "required|numeric",
      ]);


      $file = fopen(storage_path("reg_lock"), "w");
      if(flock($file, LOCK_EX)) {
        $session_id             = $request->input("orientation_session");
        $potential_registration = SIMSSessions::find($session_id);

        $registrations          = SIMSRegistrations::select(\DB::raw("sims_sessions.id AS id"), \DB::raw("COUNT(rcid) AS num_registrations"))
                                                     ->rightJoin("student_forms.sims_sessions", "sims_sessions.id", "fkey_sims_session_id")
                                                     ->where("sims_sessions.id", $session_id)
                                                     ->groupBy("sims_sessions.id")
                                                     ->first();

        if ($potential_registration->registration_limit - $registrations->num_registrations > 0) {
          $new_reg = SIMSRegistrations::firstOrNew(["rcid" => $student->RCID],
                                                   ['created_by' => $student->RCID, 'updated_by' => $student->RCID]);

          if (empty($new_reg->fkey_sims_session_id)) {
            $new_reg->fkey_sims_session_id = $session_id;
            $new_reg->save();

            $redirect = redirect()->action("SIMSRegistrationController@stage1Confirmation");

            $vpb_student = \App\User::find($student->RCID);
            try {
  				    \App\EmailQueue::sendEmailOrientation($vpb_student->CampusEmail,
                                                    "Summer Orientation Registration Confirmation",
                                                    view()->make("sims.stage1.partials.confirmation_body", ["registered_session" => $new_reg->load("session_dates")])->render());
            } catch (\Exception $e) {
              $redirect = $redirect->with("message", "We were unable to locate your email address.  Please contact <a href='mailto:orientation@roanoke.edu'>orientation@roanoke.edu</a> to confirm your registration. ");
            }
          } else {
            $redirect = redirect()->action("SIMSRegistrationController@stage1Confirmation")->with("message", "You have already registered for the dates listed below.");
          }
        } else {
          $redirect = redirect()->action("SIMSRegistrationController@index")->with("message", "That session has filled up.  Please choose another session to continue.");
        }

        flock($file, LOCK_UN);
      }

      fclose($file);
      return $redirect;
    }

    public function stage1Confirmation (Students $student, Request $request) {
      $messages = collect();
      if (\Session::has("message")) {
        $messages["message"] = \Session::get("message");
      }

      $registered_session = SIMSRegistrations::where("rcid", $student->RCID)->with("session_dates")->first();

      if (empty($registered_session)) {
        return redirect()->action("SIMSRegistrationController@index");
      }

      return view()->make("sims.stage1.confirm", compact("registered_session", "messages"));
    }
}
