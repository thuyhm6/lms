<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'image',
        'subject_name',
        'teacher_permission',
        'publish_status',
        'course_id',
        'created_by',   
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function teachers()
    {
        return $this->belongsTo(User::class, 'teacher_permission');
    }
}
