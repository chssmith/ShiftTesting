<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SIMSSessions extends Model
{
    protected $table = "sims.sessions";

    protected $dates = ["start_date", "end_date"];
    protected $appends = ["date_string"];


    public function getDateStringAttribute(){
      $s_date = new Carbon($this->start_date);
      $e_date = new Carbon($this->end_date);
      return $s_date->format("F jS")." - ".$e_date->format("jS");
    }
}
