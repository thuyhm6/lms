<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    //
    use SoftDeletes;
    public $timestamps = true;

    protected $fillable = [
        'name',
        'parent_id',
        'created_by',
        'updated_by'
    ];

}
