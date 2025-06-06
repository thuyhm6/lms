<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleDetail extends Model
{
    // Chỉ định tên bảng
    protected $table = 'schedule_detail';
    protected $fillable = ['schedule_id', 'student_id', 'attendance_status', 'attendance_date', 'notes'];
    public $timestamps = true;
}
