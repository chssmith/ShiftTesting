<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompletedSections extends Model
{
    /**
     * The Database table used by the model.
     *
     * @var  string
     */
    use SoftDeletes;
    protected $table = 'student_forms.completed_sections';
    protected $primaryKey = 'fkey_rcid';
    protected $connection = 'SAO';
    protected $fillable = ['fkey_rcid'];
}
