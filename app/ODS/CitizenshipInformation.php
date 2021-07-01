<?php

namespace App\ODS;

use Illuminate\Database\Eloquent\Model;

class CitizenshipInformation extends Model
{
    protected $table = 'ods.citizenship_information';
    protected $primaryKey = 'fkey_rcid';
    protected $connection = 'SAO';
    public $incrementing = false;
    public $timestamps = false;

    public function countries()
    {
        return $this->hasManyThrough("App\Countries", "App\CitizenshipCountryMap", 'RCID', 'key_CountryId', 'fkey_rcid', 'CitizenshipCountry');
    }
}
