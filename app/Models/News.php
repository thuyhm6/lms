<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    //
    use SoftDeletes;
    protected $table = 'news';
    public $timestamps = true;
    protected $fillable = [
        'title',
        'slug',
        'topic_id',
        'news_events',
        'image',
        'image_caption',
        'short_intro',
        'full_content',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'created_by',
        'updated_by',
        'is_visible',
        'show_on_homepage',
        'is_featured',
        'is_latest'

    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


}
