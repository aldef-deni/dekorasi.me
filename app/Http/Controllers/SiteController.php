<?php

namespace App\Http\Controllers;

use App\Models\Project;
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

        return view('site.projects', [
            'projects'   => $projects,
            'categories' => Project::active()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category'),
            'active'     => $category,
        ]);
    }

    public function projectDetail(Project $project): View
    {
        abort_unless($project->is_active, 404);

        $project->load('images');

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

    public function contact(): View
    {
        return view('site.contact');
    }
}
