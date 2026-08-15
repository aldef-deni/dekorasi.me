<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Slider;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

/**
 * Mengganti domain lama menjadi domain baru pada konten yang tersimpan di
 * database.
 *
 * URL gambar dan tautan halaman dibangun otomatis dari APP_URL, jadi tidak
 * perlu disentuh. Yang perlu diperiksa adalah teks yang Anda ketik sendiri
 * lewat dashboard — misalnya tautan tombol slider, kode embed peta, atau
 * tautan di dalam deskripsi — yang mungkin masih menunjuk domain lama.
 *
 * Contoh:
 *   php artisan dekorasi:ganti-domain dev.dekorasi.me dekorasi.me
 *   php artisan dekorasi:ganti-domain dev.dekorasi.me dekorasi.me --terapkan
 */
class GantiDomain extends Command
{
    protected $signature = 'dekorasi:ganti-domain
                            {lama : Domain lama, mis. dev.dekorasi.me}
                            {baru : Domain baru, mis. dekorasi.me}
                            {--terapkan : Benar-benar menyimpan perubahan (tanpa ini hanya pratinjau)}';

    protected $description = 'Ganti domain lama pada konten database (pratinjau dulu, simpan dengan --terapkan)';

    /** Kolom teks yang mungkin memuat tautan yang diketik admin. */
    private const KOLOM = [
        Setting::class => ['value'],
        Slider::class  => ['cta_url', 'description', 'translations'],
        Service::class => ['description', 'excerpt', 'translations'],
        Project::class => ['description', 'excerpt', 'translations'],
    ];

    public function handle(): int
    {
        $lama = trim($this->argument('lama'));
        $baru = trim($this->argument('baru'));
        $terapkan = (bool) $this->option('terapkan');

        if ($lama === '' || $baru === '' || $lama === $baru) {
            $this->error('Domain lama dan baru harus diisi dan tidak boleh sama.');

            return self::FAILURE;
        }

        $this->info($terapkan ? "Mengganti \"{$lama}\" menjadi \"{$baru}\"…" : "Pratinjau penggantian \"{$lama}\" ke \"{$baru}\"");
        $this->newLine();

        $total = 0;

        foreach (self::KOLOM as $kelas => $kolom) {
            /** @var class-string<Model> $kelas */
            $nama = class_basename($kelas);

            foreach ($kelas::all() as $baris) {
                $berubah = false;

                foreach ($kolom as $field) {
                    $nilai = $baris->getRawOriginal($field);

                    if (! is_string($nilai) || ! str_contains($nilai, $lama)) {
                        continue;
                    }

                    $this->line(sprintf('   %s#%s → %s', $nama, $baris->getKey(), $field));
                    $this->line('     '.\Illuminate\Support\Str::limit(str_replace($lama, "<{$baru}>", $nilai), 120));

                    if ($terapkan) {
                        // setAttribute dilewati agar cast JSON tidak mengacaukan isinya.
                        $baris->setRawAttributes(
                            [$field => str_replace($lama, $baru, $nilai)] + $baris->getAttributes()
                        );
                        $berubah = true;
                    }

                    $total++;
                }

                if ($berubah) {
                    $baris->save();
                }
            }
        }

        $this->newLine();

        if ($total === 0) {
            $this->info("Tidak ada konten yang memuat \"{$lama}\". Tidak ada yang perlu diubah.");

            return self::SUCCESS;
        }

        if ($terapkan) {
            $this->info("{$total} nilai diperbarui. Jalankan: php artisan optimize:clear");
        } else {
            $this->warn("{$total} nilai akan diubah. Belum ada yang disimpan.");
            $this->line('Jalankan ulang dengan --terapkan untuk menyimpannya.');
        }

        return self::SUCCESS;
    }
}
