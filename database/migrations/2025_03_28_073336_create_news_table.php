<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->unsignedBigInteger('topic_id');
            $table->string('news_events');
            $table->string('image');
            $table->string('image_caption');
            $table->string('short_intro');
            $table->text('full_content');
            $table->string('seo_title');
            $table->string('seo_description');
            $table->string('seo_keywords');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
