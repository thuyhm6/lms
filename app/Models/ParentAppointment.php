<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentAppointment extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
        'parent_id',
        'title',
        'content',
        'status',
        'contact_date',
    ];

    protected $casts = [
        'contact_date' => 'datetime', // Cast contact_date to Carbon/DateTime
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id', 'user_id'); // Foreign key parent_id links to user_id
    }
}