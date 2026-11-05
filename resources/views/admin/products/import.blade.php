@extends('admin.admin_layout.master')
@section('title', 'Import Products')

@section('content')
@php
    $columns = [
        ['name',        true,  'Mint Green Floral Suit',                  'Product ka naam'],
        ['price',       true,  '2500',                                    'Sirf number, bina "Rs."'],
        ['description', false, '2-piece stitched suit',                   'Product ki detail'],
        ['image',       false, 'https://example.com/img.jpg',             'Image ka poora link'],
        ['stock',       false, '10',                                      'Khali chhodo = unlimited'],
        ['category',    false, 'stitched / unstitched / accessories',     'Inme se koi ek'],
        ['is_active',   false, '1 ya 0',                                  '1 = store pe dikhega'],
    ];
@endphp

<div class="max-w-4xl mx-auto space-y-5 md:space-y-6"
     x-data="{
         file: null, drag: false, busy: false,
         size(b) { return b > 1048576 ? (b / 1048576).toFixed(1) + ' MB' : Math.max(1, Math.round(b / 1024)) + ' KB'; },
         pick(f) { this.file = f || null; },
         drop(e) {
             const f = e.dataTransfer.files[0]; if (!f) return;
             this.$refs.csv.files = e.dataTransfer.files; this.pick(f); this.drag = false;
         },
         template() {
             const csv = 'name,price,description,image,stock,category,is_active\n'
                       + 'Mint Green Floral Suit,2500,2-piece stitched suit,https://example.com/img.jpg,10,stitched,1\n';
             const a = document.createElement('a');
             a.href = URL.createObjectURL(new Blob([csv], { type: 'text/csv' }));
             a.download = 'products-template.csv'; a.click(); URL.revokeObjectURL(a.href);
         }
     }">

    {{-- ============ INTRO ============ --}}
    <div class="rise relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-600 via-brand-500 to-brand-400 text-white p-6 md:p-8">
        <div class="absolute -right-8 -top-10 w-44 h-44 rounded-full bg-white/10"></div>
        <div class="absolute right-20 -bottom-14 w-32 h-32 rounded-full bg-bloom-400/30"></div>
        <div class="relative">
            <h2 class="text-2xl md:text-3xl font-semibold leading-tight">Import products from CSV</h2>
            <p class="text-sm text-white/80 mt-2 max-w-xl">Ek saath bohot saare products add karo. CSV file upload karo, system sirf neeche wale columns accept karega.</p>
            <button type="button" @click="template()" class="mt-5 bg-white text-brand-700 hover:bg-brand-50 text-xs font-medium rounded-xl px-5 py-2.5 transition inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 4v11m0 0l-4-4m4 4l4-4M5 20h14"/></svg>
                Download sample template
            </button>
        </div>
    </div>

    @if($errors->any())
        <div class="rise bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl px-4 py-3 text-sm">
            <ul class="list-disc list-inside text-xs space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- ============ UPLOAD ============ --}}
    <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data" @submit="busy = true"
          class="rise bg-white rounded-3xl soft p-5 md:p-7 space-y-5" style="--d:.06s">
        @csrf
        <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-full bg-brand-600 text-white text-xs font-semibold flex items-center justify-center">1</span>
            <h3 class="font-semibold">Upload your CSV file</h3>
        </div>

        <label class="block cursor-pointer rounded-3xl border-2 border-dashed px-5 py-10 text-center transition"
               :class="drag ? 'border-brand-500 bg-brand-50' : (file ? 'border-emerald-300 bg-emerald-50/50' : 'border-gray-200 hover:border-brand-300 hover:bg-brand-50/50')"
               @dragover.prevent="drag = true" @dragleave.prevent="drag = false" @drop.prevent="drop($event)">
            <input type="file" name="csv_file" accept=".csv,.txt" required x-ref="csv" @change="pick($event.target.files[0])" class="sr-only">

            <template x-if="!file">
                <span class="block">
                    <span class="mx-auto w-14 h-14 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-2xl mb-3">📄</span>
                    <span class="block text-sm font-medium">Click to choose, or drop your CSV here</span>
                    <span class="block text-xs text-gray-400 mt-1">.csv or .txt file</span>
                </span>
            </template>
            <template x-if="file">
                <span class="block">
                    <span class="mx-auto w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl mb-3">✓</span>
                    <span class="block text-sm font-medium break-all" x-text="file.name"></span>
                    <span class="block text-xs text-gray-400 mt-1" x-text="size(file.size) + ' · click to change'"></span>
                </span>
            </template>
        </label>
        @error('csv_file') <p class="text-rose-500 text-xs">{{ $message }}</p> @enderror

        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 pt-1">
            <a href="{{ route('admin.products.index') }}" class="text-center text-xs font-medium text-gray-500 hover:text-ink px-5 py-3 rounded-xl hover:bg-gray-100 transition">Cancel</a>
            <button :disabled="!file || busy" class="btn-bloom text-white text-sm font-medium rounded-xl px-8 py-3 hover:opacity-90 transition disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center justify-center gap-2">
                <svg x-show="busy" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3" opacity=".3"/><path d="M21 12a9 9 0 00-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
                <span x-text="busy ? 'Importing…' : 'Import products'">Import products</span>
            </button>
        </div>
    </form>

    {{-- ============ COLUMNS ============ --}}
    <div class="rise bg-white rounded-3xl soft p-5 md:p-7" style="--d:.12s">
        <div class="flex items-center gap-3 mb-1">
            <span class="w-8 h-8 rounded-full bg-brand-50 text-brand-600 text-xs font-semibold flex items-center justify-center">i</span>
            <h3 class="font-semibold">Expected columns</h3>
        </div>
        <p class="text-xs text-gray-400 mb-5 ml-11">Pehli row mein ye column names honay chahiye, bilkul isi tarah likhay hue.</p>

        {{-- mobile cards --}}
        <div class="md:hidden space-y-3">
            @foreach($columns as [$col, $req, $ex, $note])
                <div class="rounded-2xl border border-gray-100 p-4">
                    <div class="flex items-center justify-between mb-2">
                        <code class="text-xs bg-brand-50 text-brand-700 rounded-lg px-2.5 py-1 font-semibold">{{ $col }}</code>
                        <span class="text-[10px] font-medium rounded-full px-2.5 py-1 {{ $req ? 'bg-rose-50 text-rose-600' : 'bg-gray-100 text-gray-500' }}">{{ $req ? 'Zaroori' : 'Optional' }}</span>
                    </div>
                    <p class="text-xs text-gray-500">{{ $note }}</p>
                    <p class="text-xs mt-1.5 text-ink break-all"><span class="text-gray-400">e.g.</span> {{ $ex }}</p>
                </div>
            @endforeach
        </div>

        {{-- desktop table --}}
        <div class="hidden md:block overflow-hidden rounded-2xl border border-gray-100">
            <table class="w-full text-sm">
                <thead class="bg-brand-50/60 text-left text-[11px] uppercase tracking-wider text-gray-400">
                    <tr><th class="py-3 px-4 font-medium">Column</th><th class="font-medium">Zaroori?</th><th class="font-medium">Example</th><th class="font-medium">Note</th></tr>
                </thead>
                <tbody>
                    @foreach($columns as [$col, $req, $ex, $note])
                        <tr class="border-t border-gray-100">
                            <td class="py-3 px-4"><code class="text-xs bg-brand-50 text-brand-700 rounded-lg px-2.5 py-1 font-semibold">{{ $col }}</code></td>
                            <td><span class="text-[11px] font-medium rounded-full px-2.5 py-1 {{ $req ? 'bg-rose-50 text-rose-600' : 'bg-gray-100 text-gray-500' }}">{{ $req ? 'Haan' : 'Nahi' }}</span></td>
                            <td class="text-xs text-ink pr-3">{{ $ex }}</td>
                            <td class="text-xs text-gray-400 pr-4">{{ $note }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-5 grid sm:grid-cols-3 gap-3 text-xs">
            @foreach([
                ['💡', 'Pehli row = headers', 'Column names pehli line mein likho.'],
                ['🔤', 'UTF-8 encoding', 'Excel se "CSV UTF-8" ke taur par save karo.'],
                ['🧪', 'Pehle chhota test', '2-3 rows se try karo, phir poori file.'],
            ] as [$i, $t, $d])
                <div class="rounded-2xl bg-lav/60 px-4 py-3.5">
                    <p class="font-medium mb-0.5">{{ $i }} {{ $t }}</p>
                    <p class="text-gray-500 leading-relaxed">{{ $d }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection