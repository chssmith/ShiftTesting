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
use App\VisaTypes;
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
			if (!\Storage::exists("pdfs")) {
				\Storage::makeDirectory("pdfs");
			}
	}

	public function index(){
		return view('admin');
	}

	public function changedStudents(){
		ini_set('max_execution_time', 300);
		$user = RCAuth::user();
		$all_changed = Students::with('visa')->get();

		$storage_path = storage_path();

		$count = 1;
		foreach($all_changed as $student){
			$report_string = view()->make("reports.student_report", ['student'=>$student])->render();
			$pdf = \PDF::loadHtml($report_string);
			$new_page = $pdf->output();
			\Storage::put("pdfs/page" . $count . ".pdf", $new_page);
			// file_put_contents($storage_path.'/pdfs/page'.$count, $new_page);
			$count++;
		}

		$merger = \PDFMerger::init();

		for($index = 1; $index < $count; $index++){
			$merger->addPathToPDF(\Storage::path('/pdfs/page'.$index.".pdf"), 'all', 'P');
		}

		$merger->setFileName('ReportFileName.pdf');
		$merger->merge();
		$merger->inline();


		//Switch to PDF MERGER

		 //return view('reports.student_report', compact('all_changed'));
	}

	public function changedParentInfo(){
		$user = RCAuth::user();
		$all_changed = Students::with('parents.employment')->get();

		$count = 1;
		foreach($all_changed as $student){
			$report_string = view()->make("reports.parent_report", ['student'=>$student])->render();
			$pdf = \PDF::loadHtml($report_string);
			$new_page = $pdf->output();
			file_put_contents(storage_path() . '/pdfs/page' . $count, $new_page);
			$count++;
		}
		$pdf = \PDF::loadView('reports.parent_report', compact('all_changed'));
		return $pdf;
	}
}
