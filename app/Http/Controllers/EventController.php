<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $upcoming = Event::published()->upcoming()->orderBy('event_date')->get();
        $past = Event::published()->past()->orderByDesc('event_date')->get();
        $undated = Event::published()->whereNull('event_date')->orderByDesc('created_at')->get();

        return view('pages.events', compact('upcoming', 'past', 'undated'));
    }

    public function show(Event $event)
    {
        abort_unless($event->is_published, 404);

        $related = Event::published()
            ->where('id', '!=', $event->id)
            ->orderByDesc('event_date')
            ->limit(3)
            ->get();

        return view('pages.events-show', compact('event', 'related'));
    }
}
