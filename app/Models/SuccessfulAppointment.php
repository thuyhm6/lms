<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuccessfulAppointment extends Model
{
    use HasFactory;

    protected $table = 'successful_appointments';

    protected $fillable = [
        'parent_id',
        'title',
        'content',
        'status',
        'contact_date',
    ];

    protected $casts = [
        'contact_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function parent()
    {
        return $this->belongsTo(Parent::class, 'parent_id', 'user_id');
    }
}