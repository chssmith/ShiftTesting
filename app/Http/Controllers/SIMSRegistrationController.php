<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use RCAuth;
use Carbon\Carbon;

use App\User;
use App\Students;
use App\EmailQueue;
use App\SIMSSessions;
use App\SIMSGuestInfo;
use App\SIMSStudentInfo;
use App\SIMSModeOfTravel;
use App\SIMSRegistrations;

class SIMSRegistrationController extends Controller
{
    public function index (Students $student) {
      $messages      = collect();
      $sessions      = SIMSSessions::orderBy("start_date")->get();
      $registrations = SIMSRegistrations::select(\DB::raw("sessions.id AS id"), \DB::raw("COUNT(rcid) AS num_registrations"))
                                        ->rightJoin("sims.sessions", "sessions.id", "fkey_sims_session_id")
                                        ->groupBy("sessions.id")
                                        ->get()
                                        ->keyBy("id");

      return view()->make("sims.index", compact("sessions", "registrations", "messages"));
    }

    //TYPE:  GET
    //BLADE: sims.session_selection
    //POST:  makes the session selection page
    // public function sessionSelectionPage (Students $student) {
    //   $sessions = SIMSSessions::orderBy("start_date")->get();
    //
    //
    //   return view()->make("sims.session_selection", compact("sessions"));
    //
    // }

    //TYPE: POST
    //FROM: AJAX on sims.session_selection
    //POST: adds the session id to the session variables
    // public function sessionSelection(Request $request){
    //   session(['session_id' => $request->id]);
    //   //Set the user info we have in datamart
    //   $user = User::find(RCAuth::user()->rcid);
    //   $sess = session("student_info");
    //   if(!isset($sess)){
    //     $student_info = [
    //       "nick_name"          => $user->NickName,
    //       "gender"             => $user->gender,
    //       "cell_phone"         => $user->CellPhone,
    //       "has_dietary_needs"  => "",
    //       "dietary_needs"      => "",
    //       "has_physical_needs" => "",
    //       "physical_needs"     => ""
    //     ];
    //     session(["student_info" => $student_info]);
    //   }
    // }

    //TYPE:  GET
    //BLADE: sims.student_info
    //POST:  makes the student info page
    public function studentInfoPage(){
      $rcid = RCAuth::user()->rcid;
      $info = SIMSStudentInfo::where("rcid", $rcid)->first();
      $registration = SIMSRegistrations::where("rcid", $rcid)->first();
      if(!isset($registration)){
        return  redirect()->action("SIMSRegistrationController@index");
      }

      if(isset($info)){
        $id = $registration->id;
        return redirect()->action("SIMSRegistrationController@endingPage", ["id"=>$id, "err"=>1]);
      }
      $user = User::find($rcid);
      $sess = session("student_info");
      if(!isset($sess)){
        $sess = [
          "nick_name"          => $user->NickName,
          "gender"             => $user->gender,
          "cell_phone"         => $user->CellPhone,
          "has_dietary_needs"  => "",
          "dietary_needs"      => "",
          "has_physical_needs" => "",
          "physical_needs"     => ""
        ];
        session(["student_info" => $sess]);
      }
      //dd($sess);

      $session_dates = $registration->session_dates->date_string;

      return view()->make("sims.student_info", compact("sess", "session_dates"));
    }

    //TYPE: POST
    //FROM: sims.student_info
    //POST: saves the student info in session
    public function studentInfo(Request $request){
      $student_info = [
        "nick_name"          => $request->preferred_name,
        "gender"             => $request->gender,
        "cell_phone"         => $request->phone,
        "has_dietary_needs"  => $request->has_dietary_needs,
        "dietary_needs"      => $request->dietary_needs,
        "has_physical_needs" => $request->has_physical_needs,
        "physical_needs"     => $request->physical_needs
      ];
      session(["student_info" => $student_info]);
      return redirect()->action("SIMSRegistrationController@parentsGuestsPage");
    }

    //TYPE:  GET
    //BLADE: sims.parents_guests
    //POST:  creates the parents/guardians/guests page
    public function parentsGuestsPage(Request $request){
      $sess = $request->session()->get("parents_guests", []);

      session(["v_pg"=>true]);
      //ASSERT: set v_pg in case they don't have any guests and don't fill out the page

      $registration = SIMSRegistrations::where("rcid", RCAuth::user()->rcid)->with("session_dates")->first();
      $session_dates = $registration->session_dates->date_string;

      return view()->make("sims.parents_guests", compact("sess", "session_dates"));
    }

    //TYPE: POST
    //FROM: sims.parents_guests
    //POST: saves the parent info in session
    public function parentsGuests(Request $request){
      $parents_guests = $request->all();
      unset($parents_guests["_token"]);
      // dd($parents_guests);
      session(["parents_guests" => $parents_guests]);

      return redirect()->action("SIMSRegistrationController@modeOfTravelPage");
    }

    //TYPE:  GET
    //BLADE: sims.mode_of_travel
    //POST:  creates the mode of travel page
    public function modeOfTravelPage(Request $request){
      $MOT = SIMSModeOfTravel::get();
      $sess = $request->session()->get("mode_of_travel", []);

      $registration = SIMSRegistrations::where("rcid", RCAuth::user()->rcid)->with("session_dates")->first();
      $session_dates = $registration->session_dates->date_string;

      return view()->make("sims.mode_of_travel", compact("MOT", "sess", "session_dates"));
    }

    //TYPE: POST
    //FROM: sims.mode_of_travel
    //POST: saves the mode of travel info in session
    public function modeOfTravel(Request $request){
      $mode_of_travel = $request->all();
      unset($mode_of_travel["_token"]);
      // dd($mode_of_travel);
      session(["mode_of_travel" => $mode_of_travel]);

      return redirect()->action("SIMSRegistrationController@confirmationPage");
    }

    //TYPE:  GET
    //BLADE: sims.confirmation
    //POST:  creates the confirmation page
    public function confirmationPage(Request $request){
      $si = $request->session()->get("student_info", []);
      $pg = $request->session()->get("v_pg", false);
      $mot = $request->session()->get("mode_of_travel", []);

      $all = ((count($si)>0) && ($pg) && (count($mot)>0));

      $guests = $request->session()->get("parents_guests", []);
      $num_guests = 0;
      if(count($guests)>0){
        $num_guests = count($guests["relationship"]);
      }

      $registration = SIMSRegistrations::where("rcid", RCAuth::user()->rcid)->with("session_dates")->first();
      $session_dates = $registration->session_dates->date_string;

      return view()->make("sims.confirmation", ["student_info"=>(count($si)>0), "guests"=>($pg), "mode_of_travel"=>(count($mot)>0), "stop"=>(!$all), "num_guests"=>($num_guests), "session_dates"=>($session_dates)]);
    }

    //TYPE: POST
    //FROM: sims.confirmation
    //POST: saves all information pertaining to sims registration to the database
    public function confirmation(Request $request){

      $rcid = RCAuth::user()->rcid;

      //Double check to make sure they have actually filled out everthing
      $si = $request->session()->get("student_info", []);
      $pg = $request->session()->get("v_pg", false);
      $mot = $request->session()->get("mode_of_travel", []);

      if(!((count($si)>0) && ($pg) && (count($mot)>0))){
        return redirect()->action("SIMSRegistrationController@confirmationPage");
      }

      //Store info in Database
      $pg = $request->session()->get("parents_guests", []);
      // dd($pg);

      //Registration
      $registration = SIMSRegistrations::where("rcid", $rcid)->first();
      $registration->shuttle = ($mot["shuttle"] == "yes");
      $registration->fkey_mode_of_travel_id = $mot["mode_of_travel"];
      $registration->save();

      //Student Info
      $student_info = new SIMSStudentInfo;
      $student_info->rcid = $rcid;
      $student_info->nick_name = $si["nick_name"];
      $student_info->gender = $si["gender"];
      $student_info->cell_phone = $si["cell_phone"];
      $student_info->dietary_needs = $si["dietary_needs"];
      $student_info->physical_needs = $si["physical_needs"];
      $student_info->created_by = $student_info->updated_by = $rcid;
      $student_info->save();

      //Guests
      if(count($pg) > 0){
        for($i = 0; $i < count($pg["relationship"]); $i++){
          $guest = new SIMSGuestInfo;
          $guest->fkey_registration_id = $registration->id;
          $guest->relationship = $pg["relationship"][$i];
          $guest->first_name = $pg["first_name"][$i];
          $guest->last_name = $pg["last_name"][$i];
          $guest->email = $pg["email"][$i];
          $guest->dietary_needs = $pg["dietary_needs"][$i];
          $guest->physical_needs = $pg["physical_needs"][$i];
          $guest->on_campus = ($pg["on_campus"]=="yes");
          $guest->created_by = $guest->updated_by = $rcid;
          $guest->save();
        }
      }

      $session = SIMSSessions::find($registration->fkey_sims_session_id);

      $session_dates = $session->date_string;

      $guests = SIMSGuestInfo::where("fkey_registration_id", $registration->id)->get();
      $mot = SIMSModeOfTravel::find($registration->fkey_mode_of_travel_id)->travel_type;
      $shuttle = $registration->shuttle;

      //Send Email
      $email = new EmailQueue;
      $email->to_email   = User::find($rcid)->CampusEmail;
      $email->from_email = "orientation@roanoke.edu";
      $email->subject    = "Summer Orientation Registration";
      $email->body       = view()->make("sims.partials.complete", compact("session_dates", "student_info", "guests", "mot", "shuttle"))->render();
      $email->template   = "campusmailer.official";
      $email->created_by = $email->updated_by = "0000001";
      $email->save();

      //Redirect to ending page
      return redirect()->action("SIMSRegistrationController@endingPage", ["id"=>$registration->id]);
    }

    public function endingPage($id, $err=0){
      $registration = SIMSRegistrations::find($id);
      $student_info = SIMSStudentInfo::where("rcid", $registration->rcid)->first();
      $guests = SIMSGuestInfo::where("fkey_registration_id", $id)->get();
      $session = SIMSSessions::find($registration->fkey_sims_session_id);

      $session_dates = $session->date_string;

      $mot = SIMSModeOfTravel::find($registration->fkey_mode_of_travel_id)->travel_type;
      $shuttle = $registration->shuttle;
      return view()->make("sims.ending", compact("session_dates", "student_info", "guests", "mot", "shuttle", "err"));
    }

    //Scottys Functions

    public function store (Students $student, Request $request) {
      $request->validate([
        "orientation_session" => "required|numeric",
      ]);


      $file = fopen(storage_path("reg_lock"), "w");
      if(flock($file, LOCK_EX)) {
        $session_id             = $request->input("orientation_session");
        $potential_registration = SIMSSessions::find($session_id);

        $registrations          = SIMSRegistrations::select(\DB::raw("sessions.id AS id"), \DB::raw("COUNT(rcid) AS num_registrations"))
                                                     ->rightJoin("sims.sessions", "sessions.id", "fkey_sims_session_id")
                                                     ->where("sessions.id", $session_id)
                                                     ->groupBy("sessions.id")
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
      $registrations = SIMSRegistrations::select(\DB::raw("sessions.id AS id"), \DB::raw("COUNT(rcid) AS num_registrations"))
                                        ->rightJoin("sims.sessions", "sessions.id", "fkey_sims_session_id")
                                        ->groupBy("sessions.id")
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
