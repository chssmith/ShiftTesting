<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CitizenshipInformation extends \App\ODS\CitizenshipInformation
{
    use SoftDeletes;
    protected $table = 'student_forms.citizenship_information';
    protected $fillable = ['fkey_rcid', 'created_by'];
    public $timestamps = true;
}
