<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medications extends model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    use SoftDeletes;
    protected $table = 'student_forms.medications';
    protected $primaryKey = 'rcid';
    protected $connection = 'SAO';
    protected $fillable = ['rcid', 'created_by'];
}
