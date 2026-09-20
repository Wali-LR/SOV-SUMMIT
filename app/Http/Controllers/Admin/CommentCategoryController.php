<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentCategoryRequest;
use App\Models\CommentCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $categories = CommentCategory::query()
            ->when($search !== '', fn ($q) => $q->where(function ($qq) use ($search) {
                $qq->where('name', 'like', "%{$search}%")
                   ->orWhere('slug', 'like', "%{$search}%");
            }))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.comment-categories.index', compact('categories', 'search'));
    }

    public function create(): View
    {
        return view('admin.comment-categories.create', ['category' => new CommentCategory(['is_active' => true, 'sort_order' => 0])]);
    }

    public function store(StoreCommentCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = CommentCategory::uniqueSlug($data['name']);
        }

        $category = CommentCategory::create($data);

        return redirect()->route('admin.comment-categories.index')
            ->with('status', "Category “{$category->name}” created.");
    }

    public function edit(CommentCategory $commentCategory): View
    {
        return view('admin.comment-categories.edit', ['category' => $commentCategory]);
    }

    public function update(StoreCommentCategoryRequest $request, CommentCategory $commentCategory): RedirectResponse
    {
        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = CommentCategory::uniqueSlug($data['name'], $commentCategory->id);
        }

        $commentCategory->update($data);

        return redirect()->route('admin.comment-categories.index')
            ->with('status', "Category “{$commentCategory->name}” updated.");
    }

    public function destroy(CommentCategory $commentCategory): RedirectResponse
    {
        $name = $commentCategory->name;
        $commentCategory->delete();

        return redirect()->route('admin.comment-categories.index')
            ->with('status', "Category “{$name}” deleted.");
    }
}
