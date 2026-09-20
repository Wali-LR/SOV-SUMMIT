<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('seo_title', 160)->nullable();
            $table->string('seo_keywords', 500)->nullable();
            $table->string('summary', 500)->nullable();
            $table->longText('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('category', 80)->nullable();
            $table->string('author', 120)->nullable();
            $table->unsignedSmallInteger('reading_time')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['is_published', 'published_at']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
