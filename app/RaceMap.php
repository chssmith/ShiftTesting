<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RaceMap extends Model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    use SoftDeletes;
    protected $table = 'student_forms.race_map';
    protected $primaryKey = 'id';
    protected $connection = 'SAO';

    public function races()
    {
        return $this->hasOne(\App\Races::class, 'code', 'fkey_code');
    }
}
