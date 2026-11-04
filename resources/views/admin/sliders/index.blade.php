@extends('admin.layout')
@section('title', 'Homepage Sliders')

@section('content')
@php
    $reorderUrl = Route::has('admin.sliders.reorder') ? route('admin.sliders.reorder') : null;
    $canReorder = $reorderUrl && auth()->user()?->can('sliders.edit');
@endphp

<style>
    .slide-ghost  { opacity: .35; background: #EFF6FF; }
    .slide-chosen { background: #fff; }
    .slide-drag   { box-shadow: 0 18px 40px -12px rgba(30,64,175,.45); background: #fff; border-radius: 12px; }
</style>
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-gray-500">Hero sliders for homepage. Drag to reorder.</p>
    @can('sliders.create')
    <a href="{{ route('admin.sliders.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">+ Add Slider</a>
    @endcan
</div>

<div class="bg-white rounded-xl shadow-sm overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left">
            <tr>
                <th class="py-3 px-4">Preview</th>
                <th>Title</th>
                <th>Type</th>
                <th>Status</th>
                <th>Order</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="slider-rows">
            @forelse($sliders as $s)
                <tr class="border-b hover:bg-gray-50 bg-white" data-id="{{ $s->id }}">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            @if($canReorder)
                                <span class="drag-handle touch-none select-none cursor-grab active:cursor-grabbing text-gray-300 hover:text-blue-600 p-1.5 -ml-1 rounded-lg hover:bg-blue-50 transition" title="Drag to reorder" aria-label="Drag to reorder">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><circle cx="9" cy="6" r="1.6"/><circle cx="15" cy="6" r="1.6"/><circle cx="9" cy="12" r="1.6"/><circle cx="15" cy="12" r="1.6"/><circle cx="9" cy="18" r="1.6"/><circle cx="15" cy="18" r="1.6"/></svg>
                                </span>
                            @endif
                            @if($s->image_url)
                                <img src="{{ $s->image_url }}" draggable="false" class="w-20 h-12 object-cover rounded shrink-0" style="{{ $s->background_style }}">
                            @else
                                <span class="text-xs text-gray-400">No image</span>
                            @endif
                        </div>
                    </td>
                    <td class="font-medium">{{ $s->title ?: '—' }}</td>
                    <td>
                        <span class="px-2 py-1 rounded text-xs {{ $s->content_type === 'image_only' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $s->content_type === 'image_only' ? 'Image Only' : 'Custom' }}
                        </span>
                    </td>
                    <td>
                        @if($s->is_active)
                            <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">Active</span>
                        @else
                            <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-600">Inactive</span>
                        @endif
                    </td>
                    <td class="text-xs text-gray-500 font-mono"><span class="pos">#{{ $loop->iteration }}</span></td>
                    <td class="space-x-3">
                        @can('sliders.edit')
                        <a href="{{ route('admin.sliders.edit', $s) }}" class="text-blue-600 hover:underline">Edit</a>
                        @endcan
                        @can('sliders.delete')
                        <form action="{{ route('admin.sliders.destroy', $s) }}" method="POST" class="inline" onsubmit="return confirm('Delete this slider?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline">Delete</button>
                        </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-gray-500">No sliders yet. Create your first one.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Save toast --}}
<div id="order-toast" class="fixed z-50 left-1/2 -translate-x-1/2 bottom-8 px-5 py-3 rounded-2xl text-xs font-medium shadow-xl opacity-0 translate-y-3 pointer-events-none transition duration-300"></div>
@endsection

@if($canReorder)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    (function () {
        const list  = document.getElementById('slider-rows');
        const toast = document.getElementById('order-toast');
        if (!list || typeof Sortable === 'undefined') return;

        const url  = @js($reorderUrl);
        const csrf = document.querySelector('meta[name=csrf-token]').content;
        let before = [...list.querySelectorAll('tr[data-id]')].map(r => r.dataset.id);
        let timer;

        const say = (msg, ok = true) => {
            toast.textContent = msg;
            toast.className = 'fixed z-50 left-1/2 -translate-x-1/2 bottom-28 md:bottom-8 px-5 py-3 rounded-2xl text-xs font-medium shadow-xl transition duration-300 pointer-events-none opacity-100 translate-y-0 '
                + (ok ? 'bg-ink text-white' : 'bg-rose-600 text-white');
            clearTimeout(timer);
            timer = setTimeout(() => {
                toast.classList.replace('opacity-100', 'opacity-0');
                toast.classList.replace('translate-y-0', 'translate-y-3');
            }, 2200);
        };

        const rows = () => [...list.querySelectorAll('tr[data-id]')];
        const renumber = () => rows().forEach((r, i) => {
            const p = r.querySelector('.pos'); if (p) p.textContent = '#' + (i + 1);
        });

        const sortable = Sortable.create(list, {
            handle: '.drag-handle',
            draggable: 'tr[data-id]',
            animation: 180,
            ghostClass: 'slide-ghost',
            chosenClass: 'slide-chosen',
            dragClass: 'slide-drag',
            onEnd: async () => {
                const ids = rows().map(r => r.dataset.id);
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
                    sortable.sort(before);
                    renumber();
                    say('Could not save the new order. Please try again.', false);
                }
            }
        });
    })();
</script>
@endpush
@endif