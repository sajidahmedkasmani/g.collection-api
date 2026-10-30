@extends('admin.layout')
@section('title', 'Homepage Sliders')

@section('content')
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-gray-500">Hero sliders for homepage. Drag to reorder.</p>
    <a href="{{ route('admin.sliders.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">+ Add Slider</a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
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
                <tr class="border-b hover:bg-gray-50" data-id="{{ $s->id }}">
                    <td class="py-3 px-4">
                        @if($s->image_url)
                            <img src="{{ $s->image_url }}" class="w-20 h-12 object-cover rounded" style="{{ $s->background_style }}">
                        @else
                            <span class="text-xs text-gray-400">No image</span>
                        @endif
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
                    <td class="text-xs text-gray-500">{{ $s->sort_order }}</td>
                    <td class="space-x-3">
                        <a href="{{ route('admin.sliders.edit', $s) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('admin.sliders.destroy', $s) }}" method="POST" class="inline" onsubmit="return confirm('Delete this slider?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-gray-500">No sliders yet. Create your first one.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection