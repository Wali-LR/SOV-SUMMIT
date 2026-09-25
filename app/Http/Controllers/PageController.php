<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(string $slug): View
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->when(!optional(auth()->user())->id, fn ($q) => $q->where('is_published', true))
            ->firstOrFail();

        return view('pages.dynamic.show', compact('page'));
    }
}
