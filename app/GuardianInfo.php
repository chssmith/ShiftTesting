<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuardianInfo extends model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    use SoftDeletes;
    protected $table      = 'student_forms.guardian_info';
    protected $primaryKey = 'id';
    protected $connection = 'SAO';

}