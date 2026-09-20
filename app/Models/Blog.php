<?php

namespace App\Models;

use App\Models\Concerns\HasContentSections;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Blog extends Model
{
    /** @use HasFactory<\Database\Factories\BlogFactory> */
    use HasFactory;
    use HasContentSections;

    protected string $contentSectionPageType = 'blog';

    protected $fillable = [
        'title',
        'slug',
        'seo_title',
        'seo_keywords',
        'summary',
        'description',
        'cover_image',
        'category',
        'author',
        'reading_time',
        'published_at',
        'is_published',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'reading_time' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $blog) {
            if (empty($blog->slug)) {
                $blog->slug = static::uniqueSlug($blog->title, $blog->id);
            }
        });
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'post';
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
}
