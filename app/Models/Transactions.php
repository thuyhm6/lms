<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    //
    protected $table = 'transactions';
    protected $fillable = [
        'student_id',
        'course_packages_id',
        'promo_sessions',
        'scholarship_amount',
        'amount_paid',
        'debt',
        'note'
    ];

    // Khai báo mối quan hệ với Student
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Khai báo mối quan hệ với CoursePackage
    public function coursePackage()
    {
        return $this->belongsTo(CoursePackage::class, 'course_packages_id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetails::class, 'transaction_id');
    }
}
