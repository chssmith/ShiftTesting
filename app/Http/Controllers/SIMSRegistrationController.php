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

    //********************************
    // BEGIN Administrative Functions
    //********************************
    public function adminIndex () {
      return view()->make("sims.admin.index");
    }

    public function adminRegistrationLookup () {
      return view()->make("sims.admin.student_lookup");
    }

    public function adminRegistrationTypeahead (Request $request) {
      $request->validate(["search" => "required"]);
      $search_terms = $request->input("search");

      if(strlen($search_terms) < 3) {
        return response()->json([]);
      }
      $search_terms = explode(' ', $search_terms);

      $students = \App\User::where(function ($query) use ($search_terms) {
        foreach($search_terms as $term) {
          $query->Where(function ($search_query) use ($term) {
            $search_query->where("FirstName", "LIKE", sprintf("%%%s%%", $term))
                         ->orWhere("LastName", "LIKE", sprintf("%%%s%%", $term))
                         ->orWhere("MiddleName", "LIKE", sprintf("%%%s%%", $term))
                         ->orWhere("nick_name", "LIKE", sprintf("%%%s%%", $term))
                         ->orWhere("NickName", "LIKE", sprintf("%%%s%%", $term))
                         ->orWhere("RCID", "LIKE", sprintf("%%%s%%", $term));
          });
        }
      })->get();

      $response = [];

      foreach($students as $student) {
        $response_entry                 = [];
        $response_entry['id']           = $student->RCID;
        $response_entry['display_data'] = view()->make("sims.admin.partials.typeahead", ['person' => $student])->render();
        $response_entry['input_data']   = $student->display_name;
        $response[]                     = $response_entry;
      }

      return ["data" => $response];
    }

    public function adminRegistrationPullRegistration (Request $request) {
      $request->validate(["student_rcid" => "required"]);

      $admin         = true;
      $student_id    = $request->input("student_rcid");
      $student       = \App\User::find($student_id);
      $registration  = SIMSRegistrations::where("rcid", $student_id)->first();
      $sessions      = SIMSSessions::orderBy("start_date")->get();
      $registrations = SIMSRegistrations::select(\DB::raw("sims_sessions.id AS id"), \DB::raw("COUNT(rcid) AS num_registrations"))
                                        ->rightJoin("student_forms.sims_sessions", "sims_sessions.id", "fkey_sims_session_id")
                                        ->groupBy("sims_sessions.id")
                                        ->get()
                                        ->keyBy("id");

      return view()->make("sims.admin.registration_edit", compact("student", "registration", "sessions", "registrations", "admin"));
    }

    public function adminRegistrationStore (Request $request) {
        $request->validate([
          "orientation_session" => "required|numeric",
          "student_rcid"        => "required"
        ]);

        $student_rcid = $request->input("student_rcid");
        $admin_rcid   = \RCAuth::user()->rcid;

        $file = fopen(storage_path("reg_lock"), "w");
        if(flock($file, LOCK_EX)) {
          $session_id = $request->input("orientation_session");
          $new_reg    = SIMSRegistrations::firstOrNew(["rcid" => $student_rcid],
                                                      ['created_by' => $admin_rcid]);

          $new_reg->updated_by           = $admin_rcid;
          if ($session_id != -1) {
            $new_reg->fkey_sims_session_id = $session_id;
          } else {
            $new_reg->fkey_sims_session_id = -1;
            $new_reg->cannot_attend = 1;
          }
          $new_reg->save();

          $vpb_student = \App\User::find($student_rcid);
          \App\EmailQueue::sendEmailOrientation($vpb_student->CampusEmail,
                                                "Summer Orientation Registration Confirmation",
                                                view()->make("sims.stage1.partials.confirmation_body", ["registered_session" => $new_reg->load("session_dates")])->render());

          flock($file, LOCK_UN);
        }
        fclose($file);

        return view()->make("sims.stage1.confirm", ["registered_session" => $new_reg->load("session_dates"), "messages" => collect()]);
      }

      public function adminRegistrationReport (Request $request) {
        $all_registrations = SIMSRegistrations::with(["session_dates", "student"])->get();

        return view()->make("sims.admin.stage1.report", compact("all_registrations"));
      }

      public function adminRegistrationReportExcel (Request $request) {
        return \Excel::download(new \App\Exports\SIMSRegistrationExport, "sims_registrations.xlsx");
      }

    //********************************
    // END Administrative Functions
    //********************************

}
