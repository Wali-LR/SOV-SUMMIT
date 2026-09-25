<?php

namespace App\Models;

use App\Models\Concerns\HasContentSections;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;
    use HasContentSections;

    protected string $contentSectionPageType = 'page';

    public const RESERVED_SLUGS = [
        'about', 'admin', 'blog', 'contact', 'cookie-policy', 'dashboard',
        'events', 'insights', 'legal-notice', 'login', 'logout', 'management-training',
        'pages', 'privacy-policy', 'products', 'profile', 'register',
        'services', 'terms-conditions', 'password', 'email',
    ];

    protected $fillable = [
        'slug',
        'title',
        'eyebrow',
        'summary',
        'description',
        'hero_image',
        'seo_title',
        'seo_keywords',
        'seo_description',
        'is_published',
        'show_in_nav',
        'nav_label',
        'nav_order',
        'position',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'show_in_nav'  => 'boolean',
        'nav_order'    => 'integer',
        'position'     => 'integer',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $page) {
            if (empty($page->slug)) {
                $page->slug = static::uniqueSlug($page->title, $page->id);
            }
        });
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'page';
        if (in_array($base, self::RESERVED_SLUGS, true)) {
            $base = $base.'-page';
        }
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

    public function scopeInNav($query)
    {
        return $query->where('show_in_nav', true)->orderBy('nav_order')->orderBy('title');
    }

    public function publicUrl(): string
    {
        return url('/'.$this->slug);
    }
}
