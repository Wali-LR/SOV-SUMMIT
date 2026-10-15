<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('eyebrow', 120)->nullable();
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();

            $table->string('hero_image')->nullable();

            $table->string('seo_title', 160)->nullable();
            $table->string('seo_keywords', 500)->nullable();
            $table->text('seo_description')->nullable();

            $table->boolean('is_published')->default(true);
            $table->boolean('show_in_nav')->default(false);
            $table->string('nav_label', 60)->nullable();
            $table->unsignedInteger('nav_order')->default(0);
            $table->unsignedInteger('position')->default(0);
            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            $table->index(['is_published', 'show_in_nav', 'nav_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
