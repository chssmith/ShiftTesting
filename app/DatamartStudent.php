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
    protected $table      = 'dbo.Person';
    protected $primaryKey = 'RCID';
    protected $connection = 'DataMart';

    public function ssn () {
      return $this->hasOne("App\HaveSSN", "ID");
    }


}
