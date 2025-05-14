<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    //lưu ý đoạn này
    public function category() {
        return $this->belongsTo(Category::class, 'category_id'); // Thay Category bằng tên của model Category
    }

    public function brand() {
        return $this->belongsTo(Brand::class, 'brand_id'); // Thay Brand bằng tên của model Brand
    }
}
