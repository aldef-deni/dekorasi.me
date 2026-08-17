<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();

            // Satu tabel dipakai bersama oleh Proyek dan Properti. Bila nanti
            // ada modul lain yang butuh video, cukup tambah relasi — tanpa
            // tabel baru.
            $table->morphs('videoable');

            $table->string('title')->nullable();

            // upload = berkas di folder uploads; youtube / vimeo = disematkan.
            $table->string('source', 20)->default('upload');
            $table->string('path')->nullable();        // untuk source "upload"
            $table->string('url')->nullable();         // tautan asli yang ditempel admin
            $table->string('video_id', 60)->nullable();// id hasil pembacaan tautan
            $table->string('poster')->nullable();      // gambar sampul video (opsional)

            $table->json('translations')->nullable();

            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
