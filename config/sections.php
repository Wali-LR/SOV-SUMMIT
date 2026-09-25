<?php

return [
    'sectionable_types' => [
        \App\Models\Event::class,
        \App\Models\Blog::class,
        \App\Models\Service::class,
    ],

    'types' => [
        'rich_text' => [
            'label' => 'Rich Text',
            'description' => 'Long-form prose with headings and lists.',
            'preview' => '/assets/img/sections/preview/rich-text.svg',
            'template' => 'sections.rich-text',
            'editor' => 'admin.sections.editors.rich-text',
            'default' => ['eyebrow' => null, 'heading' => null, 'lede' => null, 'body' => '<p></p>'],
        ],

        'image_text' => [
            'label' => 'Image + Text',
            'description' => 'A figure alongside body copy.',
            'preview' => '/assets/img/sections/preview/image-text.svg',
            'template' => 'sections.image-text',
            'editor' => 'admin.sections.editors.image-text',
            'default' => [
                'eyebrow' => null,
                'heading' => null,
                'lede' => null,
                'body' => '<p></p>',
                'image' => null,
                'image_alt' => null,
                'align' => 'left',
            ],
        ],

        'quote' => [
            'label' => 'Pull Quote',
            'description' => 'A large highlighted quotation.',
            'preview' => '/assets/img/sections/preview/quote.svg',
            'template' => 'sections.quote',
            'editor' => 'admin.sections.editors.quote',
            'default' => ['eyebrow' => null, 'body' => '', 'author' => null, 'role' => null],
        ],

        'card_grid' => [
            'label' => 'Card Grid',
            'description' => 'A grid of titled cards with numbered overlay (matches the home page approach section).',
            'preview' => '/assets/img/sections/preview/card-grid.svg',
            'template' => 'sections.card-grid',
            'editor' => 'admin.sections.editors.card-grid',
            'default' => [
                'eyebrow' => null,
                'heading' => null,
                'lede' => null,
                'columns' => 3,
                'cards' => [],
            ],
        ],

        'gallery' => [
            'label' => 'Gallery',
            'description' => 'Grid of images with captions.',
            'preview' => '/assets/img/sections/preview/gallery.svg',
            'template' => 'sections.gallery',
            'editor' => 'admin.sections.editors.gallery',
            'default' => [
                'eyebrow' => null,
                'heading' => null,
                'lede' => null,
                'columns' => 3,
                'images' => [],
            ],
        ],

        'stats' => [
            'label' => 'Stats Row',
            'description' => 'Row of headline numbers with labels.',
            'preview' => '/assets/img/sections/preview/stats.svg',
            'template' => 'sections.stats',
            'editor' => 'admin.sections.editors.stats',
            'default' => [
                'eyebrow' => null,
                'heading' => null,
                'lede' => null,
                'items' => [],
            ],
        ],

        'cta_band' => [
            'label' => 'CTA Band',
            'description' => 'Full-width call-to-action band.',
            'preview' => '/assets/img/sections/preview/cta-band.svg',
            'template' => 'sections.cta-band',
            'editor' => 'admin.sections.editors.cta-band',
            'default' => [
                'eyebrow' => null,
                'heading' => null,
                'subheading' => null,
                'cta_label' => 'Talk with us',
                'cta_href' => '/contact',
                'variant' => 'dark',
            ],
        ],

        'accordion' => [
            'label' => 'FAQ / Accordion',
            'description' => 'Expandable question and answer list.',
            'preview' => '/assets/img/sections/preview/accordion.svg',
            'template' => 'sections.accordion',
            'editor' => 'admin.sections.editors.accordion',
            'default' => [
                'eyebrow' => null,
                'heading' => null,
                'lede' => null,
                'items' => [],
            ],
        ],

        'hero_banner' => [
            'label' => 'Hero Banner',
            'description' => 'Full-bleed banner with eyebrow, heading and CTA.',
            'preview' => '/assets/img/sections/preview/hero-banner.svg',
            'template' => 'sections.hero-banner',
            'editor' => 'admin.sections.editors.hero-banner',
            'default' => [
                'eyebrow' => null,
                'heading' => null,
                'subheading' => null,
                'image' => null,
                'cta_label' => null,
                'cta_href' => null,
            ],
        ],

        'raw_html' => [
            'label' => 'Custom HTML',
            'description' => 'Advanced escape hatch — paste raw HTML.',
            'preview' => '/assets/img/sections/preview/raw-html.svg',
            'template' => 'sections.raw-html',
            'editor' => 'admin.sections.editors.raw-html',
            'default' => ['html' => ''],
        ],
    ],
];
