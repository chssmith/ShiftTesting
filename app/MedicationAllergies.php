<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicationAllergies extends model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    protected $table      = 'student_forms.medication_allergies';
    protected $primaryKey = 'rcid';
    protected $connection = 'SAO';
    protected $fillable   = ['rcid', 'created_by'];
    
}
