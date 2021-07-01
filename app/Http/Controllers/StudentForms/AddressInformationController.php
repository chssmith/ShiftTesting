<?php

namespace App\Http\Controllers\StudentForms;

use App\Address;
use App\CompletedSections;
use App\Countries;
use App\DatamartAddress;
use App\GenericAddress;
use App\Http\Controllers\Controller;
use App\States;
use App\Students;
use App\User;
use Illuminate\Http\Request;
use RCAuth;

class AddressInformationController extends SectionController
{
    private const FKEY_HOME = 1;
    private const FKEY_BILL = 3;

    public function show(Students $student, User $vpb_user, CompletedSections $completed_sections)
    {
        $user = RCAuth::user();
        $addresses = Address::where('RCID', $user->rcid)->whereIn('fkey_AddressTypeId', [1, 3])->get()->keyBy('fkey_AddressTypeId');

        $dm_addresses = DatamartAddress::where('RCID', $user->rcid)->whereIn('fkey_AddressTypeId', [1, 3])->get()->keyBy('fkey_AddressTypeId');
        $home_address = $addresses->get(1, $dm_addresses->get(1));
        $billing_address = $addresses->get(3, $dm_addresses->get(3));

        $home_address = GenericAddress::fromMixedAddress($addresses->get(1, $dm_addresses->get(1)));
        $billing_address = GenericAddress::fromMixedAddress($addresses->get(3, $dm_addresses->get(3)));

        $states = States::all();
        $countries = Countries::all();

        return view('address', compact('student', 'home_address', 'billing_address', 'states', 'countries'));
    }

    public function store(Request $request, Students $student, CompletedSections $completed_sections)
    {
        $user = RCAuth::user();

        $home_address = Address::firstOrNew(['RCID' => $user->rcid, 'fkey_AddressTypeId' => self::FKEY_HOME, 'created_by' => $user->rcid]);
        $home_addresses = $request->input('address_home', ['', '']);

        // updating the home address information
        $home_address->Address1 = $home_addresses[0];
        $home_address->Address2 = $home_addresses[1];
        $home_address->City = $request->input('city_home', null);
        $home_address->fkey_StateId = $request->input('state_home', null);
        $home_address->PostalCode = $request->input('zip_home', null);
        $home_address->fkey_CountryId = $request->input('country_home', null);
        $home_address->international_address = $request->input('international_address_home', null);
        $home_address->updated_by = $user->rcid;
        $home_address->save();

        if ($request->home_as_billing) {
            // ASSERT: Billing Address is same as Home Address
            $student->home_as_billing = true;
            Address::where('RCID', $user->rcid)->where('fkey_AddressTypeId', self::FKEY_BILL)->update(['deleted_by' => $user->rcid, 'deleted_at' => \Carbon\Carbon::now()]);
        } else {
            // Assert: Need a Billing Address Added
            $student->home_as_billing = false;
            $billing_address = Address::firstOrNew(['RCID' => $user->rcid, 'fkey_AddressTypeId' => self::FKEY_BILL, 'created_by' => $user->rcid]);

            $billing_addresses = $request->input('address_billing', ['', '']);

            // Updating the billing address
            $billing_address->Address1 = $billing_addresses[0];
            $billing_address->Address2 = $billing_addresses[1];
            $billing_address->City = $request->input('city_billing', null);
            $billing_address->fkey_StateId = $request->input('state_billing', null);
            $billing_address->PostalCode = $request->input('zip_billing', null);
            $billing_address->fkey_CountryId = $request->input('country_billing', null);
            $billing_address->international_address = $request->input('international_address_billing', null);
            $billing_address->updated_by = $user->rcid;
            $billing_address->save();
        }
        $student->save();

        $has_home_address = GenericAddress::fromAddress($home_address)->complete();

        $has_billing_address = $student->home_as_billing || (! empty($billing_address) && GenericAddress::fromAddress($billing_address)->complete());

        $completed_sections->address_information = $has_home_address && $has_billing_address;
        $completed_sections->updated_by = $user->rcid;
        $completed_sections->save();

        return redirect()->action('StudentForms\ResidenceInformationController@show');
    }

    public function getMissingInformation(Students $student)
    {
        $addresses = Address::where('RCID', $student->RCID)->whereIn('fkey_AddressTypeId', [self::FKEY_HOME, self::FKEY_BILL])->get()->keyBy('fkey_AddressTypeId');

        $home_address = $addresses->get(self::FKEY_HOME, new Address);
        $billing_address = $addresses->get(self::FKEY_BILL, new Address);
        $requirements = [
            '\App\GenericAddress::fromAddress($home_address)->complete()' => 'Incomplete Home Address',
            '$student->home_as_billing || (!empty($billing_address) && \App\GenericAddress::fromAddress($billing_address)->complete())' => 'Missing Billing Address Details',
        ];

        return self::getMessages($requirements, ['$student' => $student, '$home_address' => $home_address, '$billing_address' => $billing_address]);
    }
}
