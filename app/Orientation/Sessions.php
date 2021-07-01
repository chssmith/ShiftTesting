<?php

namespace App\Orientation;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sessions extends Model
{
    use SoftDeletes;

    protected $table = 'orientation.sessions';
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
    protected $appends = ['date_string'];

    public function getDateStringAttribute()
    {
        $s_date = new Carbon($this->start_date);
        $e_date = new Carbon($this->end_date);

        return $s_date->format('F jS').' &ndash; '.$e_date->format('jS');
    }
}
