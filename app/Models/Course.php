<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'course_code',
        'course_name',
        'image',
        'is_visible',
        'display_on_homepage'
    ];
}
