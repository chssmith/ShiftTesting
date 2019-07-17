<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class USResidence extends model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    use SoftDeletes;
    protected $table      = 'student_forms.us_residence';
    protected $primaryKey = 'RCID';
    protected $connection = 'SAO';

    
    public $incrementing = false;


}