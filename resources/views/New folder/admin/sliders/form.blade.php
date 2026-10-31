@extends('admin.layout')
@section('title', $slider->exists ? 'Edit Slider' : 'Add Slider')

@section('content')
<form action="{{ $slider->exists ? route('admin.sliders.update', $slider) : route('admin.sliders.store') }}"
      method="POST" enctype="multipart/form-data" x-data="{ mode: '{{ old('content_type', $slider->content_type) }}' }">
    @csrf
    @if($slider->exists) @method('PUT') @endif

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- LEFT: form fields --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Mode selector --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <label class="text-sm font-medium block mb-3">Content Type</label>
                <div class="flex gap-3">
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="content_type" value="image_only" x-model="mode" class="peer sr-only">
                        <div class="border rounded-lg p-4 peer-checked:border-blue-600 peer-checked:bg-blue-50">
                            <p class="font-semibold text-sm">Image Only</p>
                            <p class="text-xs text-gray-500 mt-1">Full banner image, no overlay text</p>
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="content_type" value="custom" x-model="mode" class="peer sr-only">
                        <div class="border rounded-lg p-4 peer-checked:border-blue-600 peer-checked:bg-blue-50">
                            <p class="font-semibold text-sm">Custom Design</p>
                            <p class="text-xs text-gray-500 mt-1">Image + text + buttons, editorial layout</p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Image only section --}}
            <div x-show="mode === 'image_only'" class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                <h3 class="font-bold">Banner Image</h3>
                @if($slider->image_url)
                    <img src="{{ $slider->image_url }}" class="w-full max-w-md rounded border">
                @endif
                <div>
                    <label class="text-sm font-medium">Desktop Image</label>
                    <input type="file" name="image_file" accept="image/*" class="w-full border rounded-lg px-3 py-2 mt-1 text-sm">
                    <input type="text" name="image" value="{{ old('image', $slider->image) }}" placeholder="Or paste URL" class="w-full border rounded-lg px-3 py-2 mt-2 text-xs">
                </div>
                <div>
                    <label class="text-sm font-medium">Mobile Image (optional)</label>
                    <input type="file" name="mobile_image_file" accept="image/*" class="w-full border rounded-lg px-3 py-2 mt-1 text-sm">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm font-medium">Click URL (optional)</label>
                        <input type="text" name="button_url" value="{{ old('button_url', $slider->button_url) }}" placeholder="/shop" class="w-full border rounded-lg px-3 py-2 mt-1 text-sm">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Background (fallback)</label>
                        <input type="text" name="background_color" value="{{ old('background_color', $slider->background_color) }}" placeholder="#F8DCE8" class="w-full border rounded-lg px-3 py-2 mt-1 text-sm">
                    </div>
                </div>
            </div>

            {{-- Custom design section --}}
            <div x-show="mode === 'custom'" class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                <h3 class="font-bold">Text Content</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Main Heading</label>
                        <input type="text" name="title" value="{{ old('title', $slider->title) }}" class="w-full border rounded-lg px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Subtitle</label>
                        <input type="text" name="subtitle" value="{{ old('subtitle', $slider->subtitle) }}" class="w-full border rounded-lg px-3 py-2 mt-1">
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium">Description</label>
                    <textarea name="description" rows="3" class="w-full border rounded-lg px-3 py-2 mt-1">{{ old('description', $slider->description) }}</textarea>
                </div>

                <h3 class="font-bold pt-2">Images</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Main Image</label>
                        @if($slider->image_url)
                            <img src="{{ $slider->image_url }}" class="w-32 h-20 object-cover rounded my-2">
                        @endif
                        <input type="file" name="image_file" accept="image/*" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Secondary Image</label>
                        @if($slider->secondary_image_url)
                            <img src="{{ $slider->secondary_image_url }}" class="w-32 h-20 object-cover rounded my-2">
                        @endif
                        <input type="file" name="secondary_image_file" accept="image/*" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>

                <h3 class="font-bold pt-2">Design</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Background Color</label>
                        <input type="text" name="background_color" value="{{ old('background_color', $slider->background_color) }}" placeholder="#F8DCE8" class="w-full border rounded-lg px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Gradient (optional)</label>
                        <input type="text" name="background_gradient" value="{{ old('background_gradient', $slider->background_gradient) }}" placeholder="linear-gradient(135deg,#F8DCE8,#FFF)" class="w-full border rounded-lg px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Text Color</label>
                        <input type="text" name="text_color" value="{{ old('text_color', $slider->text_color) }}" class="w-full border rounded-lg px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Text Alignment</label>
                        <select name="text_alignment" class="w-full border rounded-lg px-3 py-2 mt-1">
                            @foreach(['left','center','right'] as $a)
                                <option value="{{ $a }}" @selected(old('text_alignment', $slider->text_alignment) === $a)>{{ ucfirst($a) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Image Position</label>
                        <select name="image_position" class="w-full border rounded-lg px-3 py-2 mt-1">
                            <option value="right" @selected(old('image_position', $slider->image_position) === 'right')>Right</option>
                            <option value="left" @selected(old('image_position', $slider->image_position) === 'left')>Left</option>
                        </select>
                    </div>
                </div>

                <h3 class="font-bold pt-2">Buttons</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Primary Text</label>
                        <input type="text" name="button_text" value="{{ old('button_text', $slider->button_text) }}" placeholder="Shop collection" class="w-full border rounded-lg px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Primary URL</label>
                        <input type="text" name="button_url" value="{{ old('button_url', $slider->button_url) }}" placeholder="/shop" class="w-full border rounded-lg px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Secondary Text</label>
                        <input type="text" name="secondary_button_text" value="{{ old('secondary_button_text', $slider->secondary_button_text) }}" placeholder="View look" class="w-full border rounded-lg px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Secondary URL</label>
                        <input type="text" name="secondary_button_url" value="{{ old('secondary_button_url', $slider->secondary_button_url) }}" placeholder="/product/xyz" class="w-full border rounded-lg px-3 py-2 mt-1">
                    </div>
                </div>
            </div>

            {{-- Settings (always visible) --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-bold mb-4">Settings</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm font-medium">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $slider->sort_order ?? 0) }}" class="w-full border rounded-lg px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Slide Duration (ms)</label>
                        <input type="number" name="slide_duration" value="{{ old('slide_duration', $slider->slide_duration ?? 5500) }}" class="w-full border rounded-lg px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Linked Product</label>
                        <select name="product_id" class="w-full border rounded-lg px-3 py-2 mt-1">
                            <option value="">— none —</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" @selected(old('product_id', $slider->product_id) == $p->id)>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <label class="flex items-center gap-2 mt-4">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $slider->is_active ?? true))>
                    <span class="text-sm">Active (visible on homepage)</span>
                </label>
            </div>
        </div>

        {{-- RIGHT: live preview --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm p-4 sticky top-24">
                <p class="text-xs uppercase tracking-widest text-gray-500 mb-3">Preview</p>
                <div class="aspect-[4/5] rounded-lg overflow-hidden relative"
                     style="{{ $slider->background_style }}">
                    @if($slider->image_url && $slider->content_type === 'image_only')
                        <img src="{{ $slider->image_url }}" class="absolute inset-0 w-full h-full object-cover">
                    @endif
                    @if($slider->content_type === 'custom')
                        <div class="absolute inset-0 p-6 flex flex-col justify-center" style="color: {{ $slider->text_color ?? '#1A1A1A' }}; text-align: {{ $slider->text_alignment ?? 'left' }}">
                            <p class="text-xs opacity-70 mb-2">{{ $slider->subtitle }}</p>
                            <p class="font-serif text-3xl leading-tight mb-3">{{ $slider->title }}</p>
                            <p class="text-xs opacity-80 mb-4">{{ Str::limit($slider->description, 120) }}</p>
                            @if($slider->button_text)
                                <span class="inline-block bg-black text-white text-xs px-4 py-2 rounded-full self-start">{{ $slider->button_text }}</span>
                            @endif
                        </div>
                    @endif
                </div>
                <p class="text-xs text-gray-400 mt-2">Live preview updates after save</p>
            </div>
        </div>
    </div>

    <div class="mt-6 flex gap-3">
        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm">Save Slider</button>
        <a href="{{ route('admin.sliders.index') }}" class="bg-gray-200 px-6 py-2 rounded-lg text-sm">Cancel</a>
    </div>
</form>
@endsection