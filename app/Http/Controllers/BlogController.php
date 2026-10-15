<?php

namespace App\Http\Controllers;

use App\Models\Blog;

class BlogController extends Controller
{
    public function index()
    {
        $featured = Blog::published()
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->first();

        $posts = Blog::published()
            ->when($featured, fn ($q) => $q->where('id', '!=', $featured->id))
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(9);

        return view('pages.blog.index', compact('featured', 'posts'));
    }

    public function show(Blog $blog)
    {
        abort_unless($blog->is_published, 404);

        $related = Blog::published()
            ->where('id', '!=', $blog->id)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('pages.blog.show', compact('blog', 'related'));
    }
}
