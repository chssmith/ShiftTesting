<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicationAllergies extends model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    use SoftDeletes;
    protected $table = 'student_forms.medication_allergies';
    protected $primaryKey = 'rcid';
    protected $connection = 'SAO';
    protected $fillable = ['rcid', 'created_by'];
}
