<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_sections', function (Blueprint $t) {
            $t->id();
            $t->morphs('sectionable');
            $t->string('page_type', 40)->nullable();
            $t->string('type', 60);
            $t->string('short', 255)->nullable();
            $t->unsignedInteger('position')->default(0);
            $t->json('data');
            $t->boolean('is_published')->default(true);
            $t->timestamps();

            $t->index(['sectionable_type', 'sectionable_id', 'position']);
            $t->index('page_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_sections');
    }
};
