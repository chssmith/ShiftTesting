<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CitizenshipCountryMap extends Model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    use SoftDeletes;
    protected $table      = 'Scotty_StudentAffairsOperations.student_forms.citizenship_country_map';
    protected $primaryKey = 'id';
    protected $connection = 'SAO';



}
