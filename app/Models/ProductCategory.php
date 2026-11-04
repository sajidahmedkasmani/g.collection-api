<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    protected $table = 'product_categories';

    protected $fillable = ['collection_id', 'name', 'slug', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(CatalogCollection::class, 'collection_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
