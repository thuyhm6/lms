<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classes extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'classes';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'class_name',
        'class_code',
        'learning_format',
        'course_id',
        'status',
        'active_days',
        'price',
        'discount_price',
        'teacher_id',
        'description',
        'schedule',
    ];

   public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'user_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_classes', 'class_id', 'student_id')
                    ->withPivot('status');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'class_id', 'id');
    }
}
