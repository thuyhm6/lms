<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetails extends Model
{
    //
    protected $table = 'transaction_details';
    protected $fillable = [
        'transaction_id',
        'amount_paid',
        'note',
        'created_at',
        'updated_at',
    ];
    // Khai báo mối quan hệ với Transactions
    public function transaction()
    {
        return $this->belongsTo(Transactions::class, 'transaction_id');
    }
}
