<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CitizenshipInformation extends Model
{
    use SoftDeletes;
    protected $table      = 'student_forms.citizenship_information';
    protected $primaryKey = 'fkey_rcid';
    protected $connection = 'SAO';
    public  $incrementing = false;

    public function countries () {
      return $this->hasManyThrough("App\Countries", "App\CitizenshipCountryMap", "RCID", "key_CountryId", "fkey_rcid", "CitizenshipCountry");
    }
}
