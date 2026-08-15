<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasTranslations;

    /** Kolom yang punya versi terjemahan. */
    protected array $translatable = ['title', 'subtitle', 'description', 'cta_label'];

    protected $fillable = [
        'translations',
        'title', 'subtitle', 'description', 'image',
        'cta_label', 'cta_url', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'translations' => 'array',
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
