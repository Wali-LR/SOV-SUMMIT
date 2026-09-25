<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::published()->ordered()->get();
        return view('pages.services.index', compact('services'));
    }

    public function show(Service $service)
    {
        abort_unless($service->is_published, 404);

        $related = Service::published()
            ->where('id', '!=', $service->id)
            ->ordered()
            ->limit(4)
            ->get();

        return view('pages.services.show', compact('service', 'related'));
    }

    public function managementTraining()
    {
        $service = Service::published()->where('slug', 'management-training')->firstOrFail();
        return $this->show($service);
    }
}
