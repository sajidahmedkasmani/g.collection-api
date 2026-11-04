<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatalogCollection;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CatalogController extends Controller
{
    public function index()
    {
        $collections = CatalogCollection::with('categories')->orderBy('name')->get();

        return view('admin.catalog.index', compact('collections'));
    }

    public function storeCollection(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);
        $slug = Str::slug($data['name']);
        $baseSlug = $slug;
        $suffix = 2;
        while (CatalogCollection::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $suffix++;
        }

        CatalogCollection::create(['name' => $data['name'], 'slug' => $slug]);

        return back()->with('success', 'Collection created.');
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'collection_id' => ['required', 'integer', Rule::exists('product_collections', 'id')],
            'name' => ['required', 'string', 'max:40'],
        ]);
        $slug = Str::slug($data['name']);
        $baseSlug = $slug;
        $suffix = 2;
        while (ProductCategory::where('collection_id', $data['collection_id'])->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $suffix++;
        }

        ProductCategory::create([
            'collection_id' => $data['collection_id'],
            'name' => $data['name'],
            'slug' => $slug,
        ]);

        return back()->with('success', 'Category created.');
    }

    public function toggleCollection(CatalogCollection $collection)
    {
        $collection->update(['is_active' => !$collection->is_active]);

        return back()->with('success', 'Collection status updated.');
    }

    public function toggleCategory(ProductCategory $category)
    {
        $category->update(['is_active' => !$category->is_active]);

        return back()->with('success', 'Category status updated.');
    }
}
