<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentConcerns extends model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    use SoftDeletes;

    protected $table = 'student_forms.concern_map';
    protected $primaryKey = 'rcid';
    protected $connection = 'SAO';
}
