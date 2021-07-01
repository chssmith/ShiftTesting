<?php

namespace App\ODS;

use Illuminate\Database\Eloquent\Model;

class EmploymentInfo extends Model
{
    protected $table = 'ods.employment_info';
    public $timestamps = false;

    public function country()
    {
        return $this->hasOne("App\Countries", 'key_CountryId', 'fkey_CountryId');
    }
}
