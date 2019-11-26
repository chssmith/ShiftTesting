<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class APExams extends Model
{
    protected $table = "student_forms.ap_exams";

    public function map () {
      return $this->hasMany("\App\APMap", "fkey_ap_exam", "id");
    }
}
