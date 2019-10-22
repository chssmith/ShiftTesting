<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InsectAllergies extends model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    protected $table      = 'student_forms.insect_allergies';
    protected $primaryKey = 'rcid';
    protected $connection = 'SAO';
    protected $fillable   = ['rcid', 'created_by'];    

}
