<?php

namespace App\Models;

use App\Models\Concerns\HasContentSections;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;
    use HasContentSections;

    protected string $contentSectionPageType = 'event';

    protected $fillable = [
        'title',
        'slug',
        'seo_title',
        'seo_keywords',
        'summary',
        'description',
        'cover_image',
        'event_date',
        'location',
        'is_published',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'is_published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $event) {
            if (empty($event->slug)) {
                $event->slug = static::uniqueSlug($event->title, $event->id);
            }
        });
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'event';
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }

    public function getCoverUrlAttribute(): ?string
    {
        if (!$this->cover_image) {
            return null;
        }
        if (Str::startsWith($this->cover_image, ['http://', 'https://'])) {
            return $this->cover_image;
        }
        return Storage::disk('spaces')->url($this->cover_image);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->whereNotNull('event_date')->where('event_date', '>=', now()->startOfDay());
    }

    public function scopePast($query)
    {
        return $query->whereNotNull('event_date')->where('event_date', '<', now()->startOfDay());
    }
}
