<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CitizenshipInformation extends Model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    use SoftDeletes;
    protected $table      = 'student_forms.citizenship_information';
    protected $primaryKey = 'id';
    protected $connection = 'SAO';

}
