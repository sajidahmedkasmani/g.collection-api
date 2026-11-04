@extends('admin.layout')
@section('title', 'FAQ Management')

@section('content')
@php
    $reorderUrl = Route::has('admin.faqs.reorder') ? route('admin.faqs.reorder') : null;
    $canReorder = $reorderUrl && auth()->user()?->can('faqs.edit');
@endphp

<style>
    .faq-ghost  { opacity: .35; background: #F4EFFD; }
    .faq-chosen { background: #fff; }
    .faq-drag   { box-shadow: 0 18px 40px -12px rgba(95,50,168,.45); background: #fff; border-radius: 14px; }
</style>

<div class="max-w-6xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-ink">Frequently Asked Questions (FAQs)</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage customer questions and answers shown on the home page FAQ section.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.cms.index') }}" class="bg-white soft text-gray-700 hover:text-brand-600 px-4 py-2.5 rounded-xl text-xs font-medium transition">
                ← Back to CMS
            </a>
            @can('faqs.create')
            <a href="{{ route('admin.faqs.create') }}" class="btn-bloom text-white px-5 py-2.5 rounded-xl text-xs font-semibold hover:opacity-90 transition flex items-center gap-1.5 shadow-sm">
                <span class="text-base leading-none">+</span> Add New FAQ
            </a>
            @endcan
        </div>
    </div>

    {{-- Category Filters --}}
    @if($categories->count() > 1)
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('admin.faqs.index') }}"
               class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ !request('category') ? 'bg-brand-500 text-white shadow-sm' : 'bg-white soft text-gray-600 hover:text-brand-600' }}">
                All ({{ \App\Models\Faq::count() }})
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('admin.faqs.index', ['category' => $cat]) }}"
                   class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ request('category') === $cat ? 'bg-brand-500 text-white shadow-sm' : 'bg-white soft text-gray-600 hover:text-brand-600' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- Drag hint --}}
    @if($canReorder && $faqs->count() > 1)
        <div class="flex items-start gap-3 bg-brand-50 text-brand-700 rounded-2xl px-4 py-3 mb-4 text-xs leading-relaxed">
            <svg class="w-4 h-4 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="currentColor"><circle cx="9" cy="6" r="1.6"/><circle cx="15" cy="6" r="1.6"/><circle cx="9" cy="12" r="1.6"/><circle cx="15" cy="12" r="1.6"/><circle cx="9" cy="18" r="1.6"/><circle cx="15" cy="18" r="1.6"/></svg>
            <p>
                <strong class="font-semibold">Drag the handle</strong> on the left of any row to change the order. It saves automatically.
                @if(request('category')) You are viewing one category, so only this category's order changes. @endif
            </p>
        </div>
    @endif

    {{-- FAQS TABLE / LIST --}}
    <div class="bg-white rounded-2xl soft overflow-hidden">
        @if($faqs->isEmpty())
            <div class="p-12 text-center text-gray-400">
                <span class="text-4xl block mb-2">❓</span>
                <p class="text-base font-medium text-gray-600">No FAQs found</p>
                <p class="text-xs text-gray-400 mt-1">Get started by creating your first frequently asked question.</p>
                @can('faqs.create')
                <a href="{{ route('admin.faqs.create') }}" class="mt-4 inline-block btn-bloom text-white px-5 py-2 rounded-xl text-xs font-semibold">
                    + Add First FAQ
                </a>
                @endcan
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/50 text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                            <th class="py-3.5 px-5">Order</th>
                            <th class="py-3.5 px-5">Category</th>
                            <th class="py-3.5 px-5">Question & Answer</th>
                            <th class="py-3.5 px-5 text-center">Status</th>
                            <th class="py-3.5 px-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="faq-list" class="divide-y divide-gray-100 text-sm">
                        @foreach($faqs as $faq)
                            <tr data-id="{{ $faq->id }}" class="hover:bg-gray-50/60 transition bg-white">
                                <td class="py-4 px-5 w-24">
                                    <div class="flex items-center gap-2">
                                        @if($canReorder)
                                            <span class="drag-handle touch-none select-none cursor-grab active:cursor-grabbing text-gray-300 hover:text-brand-500 p-1 -ml-1 rounded-lg hover:bg-brand-50 transition" title="Drag to reorder" aria-label="Drag to reorder">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><circle cx="9" cy="6" r="1.6"/><circle cx="15" cy="6" r="1.6"/><circle cx="9" cy="12" r="1.6"/><circle cx="15" cy="12" r="1.6"/><circle cx="9" cy="18" r="1.6"/><circle cx="15" cy="18" r="1.6"/></svg>
                                            </span>
                                        @endif
                                        <span class="pos text-xs text-gray-400 font-mono">#{{ $loop->iteration }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-5">
                                    <span class="inline-block px-2.5 py-1 rounded-lg text-[11px] font-medium bg-brand-50 text-brand-700 whitespace-nowrap">
                                        {{ $faq->category }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 max-w-md min-w-[220px]">
                                    <p class="font-medium text-ink leading-snug">{{ $faq->question }}</p>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $faq->answer }}</p>
                                </td>
                                <td class="py-4 px-5 text-center">
                                    @can('faqs.edit')
                                    <form action="{{ route('admin.faqs.toggle-active', $faq) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 rounded-full text-[11px] font-semibold transition {{ $faq->is_active ? 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                            {{ $faq->is_active ? 'Active' : 'Hidden' }}
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-xs text-gray-500">{{ $faq->is_active ? 'Active' : 'Hidden' }}</span>
                                    @endcan
                                </td>
                                <td class="py-4 px-5 text-right whitespace-nowrap space-x-2">
                                    @can('faqs.edit')
                                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="inline-flex items-center text-xs font-semibold text-brand-600 hover:text-brand-700 bg-brand-50 px-3 py-1.5 rounded-lg transition">
                                        Edit
                                    </a>
                                    @endcan
                                    @can('faqs.delete')
                                    <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this FAQ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center text-xs font-semibold text-rose-600 hover:text-rose-700 bg-rose-50 px-3 py-1.5 rounded-lg transition">
                                            Delete
                                        </button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- Save toast --}}
<div id="order-toast" class="fixed z-50 left-1/2 -translate-x-1/2 bottom-28 md:bottom-8 px-5 py-3 rounded-2xl text-xs font-medium shadow-xl opacity-0 translate-y-3 pointer-events-none transition duration-300"></div>
@endsection

@if($canReorder)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    (function () {
        const list  = document.getElementById('faq-list');
        const toast = document.getElementById('order-toast');
        if (!list || typeof Sortable === 'undefined') return;

        const url  = @js($reorderUrl);
        const csrf = document.querySelector('meta[name=csrf-token]').content;
        let before = [...list.children].map(r => r.dataset.id);
        let timer;

        const say = (msg, ok = true) => {
            toast.textContent = msg;
            toast.className = 'fixed z-50 left-1/2 -translate-x-1/2 bottom-28 md:bottom-8 px-5 py-3 rounded-2xl text-xs font-medium shadow-xl transition duration-300 pointer-events-none opacity-100 translate-y-0 '
                + (ok ? 'bg-ink text-white' : 'bg-rose-600 text-white');
            clearTimeout(timer);
            timer = setTimeout(() => { toast.classList.replace('opacity-100', 'opacity-0'); toast.classList.replace('translate-y-0', 'translate-y-3'); }, 2200);
        };

        const renumber = () => [...list.children].forEach((r, i) => {
            const p = r.querySelector('.pos'); if (p) p.textContent = '#' + (i + 1);
        });

        const sortable = Sortable.create(list, {
            handle: '.drag-handle',
            animation: 180,
            ghostClass: 'faq-ghost',
            chosenClass: 'faq-chosen',
            dragClass: 'faq-drag',
            onEnd: async () => {
                const ids = [...list.children].map(r => r.dataset.id);
                if (ids.join() === before.join()) return;
                renumber();
                try {
                    const r = await fetch(url, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ ids })
                    });
                    if (!r.ok) throw new Error('save failed');
                    before = ids;
                    say('Order saved ✓');
                } catch (e) {
                    sortable.sort(before);   // put rows back
                    renumber();
                    say('Could not save the new order. Please try again.', false);
                }
            }
        });
    })();
</script>
@endpush
@endif