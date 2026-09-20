<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogRequest;
use App\Models\Blog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $blogs = Blog::orderByDesc('published_at')->orderByDesc('created_at')->paginate(20);
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create(): View
    {
        return view('admin.blogs.create', ['blog' => new Blog()]);
    }

    public function store(StoreBlogRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->uploadCover($request->file('cover_image'));
        } else {
            unset($data['cover_image']);
        }

        if (empty($data['slug'])) {
            $data['slug'] = Blog::uniqueSlug($data['title']);
        }

        $blog = Blog::create($data);

        return redirect()->route('admin.blogs.index')
            ->with('status', "Post “{$blog->title}” created.");
    }

    public function edit(Blog $blog): View
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(StoreBlogRequest $request, Blog $blog): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $newPath = $this->uploadCover($request->file('cover_image'));
            $this->deleteCover($blog->cover_image);
            $data['cover_image'] = $newPath;
        } else {
            unset($data['cover_image']);
        }

        if (empty($data['slug'])) {
            $data['slug'] = Blog::uniqueSlug($data['title'], $blog->id);
        }

        $blog->update($data);

        return redirect()->route('admin.blogs.index')
            ->with('status', "Post “{$blog->title}” updated.");
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        $this->deleteCover($blog->cover_image);
        $title = $blog->title;
        $blog->delete();

        return redirect()->route('admin.blogs.index')
            ->with('status', "Post “{$title}” deleted.");
    }

    private function uploadCover($file): string
    {
        $ext = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = 'blogs/'.date('Y/m').'/'.Str::uuid().'.'.$ext;
        Storage::disk('spaces')->putFileAs('', $file, $filename, ['visibility' => 'public']);
        return $filename;
    }

    private function deleteCover(?string $path): void
    {
        if ($path && !Str::startsWith($path, ['http://', 'https://'])) {
            Storage::disk('spaces')->delete($path);
        }
    }
}
