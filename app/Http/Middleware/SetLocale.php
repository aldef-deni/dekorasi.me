<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menentukan bahasa tampilan untuk setiap permintaan.
 *
 * Pilihan bahasa disimpan di sesi. Bila belum pernah memilih, dipakai
 * bahasa bawaan dari config/app.php (Indonesia).
 */
class SetLocale
{
    /** Bahasa yang didukung: kode => nama tampilan. */
    public const SUPPORTED = [
        'id' => 'Indonesia',
        'en' => 'English',
    ];

    /**
     * Bahasa utama — teks aslinya tersimpan di kolom biasa, bukan di kolom
     * terjemahan.
     *
     * Sengaja konstanta, bukan config('app.locale'): Laravel ikut menimpa
     * nilai config itu setiap kali app()->setLocale() dipanggil, sehingga
     * tidak bisa dipakai sebagai acuan bahasa utama.
     */
    public const PRIMARY = 'id';

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale');

        if (! array_key_exists((string) $locale, self::SUPPORTED)) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
