<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    public function templates()
    {
        $types = collect(config('sections.types', []))->map(function ($def, $key) {
            return [
                'key' => $key,
                'label' => $def['label'] ?? $key,
                'description' => $def['description'] ?? '',
                'preview' => $def['preview'] ?? null,
            ];
        })->values();

        return response()->json(['types' => $types]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sectionable_type' => ['required', 'string'],
            'sectionable_id' => ['required', 'integer'],
            'type' => ['required', 'string'],
            'page_type' => ['nullable', 'string', 'max:40'],
            'short' => ['nullable', 'string', 'max:255'],
        ]);

        $this->authorizeSectionable($data['sectionable_type']);
        $definition = config("sections.types.{$data['type']}");
        abort_unless($definition, 422, 'Unknown section type.');

        $model = $this->resolveModel($data['sectionable_type'], $data['sectionable_id']);

        $section = new ContentSection();
        $section->sectionable_type = $data['sectionable_type'];
        $section->sectionable_id = $data['sectionable_id'];
        $section->type = $data['type'];
        $section->page_type = $data['page_type'] ?? (method_exists($model, 'contentSectionPageType') ? $model->contentSectionPageType() : null);
        $section->short = $data['short'] ?? null;
        $section->data = $definition['default'] ?? [];
        $section->is_published = true;
        $section->position = ((int) $model->sections()->max('position')) + 1;
        $section->save();

        return response()->json([
            'id' => $section->id,
            'html' => view('components.content-section-wrapper', ['section' => $section])->render(),
        ]);
    }

    public function edit(ContentSection $section)
    {
        return response()->json([
            'id' => $section->id,
            'type' => $section->type,
            'label' => $section->definition()['label'] ?? $section->type,
            'short' => $section->short,
            'is_published' => $section->is_published,
            'form' => view($section->editor(), ['section' => $section])->render(),
        ]);
    }

    public function update(Request $request, ContentSection $section)
    {
        $payload = $request->validate([
            'data' => ['required', 'array'],
            'short' => ['nullable', 'string', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $section->data = $this->mergeData($section->data ?? [], $payload['data']);
        if (array_key_exists('short', $payload)) {
            $section->short = $payload['short'];
        }
        if (array_key_exists('is_published', $payload)) {
            $section->is_published = (bool) $payload['is_published'];
        }
        $section->save();

        return response()->json([
            'id' => $section->id,
            'html' => view('components.content-section-wrapper', ['section' => $section])->render(),
        ]);
    }

    public function destroy(ContentSection $section)
    {
        $section->delete();
        return response()->json(['ok' => true]);
    }

    public function reorder(Request $request)
    {
        $payload = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
            'sectionable_type' => ['required', 'string'],
            'sectionable_id' => ['required', 'integer'],
        ]);

        $this->authorizeSectionable($payload['sectionable_type']);

        DB::transaction(function () use ($payload) {
            foreach ($payload['ids'] as $index => $id) {
                ContentSection::where('id', $id)
                    ->where('sectionable_type', $payload['sectionable_type'])
                    ->where('sectionable_id', $payload['sectionable_id'])
                    ->update(['position' => $index + 1]);
            }
        });

        return response()->json(['ok' => true]);
    }

    protected function authorizeSectionable(string $class): void
    {
        $allowed = config('sections.sectionable_types', []);
        abort_unless(in_array($class, $allowed, true), 403, 'Sectionable class not allowed.');
    }

    protected function resolveModel(string $class, int $id)
    {
        abort_unless(class_exists($class), 422, 'Unknown model.');
        return $class::query()->findOrFail($id);
    }

    protected function mergeData(array $existing, array $incoming): array
    {
        return array_replace_recursive($existing, $incoming);
    }
}
