<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HealthConcerns extends Model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    protected $table = 'student_forms.health_concerns';
    protected $primaryKey = 'id';
    protected $connection = 'SAO';

    public function student_concerns()
    {
        return $this->hasOne('App\StudentConcerns', 'fkey_concern_id', 'id');
    }
}
