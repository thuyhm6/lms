<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    use HasFactory;

    protected $table = 'homeworks';

    protected $fillable = [
        // 'title',
        'description',
        'attachment_path',
        // 'file_name',
        // 'file_size',
        // 'mime_type',
        'deadline',
        'schedule_session_id',
        // 'status',
        'created_by'
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];


    public function submissions()
{
    return $this->hasMany(HomeworkSubmission::class);
}
    // Relationship với User (nếu có)
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // Relationship với Schedule
    // public function schedule()
    // {
    //     return $this->belongsTo(Schedule::class);
    // }

    // Accessor để lấy URL file
    // public function getFileUrlAttribute()
    // {
    //     return asset($this->file_path);
    // }

    // Accessor kiểm tra hết hạn
    // public function getIsExpiredAttribute()
    // {
    //     return $this->deadline->isPast();
    // }

    // Accessor lấy thời gian còn lại
    // public function getTimeRemainingAttribute()
    // {
    //     if ($this->deadline->isPast()) {
    //         return 'Đã hết hạn';
    //     }
        
    //     return $this->deadline->diffForHumans();
    // }

    // Scope lấy bài tập chưa hết hạn
    // public function scopeActive($query)
    // {
    //     return $query->where('deadline', '>', now());
    // }

    // Scope lấy bài tập đã hết hạn
    // public function scopeExpired($query)
    // {
    //     return $query->where('deadline', '<=', now());
    // }
}
