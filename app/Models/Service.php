<?php

namespace App\Models;

use App\Models\Concerns\HasContentSections;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;
    use HasContentSections;

    protected string $contentSectionPageType = 'service';

    protected $fillable = [
        'slug',
        'title',
        'eyebrow',
        'summary',
        'description',
        'hero_image',
        'services_included_label',
        'services_included',
        'suitable_for_label',
        'suitable_for',
        'faqs',
        'seo_title',
        'seo_keywords',
        'seo_description',
        'is_published',
        'position',
        'published_at',
    ];

    protected $casts = [
        'services_included' => 'array',
        'suitable_for'      => 'array',
        'faqs'              => 'array',
        'is_published'      => 'boolean',
        'position'          => 'integer',
        'published_at'      => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $service) {
            if (empty($service->slug)) {
                $service->slug = static::uniqueSlug($service->title, $service->id);
            }
        });
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'service';
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }

    public function getHeroUrlAttribute(): ?string
    {
        $path = $this->hero_image;
        if (!$path) {
            return null;
        }
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }
        if (Str::startsWith($path, 'assets/')) {
            return asset($path);
        }
        return Storage::disk('spaces')->url($path);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position')->orderBy('title');
    }

    public function publicUrl(): string
    {
        if ($this->slug === 'management-training') {
            return url('/management-training');
        }
        return route('services.show', $this->slug);
    }
}
