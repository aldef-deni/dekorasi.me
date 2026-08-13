<?php

namespace App\Support;

use App\Models\Project;
use App\Models\Slider;
use Illuminate\Support\Collection;

/**
 * Menentukan gambar banner untuk tiap halaman.
 *
 * Urutan penentuannya:
 *   1. Banner khusus yang diunggah admin lewat Pengaturan Situs.
 *   2. Bila kosong, diambil otomatis dari gambar landscape yang sudah ada di
 *      sistem (slider beranda & sampul proyek) — tiap halaman mendapat gambar
 *      berbeda, dibagi berdasarkan urutan halaman.
 *   3. Bila belum ada gambar sama sekali, memakai gambar bawaan.
 */
class Banner
{
    /** Urutan ini menentukan gambar mana yang dipakai tiap halaman. */
    public const PAGES = ['about', 'services', 'projects', 'contact'];

    /** Cache per-request agar kumpulan gambar tidak diambil berulang kali. */
    protected static ?Collection $pool = null;

    public static function url(string $page): string
    {
        // 1. Banner khusus pilihan admin selalu menang.
        if ($custom = setting("banner.{$page}")) {
            return upload_url($custom);
        }

        $pool = static::pool();

        if ($pool->isEmpty()) {
            return asset('img/about-dekorasi.jpg');
        }

        // 2. Bagi rata: halaman ke-N memakai gambar ke-N (berputar bila kurang).
        $index = array_search($page, self::PAGES, true);
        $index = $index === false ? 0 : $index;

        return upload_url($pool[$index % $pool->count()]);
    }

    /**
     * Kumpulan gambar landscape yang sudah terunggah.
     * Slider didahulukan karena memang berformat lebar.
     *
     * @return Collection<int, string>
     */
    protected static function pool(): Collection
    {
        if (static::$pool !== null) {
            return static::$pool;
        }

        try {
            $slider = Slider::query()->whereNotNull('image')->orderBy('sort_order')->orderBy('id')->pluck('image');
            $proyek = Project::query()->whereNotNull('cover_image')->orderBy('sort_order')->orderBy('id')->pluck('cover_image');

            static::$pool = $slider->merge($proyek)->filter()->unique()->values();
        } catch (\Throwable) {
            // Database belum siap — halaman tetap tampil memakai gambar bawaan.
            static::$pool = collect();
        }

        return static::$pool;
    }

    /** Dipakai halaman pengaturan untuk menampilkan asal gambar bawaan. */
    public static function isAutomatic(string $page): bool
    {
        return ! setting("banner.{$page}") && static::pool()->isNotEmpty();
    }
}
