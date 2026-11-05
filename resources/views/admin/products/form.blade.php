@extends('admin.admin_layout.master')
@section('title', $product->exists ? 'Edit Product' : 'Add Product')

@section('content')
@php
    $currentImage = $product->image
        ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image))
        : '';
    $cats = ['' => 'None', 'stitched' => 'Stitched', 'unstitched' => 'Unstitched', 'accessories' => 'Accessories', 'other' => 'Other'];
    $selectedCat = (string) old('category', $product->category);
@endphp

<form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
      method="POST" enctype="multipart/form-data"
      x-data="{
          name: @js(old('name', $product->name ?? '')),
          price: @js(old('price', $product->price ?? '')),
          preview: @js($currentImage),
          fileName: '',
          drag: false,
          pickFile(e) {
              const f = e.target.files && e.target.files[0];
              if (!f) return;
              this.fileName = f.name;
              this.preview = URL.createObjectURL(f);
          },
          drop(e) {
              if (!e.dataTransfer.files.length) return;
              this.$refs.file.files = e.dataTransfer.files;
              this.pickFile({ target: this.$refs.file });
              this.drag = false;
          },
          pickUrl(e) {
              const v = e.target.value.trim();
              if (/^https?:\/\//i.test(v)) this.preview = v;
          }
      }">
    @csrf
    @if($product->exists) @method('PUT') @endif

    @if($errors->any())
        <div class="rise bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl px-4 py-3 text-sm mb-5">
            <p class="font-medium mb-1">Please fix the following:</p>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-5 md:gap-6 pb-24">

        {{-- ============ LEFT: DETAILS ============ --}}
        <div class="lg:col-span-2 space-y-5 md:space-y-6">

            <div class="rise bg-white rounded-3xl soft p-5 md:p-7 space-y-6">
                <div>
                    <h2 class="font-semibold">Basic details</h2>
                    <p class="text-xs text-gray-400 mt-0.5">What customers will see on the store.</p>
                </div>

                <label class="block">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Product name <span class="text-rose-500">*</span></span>
                    <input type="text" name="name" x-model="name" required placeholder="e.g. Mint Green Floral Lawn Suit"
                           class="mt-2 w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-brand-500 focus:ring-4 ring-brand-100 transition @error('name') border-rose-300 @enderror">
                    @error('name') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </label>

                <div class="grid sm:grid-cols-2 gap-5">
                    <label class="block">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Price <span class="text-rose-500">*</span></span>
                        <div class="mt-2 flex items-center border border-gray-200 rounded-2xl focus-within:border-brand-500 focus-within:ring-4 ring-brand-100 transition @error('price') border-rose-300 @enderror">
                            <span class="pl-4 text-sm text-gray-400">Rs.</span>
                            <input type="number" step="0.01" name="price" x-model="price" required placeholder="2500"
                                   class="flex-1 min-w-0 bg-transparent px-3 py-3 text-sm focus:outline-none">
                        </div>
                        @error('price') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </label>
                    <label class="block">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Stock</span>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" placeholder="Leave empty for unlimited"
                               class="mt-2 w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-brand-500 focus:ring-4 ring-brand-100 transition @error('stock') border-rose-300 @enderror">
                        @error('stock') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </label>
                </div>

                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Category</span>
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach($cats as $val => $label)
                            <label class="cursor-pointer">
                                <input type="radio" name="category" value="{{ $val }}" class="peer sr-only" @checked($selectedCat === (string) $val)>
                                <span class="inline-block text-xs rounded-full border border-gray-200 px-4 py-2.5 transition hover:border-brand-200
                                             peer-checked:bg-brand-600 peer-checked:text-white peer-checked:border-brand-600 peer-checked:shadow-md peer-checked:shadow-brand-600/25">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('category') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>

                <label class="block" x-data="{ n: @js(strlen((string) old('description', $product->description ?? ''))) }">
                    <span class="flex items-center justify-between">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Description</span>
                        <span class="text-[11px] text-gray-400" x-text="n + ' characters'"></span>
                    </span>
                    <textarea name="description" rows="6" @input="n = $event.target.value.length" placeholder="Fabric, work, what's included, care tips..."
                              class="mt-2 w-full border border-gray-200 rounded-2xl px-4 py-3 text-sm leading-relaxed focus:outline-none focus:border-brand-500 focus:ring-4 ring-brand-100 transition">{{ old('description', $product->description) }}</textarea>
                    @error('description') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </label>
            </div>
        </div>

        {{-- ============ RIGHT: IMAGE + STATUS + PREVIEW ============ --}}
        <div class="space-y-5 md:space-y-6">

            <div class="rise bg-white rounded-3xl soft p-5 md:p-6 space-y-4" style="--d:.08s">
                <h2 class="font-semibold">Product image</h2>

                {{-- live preview card --}}
                <div class="rounded-2xl bg-lav/60 p-3">
                    <div class="aspect-[3/4] rounded-xl overflow-hidden bg-blush relative">
                        <template x-if="preview">
                            <img :src="preview" alt="" class="w-full h-full object-cover" onerror="this.style.opacity=.2">
                        </template>
                        <div x-show="!preview" class="absolute inset-0 flex flex-col items-center justify-center text-plum/50 text-xs">
                            <span class="font-serif italic text-5xl">G.</span>
                            <span class="mt-1">No image yet</span>
                        </div>
                    </div>
                    <div class="px-1 pt-3">
                        <p class="text-sm font-medium truncate" x-text="name || 'Product name'">Product name</p>
                        <p class="text-xs text-gray-500" x-text="price ? 'Rs. ' + Number(price).toLocaleString() : 'Rs. 0'">Rs. 0</p>
                    </div>
                </div>

                {{-- upload --}}
                <label class="block cursor-pointer rounded-2xl border-2 border-dashed px-4 py-5 text-center transition"
                       :class="drag ? 'border-brand-500 bg-brand-50' : 'border-gray-200 hover:border-brand-300 hover:bg-brand-50/50'"
                       @dragover.prevent="drag = true" @dragleave.prevent="drag = false" @drop.prevent="drop($event)">
                    <input type="file" name="image_file" accept="image/*" x-ref="file" @change="pickFile($event)" class="sr-only">
                    <span class="block text-2xl mb-1">📷</span>
                    <span class="block text-sm font-medium" x-text="fileName || 'Click or drop an image'">Click or drop an image</span>
                    <span class="block text-[11px] text-gray-400 mt-1">JPG, PNG or WEBP</span>
                </label>
                @error('image_file') <p class="text-rose-500 text-xs">{{ $message }}</p> @enderror

                <div class="flex items-center gap-3 text-[11px] text-gray-400"><span class="flex-1 h-px bg-gray-100"></span>or paste a link<span class="flex-1 h-px bg-gray-100"></span></div>

                <div>
                    <input type="text" name="image" value="{{ old('image', $product->image) }}" @input="pickUrl($event)" placeholder="https://example.com/image.jpg"
                           class="w-full border border-gray-200 rounded-2xl px-4 py-3 text-xs focus:outline-none focus:border-brand-500 focus:ring-4 ring-brand-100 transition">
                    @error('image') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="rise bg-white rounded-3xl soft p-5 md:p-6" style="--d:.14s">
                <label class="flex items-center justify-between gap-4 cursor-pointer">
                    <span>
                        <span class="block text-sm font-semibold">Active</span>
                        <span class="block text-xs text-gray-400 mt-0.5">Visible on the store</span>
                    </span>
                    <span class="relative shrink-0">
                        <input type="checkbox" name="is_active" value="1" class="peer sr-only" @checked(old('is_active', $product->is_active ?? true))>
                        <span class="block w-12 h-7 rounded-full bg-gray-300 peer-checked:bg-emerald-500 transition-colors"></span>
                        <span class="absolute top-1 left-1 w-5 h-5 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></span>
                    </span>
                </label>
            </div>
        </div>
    </div>

    {{-- ============ SAVE BAR ============ --}}
    <div class="sticky bottom-[5.5rem] md:bottom-4 z-20">
        <div class="bg-white/95 backdrop-blur rounded-2xl soft border border-white px-4 py-3 flex items-center justify-between gap-3">
            <a href="{{ route('admin.products.index') }}" class="text-xs font-medium text-gray-500 hover:text-ink px-4 py-2.5 rounded-xl hover:bg-gray-100 transition">Cancel</a>
            <button class="btn-bloom text-white text-sm font-medium rounded-xl px-8 py-3 hover:opacity-90 active:scale-[.98] transition">
                {{ $product->exists ? 'Save changes' : 'Save product' }}
            </button>
        </div>
    </div>
</form>
@endsection