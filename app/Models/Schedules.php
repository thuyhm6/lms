<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedules extends Model
{
    protected $table = 'schedules';
    protected $primaryKey = 'id';
    protected $fillable = [
        'class_id', 'start_date', 'start_time', 'end_time', 'teacher_id'
    ];
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo(Student::class, 'teacher_id', 'user_id');
    }

    public function assistantTeacher()
    {
        return $this->belongsTo(Student::class, 'assistant_teacher_id', 'user_id');
    }
}
