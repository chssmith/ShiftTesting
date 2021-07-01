<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HaveSSN extends Model
{
    protected $table = 'Staging.dbo.students_with_stored_ssn';
    protected $primaryKey = 'ID';

    public function getIdAttribute($value)
    {
        return trim($value);
    }
}
