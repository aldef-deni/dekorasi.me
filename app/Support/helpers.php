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

if (! function_exists('absolute_url')) {
    /**
     * Jadikan URL absolut bila masih relatif.
     *
     * URL gambar sengaja disimpan root-relatif ("/uploads/...") supaya tidak
     * terikat domain. Sebagian tempat tetap butuh bentuk absolut — og:image
     * dan canonical, karena dibaca mesin di luar situs.
     */
    function absolute_url(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://') || str_starts_with($url, 'data:')) {
            return $url;
        }

        return url($url);
    }
}

if (! function_exists('setting_t')) {
    /**
     * Pengaturan yang mengikuti bahasa aktif.
     *
     * Terjemahan disimpan pada key berakhiran "_<kode bahasa>", mis.
     * "about.body_en". Bila kosong, otomatis mundur ke bahasa utama.
     */
    function setting_t(string $key, mixed $default = null): mixed
    {
        if (app()->getLocale() !== \App\Http\Middleware\SetLocale::PRIMARY) {
            $terjemahan = setting($key.'_'.app()->getLocale());

            if (filled($terjemahan)) {
                return $terjemahan;
            }
        }

        return setting($key, $default);
    }
}

if (! function_exists('maps_embed_url')) {
    /**
     * URL peta untuk disematkan di halaman Kontak.
     *
     * Urutannya:
     *   1. Kode embed khusus dari Pengaturan Situs — menerima URL polos maupun
     *      seluruh tag <iframe>, karena banyak orang menempel tag utuh.
     *   2. Bila kosong, peta dibangun otomatis dari alamat yang diisi admin,
     *      sehingga peta selalu mengikuti alamat tanpa perlu diatur terpisah.
     */
    function maps_embed_url(): ?string
    {
        $embed = trim((string) setting('contact.maps_embed'));

        if ($embed !== '') {
            // Ambil src="..." bila yang ditempel adalah tag iframe utuh.
            if (preg_match('/src=["\']([^"\']+)["\']/i', $embed, $cocok)) {
                return $cocok[1];
            }

            if (str_starts_with($embed, 'http')) {
                return $embed;
            }
        }

        $alamat = trim((string) setting('contact.address'));

        if ($alamat === '') {
            return null;
        }

        // z=16 : tingkat perbesaran yang pas untuk skala jalan.
        //
        // Catatan: Google selalu menampilkan panel info alamat di kiri atas peta
        // sematan dan tidak bisa dimatikan lewat parameter URL. Karena itu kartu
        // alamat di halaman diletakkan pada sisi kanan (lihat .map-card).
        return 'https://www.google.com/maps?q='.rawurlencode($alamat).'&z=16&output=embed';
    }
}

if (! function_exists('maps_link_url')) {
    /**
     * Tautan Google Maps untuk dibuka di tab baru.
     *
     * @param  bool  $petunjukArah  true = langsung ke mode petunjuk arah
     */
    function maps_link_url(bool $petunjukArah = false): ?string
    {
        $alamat = trim((string) setting('contact.address'));

        if ($alamat === '') {
            return null;
        }

        return $petunjukArah
            ? 'https://www.google.com/maps/dir/?api=1&destination='.rawurlencode($alamat)
            : 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($alamat);
    }
}

if (! function_exists('avatar_url')) {
    /**
     * Foto profil pengguna. Bila belum mengunggah foto, dibuatkan lingkaran
     * berinisial nama dengan warna merek — tanpa perlu berkas gambar.
     */
    function avatar_url(?\App\Models\User $user): string
    {
        if ($user?->avatar) {
            return upload_url($user->avatar);
        }

        $inisial = collect(preg_split('/\s+/', trim((string) ($user?->name ?: 'A'))))
            ->filter()
            ->take(2)
            ->map(fn (string $kata) => mb_strtoupper(mb_substr($kata, 0, 1)))
            ->implode('');

        $svg = <<<SVG
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100">
              <defs>
                <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
                  <stop offset="0%" stop-color="#d9a441"/>
                  <stop offset="100%" stop-color="#8a6318"/>
                </linearGradient>
              </defs>
              <rect width="100" height="100" rx="50" fill="url(#g)"/>
              <text x="50" y="50" text-anchor="middle" dominant-baseline="central"
                    font-family="Georgia, serif" font-size="42" font-weight="600" fill="#fff">{$inisial}</text>
            </svg>
            SVG;

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
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
