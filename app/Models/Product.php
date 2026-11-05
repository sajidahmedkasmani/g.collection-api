<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
    'name', 'slug', 'price', 'description', 'image',
    'stock', 'is_active', 'external_id', 'price_paisa',
    'image_url', 'category', 'category_id',
];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    // Frontend display ke liye helper
    // public function getDisplayImageAttribute(): ?string
    // {
    //     if ($this->image) {
    //         return str_starts_with($this->image, 'http')
    //             ? $this->image
    //             : asset('storage/' . $this->image);
    //     }
    //     return $this->image_url;
    // }
    public function getDisplayImageAttribute(): ?string
    {
        if ($this->image) {
            return str_starts_with($this->image, 'http')
                ? $this->image
                : asset('uploads/' . $this->image);
        }
        return $this->image_url;
    }

    public function getDisplayPriceAttribute(): float
    {
        if ($this->price > 0) {
            return (float) $this->price;
        }
        return $this->price_paisa / 100;
    }
}