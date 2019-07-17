<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MilitaryOptions extends model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    protected $table      = 'student_forms.military_options';
    protected $primaryKey = 'id';
    protected $connection = 'SAO';

}