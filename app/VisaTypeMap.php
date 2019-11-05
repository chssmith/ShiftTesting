<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VisaTypeMap extends Model
{
    use SoftDeletes;
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    protected $table      = 'student_forms.visa_type_map';
    protected $connection = 'SAO';
    protected $primaryKey = "RCID";

}
