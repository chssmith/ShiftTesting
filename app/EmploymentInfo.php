<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmploymentInfo extends Model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    use SoftDeletes;
    protected $table = 'student_forms.employment_info';
    protected $primaryKey = 'id';
    protected $connection = 'SAO';
    protected $fillable = ['fkey_guardian_id', 'created_by'];

    public function country()
    {
        return $this->hasOne(\App\Countries::class, 'key_CountryId', 'fkey_CountryId');
    }

    public function state()
    {
        return $this->hasOne(\App\States::class, 'StateCode', 'fkey_StateCode');
    }
}
