<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SIMSRegistrations extends Model
{
  use SoftDeletes;

  protected $table = "sims.registrations";
  protected $primaryKey = 'id';
  protected $fillable = ["rcid", "created_by", "updated_by"];

  public function session_dates () {
    return $this->hasOne("\App\SIMSSessions", "id", "fkey_sims_session_id");
  }

  public function student () {
    return $this->belongsTo("\App\User", "rcid", "RCID");
  }

}
