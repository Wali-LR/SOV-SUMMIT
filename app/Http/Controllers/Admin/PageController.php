<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePageRequest;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        return view('admin.pages.create', ['page' => new Page()]);
    }

    public function store(StorePageRequest $request): RedirectResponse
    {
        $data = $request->payload();

        if ($request->hasFile('hero_image')) {
            $data['hero_image'] = $this->uploadHero($request->file('hero_image'));
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
            $newPath = $this->uploadHero($request->file('hero_image'));
            $this->deleteHero($page->hero_image);
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
        $this->deleteHero($page->hero_image);
        $title = $page->title;
        $page->sections()->delete();
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('status', "Page “{$title}” deleted.");
    }

    private function uploadHero($file): string
    {
        $ext = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = 'pages/'.date('Y/m').'/'.Str::uuid().'.'.$ext;
        Storage::disk('spaces')->putFileAs('', $file, $filename, ['visibility' => 'public']);
        return $filename;
    }

    private function deleteHero(?string $path): void
    {
        if ($path && !Str::startsWith($path, ['http://', 'https://', 'assets/'])) {
            Storage::disk('spaces')->delete($path);
        }
    }
}
