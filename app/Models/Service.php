<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Service extends Model
{
    use HasTranslations;

    /** Kolom yang punya versi terjemahan. */
    protected array $translatable = ['title', 'subtitle', 'excerpt', 'features', 'description', 'price'];

    protected $fillable = [
        'translations',
        'title', 'subtitle', 'slug', 'icon', 'excerpt', 'features', 'price',
        'description', 'image', 'sort_order', 'is_featured', 'is_active',
    ];

    protected $casts = [
        'translations' => 'array',
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
        'sort_order'  => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Isi paket sebagai daftar — disimpan satu poin per baris.
     *
     * @return Collection<int, string>
     */
    public function featureList(): Collection
    {
        return collect(preg_split('/\R+/', (string) $this->t('features')))
            ->map(fn (string $baris) => trim(ltrim($baris, "-•* \t")))
            ->filter()
            ->values();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
