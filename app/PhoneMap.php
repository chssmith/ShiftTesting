<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhoneMap extends Model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    use SoftDeletes;
    protected $table      = 'student_forms.phone_map';
    protected $primaryKey = 'id';
    protected $connection = 'SAO';

    protected $fillable   = ['RCID', 'fkey_PhoneTypeId'];

}
