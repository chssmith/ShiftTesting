<?php

namespace App\ODS;

use Illuminate\Database\Eloquent\Model;

class Students extends \App\Students
{
    protected $table = 'ods.students';
    public $timestamps = false;

    public function visa()
    {
        return $this->hasOne(\App\ODS\VisaTypeMap::class, 'RCID', 'RCID');
    }
}
