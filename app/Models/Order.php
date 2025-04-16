<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }

    public function transaction() {
        return $this->hasOne(Transaction::class);
    }

    // public function getSubtotalAttribute($value) {
    //     return number_format($value,2);
    // }

    // public function setSubtotalAttribute($value) {
    //     $this->attributes['subtotal'] =  floatval(str_replace(',', '', $value));
    // }

    // public function getTotalAttribute($value) {
    //     return number_format($value,2);
    // }

    // public function setTotalAttribute($value) {
    //     $this->attributes['total'] =  floatval(str_replace(',', '', $value));
    // }
}
