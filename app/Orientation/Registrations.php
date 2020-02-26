<?php

namespace App\Orientation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registrations extends Model
{
  use SoftDeletes;

  protected $table = "orientation.registrations";
  protected $primaryKey = 'id';
  protected $fillable = ["rcid", "created_by", "updated_by"];

  public function session_dates () {
    return $this->hasOne("\App\Orientation\Sessions", "id", "fkey_sims_session_id");
  }

  public function student () {
    return $this->belongsTo("\App\User", "rcid", "RCID");
  }

  public function student_info() {
    return $this->hasOne("\App\Orientation\StudentInfo", "rcid", "rcid");
  }

  public function guests(){
    return $this->hasMany("\App\Orientation\GuestInfo", "fkey_registration_id", "id");
  }

  public function mode_of_travel(){
    return $this->hasOne("\App\Orientation\ModeOfTravel", "id", "fkey_mode_of_travel_id");
  }

}
