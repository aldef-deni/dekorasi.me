<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Support\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(private readonly ImageService $images)
    {
    }

    public function index(Request $request): View
    {
        $projects = Project::query()
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->string('category')))
            ->withCount('images')
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        return view('admin.projects.index', [
            'projects'   => $projects,
            'categories' => Project::query()->whereNotNull('category')->distinct()->pluck('category'),
        ]);
    }

    public function create(): View
    {
        return view('admin.projects.form', ['project' => new Project()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->images->store($request->file('cover_image'), 'projects');
        }

        $project = Project::create($data);
        $this->simpanTerjemahan($project, $request);

        return redirect()->route('admin.projects.edit', $project)
            ->with('success', 'Proyek berhasil dibuat. Silakan tambahkan foto galeri.');
    }

    public function edit(Project $project): View
    {
        $project->load('images');

        return view('admin.projects.form', compact('project'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $data = $this->validated($request, $project);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->images->store($request->file('cover_image'), 'projects', $project->cover_image);
        }

        $project->update($data);
        $this->simpanTerjemahan($project, $request);

        return redirect()->route('admin.projects.edit', $project)->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        // Hapus seluruh berkas gambar sebelum baris database ikut terhapus (cascade).
        $this->images->delete($project->cover_image);
        $project->images->each(fn ($image) => $this->images->delete($image->path));

        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Proyek berhasil dihapus.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, ?Project $project = null): array
    {
        $request->merge([
            'slug' => Str::slug($request->input('slug') ?: $request->input('title')),
        ]);

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'max:255', Rule::unique('projects', 'slug')->ignore($project?->id)],
            'category'    => ['nullable', 'string', 'max:100'],
            'client'      => ['nullable', 'string', 'max:150'],
            'location'    => ['nullable', 'string', 'max:150'],
            'area'        => ['nullable', 'string', 'max:50'],
            'year'        => ['nullable', 'integer', 'min:1950', 'max:'.(date('Y') + 5)],
            'excerpt'     => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
        ], [], [
            'title'       => 'judul',
            'category'    => 'kategori',
            'client'      => 'klien',
            'location'    => 'lokasi',
            'area'        => 'luas',
            'year'        => 'tahun',
            'excerpt'     => 'ringkasan',
            'description' => 'deskripsi',
            'cover_image' => 'gambar sampul',
            'sort_order'  => 'urutan',
        ]);

        unset($data['cover_image']);
        $data['sort_order']  = $request->integer('sort_order');
        $data['is_active']   = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        return $data;
    }

    /** Simpan versi Inggris dari kolom yang bisa diterjemahkan. */
    private function simpanTerjemahan(\Illuminate\Database\Eloquent\Model $model, Request $request): void
    {
        $model->setTranslation('en', (array) $request->input('en', []));
        $model->save();
    }
}
