<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Support\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function __construct(private readonly ImageService $images)
    {
    }

    public function index(): View
    {
        return view('admin.services.index', [
            'services' => Service::ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.services.form', ['service' => new Service()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image'] = $this->images->store($request->file('image'), 'services');
        }

        Service::create($data);

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.form', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $data = $this->validated($request, $service);

        if ($request->hasFile('image')) {
            $data['image'] = $this->images->store($request->file('image'), 'services', $service->image);
        }

        $service->update($data);

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $this->images->delete($service->image);
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil dihapus.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, ?Service $service = null): array
    {
        // Slug dibentuk lebih dulu agar cek keunikannya berlaku juga untuk
        // slug yang dibuat otomatis dari judul.
        $request->merge([
            'slug' => Str::slug($request->input('slug') ?: $request->input('title')),
        ]);

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'max:255', Rule::unique('services', 'slug')->ignore($service?->id)],
            'icon'        => ['nullable', 'string', 'max:100'],
            'excerpt'     => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
        ], [], [
            'title'      => 'judul',
            'icon'       => 'ikon',
            'excerpt'    => 'ringkasan',
            'description'=> 'deskripsi',
            'image'      => 'gambar',
            'sort_order' => 'urutan',
        ]);

        unset($data['image']);
        $data['sort_order'] = $request->integer('sort_order');
        $data['is_active']  = $request->boolean('is_active');

        return $data;
    }
}
