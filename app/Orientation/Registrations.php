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

}
