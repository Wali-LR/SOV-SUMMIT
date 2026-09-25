<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $serviceId = $this->route('service')?->id;

        return [
            'title'   => ['required', 'string', 'max:255'],
            'slug'    => ['nullable', 'string', 'max:200', 'alpha_dash', Rule::unique('services', 'slug')->ignore($serviceId)],
            'eyebrow' => ['nullable', 'string', 'max:120'],
            'summary' => ['nullable', 'string', 'max:2000'],
            'description' => ['nullable', 'string'],
            'hero_image'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],

            'services_included_label' => ['nullable', 'string', 'max:120'],
            'services_included'       => ['nullable', 'string'],
            'suitable_for_label'      => ['nullable', 'string', 'max:120'],
            'suitable_for'            => ['nullable', 'string'],

            'question'   => ['nullable', 'array'],
            'question.*' => ['nullable', 'string', 'max:255'],
            'answer'     => ['nullable', 'array'],
            'answer.*'   => ['nullable', 'string', 'max:2000'],

            'seo_title'       => ['nullable', 'string', 'max:160'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords'    => ['nullable', 'string', 'max:500'],

            'is_published' => ['sometimes', 'boolean'],
            'position'     => ['nullable', 'integer', 'min:0'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published'),
        ]);
    }

    /**
     * Convert the textarea/repeater fields into the structured JSON shapes
     * the Service model expects (arrays).
     */
    public function payload(): array
    {
        $data = $this->validated();

        $data['services_included'] = $this->splitLines($data['services_included'] ?? null);
        $data['suitable_for']      = $this->splitLines($data['suitable_for'] ?? null);
        $data['faqs']              = $this->zipFaqs($data['question'] ?? [], $data['answer'] ?? []);

        unset($data['question'], $data['answer']);

        if (empty($data['services_included_label'])) {
            $data['services_included_label'] = $data['services_included'] ? 'Services included' : null;
        }

        return $data;
    }

    private function splitLines(?string $text): ?array
    {
        if (!$text) return null;
        $items = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', $text))));
        return $items ?: null;
    }

    private function zipFaqs(array $q, array $a): ?array
    {
        $out = [];
        foreach ($q as $i => $question) {
            $question = trim((string) $question);
            $answer   = trim((string) ($a[$i] ?? ''));
            if ($question === '' && $answer === '') continue;
            $out[] = ['q' => $question, 'a' => $answer];
        }
        return $out ?: null;
    }
}
