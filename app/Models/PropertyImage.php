<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyImage extends Model
{
    protected $fillable = ['property_id', 'path', 'caption', 'sort_order'];

    protected $casts = ['sort_order' => 'integer'];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
