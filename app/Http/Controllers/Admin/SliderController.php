<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSlider;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = HomeSlider::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.sliders.form', [
            'slider' => new HomeSlider(['content_type' => 'custom', 'text_color' => '#1A1A1A', 'slide_duration' => 5500, 'is_active' => true]),
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->handleUpload($request, 'image_file', null);
        $data['mobile_image'] = $this->handleUpload($request, 'mobile_image_file', null);
        $data['secondary_image'] = $this->handleUpload($request, 'secondary_image_file', null);

        HomeSlider::create($data);
        return redirect()->route('admin.sliders.index')->with('success', 'Slider created.');
    }

    public function edit(HomeSlider $slider)
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.sliders.form', compact('slider', 'products'));
    }

    public function update(Request $request, HomeSlider $slider)
    {
        $data = $this->validated($request);
        $data['image'] = $this->handleUpload($request, 'image_file', $slider->image);
        $data['mobile_image'] = $this->handleUpload($request, 'mobile_image_file', $slider->mobile_image);
        $data['secondary_image'] = $this->handleUpload($request, 'secondary_image_file', $slider->secondary_image);

        $slider->update($data);
        return redirect()->route('admin.sliders.index')->with('success', 'Slider updated.');
    }

    public function destroy(HomeSlider $slider)
    {
        foreach (['image', 'mobile_image', 'secondary_image'] as $col) {
            if ($slider->$col && !str_starts_with($slider->$col, 'http')) {
                @unlink(public_path('uploads/' . $slider->$col));
            }
        }
        $slider->delete();
        return back()->with('success', 'Slider deleted.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer|exists:home_sliders,id']);
        foreach ($request->order as $i => $id) {
            HomeSlider::where('id', $id)->update(['sort_order' => $i]);
        }
        return response()->json(['ok' => true]);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'content_type' => 'required|in:image_only,custom',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'mobile_image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'secondary_image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'background_color' => 'nullable|string|max:20',
            'background_gradient' => 'nullable|string|max:255',
            'text_color' => 'nullable|string|max:20',
            'text_alignment' => 'nullable|in:left,center,right',
            'image_position' => 'nullable|in:left,right',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_url' => 'nullable|string|max:255',
            'product_id' => 'nullable|exists:products,id',
            'sort_order' => 'nullable|integer|min:0',
            'slide_duration' => 'nullable|integer|min:2000|max:20000',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['slide_duration'] = (int) ($data['slide_duration'] ?? 5500);

        // Remove file inputs from data (handled separately)
        foreach (['image_file', 'mobile_image_file', 'secondary_image_file'] as $k) unset($data[$k]);

        return $data;
    }

    private function handleUpload(Request $request, string $input, ?string $oldPath): ?string
    {
        if (!$request->hasFile($input)) {
            return $oldPath;
        }
        // Delete old file if local
        if ($oldPath && !str_starts_with($oldPath, 'http')) {
            @unlink(public_path('uploads/' . $oldPath));
        }
        $file = $request->file($input);
        $dir = public_path('uploads/sliders');
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $name = Str::random(12) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $name);
        return 'sliders/' . $name;
    }
}