<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePageRequest;
use App\Models\Page;
use App\Support\MediaStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        $pages = Page::orderBy('nav_order')->orderBy('title')->paginate(20);

        return view('admin.pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.pages.create', ['page' => new Page]);
    }

    public function store(StorePageRequest $request): RedirectResponse
    {
        $data = $request->payload();

        if ($request->hasFile('hero_image')) {
            $data['hero_image'] = MediaStorage::upload($request->file('hero_image'), 'pages');
        } else {
            unset($data['hero_image']);
        }

        if (empty($data['slug'])) {
            $data['slug'] = Page::uniqueSlug($data['title']);
        }

        $page = Page::create($data);

        return redirect()->route('admin.pages.edit', $page)
            ->with('status', "Page “{$page->title}” created. Add sections below.");
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(StorePageRequest $request, Page $page): RedirectResponse
    {
        $data = $request->payload();

        if ($request->hasFile('hero_image')) {
            $newPath = MediaStorage::upload($request->file('hero_image'), 'pages');
            MediaStorage::delete($page->hero_image);
            $data['hero_image'] = $newPath;
        } else {
            unset($data['hero_image']);
        }

        if (empty($data['slug'])) {
            $data['slug'] = Page::uniqueSlug($data['title'], $page->id);
        }

        $page->update($data);

        return redirect()->route('admin.pages.index')
            ->with('status', "Page “{$page->title}” updated.");
    }

    public function destroy(Page $page): RedirectResponse
    {
        MediaStorage::delete($page->hero_image);
        $title = $page->title;
        $page->sections()->delete();
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('status', "Page “{$title}” deleted.");
    }
}
