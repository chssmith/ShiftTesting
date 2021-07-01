<?php

namespace App\Http\Controllers\StudentForms;

use App\CitizenshipCountryMap;
use App\CitizenshipInformation;
use App\CompletedSections;
use App\Counties;
use App\Countries;
use App\Http\Controllers\Controller;
use App\ODS\CitizenshipInformation as ODSCitizenshipInformation;
use App\ODS\USResidence as ODSUSResidence;
use App\ODS\VisaTypeMap as ODSVisaTypeMap;
use App\States;
use App\Students;
use App\User;
use App\USResidence;
use App\VisaTypeMap;
use App\VisaTypes;
use Illuminate\Http\Request;
use RCAuth;

class CitizenshipInformationController extends SectionController
{
    public const NUM_COUNTRIES = 2;

    public function show(Students $student, User $vpb_user, CompletedSections $completed_sections)
    {
        $user = RCAuth::user();

        $us_resident = USResidence::where('RCID', $user->rcid)->first();
        if (empty($us_resident)) {
            $ods_resident = ODSUSResidence::where('RCID', $user->rcid)->first();
        } else {
            $ods_resident = null;
        }

        $citizenship = CitizenshipInformation::where('fkey_rcid', $user->rcid)->first();
        if (empty($citizenship)) {
            $ods_citizenship = ODSCitizenshipInformation::where('fkey_rcid', $user->rcid)->first();
        } else {
            $ods_citizenship = null;
        }

        $visa = VisaTypeMap::where('RCID', $user->rcid)->first();
        if (empty($visa)) {
            $ods_visa = ODSVisaTypeMap::find($user->rcid);
        } else {
            $ods_visa = null;
        }

        $visa_types = VisaTypes::all();

        $states = States::all();
        $countries = Countries::all();
        $counties = Counties::all()->keyBy('county_id')->map(
            function ($item) {
                $display = $item->description;
                if (strpos($display, 'Co:') !== false) {
                    $display = str_replace('Co: ', '', $display);
                    $display .= ' County';
                }
                if (strpos($display, 'Ct:') !== false) {
                    $display = str_replace('Ct: ', '', $display);
                }
                $item->display = $display;

                return $item;
            })->sortBy('display');

        return view('citizen_info', compact('countries', 'student', 'us_resident', 'ods_resident', 'citizenship', 'ods_citizenship', 'visa_types', 'visa', 'ods_visa', 'counties', 'states'));
    }

    public function store(Request $request, Students $student, CompletedSections $completed_sections)
    {
        $user = RCAuth::user();

        $green_card_input = $request->input('GreenCard', []);
        $us_resident = USResidence::firstOrNew(['RCID' => $student->RCID, 'created_by' => $user->rcid], ['updated_by' => $user->rcid]);

        $citizenship = CitizenshipInformation::firstOrNew(['fkey_rcid' => $student->RCID], ['created_by' => $user->rcid]); //$student->load("citizenship");

        $citizenship->country_of_birth = $request->input('BirthCountry', null);
        $citizenship->updated_by = $user->rcid;

        $citizenship->us = (bool) $request->US_citizen;
        if ($request->US_citizen) {
            $us_resident->fkey_StateCode = $request->state;
            $us_resident->fkey_CityCode = $request->state == 'VA' ? $request->input('county', null) : null;
            $us_resident->save();
        } else {
            USResidence::where('RCID', $user->rcid)->update(['deleted_by' => $user->rcid, 'deleted_at' => \Carbon\Carbon::now()]);
        }

        $citizenship->another = (bool) $request->another_citizen;
        if ($citizenship->another) {
            $citizenship->permanent_residence = $request->input('PermanentCountry', null);
            $foreign = CitizenshipCountryMap::orderBy('ID')->where('RCID', $user->rcid)->get();
            for ($i = 0; $i < self::NUM_COUNTRIES; $i++) {
                if (! isset($foreign[$i])) {
                    $foreign[$i] = new CitizenshipCountryMap;
                    $foreign[$i]->RCID = $student->RCID;
                    $foreign[$i]->created_by = $user->rcid;
                }
                $foreign[$i]->CitizenshipCountry = $request->CitizenshipCountry[$i];
                $foreign[$i]->updated_by = $user->rcid;
                $foreign[$i]->save();
            }
            $citizenship->green_card = in_array('GreenCard', $green_card_input);
        } else {
            //Delete all foreign information, because they are not listed as a citizen of another country
            $citizenship->permanent_residence = $citizenship->green_card = null;
            CitizenshipCountryMap::orderBy('ID')->where('RCID', $user->rcid)->update(['deleted_by' => $user->rcid, 'deleted_at' => \Carbon\Carbon::now()]);
            $foreign = collect();
        }

        if ($citizenship->another && in_array('Visa', $green_card_input) && ! empty($request->get('VisaTypes', null))) {
            $visa = VisaTypeMap::firstOrNew(['RCID' => $student->RCID, 'created_by' => $user->rcid]);
            $visa->updated_by = $user->rcid;
            $visa->fkey_code = $request->VisaTypes;
            $visa->save();
        } else {
            VisaTypeMap::where('RCID', $user->rcid)->update(['deleted_by' => $user->rcid, 'deleted_at' => \Carbon\Carbon::now()]);
        }

        $citizenship->other = (bool) $request->other_citizen;
        $citizenship->save();
        self::completedCitizenshipInfo($student, $completed_sections, $citizenship, $us_resident, $foreign, ! empty($citizenship) && $citizenship->green_card, isset($visa) && ! empty($visa));

        return redirect()->action('StudentForms\AllergyInformationController@show');
    }

    // Pre :
    // Post: Checks that the form is completed
    private function completedCitizenshipInfo(Students $student, CompletedSections $completed_sections, CitizenshipInformation $citizenship,
                                                                                        USResidence $us_resident, $foreign, $permanent_residence, $visa)
    {
        $user = RCAuth::user();

        $basic_citizenship = ! empty($citizenship) && ! empty($citizenship->country_of_birth) && ($citizenship->us || $citizenship->another || $citizenship->other);
        $us_citizenship = $basic_citizenship && (! $citizenship->us || (! empty($us_resident) && ! empty($us_resident->fkey_StateCode) &&
                                                                                                                                              ($us_resident->fkey_StateCode != 'VA' || ! empty($us_resident->fkey_CityCode))));
        $another_citizenship = $basic_citizenship && (! $citizenship->another || (! empty($citizenship->permanent_residence) &&
                                                                                                                                                         $foreign->reduce(function ($collector, $item) {
                                                                                                                                                             return $collector || ! empty($item->CitizenshipCountry);
                                                                                                                                                         }, false) && ($permanent_residence || $visa)));

        $completed_sections->citizenship_information = $basic_citizenship && $us_citizenship && $another_citizenship;
        $completed_sections->updated_by = $user->rcid;
        $completed_sections->save();
    }

    private function checkBasicCitizenship($scope)
    {
        $requirements = [
      '!empty($citizenship)'                                               => 'No Citizenship Information Found',
      '!empty($citizenship->country_of_birth)'                             => 'Missing Country of Birth',
      '!empty($citizenship) && ($citizenship->us || $citizenship->another || $citizenship->other)' => 'No Citizenship Information Selected',
    ];

        return self::getMessages($requirements, $scope);
    }

    private function checkUSCitizenship($scope)
    {
        $requirements = [
      '!empty($us_resident) && !empty($us_resident->fkey_StateCode) && ($us_resident->fkey_StateCode != "VA" || !empty($us_resident->fkey_CityCode))' => 'Missing US State Residency or County Residency',
    ];

        return self::getMessages($requirements, $scope);
    }

    private function checkAnotherCitizenship($scope)
    {
        $requirements = [
      '!empty($citizenship->permanent_residence)' => 'Missing Country of Permanent Residency',
      '$foreign->reduce(function ($collector, $item) { return $collector || !empty($item->CitizenshipCountry);}, false)' => 'Missing at least one Country of Citizenship',
      '($citizenship->green_card || !empty($visa))' => 'Missing Residency or Visa Details',
    ];

        return self::getMessages($requirements, $scope);
    }

    public function getMissingInformation(Students $student)
    {
        $messages = collect();
        $citizenship = CitizenshipInformation::where('fkey_rcid', $student->RCID)->first();
        $us_resident = USResidence::where('RCID', $student->RCID)->first();
        $foreign = CitizenshipCountryMap::orderBy('ID')->where('RCID', $student->RCID)->get();
        $visa = VisaTypeMap::where('RCID', $student->RCID)->first();
        $scope = [
      '$citizenship' => $citizenship,
      '$us_resident' => $us_resident,
      '$foreign'     => $foreign,
      '$visa'        => $visa,
    ];
        $messages['Basic Citizenship'] = $this->checkBasicCitizenship($scope);
        if (! empty($citizenship) && $citizenship->us) {
            //US Citizenship
            $messages['US Citizenship'] = $this->checkUSCitizenship($scope);
        }
        if (! empty($citizenship) && $citizenship->another) {
            //Another Citizenship
            $messages['Other Citizenship'] = $this->checkAnotherCitizenship($scope);
        }

        return $messages->flatten();
    }
}
