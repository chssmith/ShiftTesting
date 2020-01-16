<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use RCAuth;
use App\Students;
use App\SIMSSessions;
use App\SIMSRegistrations;

class SIMSRegistrationController extends Controller
{
    public function index (Students $student) {
      $sessions = SIMSSessions::orderBy("start_date")->get();

      return view()->make("sims.index", compact("sessions"));
    }

    //TYPE: POST
    //FROM: sims/index
    //POST: registers the future student for the sim day they selected
    public function register(Request $request, $id){
      $rcid = RCAuth::user()->rcid;
      $registration = new SIMSRegistrations;
      $registration->rcid = $rcid;
      $registration->fkey_sims_session_id = $id;
      $registration->on_campus = $request->has("guardian_stay");
      $registration->guardian_name = $request->guardian_name;
      $registration->created_by = $registration->updated_by = $rcid;
      $registration->save();

      return redirect()->action("StudentInformationController@index");
    }
}
