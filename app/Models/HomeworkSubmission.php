<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeworkSubmission extends Model
{
    protected $table = 'homework_submissions';
    protected $fillable = [
        'homework_id',
        'student_id',
        'submission_note',
        'file_path',
        'submitted_at',
    ];
   
    public function homework()
    {
        return $this->belongsTo(Homework::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

}
