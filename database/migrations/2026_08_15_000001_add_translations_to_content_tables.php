<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan kolom terjemahan untuk konten yang dikelola dashboard.
 *
 * Isinya JSON berbentuk { "en": { "title": "...", "excerpt": "..." } }.
 * Bahasa Indonesia tetap disimpan di kolom aslinya sebagai bahasa utama,
 * sehingga data lama tidak perlu dipindahkan sama sekali.
 */
return new class extends Migration
{
    /** @var list<string> */
    private array $tables = ['sliders', 'services', 'projects'];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->json('translations')->nullable()->after('id');
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropColumn('translations');
            });
        }
    }
};
