<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IBMap extends Model
{
    use SoftDeletes;
    protected $table = 'student_forms.ib_map';
}
