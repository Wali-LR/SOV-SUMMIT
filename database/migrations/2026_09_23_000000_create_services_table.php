<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('eyebrow', 120)->nullable();
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();

            $table->string('hero_image')->nullable();

            $table->string('services_included_label', 120)->nullable()->default('Services included');
            $table->json('services_included')->nullable();
            $table->string('suitable_for_label', 120)->nullable();
            $table->json('suitable_for')->nullable();
            $table->json('faqs')->nullable();

            $table->string('seo_title', 160)->nullable();
            $table->string('seo_keywords', 500)->nullable();
            $table->text('seo_description')->nullable();

            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('position')->default(0);
            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            $table->index(['is_published', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
