<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuardianRelationshipTypes extends Model
{
    protected $table = 'student_forms.guardian_types';
    public $timestamps = false;
    public $incrementing = false;
}
