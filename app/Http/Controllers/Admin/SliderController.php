<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Support\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SliderController extends Controller
{
    public function __construct(private readonly ImageService $images)
    {
    }

    public function index(): View
    {
        return view('admin.sliders.index', [
            'sliders' => Slider::ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.sliders.form', ['slider' => new Slider()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, required: true);

        if ($request->hasFile('image')) {
            $data['image'] = $this->images->store($request->file('image'), 'sliders');
        }

        Slider::create($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slide berhasil ditambahkan.');
    }

    public function edit(Slider $slider): View
    {
        return view('admin.sliders.form', compact('slider'));
    }

    public function update(Request $request, Slider $slider): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image'] = $this->images->store($request->file('image'), 'sliders', $slider->image);
        }

        $slider->update($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slide berhasil diperbarui.');
    }

    public function destroy(Slider $slider): RedirectResponse
    {
        $this->images->delete($slider->image);
        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('success', 'Slide berhasil dihapus.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, bool $required = false): array
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'subtitle'    => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image'       => [$required ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'cta_label'   => ['nullable', 'string', 'max:100'],
            'cta_url'     => ['nullable', 'string', 'max:255'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
        ], [], [
            'title'      => 'judul',
            'image'      => 'gambar',
            'cta_label'  => 'label tombol',
            'cta_url'    => 'tautan tombol',
            'sort_order' => 'urutan',
        ]);

        unset($data['image']);
        $data['sort_order'] = $request->integer('sort_order');
        $data['is_active']  = $request->boolean('is_active');

        return $data;
    }
}
