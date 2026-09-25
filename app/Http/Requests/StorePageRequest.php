<?php

namespace App\Http\Requests;

use App\Models\Page;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $pageId = $this->route('page')?->id;

        return [
            'title'   => ['required', 'string', 'max:255'],
            'slug'    => [
                'nullable', 'string', 'max:200', 'alpha_dash',
                Rule::notIn(Page::RESERVED_SLUGS),
                Rule::unique('pages', 'slug')->ignore($pageId),
            ],
            'eyebrow' => ['nullable', 'string', 'max:120'],
            'summary' => ['nullable', 'string', 'max:2000'],
            'description' => ['nullable', 'string'],
            'hero_image'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],

            'seo_title'       => ['nullable', 'string', 'max:160'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords'    => ['nullable', 'string', 'max:500'],

            'is_published' => ['sometimes', 'boolean'],
            'show_in_nav'  => ['sometimes', 'boolean'],
            'nav_label'    => ['nullable', 'string', 'max:60'],
            'nav_order'    => ['nullable', 'integer', 'min:0'],
            'position'     => ['nullable', 'integer', 'min:0'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.not_in' => 'This slug is reserved by an existing route. Choose another.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published'),
            'show_in_nav'  => $this->boolean('show_in_nav'),
        ]);
    }

    public function payload(): array
    {
        return $this->validated();
    }
}
