<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Project extends Model
{
    use HasTranslations;

    /** Kolom yang punya versi terjemahan. */
    protected array $translatable = ['title', 'category', 'excerpt', 'description', 'area'];

    protected $fillable = [
        'translations',
        'title', 'slug', 'category', 'client', 'location', 'area', 'year',
        'excerpt', 'description', 'cover_image',
        'sort_order', 'is_featured', 'is_active',
    ];

    protected $casts = [
        'translations' => 'array',
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
        'sort_order'  => 'integer',
        'year'        => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order')->orderBy('id');
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
}
