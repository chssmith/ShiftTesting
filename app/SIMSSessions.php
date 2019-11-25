<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SIMSSessions extends Model
{
    protected $table = "student_forms.sims_sessions";

    protected $dates = ["start_date", "end_date"];
}
