@extends('admin.admin_layout.master')
@section('title', 'Products')

@section('content')
@php
    if (method_exists($products, 'withQueryString')) { $products = $products->withQueryString(); }
    $search = trim((string) request('search'));
    $status = request('status');
    $statusUrl = fn($s) => route('admin.products.index', array_filter(['search' => $search, 'status' => $s]));
    $grid = 'grid-cols-[auto_minmax(0,1fr)] md:grid-cols-[28px_minmax(0,2.6fr)_110px_110px_150px_84px]';
@endphp

<div x-data="{
    selected: [],
    allIds: [{{ $products->pluck('id')->join(',') }}],
    get allChecked() { return this.allIds.length > 0 && this.selected.length === this.allIds.length; },
    toggleAll() { this.selected = this.allChecked ? [] : [...this.allIds]; }
}" class="space-y-5">

    {{-- ============ TOOLBAR ============ --}}
    <div class="rise bg-white rounded-3xl soft p-4 md:p-5 space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center gap-3 lg:gap-4">
            <form method="GET" action="{{ route('admin.products.index') }}" class="flex-1 flex items-center gap-2 bg-lav/70 rounded-2xl pl-4 pr-1.5 py-1.5 focus-within:ring-4 ring-brand-100 transition">
                @if($status !== null && $status !== '')<input type="hidden" name="status" value="{{ $status }}">@endif
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5" stroke-linecap="round"/></svg>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search products by name..."
                       class="flex-1 min-w-0 bg-transparent text-sm py-2.5 focus:outline-none placeholder:text-gray-400">
                @if($search)
                    <a href="{{ route('admin.products.index', array_filter(['status' => $status])) }}" class="text-xs text-gray-400 hover:text-gray-600 px-2" title="Clear search">✕</a>
                @endif
                <button class="bg-brand-600 hover:bg-brand-700 text-white text-xs font-medium rounded-xl px-5 py-2.5 transition">Search</button>
            </form>

            <div class="flex items-center gap-2 shrink-0">
                @can('products.create')
                    <a href="{{ route('admin.products.import.form') }}" class="flex-1 lg:flex-none text-center border border-brand-200 text-brand-600 hover:bg-brand-50 text-xs font-medium rounded-xl px-4 py-3 transition">Import CSV</a>
                    <a href="{{ route('admin.products.create') }}" class="btn-bloom flex-1 lg:flex-none text-center text-white text-xs font-medium rounded-xl px-5 py-3 hover:opacity-90 transition">+ Add Product</a>
                @endcan
            </div>
        </div>

        <div class="flex items-center justify-between gap-3 flex-wrap">
            <div class="inline-flex bg-lav/70 rounded-2xl p-1 text-xs">
                @foreach([null => 'All', 'active' => 'Active', 'inactive' => 'Inactive'] as $val => $label)
                    @php $on = (string) $status === (string) $val; @endphp
                    <a href="{{ $statusUrl($val) }}" class="px-4 py-2 rounded-xl transition {{ $on ? 'bg-white text-brand-600 font-medium shadow-sm' : 'text-gray-500 hover:text-ink' }}">{{ $label }}</a>
                @endforeach
            </div>
            <p class="text-xs text-gray-400">
                {{ method_exists($products, 'total') ? $products->total() : $products->count() }} products
                @if($search) · matching “{{ $search }}” @endif
            </p>
        </div>
    </div>

    {{-- ============ BULK ACTIONS ============ --}}
    @canany(['products.bulk-status', 'products.bulk-delete'])
    <div x-cloak x-show="selected.length > 0" x-transition
         class="fixed bottom-24 inset-x-4 z-40 md:static md:inset-auto md:bottom-auto bg-ink text-white rounded-2xl shadow-2xl md:shadow-none px-4 py-3 flex items-center gap-2 flex-wrap">
        <span class="text-sm mr-1"><span class="font-semibold" x-text="selected.length"></span> selected</span>

        @can('products.bulk-status')
        <form method="POST" action="{{ route('admin.products.bulk-status') }}" class="inline">
            @csrf
            <template x-for="id in selected" :key="'s1'+id"><input type="hidden" name="ids[]" :value="id"></template>
            <input type="hidden" name="status" value="1">
            <button class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-medium rounded-xl px-4 py-2 transition">Activate</button>
        </form>
        <form method="POST" action="{{ route('admin.products.bulk-status') }}" class="inline">
            @csrf
            <template x-for="id in selected" :key="'s0'+id"><input type="hidden" name="ids[]" :value="id"></template>
            <input type="hidden" name="status" value="0">
            <button class="bg-white/15 hover:bg-white/25 text-white text-xs font-medium rounded-xl px-4 py-2 transition">Deactivate</button>
        </form>
        @endcan

        @can('products.bulk-delete')
        <form method="POST" action="{{ route('admin.products.bulk-delete') }}" class="inline"
              onsubmit="return confirm('Delete selected products? This cannot be undone.')">
            @csrf @method('DELETE')
            <template x-for="id in selected" :key="'d'+id"><input type="hidden" name="ids[]" :value="id"></template>
            <button class="bg-rose-500 hover:bg-rose-600 text-white text-xs font-medium rounded-xl px-4 py-2 transition">Delete</button>
        </form>
        @endcan

        <button type="button" @click="selected = []" class="text-xs text-white/60 hover:text-white ml-auto">Clear</button>
    </div>
    @endcanany

    {{-- ============ LIST ============ --}}
    <div class="rise bg-white rounded-3xl soft overflow-hidden" style="--d:.1s">

        {{-- header --}}
        <div class="grid {{ $grid }} gap-x-3 items-center px-4 md:px-5 py-3 bg-brand-50/60 text-[11px] uppercase tracking-wider text-gray-400">
            <input type="checkbox" :checked="allChecked" @change="toggleAll()" class="w-4 h-4 rounded border-gray-300 accent-brand-600" aria-label="Select all">
            <span class="md:hidden normal-case tracking-normal text-xs">Select all on this page</span>
            <span class="hidden md:block">Product</span>
            <span class="hidden md:block">Price</span>
            <span class="hidden md:block">Stock</span>
            <span class="hidden md:block">Status</span>
            <span class="hidden md:block text-right">Actions</span>
        </div>

        @forelse($products as $p)
            @php
                $src = $p->image
                    ? (str_starts_with($p->image, 'http') ? $p->image : asset('storage/' . $p->image))
                    : null;
                $stock = $p->stock;
            @endphp
            <div class="grid {{ $grid }} gap-x-3 gap-y-3 items-center px-4 md:px-5 py-4 border-t border-gray-100 hover:bg-brand-50/30 transition">

                {{-- checkbox --}}
                <div class="self-start md:self-center pt-1 md:pt-0">
                    @canany(['products.bulk-status', 'products.bulk-delete'])
                        <input type="checkbox" value="{{ $p->id }}" x-model.number="selected" class="w-4 h-4 rounded border-gray-300 accent-brand-600" aria-label="Select {{ $p->name }}">
                    @endcanany
                </div>

                {{-- product --}}
                <div class="flex items-center gap-3 min-w-0">
                    @if($src)
                        <img src="{{ $src }}" alt="" loading="lazy" class="w-14 h-[68px] md:w-12 md:h-14 object-cover rounded-xl bg-brand-50 shrink-0">
                    @else
                        <span class="w-14 h-[68px] md:w-12 md:h-14 rounded-xl bg-brand-50 text-brand-400 flex items-center justify-center text-xs shrink-0">—</span>
                    @endif
                    <div class="min-w-0">
                        <p class="font-medium text-sm leading-snug line-clamp-2">{{ $p->name }}</p>
                        <p class="text-[11px] text-gray-400 truncate">#{{ $p->id }}@if($p->slug) · {{ $p->slug }}@endif</p>
                    </div>
                </div>

                {{-- on mobile these 4 sit in one row; on desktop they become grid cells --}}
                <div class="col-span-2 md:col-span-1 flex items-center justify-between gap-3 pt-3 border-t border-dashed border-gray-200 md:pt-0 md:border-0 md:contents">

                    {{-- price --}}
                    <div class="md:order-none">
                        <p class="text-[10px] uppercase tracking-wider text-gray-400 md:hidden">Price</p>
                        <p class="text-sm font-semibold whitespace-nowrap">Rs. {{ number_format($p->price) }}</p>
                    </div>

                    {{-- stock --}}
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-gray-400 md:hidden">Stock</p>
                        @if($stock === null)
                            <span class="text-sm text-gray-500">∞</span>
                        @elseif($stock <= 0)
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-medium bg-rose-50 text-rose-600">Out</span>
                        @elseif($stock <= 5)
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-medium bg-amber-50 text-amber-600">{{ $stock }} left</span>
                        @else
                            <span class="text-sm">{{ $stock }}</span>
                        @endif
                    </div>

                    {{-- status toggle --}}
                    <div>
                        @can('products.bulk-status')
                            <div x-data="statusToggle(@js((bool) $p->is_active), @js(route('admin.products.bulk-status')), {{ $p->id }})" class="flex items-center gap-2">
                                <button type="button" role="switch" :aria-checked="on" @click="flip()" :disabled="busy"
                                        :class="on ? 'bg-emerald-500' : 'bg-gray-300'"
                                        class="relative w-11 h-6 rounded-full transition-colors duration-200 disabled:opacity-60 shrink-0"
                                        :aria-label="on ? 'Deactivate product' : 'Activate product'">
                                    <span :class="on ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-5 h-5 rounded-full bg-white shadow transition-transform duration-200"></span>
                                </button>
                                <span class="text-[11px] w-12" :class="on ? 'text-emerald-600' : 'text-gray-400'" x-text="on ? 'Active' : 'Inactive'">{{ $p->is_active ? 'Active' : 'Inactive' }}</span>
                            </div>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-medium {{ $p->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-500' }}">{{ $p->is_active ? 'Active' : 'Inactive' }}</span>
                        @endcan
                    </div>

                    {{-- actions --}}
                    <div class="flex items-center justify-end gap-1.5">
                        @can('products.edit')
                            <a href="{{ route('admin.products.edit', $p) }}" title="Edit" aria-label="Edit {{ $p->name }}"
                               class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 hover:bg-brand-600 hover:text-white flex items-center justify-center transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 20h4L19 9a2.800 2.800 0 00-4-4L4 16v4z"/><path d="M13.500 6.500l4 4"/></svg>
                            </a>
                        @endcan
                        @can('products.delete')
                            <form action="{{ route('admin.products.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button title="Delete" aria-label="Delete {{ $p->name }}" class="w-9 h-9 rounded-xl bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white flex items-center justify-center transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 7h16M10 11v6M14 11v6M6 7l1 12a1 1 0 001 1h8a1 1 0 001-1l1-12M9 7V4h6v3"/></svg>
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <div class="py-16 text-center border-t border-gray-100">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-brand-50 text-brand-500 flex items-center justify-center text-2xl mb-3">📦</div>
                <p class="font-medium">No products found</p>
                <p class="text-xs text-gray-400 mt-1">Try a different search or filter.</p>
                @if($search || $status)
                    <a href="{{ route('admin.products.index') }}" class="inline-block mt-4 text-xs text-brand-600 hover:underline">Clear filters</a>
                @endif
            </div>
        @endforelse
    </div>

    {{-- ============ PAGINATION ============ --}}
    @if(method_exists($products, 'hasPages') && $products->hasPages())
        <div class="flex items-center justify-between gap-3 flex-wrap pb-2">
            <p class="text-xs text-gray-400">Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }}</p>
            <div class="flex items-center gap-2 text-xs">
                @if($products->onFirstPage())
                    <span class="px-4 py-2.5 rounded-xl bg-white text-gray-300">← Prev</span>
                @else
                    <a href="{{ $products->previousPageUrl() }}" class="px-4 py-2.5 rounded-xl bg-white soft hover:bg-brand-600 hover:text-white transition">← Prev</a>
                @endif
                <span class="px-3 text-gray-500">Page {{ $products->currentPage() }} / {{ $products->lastPage() }}</span>
                @if($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="px-4 py-2.5 rounded-xl bg-white soft hover:bg-brand-600 hover:text-white transition">Next →</a>
                @else
                    <span class="px-4 py-2.5 rounded-xl bg-white text-gray-300">Next →</span>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('statusToggle', (on, url, id) => ({
            on, busy: false,
            async flip() {
                if (this.busy) return;
                this.busy = true;
                const prev = this.on;
                this.on = !prev;                       // optimistic update
                try {
                    const fd = new FormData();
                    fd.append('_token', document.querySelector('meta[name=csrf-token]').content);
                    fd.append('ids[]', id);
                    fd.append('status', this.on ? 1 : 0);
                    const r = await fetch(url, {
                        method: 'POST', body: fd, credentials: 'same-origin',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    });
                    if (!r.ok) throw new Error('Request failed');
                } catch (e) {
                    this.on = prev;                     // revert if it failed
                    alert('Could not update the status. Please try again.');
                }
                this.busy = false;
            }
        }));
    });
</script>
@endpush