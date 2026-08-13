<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\Setting;
use App\Models\Slider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Pemeriksaan cepat penyebab gambar tidak tampil.
 *
 * Jalankan di server: php artisan dekorasi:diagnosa
 */
class Diagnosa extends Command
{
    protected $signature = 'dekorasi:diagnosa';

    protected $description = 'Periksa konfigurasi gambar: APP_URL, symlink uploads, berkas statis, dan URL contoh';

    public function handle(): int
    {
        $this->info('=== Diagnosa Dekorasi.me ===');
        $this->newLine();

        $masalah = 0;

        // --- 1. Konfigurasi dasar -------------------------------------------
        $this->line('<comment>1. Konfigurasi</comment>');
        $appUrl = rtrim((string) config('app.url'), '/');
        $this->line("   APP_URL          : {$appUrl}");
        $this->line('   URL disk publik  : '.config('filesystems.disks.public.url'));
        $this->line('   public_path()    : '.public_path());
        $this->line('   base_path()      : '.base_path());

        if ($appUrl === '' || str_contains($appUrl, 'localhost') || str_contains($appUrl, '127.0.0.1')) {
            $this->error('   [MASALAH] APP_URL masih localhost / kosong. Semua URL gambar akan salah.');
            $this->line('             Perbaiki APP_URL di .env, lalu: php artisan config:cache');
            $masalah++;
        }

        $this->newLine();

        // --- 2. Folder unggahan ----------------------------------------------
        $this->line('<comment>2. Folder unggahan</comment>');
        $folder = config('filesystems.disks.public.root');
        $this->line('   Lokasi           : '.$folder);

        if (! is_dir($folder)) {
            $this->error('   [MASALAH] Folder "uploads" TIDAK ADA.');
            $this->line('             Buat folder "uploads" di root project, lalu chmod 775.');
            $masalah++;
        } else {
            $this->line('   Status           : ADA');

            if (! is_writable($folder)) {
                $this->error('   [MASALAH] Folder tidak bisa ditulis — unggahan gambar akan gagal.');
                $this->line('             Jalankan: chmod -R 775 uploads');
                $masalah++;
            } else {
                $this->line('   Bisa ditulis     : ya');
            }

            $this->line('   Pelindung        : '.(file_exists($folder.'/.htaccess')
                ? '.htaccess ADA'
                : '.htaccess HILANG (eksekusi skrip tidak diblokir)'));
        }

        // Hitung gambar saja — abaikan .htaccess dan berkas sistem lainnya.
        $jumlahBerkas = collect(Storage::disk('public')->allFiles())
            ->reject(fn ($f) => str_starts_with(basename($f), '.'))
            ->count();

        $this->line("   Gambar terunggah : {$jumlahBerkas}");

        // Sisa berkas dari struktur lama (sebelum pindah dari storage/app/public)
        $lama = storage_path('app/public');

        if (is_dir($lama)) {
            $sisa = collect(scandir($lama))->reject(fn ($f) => in_array($f, ['.', '..', '.gitignore'], true));

            if ($sisa->isNotEmpty()) {
                $this->warn('   [PERHATIAN] Masih ada '.$sisa->count().' item di storage/app/public.');
                $this->line('               Pindahkan isinya ke folder "uploads":');
                $this->line('               mv storage/app/public/* uploads/');
            }
        }

        $this->line('   Catatan          : storage:link TIDAK diperlukan pada struktur ini.');

        $this->newLine();

        // --- 3. Berkas statis wajib ------------------------------------------
        $this->line('<comment>3. Berkas statis</comment>');
        $wajib = [
            'img/placeholder.svg',
            'img/about-dekorasi.jpg',
            'img/brand/logo.png',
            'img/brand/mark.png',
            'css/site.css',
            'css/admin-brand.css',
            'css/admin-auth.css',
            'assets/vendor/css/core.css',
            'assets/js/main.js',
        ];

        foreach ($wajib as $berkas) {
            $ada = file_exists(public_path($berkas));
            $this->line(sprintf('   %-32s %s', $berkas, $ada ? 'ADA' : 'HILANG'));

            if (! $ada) {
                $masalah++;
            }
        }

        $this->newLine();

        // --- 4. URL contoh ----------------------------------------------------
        $this->line('<comment>4. URL contoh (buka di browser untuk menguji)</comment>');
        $this->line('   Placeholder      : '.asset('img/placeholder.svg'));

        $slider = Slider::whereNotNull('image')->first();
        $project = Project::whereNotNull('cover_image')->first();
        $aboutImage = Setting::get('about.image');

        $this->line('   Slider berlogo   : '.($slider ? upload_url($slider->image) : '(belum ada slider bergambar)'));
        $this->line('   Sampul proyek    : '.($project ? upload_url($project->cover_image) : '(belum ada proyek bergambar)'));
        $this->line('   Gambar Tentang   : '.($aboutImage ? upload_url($aboutImage) : '(memakai bawaan img/about-dekorasi.jpg)'));

        $this->newLine();

        // --- 5. Cache ----------------------------------------------------------
        $this->line('<comment>5. Cache</comment>');
        $this->line('   Config di-cache  : '.(file_exists(base_path('bootstrap/cache/config.php')) ? 'ya' : 'tidak'));
        $this->line('   Route di-cache   : '.(file_exists(base_path('bootstrap/cache/routes-v7.php')) ? 'ya' : 'tidak'));

        $this->newLine();

        // --- 6. Keamanan berkas sensitif -------------------------------------
        $this->line('<comment>6. Keamanan (berkas sensitif harus TIDAK bisa diakses)</comment>');

        if (! $appUrl || str_contains($appUrl, 'localhost') || str_contains($appUrl, '127.0.0.1')) {
            $this->line('   Dilewati — APP_URL belum menunjuk domain sungguhan.');
        } else {
            $ujiUrl = [
                '/.env',
                '/composer.json',
                '/storage/logs/laravel.log',
                '/app/Models/User.php',
                '/uploads/..%2f.env',
            ];

            foreach ($ujiUrl as $jalur) {
                try {
                    $respons = \Illuminate\Support\Facades\Http::withoutVerifying()
                        ->timeout(10)
                        ->get($appUrl.$jalur);

                    $kode = $respons->status();
                    $aman = in_array($kode, [403, 404], true);

                    $this->line(sprintf(
                        '   %-30s HTTP %d  %s',
                        $jalur,
                        $kode,
                        $aman ? 'aman' : '<< BOCOR — SEGERA PERBAIKI'
                    ));

                    if (! $aman) {
                        $masalah++;
                    }
                } catch (\Throwable $e) {
                    $this->line(sprintf('   %-30s gagal diuji (%s)', $jalur, $e->getMessage()));
                }
            }

            $this->line('   Catatan: bila ada yang BOCOR, ganti kata sandi database dan');
            $this->line('            jalankan "php artisan key:generate" setelah menutup celahnya.');
        }

        $this->newLine();

        if ($masalah > 0) {
            $this->error("Ditemukan {$masalah} masalah. Perbaiki sesuai catatan di atas, lalu jalankan:");
            $this->line('  php artisan optimize:clear');

            return self::FAILURE;
        }

        $this->info('Semua pemeriksaan lolos.');
        $this->line('Kalau gambar masih tidak tampil, buka URL contoh di atas satu per satu:');
        $this->line('  404 = berkas tidak ada di server   403 = diblokir .htaccess');

        return self::SUCCESS;
    }
}
