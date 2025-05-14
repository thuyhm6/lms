<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    protected $table = 'parents';
    protected $primaryKey = 'user_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'status',
        'learning_format',
        'school', 
        'grade',
        'marketing_source',
        'notes',
    ];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'parent_subject', 'parent_id', 'subject_id');
    }
    

    public function student()
    {
        return $this->hasMany(Student::class, 'parent_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
}
