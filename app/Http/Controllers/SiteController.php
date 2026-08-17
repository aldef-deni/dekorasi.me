<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Property;
use App\Models\Service;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        return view('site.home', [
            'sliders'  => Slider::active()->ordered()->get(),
            'services' => Service::active()->ordered()->take(6)->get(),
            'projects' => Project::active()->featured()->ordered()->take(6)->get(),
            // Properti unggulan; yang sudah terjual tidak ikut tampil di beranda.
            'properties' => Property::active()->tersedia()->featured()->ordered()->take(6)->get(),
        ]);
    }

    public function about(): View
    {
        return view('site.about', [
            'projectCount' => Project::active()->count(),
        ]);
    }

    public function services(): View
    {
        return view('site.services', [
            'services' => Service::active()->ordered()->get(),
        ]);
    }

    public function serviceDetail(Service $service): View
    {
        abort_unless($service->is_active, 404);

        return view('site.service-detail', [
            'service' => $service,
            'others'  => Service::active()->ordered()->whereKeyNot($service->id)->take(4)->get(),
        ]);
    }

    public function projects(Request $request): View
    {
        $category = $request->string('kategori')->toString();

        $projects = Project::active()
            ->when($category, fn ($q) => $q->where('category', $category))
            ->ordered()
            ->paginate(9)
            ->withQueryString();

        // Kategori dibawa sebagai pasangan nilai + label: nilainya tetap bahasa
        // Indonesia (dipakai untuk menyaring), labelnya mengikuti bahasa aktif.
        $categories = Project::active()
            ->whereNotNull('category')
            ->orderBy('category')
            ->get(['id', 'category', 'translations'])
            ->unique('category')
            ->map(fn (Project $project) => [
                'value' => $project->category,
                'label' => $project->t('category'),
            ])
            ->sortBy('label')
            ->values();

        return view('site.projects', [
            'projects'   => $projects,
            'categories' => $categories,
            'active'     => $category,
        ]);
    }

    public function projectDetail(Project $project): View
    {
        abort_unless($project->is_active, 404);

        $project->load(['images', 'videos']);

        return view('site.project-detail', [
            'project' => $project,
            'related' => Project::active()
                ->whereKeyNot($project->id)
                ->when($project->category, fn ($q) => $q->where('category', $project->category))
                ->ordered()
                ->take(3)
                ->get(),
        ]);
    }

    public function properties(Request $request): View
    {
        $type   = $request->string('jenis')->toString();
        $status = $request->string('status')->toString();

        $properties = Property::active()
            ->when($type, fn ($q) => $q->where('type', $type))
            ->when($status, fn ($q) => $q->where('listing_status', $status))
            ->ordered()
            ->paginate(9)
            ->withQueryString();

        // Nilai jenis tetap bahasa Indonesia karena dipakai menyaring;
        // labelnya mengikuti bahasa aktif.
        $types = Property::active()
            ->whereNotNull('type')
            ->orderBy('type')
            ->get(['id', 'type', 'translations'])
            ->unique('type')
            ->map(fn (Property $property) => [
                'value' => $property->type,
                'label' => $property->t('type'),
            ])
            ->sortBy('label')
            ->values();

        // Hanya status yang benar-benar dipakai yang ditawarkan sebagai filter.
        $statuses = Property::active()
            ->distinct()
            ->orderBy('listing_status')
            ->pluck('listing_status')
            ->map(fn (string $kode) => ['value' => $kode, 'label' => __('site.properties.status.'.$kode)])
            ->values();

        return view('site.properties', [
            'properties'   => $properties,
            'types'        => $types,
            'statuses'     => $statuses,
            'activeType'   => $type,
            'activeStatus' => $status,
        ]);
    }

    public function propertyDetail(Property $property): View
    {
        abort_unless($property->is_active, 404);

        $property->load(['images', 'videos']);

        return view('site.property-detail', [
            'property' => $property,
            'related'  => Property::active()
                ->tersedia()
                ->whereKeyNot($property->id)
                ->when($property->type, fn ($q) => $q->where('type', $property->type))
                ->ordered()
                ->take(3)
                ->get(),
        ]);
    }

    public function contact(): View
    {
        return view('site.contact');
    }
}
