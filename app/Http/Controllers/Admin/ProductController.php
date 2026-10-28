<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.form', ['product' => new Product()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']) . '-' . Str::lower(Str::random(4));

        if ($request->hasFile('image_file')) {
            $data['image'] = $request->file('image_file')->store('products', 'public');
        }

        Product::create($data);
        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image_file')) {
            if ($product->image && !str_starts_with($product->image, 'http')) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image_file')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && !str_starts_with($product->image, 'http')) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }

    // private function validated(Request $request): array
    // {
    //     $data = $request->validate([
    //         'name' => 'required|string|max:200',
    //         'price_paisa' => 'required|numeric|min:0',
    //         'description' => 'nullable|string',
    //         'image_url' => 'nullable|string',
    //         'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
    //         'stock' => 'nullable|integer|min:0',
    //         'is_active' => 'nullable|boolean',
    //     ]);

    //     $data['is_active'] = $request->boolean('is_active');
    //     unset($data['image_file']);
    //     return $data;
    // }

    private function validated(Request $request): array
{
    $data = $request->validate([
        'name' => 'required|string|max:200',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'image' => 'nullable|string',
        'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        'stock' => 'nullable|integer|min:0',
        'category' => 'nullable|string|max:40',
        'is_active' => 'nullable|boolean',
    ]);

    $data['is_active'] = $request->boolean('is_active');
    $data['price_paisa'] = (int) round($request->price * 100);   // <-- YEH LINE ADD KARO

    unset($data['image_file']);
    return $data;
}
}