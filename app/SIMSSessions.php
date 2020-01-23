<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SIMSSessions extends Model
{
    protected $table = "sims.sessions";

    protected $dates = ["start_date", "end_date"];
}
