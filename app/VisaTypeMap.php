<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VisaTypeMap extends Model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    protected $table      = 'student_forms.visa_type_map';
    protected $connection = 'SAO';

}
