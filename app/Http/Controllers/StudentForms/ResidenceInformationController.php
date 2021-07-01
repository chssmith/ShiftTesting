<?php

namespace App\Http\Controllers\StudentForms;

use App\Address;
use App\CompletedSections;
use App\GenericAddress;
use App\Http\Controllers\Controller;
use App\States;
use App\Students;
use App\User;
use Illuminate\Http\Request;
use RCAuth;

class ResidenceInformationController extends SectionController
{
    private const FKEY_RESIDENCE = 4;

    public function show(Students $student, User $vpb_user, CompletedSections $completed_sections)
    {
        $user = RCAuth::user();
        $local_address = Address::where('RCID', $user->rcid)->where('fkey_AddressTypeId', self::FKEY_RESIDENCE)->first();
        $states = States::all();

        return view('residence_hall', compact('local_address', 'student', 'states'));
    }

    public function store(Request $request, Students $student, CompletedSections $completed_sections)
    {
        $user = RCAuth::user();

        if (in_array($request->residence, ['hall', 'home'])) {
            // Need to delete all local info
            Address::where('RCID', $user->rcid)->where('fkey_AddressTypeId', self::FKEY_RESIDENCE)->update(['deleted_by' => $user->rcid, 'deleted_at' => \Carbon\Carbon::now()]);

            $student->home_as_local = $request->residence == 'home';
        // need to delete local address and update student bit
        } else {
            // need to update student bit and update local address
            $student->home_as_local = 0;
            $local_address = Address::firstOrNew(['RCID' => $user->rcid, 'fkey_AddressTypeId' => self::FKEY_RESIDENCE, 'created_by' => $user->rcid]);

            // updating local address information
            $local_address->Address1 = $request->local_Address1;
            $local_address->Address2 = $request->local_Address2;
            $local_address->City = $request->local_city;
            $local_address->fkey_StateId = $request->local_state;
            $local_address->PostalCode = $request->local_zip;
            $local_address->fkey_CountryId = GenericAddress::US_ID;
            $local_address->updated_by = $user->rcid;
            $local_address->save();
        }

        $student->save();
        $completed_sections->residence_information = $student->home_as_local || $request->residence == 'hall' || GenericAddress::fromAddress($local_address)->complete();
        $completed_sections->updated_by = $user->rcid;
        $completed_sections->save();

        return redirect()->action('StudentForms\CitizenshipInformationController@show');
    }

    public function getMissingInformation(Students $student)
    {
        return collect();
    }
}
