<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ContentSection extends Model
{
    protected $fillable = [
        'sectionable_type',
        'sectionable_id',
        'page_type',
        'type',
        'short',
        'position',
        'data',
        'is_published',
    ];

    protected $casts = [
        'data' => 'array',
        'is_published' => 'boolean',
        'position' => 'integer',
    ];

    public function sectionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function definition(): array
    {
        return config("sections.types.{$this->type}", []);
    }

    public function template(): string
    {
        return $this->definition()['template'] ?? 'sections.unknown';
    }

    public function editor(): string
    {
        return $this->definition()['editor'] ?? 'admin.sections.editors.unknown';
    }
}
