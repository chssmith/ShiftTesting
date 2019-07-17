<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentConcerns extends model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    protected $table      = 'student_forms.concern_map';
    protected $primaryKey = 'rcid';
    protected $connection = 'SAO';

}