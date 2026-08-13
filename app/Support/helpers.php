<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

if (! function_exists('setting')) {
    /**
     * Ambil nilai pengaturan situs.
     *
     * Dibungkus try/catch supaya halaman tetap tampil (memakai nilai default)
     * saat database belum dimigrasi atau sedang tidak bisa diakses.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        try {
            $value = Setting::get($key, $default);
        } catch (\Throwable) {
            return $default;
        }

        return ($value === null || $value === '') ? $default : $value;
    }
}

if (! function_exists('upload_url')) {
    /**
     * URL publik untuk file yang tersimpan di disk "public".
     * Mengembalikan $fallback bila path kosong.
     */
    function upload_url(?string $path, ?string $fallback = null): ?string
    {
        if (! $path) {
            return $fallback;
        }

        // Path absolut / URL penuh dipakai apa adanya.
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}

if (! function_exists('banner_url')) {
    /**
     * URL banner untuk sebuah halaman ('about', 'services', 'projects', 'contact').
     * Lihat App\Support\Banner untuk urutan penentuannya.
     */
    function banner_url(string $page): string
    {
        return \App\Support\Banner::url($page);
    }
}

if (! function_exists('parse_poin')) {
    /**
     * Ubah teks multi-baris menjadi daftar poin berlabel.
     *
     * Setiap baris boleh ditulis "Label : Penjelasan" — bagian sebelum titik
     * dua menjadi judul poin, sisanya menjadi penjelasan. Baris tanpa titik dua
     * tetap dipakai, hanya saja tanpa penjelasan.
     *
     * @return \Illuminate\Support\Collection<int, array{label: string, teks: string}>
     */
    function parse_poin(?string $teks): \Illuminate\Support\Collection
    {
        return collect(preg_split('/\R+/', (string) $teks))
            ->map(fn (string $baris) => trim(ltrim($baris, "-•* \t")))
            ->filter()
            ->map(function (string $baris) {
                // Hanya titik dua pertama yang dianggap pemisah label.
                $bagian = preg_split('/\s*:\s*/', $baris, 2);

                return count($bagian) === 2 && $bagian[1] !== ''
                    ? ['label' => $bagian[0], 'teks' => $bagian[1]]
                    : ['label' => $baris, 'teks' => ''];
            })
            ->values();
    }
}

if (! function_exists('whatsapp_url')) {
    /** Bangun link wa.me dari nomor telepon bebas format. */
    function whatsapp_url(?string $number, string $message = ''): string
    {
        $digits = preg_replace('/\D+/', '', (string) $number);

        if (! $digits) {
            return '#';
        }

        // 08xxx -> 628xxx
        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }

        return 'https://wa.me/'.$digits.($message ? '?text='.rawurlencode($message) : '');
    }
}
