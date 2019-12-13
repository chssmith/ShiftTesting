<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DualEnrollmentCourses extends Model
{
  protected $table = "student_forms.dual_enrollment_courses";

  public function map () {
    return $this->hasMany("\App\DEMap", "fkey_dual_enrollment_course", "id");
  }

}
