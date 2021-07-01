<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DEMap extends Model
{
    use SoftDeletes;

    protected $table = 'student_forms.de_map';
}
