<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Property;
use App\Models\Service;
use App\Models\Slider;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'projects'        => Project::count(),
                'projectsActive'  => Project::active()->count(),
                'services'        => Service::count(),
                'properties'      => Property::count(),
                'sliders'         => Slider::active()->count(),
            ],
            'recentProjects' => Project::latest()->take(5)->get(),
        ]);
    }
}
