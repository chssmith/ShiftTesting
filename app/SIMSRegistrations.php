<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SIMSRegistrations extends Model
{
    protected $table    = "student_forms.sims_registrations";
    public    $fillable = ["rcid", "created_by", "updated_by"];

    public function session_dates () {
      return $this->hasOne("\App\SIMSSessions", "id", "fkey_sims_session_id");
    }
}
