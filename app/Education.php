<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    protected $table = 'student_forms.education_levels';
    protected $primaryKey = 'id';
    protected $connection = 'SAO';
}
