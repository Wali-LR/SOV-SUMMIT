<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        return view('admin.events.create', ['event' => new Event()]);
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->uploadCover($request->file('cover_image'));
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
            $newPath = $this->uploadCover($request->file('cover_image'));
            $this->deleteCover($event->cover_image);
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
        $this->deleteCover($event->cover_image);
        $title = $event->title;
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('status', "Event “{$title}” deleted.");
    }

    private function uploadCover($file): string
    {
        $ext = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = 'events/'.date('Y/m').'/'.Str::uuid().'.'.$ext;
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
