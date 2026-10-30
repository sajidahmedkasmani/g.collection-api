<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSlider extends Model
{
    protected $fillable = [
        'title', 'subtitle', 'description', 'content_type',
        'image', 'mobile_image', 'secondary_image',
        'background_color', 'background_gradient', 'text_color',
        'text_alignment', 'image_position',
        'button_text', 'button_url', 'secondary_button_text', 'secondary_button_url',
        'product_id', 'sort_order', 'slide_duration', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'slide_duration' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Image ka full URL
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) return null;
        return str_starts_with($this->image, 'http')
            ? $this->image
            : asset('uploads/' . $this->image);
    }

    public function getMobileImageUrlAttribute(): ?string
    {
        if (!$this->mobile_image) return null;
        return str_starts_with($this->mobile_image, 'http')
            ? $this->mobile_image
            : asset('uploads/' . $this->mobile_image);
    }

    public function getSecondaryImageUrlAttribute(): ?string
    {
        if (!$this->secondary_image) return null;
        return str_starts_with($this->secondary_image, 'http')
            ? $this->secondary_image
            : asset('uploads/' . $this->secondary_image);
    }

    // Background style (solid or gradient)
    public function getBackgroundStyleAttribute(): string
    {
        if ($this->background_gradient) {
            return 'background: ' . $this->background_gradient . ';';
        }
        if ($this->background_color) {
            return 'background-color: ' . $this->background_color . ';';
        }
        return 'background-color: #FAF7F2;';
    }

    // Scope: only active, sorted
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }
}