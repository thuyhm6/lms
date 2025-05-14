<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends Model
{
    protected $table = 'teachers';
    protected $primaryKey = 'user_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'teacher_code',
        'academic_degree',
        'title',
        'facebook',
        'display_on_homepage',
        'introduction',
        'achievements',
        'status',
        'notes',
        'full_name'
    ];

    protected $casts = [
        'display_on_homepage' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}