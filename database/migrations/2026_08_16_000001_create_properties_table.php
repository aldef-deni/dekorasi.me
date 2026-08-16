<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('type')->nullable()->index();          // Rumah, Apartemen, Ruko, Tanah, dll.
            $table->string('listing_status')->default('dijual')->index(); // dijual | disewakan | terjual | tersewa

            // Harga disimpan sebagai angka supaya bisa diurutkan dan disaring.
            // Format tampilannya dibentuk saat render, bukan disimpan.
            $table->decimal('price', 15, 2)->nullable()->index();
            $table->string('price_note', 60)->nullable();         // mis. "/ bulan", "Nego"

            $table->string('location')->nullable()->index();      // kota / kawasan, dipakai untuk filter
            $table->string('address', 300)->nullable();

            $table->unsignedInteger('land_area')->nullable();     // luas tanah (m²)
            $table->unsignedInteger('building_area')->nullable(); // luas bangunan (m²)
            $table->unsignedTinyInteger('bedrooms')->nullable();
            $table->unsignedTinyInteger('bathrooms')->nullable();
            $table->unsignedTinyInteger('carports')->nullable();
            $table->unsignedTinyInteger('floors')->nullable();
            $table->string('certificate', 50)->nullable();        // SHM, HGB, PPJB, dll.
            $table->year('year_built')->nullable();

            $table->string('excerpt', 500)->nullable();
            $table->longText('description')->nullable();
            $table->string('cover_image')->nullable();

            $table->json('translations')->nullable();

            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
