<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Students;
use App\PhoneMap;
use App\RaceMap;
use App\Races;
use App\MaritalStatuses;
use App\Address;
use App\States;
use App\HealthConcerns;
use App\OtherConcerns;
use App\StudentConcerns;
use App\MilitaryOptions;
use App\Medications;
use App\MedicationAllergies;
use App\InsectAllergies;
use App\Countries;
use App\CitizenshipInformation;
use App\Counties;
use App\USResidence;
use App\VisaTypes;;
use App\VisaTypeMap;
use App\GuardianInfo;
use App\EmergencyContact;
use App\EmploymentInfo;
use App\CompletedSections;
use App\Education;
use App\DatamartStudent;
use App\DatamartPhones;
use App\DatamartAddress;
use RCAuth;

class AdminController extends Controller
{

	public function __construct(){
      $this->middleware("force_login");
	}

	public function index(){
		return view('admin');
	}

	public function changedStudents(){
		$user = RCAuth::user();
		$all_changed = Students::with('visa')->get();

		$pdf = \PDF::loadView("reports.student_report", compact("all_changed"));

		return $pdf->stream('test');
		 //return view('reports.student_report', compact('all_changed'));
	}

	public function changedParentInfo(){
		$user = RCAuth::user();
		$all_changed = Students::with('parents.employment')->get();

		$pdf = \PDF::loadView('reports.parent_report', compact('all_changed'));
		return $pdf;
	}
}
