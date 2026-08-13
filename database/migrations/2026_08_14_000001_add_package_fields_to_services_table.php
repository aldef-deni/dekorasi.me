<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Layanan berubah menjadi "Paket Layanan" bertingkat (Silver / Gold / Platinum).
 *
 * Tabel services diperluas — bukan dibuat tabel baru — agar CRUD, rute, dan
 * halaman detail yang sudah ada tetap terpakai.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Keterangan singkat di bawah nama paket, mis. "Design Only".
            $table->string('subtitle')->nullable()->after('title');

            // Daftar isi paket — satu poin per baris.
            $table->text('features')->nullable()->after('excerpt');

            // Teks bebas, mis. "Mulai Rp 350.000/m²" atau "Hubungi kami".
            $table->string('price')->nullable()->after('features');

            // Menandai paket yang disorot (ditampilkan lebih menonjol).
            $table->boolean('is_featured')->default(false)->index()->after('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'features', 'price', 'is_featured']);
        });
    }
};
