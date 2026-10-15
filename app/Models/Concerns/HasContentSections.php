<?php

namespace App\Models\Concerns;

use App\Models\ContentSection;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasContentSections
{
    public function sections(): MorphMany
    {
        return $this->morphMany(ContentSection::class, 'sectionable')->orderBy('position');
    }

    public function publishedSections(): MorphMany
    {
        return $this->sections()->where('is_published', true);
    }

    public function contentSectionPageType(): string
    {
        return property_exists($this, 'contentSectionPageType') ? $this->contentSectionPageType : 'default';
    }
}
