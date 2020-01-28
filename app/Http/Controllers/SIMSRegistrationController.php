<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use RCAuth;
use App\User;
use App\Students;
use App\SIMSSessions;
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
          "first_name"         => $user->FirstName,
          "last_name"          => $user->LastName,
          "nick_name"          => $user->NickName,
          "gender"             => $user->gender,
          "pronouns"           => "",
          "cell_phone"         => $user->CellPhone,
          "city"               => "",
          "state"              => "",
          "country"            => "",
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
        "first_name"         => $request->first_name,
        "last_name"          => $request->last_name,
        "nick_name"          => $request->preferred_name,
        "gender"             => $request->gender,
        "pronouns"           => $request->pronouns,
        "cell_phone"         => $request->phone,
        "city"               => $request->city,
        "state"              => $request->state,
        "country"            => $request->country,
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
    public function parentsGuestsPage(){
      return view()->make("sims.parents_guests");
    }

    //TYPE: POST
    //FROM: sims.parents_guests
    //POST: saves the parent info in session
    public function parentsGuests(Request $request){
      $parents_guests = [];
      for($i = 0; $i < 5; $i++){
        $parents_guests["g".$i."_first_name"] = $request["g".$i."_first_name"];
        $parents_guests["g".$i."_last_name"] = $request["g".$i."_last_name"];
        $parents_guests["g".$i."_email"] = $request["g".$i."_email"];
        $parents_guests["g".$i."_relationship"] = $request["g".$i."_relationship"];
        $parents_guests["g".$i."_on_campus"] = $request["g".$i."_on_campus"];
      }

      session(["parents_guests" => $parents_guests]);
      
    }


}
