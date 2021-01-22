<?php

namespace App\Http\Controllers\StudentForms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use RCAuth;

use App\User;
use App\Students;
use App\Medications;
use App\MedicationAllergies;
use App\InsectAllergies;

use App\ODS\MedicalData as ODSMedicalData;

use App\CompletedSections;

class AllergyInformationController extends SectionController
{
  public function show(Students $student, User $vpb_user, CompletedSections $completed_sections){
		$user           = RCAuth::user();
		$medications    = Medications::where('rcid', $user->rcid)->first();
		$med_allergy    = MedicationAllergies::where('rcid', $user->rcid)->first();
		$insect_allergy = InsectAllergies::where('rcid', $user->rcid)->first();
		$ods_data       = ODSMedicalData::find($user->rcid);

		return view('allergy', compact('user', 'medications', 'med_allergy', 'insect_allergy', "ods_data"));
	}

	public function store(Request $request, Students $student, CompletedSections $completed_sections){
		$user = RCAuth::user();

		$medications = Medications::firstOrNew(['rcid' => $user->rcid, "created_by" => $user->rcid]);
		$medications->take_medications = !empty($request->medications);
		$medications->medications 	   = !empty($request->medications) ? $request->input("medications_text", "") : NULL;
		$medications->updated_by       = $user->rcid;
		$medications->save();

		$med_allergy = MedicationAllergies::firstOrNew(['rcid' => $user->rcid, "created_by" => $user->rcid]);
		$med_allergy->have_medication_allergies = !empty($request->med_allergies);
		$med_allergy->medication_allergies      = !empty($request->med_allergies) ? $request->input("med_allergy_text", "") : NULL;
		$med_allergy->updated_by                = $user->rcid;
		$med_allergy->save();

		$insect_allergy = InsectAllergies::firstOrNew(['rcid' => $user->rcid, "created_by" => $user->rcid]);
		$insect_allergy->have_insect_allergies = !empty($request->insect_allergies);
		$insect_allergy->insect_allergies      = !empty($request->insect_allergies) ? $request->input("insect_allergy_text", "") : NULL;
		$insect_allergy->updated_by            = $user->rcid;
		$insect_allergy->save();

		// Checks if the user has completed the allergy section
		$completed_allergy = (!$medications->take_medications          || !empty($medications->medications)) &&
												 (!$med_allergy->have_medication_allergies || !empty($med_allergy->medication_allergies)) &&
												 (!$insect_allergy->have_insect_allergies  || !empty($insect_allergy->insect_allergies));

		if ($completed_allergy != $completed_sections->allergy_information) {
			$completed_sections->allergy_information = $completed_allergy;
			$completed_sections->updated_by = $user->rcid;
			$completed_sections->save();
		}

		// UPDATE TO CORRECT PATH
		return redirect()->action('StudentForms\MedicalInformationController@show');
	}

  public function getMissingInformation (Students $student) {
    $scope = [
      '$medications'    => Medications::firstOrNew(['rcid' => $student->RCID]),
      '$med_allergy'    => MedicationAllergies::firstOrNew(['rcid' => $student->RCID]),
      '$insect_allergy' => InsectAllergies::firstorNew(['rcid' => $student->RCID])
    ];

    $requirements = [
      '(!$medications->take_medications          || !empty($medications->medications))'          => 'Missing Medication Information',
      '(!$med_allergy->have_medication_allergies || !empty($med_allergy->medication_allergies))' => 'Missing Medication Allergies',
      '(!$insect_allergy->have_insect_allergies  || !empty($insect_allergy->insect_allergies))'  => 'Missing Insect Allergies'
    ];

    return self::getMessages($requirements, $scope);
  }
}
