<?php

namespace App\Orientation;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Sessions extends Model
{
    protected $table = "orientation.sessions";

    protected $dates = ["start_date", "end_date"];
    protected $appends = ["date_string"];


    public function getDateStringAttribute(){
      $s_date = new Carbon($this->start_date);
      $e_date = new Carbon($this->end_date);
      return $s_date->format("F jS")." &ndash; ".$e_date->format("jS");
    }
}
