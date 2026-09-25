<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::ordered()->paginate(20);
        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('admin.services.create', ['service' => new Service()]);
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $data = $request->payload();

        if ($request->hasFile('hero_image')) {
            $data['hero_image'] = $this->uploadHero($request->file('hero_image'));
        } else {
            unset($data['hero_image']);
        }

        if (empty($data['slug'])) {
            $data['slug'] = Service::uniqueSlug($data['title']);
        }

        $service = Service::create($data);

        return redirect()->route('admin.services.index')
            ->with('status', "Service “{$service->title}” created.");
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(StoreServiceRequest $request, Service $service): RedirectResponse
    {
        $data = $request->payload();

        if ($request->hasFile('hero_image')) {
            $newPath = $this->uploadHero($request->file('hero_image'));
            $this->deleteHero($service->hero_image);
            $data['hero_image'] = $newPath;
        } else {
            unset($data['hero_image']);
        }

        if (empty($data['slug'])) {
            $data['slug'] = Service::uniqueSlug($data['title'], $service->id);
        }

        $service->update($data);

        return redirect()->route('admin.services.index')
            ->with('status', "Service “{$service->title}” updated.");
    }

    public function destroy(Service $service): RedirectResponse
    {
        $this->deleteHero($service->hero_image);
        $title = $service->title;
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('status', "Service “{$title}” deleted.");
    }

    private function uploadHero($file): string
    {
        $ext = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = 'services/'.date('Y/m').'/'.Str::uuid().'.'.$ext;
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
