<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatamartStudent extends Model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    protected $table      = 'DataMart.dbo.Person';
    protected $primaryKey = 'RCID';

    public function ssn () {
      return $this->hasOne("App\HaveSSN", "ID");
    }


    public function getRcidAttribute($value) {
      return trim($value);
    }

}
