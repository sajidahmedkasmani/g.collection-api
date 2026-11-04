<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CatalogCollection extends Model
{
    protected $table = 'product_collections';

    protected $fillable = ['name', 'slug', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function categories(): HasMany
    {
        return $this->hasMany(ProductCategory::class, 'collection_id');
    }
}
