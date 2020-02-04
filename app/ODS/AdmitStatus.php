<?php

namespace App\ODS;

use Illuminate\Database\Eloquent\Model;

class AdmitStatus extends Model
{
    protected $table        = "ods.admit_status";
    protected $primaryKey   = 'rcid';
    public    $incrementing = false;

    public function getRCIDAttribute ($value) {
      return trim($value);
    }
}
