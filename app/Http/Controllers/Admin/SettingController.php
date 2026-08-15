<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SettingController extends Controller
{
    /** Field bertipe gambar — ditangani terpisah dari field teks. */
    private const IMAGE_FIELDS = [
        'site.logo', 'site.logo_dark', 'site.favicon', 'seo.og_image',
        // Banner kepala tiap halaman
        'banner.about', 'banner.services', 'banner.projects', 'banner.contact',
    ];

    public function __construct(private readonly ImageService $images)
    {
    }

    public function edit(): View
    {
        return view('admin.settings.edit');
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'site_name'        => ['required', 'string', 'max:100'],
            'site_tagline'     => ['nullable', 'string', 'max:200'],
            'site_description' => ['nullable', 'string', 'max:500'],

            'contact_phone'      => ['nullable', 'string', 'max:50'],
            'contact_whatsapp'   => ['nullable', 'string', 'max:50'],
            'contact_email'      => ['nullable', 'email', 'max:100'],
            'contact_address'    => ['nullable', 'string', 'max:300'],
            'contact_hours'      => ['nullable', 'string', 'max:150'],
            'contact_maps_embed' => ['nullable', 'string', 'max:2000'],

            'social_instagram' => ['nullable', 'string', 'max:255'],
            'social_facebook'  => ['nullable', 'string', 'max:255'],
            'social_tiktok'    => ['nullable', 'string', 'max:255'],
            'social_youtube'   => ['nullable', 'string', 'max:255'],
            'social_linkedin'  => ['nullable', 'string', 'max:255'],

            'seo_title'    => ['nullable', 'string', 'max:150'],

            // Versi bahasa Inggris
            'site_tagline_en'     => ['nullable', 'string', 'max:200'],
            'site_description_en' => ['nullable', 'string', 'max:500'],
            'seo_title_en'        => ['nullable', 'string', 'max:150'],
            'seo_keywords' => ['nullable', 'string', 'max:300'],

            'site_logo'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'site_logo_dark' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'site_favicon'   => ['nullable', 'image', 'mimes:png,ico,webp', 'max:512'],
            'seo_og_image'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'banner_about'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'banner_services' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'banner_projects' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'banner_contact'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
        ], [], [
            'site_name'       => 'nama situs',
            'contact_email'   => 'email',
            'site_logo'       => 'logo',
            'site_logo_dark'  => 'logo versi gelap',
            'site_favicon'    => 'favicon',
            'seo_og_image'    => 'gambar share',
            'banner_about'    => 'banner Tentang Kami',
            'banner_services' => 'banner Paket Layanan',
            'banner_projects' => 'banner Portofolio',
            'banner_contact'  => 'banner Kontak',
        ]);

        // Nama input "site_name" dipetakan ke key setting "site.name"
        // (hanya underscore pertama yang menjadi titik).
        foreach ($data as $field => $value) {
            $key = Str::replaceFirst('_', '.', $field);

            if (in_array($key, self::IMAGE_FIELDS, true)) {
                continue; // gambar ditangani di bawah
            }

            Setting::put($key, $value, Str::before($key, '.'));
        }

        foreach (self::IMAGE_FIELDS as $key) {
            $input = str_replace('.', '_', $key);

            if ($request->hasFile($input)) {
                $path = $this->images->store($request->file($input), 'site', Setting::get($key));
                Setting::put($key, $path, Str::before($key, '.'));
            }
        }

        return back()->with('success', 'Pengaturan situs berhasil disimpan.');
    }
}
