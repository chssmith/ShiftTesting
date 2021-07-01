<?php

namespace App\ODS;

use Illuminate\Database\Eloquent\Model;

class GuardianInfo extends Model
{
    protected $table = 'ods.guardian_info';
    public $timestamps = false;

    public function employment()
    {
        return $this->hasOne('App\ODS\EmploymentInfo', 'fkey_guardian_id', 'fkey_parent_rcid');
    }

    public function country()
    {
        return $this->hasOne("App\Countries", 'key_CountryId', 'fkey_CountryId');
    }
}
