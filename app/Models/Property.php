<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Property extends Model
{
    use HasTranslations;

    /**
     * Status penawaran yang tersedia.
     *
     * Nilainya sengaja tetap bahasa Indonesia karena tersimpan di database
     * dan dipakai untuk menyaring; labelnya diambil dari berkas bahasa
     * (site.properties.status.*) sehingga ikut berganti saat bahasa diubah.
     */
    public const STATUSES = ['dijual', 'disewakan', 'terjual', 'tersewa'];

    /** Status yang berarti properti sudah tidak tersedia lagi. */
    public const STATUS_SELESAI = ['terjual', 'tersewa'];

    /** Kolom yang punya versi terjemahan. */
    protected array $translatable = ['title', 'type', 'location', 'certificate', 'price_note', 'excerpt', 'description'];

    protected $fillable = [
        'translations',
        'title', 'slug', 'type', 'listing_status',
        'price', 'price_note',
        'location', 'address',
        'land_area', 'building_area', 'bedrooms', 'bathrooms', 'carports', 'floors',
        'certificate', 'year_built',
        'excerpt', 'description', 'cover_image',
        'sort_order', 'is_featured', 'is_active',
    ];

    protected $casts = [
        'translations'  => 'array',
        'price'         => 'decimal:2',
        'land_area'     => 'integer',
        'building_area' => 'integer',
        'bedrooms'      => 'integer',
        'bathrooms'     => 'integer',
        'carports'      => 'integer',
        'floors'        => 'integer',
        'year_built'    => 'integer',
        'sort_order'    => 'integer',
        'is_featured'   => 'boolean',
        'is_active'     => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function videos(): MorphMany
    {
        return $this->morphMany(Video::class, 'videoable')->orderBy('sort_order')->orderBy('id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('id');
    }

    /** Properti yang masih ditawarkan (belum terjual / tersewa). */
    public function scopeTersedia(Builder $query): Builder
    {
        return $query->whereNotIn('listing_status', self::STATUS_SELESAI);
    }

    /** Label status sesuai bahasa aktif. */
    public function labelStatus(): string
    {
        return __('site.properties.status.'.$this->listing_status);
    }

    public function sudahTerjual(): bool
    {
        return in_array($this->listing_status, self::STATUS_SELESAI, true);
    }

    /**
     * Harga siap tampil, mis. "Rp 1.500.000.000 / bulan".
     * Bila harga belum diisi, ditampilkan sebagai "Harga atas permintaan".
     */
    public function hargaTampil(): string
    {
        if ($this->price === null) {
            return __('site.properties.price_on_request');
        }

        return trim(format_rupiah($this->price).' '.(string) $this->t('price_note'));
    }

    /** Versi ringkas untuk kartu, mis. "Rp 1,5 M". */
    public function hargaRingkas(): string
    {
        if ($this->price === null) {
            return __('site.properties.price_on_request');
        }

        return trim(format_rupiah_ringkas($this->price).' '.(string) $this->t('price_note'));
    }

    /**
     * Ringkasan spesifikasi untuk kartu: kamar tidur, kamar mandi, luas.
     *
     * @return list<array{ikon: string, nilai: string, label: string}>
     */
    public function ringkasanSpek(): array
    {
        $spek = [];

        if ($this->bedrooms) {
            $spek[] = ['ikon' => 'bed', 'nilai' => (string) $this->bedrooms, 'label' => __('site.properties.bedrooms')];
        }

        if ($this->bathrooms) {
            $spek[] = ['ikon' => 'bath', 'nilai' => (string) $this->bathrooms, 'label' => __('site.properties.bathrooms')];
        }

        if ($this->building_area) {
            $spek[] = ['ikon' => 'area', 'nilai' => $this->building_area.' m²', 'label' => __('site.properties.building_area')];
        } elseif ($this->land_area) {
            $spek[] = ['ikon' => 'area', 'nilai' => $this->land_area.' m²', 'label' => __('site.properties.land_area')];
        }

        return $spek;
    }
}
