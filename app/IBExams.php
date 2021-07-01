<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IBExams extends Model
{
    protected $table = 'student_forms.ib_exams';

    public function map()
    {
        return $this->hasMany("\App\IBMap", 'fkey_ib_exam', 'id');
    }
}
