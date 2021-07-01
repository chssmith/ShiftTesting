<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    protected $table = 'DataMart.dbo.view_personbasic_username';
    protected $primaryKey = 'RCID';
    protected $connection = 'SAO';
    protected $appends = ['display_name'];

    public $incrementing = false;

    public function getDisplayNameAttribute()
    {
        $from_name = $this->FirstName;

        if (isset($this->Nickname) && ! is_null($this->Nickname)) {
            $from_name = $this->Nickname;
        }

        $from_name .= ' '.$this->LastName;

        return $from_name;
    }
}
