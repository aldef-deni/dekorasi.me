<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AboutController extends Controller
{
    private const IMAGE_FIELDS = ['about.image', 'about.image_secondary'];

    public function __construct(private readonly ImageService $images)
    {
    }

    public function edit(): View
    {
        return view('admin.about.edit');
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'about_heading'  => ['required', 'string', 'max:150'],
            'about_subtitle' => ['nullable', 'string', 'max:250'],
            'about_body'     => ['nullable', 'string', 'max:8000'],
            'about_vision'   => ['nullable', 'string', 'max:1000'],
            'about_mission'  => ['nullable', 'string', 'max:2000'],

            'about_stat1_value' => ['nullable', 'string', 'max:20'],
            'about_stat1_label' => ['nullable', 'string', 'max:60'],
            'about_stat2_value' => ['nullable', 'string', 'max:20'],
            'about_stat2_label' => ['nullable', 'string', 'max:60'],
            'about_stat3_value' => ['nullable', 'string', 'max:20'],
            'about_stat3_label' => ['nullable', 'string', 'max:60'],
            'about_stat4_value' => ['nullable', 'string', 'max:20'],
            'about_stat4_label' => ['nullable', 'string', 'max:60'],

            'about_image'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'about_image_secondary' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ], [], [
            'about_heading' => 'judul',
            'about_body'    => 'isi',
            'about_image'   => 'gambar utama',
        ]);

        foreach ($data as $field => $value) {
            $key = Str::replaceFirst('_', '.', $field);

            if (in_array($key, self::IMAGE_FIELDS, true)) {
                continue;
            }

            Setting::put($key, $value, 'about');
        }

        foreach (self::IMAGE_FIELDS as $key) {
            $input = str_replace('.', '_', $key);

            if ($request->hasFile($input)) {
                $path = $this->images->store($request->file($input), 'about', Setting::get($key));
                Setting::put($key, $path, 'about');
            }
        }

        return back()->with('success', 'Halaman Tentang Kami berhasil disimpan.');
    }
}
