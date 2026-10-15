<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use App\Support\MediaStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $events = Event::orderByDesc('event_date')->orderByDesc('created_at')->paginate(20);

        return view('admin.events.index', compact('events'));
    }

    public function create(): View
    {
        return view('admin.events.create', ['event' => new Event]);
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = MediaStorage::upload($request->file('cover_image'), 'events');
        } else {
            unset($data['cover_image']);
        }

        if (empty($data['slug'])) {
            $data['slug'] = Event::uniqueSlug($data['title']);
        }

        $event = Event::create($data);

        return redirect()->route('admin.events.index')
            ->with('status', "Event “{$event->title}” created.");
    }

    public function edit(Event $event): View
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(StoreEventRequest $request, Event $event): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $newPath = MediaStorage::upload($request->file('cover_image'), 'events');
            MediaStorage::delete($event->cover_image);
            $data['cover_image'] = $newPath;
        } else {
            unset($data['cover_image']);
        }

        if (empty($data['slug'])) {
            $data['slug'] = Event::uniqueSlug($data['title'], $event->id);
        }

        $event->update($data);

        return redirect()->route('admin.events.index')
            ->with('status', "Event “{$event->title}” updated.");
    }

    public function destroy(Event $event): RedirectResponse
    {
        MediaStorage::delete($event->cover_image);
        $title = $event->title;
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('status', "Event “{$title}” deleted.");
    }
}
