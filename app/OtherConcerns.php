<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtherConcerns extends model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    use SoftDeletes;
    protected $table = 'student_forms.other_concerns';
    protected $primaryKey = 'rcid';
    protected $connection = 'SAO';
}
