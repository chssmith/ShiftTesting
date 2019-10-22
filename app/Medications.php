<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medications extends model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    protected $table      = 'student_forms.medications';
    protected $primaryKey = 'rcid';
    protected $connection = 'SAO';
    protected $fillable   = ['rcid', 'created_by'];

}
