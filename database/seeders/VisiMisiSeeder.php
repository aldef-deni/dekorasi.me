<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Memperbarui Visi & Misi pada halaman Tentang Kami.
 *
 * Dipisah dari DatabaseSeeder karena seeder utama memakai firstOrCreate —
 * nilai yang sudah ada di server tidak akan tertimpa. Seeder ini sengaja
 * menimpa, jalankan bila ingin memakai teks resmi terbaru:
 *
 *   php artisan db:seed --class=VisiMisiSeeder --force
 *
 * Setelah dijalankan, teksnya tetap bisa disunting lewat
 * Dashboard > Tentang Kami.
 */
class VisiMisiSeeder extends Seeder
{
    public function run(): void
    {
        Setting::put(
            'about.vision',
            'Menjadi perusahaan penyedia solusi interior dan furnitur terpadu yang terpercaya di Indonesia, '
            .'dengan menghasilkan ruang yang inovatif, fungsional, dan bernilai estetika tinggi bagi setiap generasi.',
            'about',
        );

        // Format "Label : Penjelasan" — label menjadi judul poin di halaman depan.
        Setting::put('about.mission', implode("\n", [
            'Kualitas Prima : Menyediakan produk furnitur dan hasil renovasi interior dengan standar material terbaik.',
            'Inovasi Desain : Terus beradaptasi dengan tren desain global untuk memberikan konsep yang segar dan kreatif.',
            'Kepuasan Klien : Mengutamakan komunikasi yang transparan dan personalisasi desain demi mewujudkan ekspektasi klien.',
            'Ketepatan Waktu : Menjaga efisiensi kerja yang profesional agar setiap proyek selesai sesuai jadwal yang disepakati.',
        ]), 'about');

        $this->command?->info('Visi & Misi diperbarui.');
    }
}
