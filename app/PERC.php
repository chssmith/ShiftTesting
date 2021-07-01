<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PERC extends Model
{
    use SoftDeletes;
    protected $table = 'student_forms.perc_codes';
    protected $fillable = ['rcid', 'perc', 'created_by', 'created_at', 'updated_by'];
}
