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
        // $query = Product::query();

        // if ($request->filled('search')) {
        //     $query->where('name', 'like', '%' . $request->search . '%');
        // }
        // if ($request->filled('status')) {
        //     $query->where('is_active', $request->status === 'active');
        // }

        // $products = $query->latest()->paginate(15)->withQueryString();

        $query = Product::query();

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
            // agar category sirf ek column hai: $query->where('category', $request->category);
        }
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }
        match ($request->get('sort', 'newest')) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            default      => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();

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
            // $data['image'] = $request->file('image_file')->store('products', 'public');
            $data['image'] = $request->file('image_file')->store('products', 'uploads');
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
                // Storage::disk('public')->delete($product->image);
                Storage::disk('uploads')->delete($product->image);

            }
            // $data['image'] = $request->file('image_file')->store('products', 'public');
            $data['image'] = $request->file('image_file')->store('products', 'uploads');
        }

        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && !str_starts_with($product->image, 'http')) {
            // Storage::disk('public')->delete($product->image);
            Storage::disk('uploads')->delete($product->image);
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

    public function importForm()
    {
        return view('admin.products.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:20480',
        ]);

        $handle = fopen($request->file('csv_file')->getRealPath(), 'r');

        // BOM hatao
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") rewind($handle);

        $header = fgetcsv($handle, 0, ',', '"', '\\');
        if (!$header) {
            fclose($handle);
            return back()->with('error', 'CSV khaali hai ya header row nahi mili.');
        }

        $header = array_map(fn($h) => strtolower(trim($h)), $header);

        // Zaroori columns check karo
        $required = ['name', 'price'];
        $missing = array_diff($required, $header);
        if (!empty($missing)) {
            fclose($handle);
            return back()->with('error', 'CSV mein yeh columns missing hain: ' . implode(', ', $missing) . '. Expected columns: name, price, description, image, stock, category, is_active');
        }

        $imported = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        while (($row = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
            // Row ko header ke size ke barabar karo
            $row = array_slice($row, 0, count($header));
            $row = array_pad($row, count($header), '');
            $data = array_combine($header, $row);

            $name = trim($data['name'] ?? '');
            if ($name === '') { $skipped++; continue; }

            $price = (float) preg_replace('/[^0-9.]/', '', $data['price'] ?? '0');
            $description = trim($data['description'] ?? '');
            $image = trim($data['image'] ?? '');
            $stock = trim($data['stock'] ?? '');
            $category = strtolower(trim($data['category'] ?? 'other')) ?: 'other';
            $isActive = isset($data['is_active']) ? (bool)(int)$data['is_active'] : true;

            // Category validate
            if (!in_array($category, ['stitched', 'unstitched', 'accessories', 'other'])) {
                $category = 'other';
            }

            // Slug unique
            $slug = Str::slug($name);
            $baseSlug = $slug;
            $i = 1;
            while (Product::where('slug', $slug)->where('name', '!=', $name)->exists()) {
                $slug = $baseSlug . '-' . $i++;
            }

            try {
                $existing = Product::where('name', $name)->first();

                $payload = [
                    'name'        => $name,
                    'slug'        => $existing ? $existing->slug : $slug,
                    'price'       => $price,
                    'price_paisa' => (int) round($price * 100),
                    'description' => $description,
                    'image'       => $image ?: null,
                    'image_url'   => $image ?: null,
                    'stock'       => $stock !== '' ? (int) $stock : null,
                    'category'    => $category,
                    'is_active'   => $isActive,
                ];

                if ($existing) {
                    $existing->update($payload);
                    $updated++;
                } else {
                    Product::create($payload);
                    $imported++;
                }
            } catch (\Throwable $e) {
                $errors[] = $name . ': ' . $e->getMessage();
                $skipped++;
            }
        }

        fclose($handle);

        $msg = "Import done! Naye: {$imported}, Update: {$updated}, Skip: {$skipped}.";
        if (count($errors)) {
            $msg .= ' Errors: ' . implode(' | ', array_slice($errors, 0, 3));
        }

        return redirect()->route('admin.products.index')->with('success', $msg);
    }

    

    /**
    * Bulk delete selected products.
    */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['integer', 'exists:products,id'],
        ]);

        $count = Product::whereIn('id', $request->ids)->delete();

        return back()->with('success', "{$count} product(s) deleted successfully.");
    }

    /**
    * Bulk update status (active/inactive) for selected products.
    */
    public function bulkStatus(Request $request)
    {
        $request->validate([
            'ids'    => ['required', 'array'],
            'ids.*'  => ['integer', 'exists:products,id'],
            'status' => ['required', 'boolean'],
        ]);

        $count = Product::whereIn('id', $request->ids)
            ->update(['is_active' => (bool) $request->status]);

        $label = $request->status ? 'activated' : 'deactivated';

        return back()->with('success', "{$count} product(s) {$label} successfully.");
    }



}