<?php
namespace App;

use App\Address;
use App\GuardianInfo;

class GenericAddress {
  public const US_ID = 273;

  public $address;
  public $city;
  public $state;
  public $state_name;
  public $zip_code;
  public $country_id;
  public $country_name;
  public $international_address;

  public function __construct () {
    $this->address = ["", ""];
  }

  public static function fromMixedAddress ($address) {
    $new_address = new GenericAddress;
    if(!empty($address)) {
      $new_address->address[0]            = $address->Address1;
      $new_address->address[1]            = $address->Address2;
      $new_address->city                  = $address->City;
      $new_address->state                 = $address->fkey_StateId;
      if (isset($address->state)) {
        $new_address->state_name          = $address->state->StateName;
      }
      $new_address->zip_code              = $address->PostalCode;
      $new_address->country_id            = $address->fkey_CountryId;
      if (isset($address->country)) {
        $new_address->country_name        = $address->country->CountryName;
      }
      if( !empty($address->international_address) ) {
        $new_address->international_address = $address->international_address;
      }
    } else {

    }
    return $new_address;
  }

  public static function fromAddress (Address $address) {
    $new_address = new GenericAddress;
    $new_address->address[0]            = $address->Address1;
    $new_address->address[1]            = $address->Address2;
    $new_address->city                  = $address->City;
    $new_address->state                 = $address->fkey_StateId;
    if (isset($address->state)) {
      $new_address->state_name          = $address->state->StateName;
    }
    $new_address->zip_code              = $address->PostalCode;
    $new_address->country_id            = $address->fkey_CountryId;
    if (isset($address->country)) {
      $new_address->country_name        = $address->country->CountryName;
    }
    $new_address->international_address = $address->international_address;

    return $new_address;
  }

  public static function fromGuardianInfo (GuardianInfo $guardian) {
    $new_address = new GenericAddress;
    $new_address->address[0]            = $guardian->Address1;
    $new_address->address[1]            = $guardian->Address2;
    $new_address->city                  = $guardian->City;
    $new_address->state                 = $guardian->fkey_StateCode;
    if (isset($guardian->state)) {
      $new_address->state_name          = $guardian->state->StateName;
    }
    $new_address->zip_code              = $guardian->PostalCode;
    $new_address->country_id            = $guardian->fkey_CountryId;
    if (isset($guardian->country)) {
      $new_address->country_name        = $guardian->country->CountryName;
    }
    $new_address->international_address = $guardian->international_address;

    return $new_address;
  }

  public static function fromEmploymentInfo (EmploymentInfo $employment) {
    $new_address = new GenericAddress;
    $new_address->address[0]            = $employment->Street1;
    $new_address->address[1]            = $employment->Street2;
    $new_address->city                  = $employment->city;
    $new_address->state                 = $employment->fkey_StateCode;
    if (isset($employment->state)) {
      $new_address->state_name          = $employment->state->StateName;
    }
    $new_address->zip_code              = $employment->postal_code;
    $new_address->country_id            = $employment->fkey_CountryId;
    if (isset($employment->country)) {
      $new_address->country_name        = $employment->country->CountryName;
    }
    $new_address->international_address = $employment->international_address;

    return $new_address;
  }

  public function get_missing () {
    $messages = collect();

    if(empty($this->country_id)) {
      $messages[] = "is missing Country information.  Please enter all necessary address information.";
    } else if ($this->country_id == \App\GenericAddress::US_UD) {
      if (empty($this->address[0])) {
        $messages[] = "must have the first address line populated.";
      }

      if (!empty($this->city)) {
        $messages[] = "must have a valid City chosen.";
      }

      if (!empty($this->state)) {
        $messages[] = "must have a valid City chosen.";
      }

      if (!empty($this->zip_code)) {
        $messages[] = "must have a valid Zip Code chosen.";
      }
    } else if (empty($this->international_address)) {
      $messages[] = "must have full mailing address entered.";
    }

    return $messages;
  }

  public function complete () {
    return !empty($this->international_address) ||
          (!empty($this->address[0]) && !empty($this->country_id) && !empty($this->city) && !empty($this->state) && !empty($this->zip_code));
  }
}
