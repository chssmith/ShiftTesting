<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Students;
use App\SIMSSessions;
use App\SIMSRegistrations;

class SIMSRegistrationController extends Controller
{
    public function index (Students $student) {
      $sessions = SIMSSessions::orderBy("start_date")->get();

      return view()->make("sims.index", compact("sessions"));
    }
}
