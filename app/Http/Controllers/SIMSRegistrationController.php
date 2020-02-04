<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use RCAuth;
use App\User;
use App\Students;
use App\SIMSSessions;
use App\SIMSGuestInfo;
use App\SIMSStudentInfo;
use App\SIMSModeOfTravel;
use App\SIMSRegistrations;

class SIMSRegistrationController extends Controller
{
    //TYPE:  GET
    //BLADE: sims.session_selection
    //POST:  makes the session selection page
    public function sessionSelectionPage (Students $student) {
      $sessions = SIMSSessions::orderBy("start_date")->get();

      return view()->make("sims.session_selection", compact("sessions"));
    }

    //TYPE: POST
    //FROM: AJAX on sims.session_selection
    //POST: adds the session id to the session variables
    public function sessionSelection(Request $request){
      session(['session_id' => $request->id]);
      //Set the user info we have in datamart
      $user = User::find(RCAuth::user()->rcid);
      $sess = session("student_info");
      if(!isset($sess)){
        $student_info = [
          "nick_name"          => $user->NickName,
          "gender"             => $user->gender,
          "cell_phone"         => $user->CellPhone,
          "has_dietary_needs"  => "",
          "dietary_needs"      => "",
          "has_physical_needs" => "",
          "physical_needs"     => ""
        ];
        session(["student_info" => $student_info]);
      }
    }

    //TYPE:  GET
    //BLADE: sims.student_info
    //POST:  makes the student info page
    public function studentInfoPage(){
      $sess = session("student_info");
      //dd($sess);
      return view()->make("sims.student_info", compact("sess"));
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
      //ASSET: set v_pg in case they don't have any guests and don't fill out the page

      return view()->make("sims.parents_guests", compact("sess"));
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

      return view()->make("sims.mode_of_travel", compact("MOT", "sess"));
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

      return view()->make("sims.confirmation", ["student_info"=>(count($si)>0), "guests"=>($pg), "mode_of_travel"=>(count($mot)>0), "stop"=>(!$all), "num_guests"=>($num_guests)]);
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
      $sid = $request->session()->get("session_id", "");

      if(!((count($si)>0) && ($pg) && (count($mot)>0) && ($sid != ""))){
        return redirect()->action("SIMSRegistrationController@confirmationPage");
      }

      //Store info in Database
      $pg = $request->session()->get("parents_guests", []);
      // dd($pg);

      //Registration
      $registration = new SIMSRegistrations;
      $registration->fkey_sims_session_id = $sid;
      $registration->shuttle = ($mot["shuttle"] == "yes");
      $registration->fkey_mode_of_travel_id = $mot["mode_of_travel"];
      $registration->created_by = $registration->updated_by = $rcid;
      $registration->save();

      //Student Info
      $student_info = new SIMSStudentInfo;
      $student_info->fkey_registration_id = $registration->id;
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

      //Add Redirect to confirmation page
    }
}
