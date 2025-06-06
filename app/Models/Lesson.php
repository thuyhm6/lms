<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'topic',
        'lesson_name',
        'content',
        'subject_id',
        'teacher_id',
        'type',
        'fee_type',
        'file_type',
        'duration',
        'file_link',
        'created_by',
        'updated_by'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function teachers()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
