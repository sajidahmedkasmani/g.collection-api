@extends('admin.layout')
@section('title', 'FAQ Management')

@section('content')
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
            <a href="{{ route('admin.faqs.create') }}" class="btn-bloom text-white px-5 py-2.5 rounded-xl text-xs font-semibold hover:opacity-90 transition flex items-center gap-1.5 shadow-sm">
                <span class="text-base leading-none">+</span> Add New FAQ
            </a>
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

    {{-- FAQS TABLE / LIST --}}
    <div class="bg-white rounded-2xl soft overflow-hidden">
        @if($faqs->isEmpty())
            <div class="p-12 text-center text-gray-400">
                <span class="text-4xl block mb-2">❓</span>
                <p class="text-base font-medium text-gray-600">No FAQs found</p>
                <p class="text-xs text-gray-400 mt-1">Get started by creating your first frequently asked question.</p>
                <a href="{{ route('admin.faqs.create') }}" class="mt-4 inline-block btn-bloom text-white px-5 py-2 rounded-xl text-xs font-semibold">
                    + Add First FAQ
                </a>
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
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($faqs as $faq)
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="py-4 px-5 text-xs text-gray-400 font-mono">
                                    #{{ $faq->sort_order }}
                                </td>
                                <td class="py-4 px-5">
                                    <span class="inline-block px-2.5 py-1 rounded-lg text-[11px] font-medium bg-brand-50 text-brand-700">
                                        {{ $faq->category }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 max-w-md">
                                    <p class="font-medium text-ink leading-snug">{{ $faq->question }}</p>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $faq->answer }}</p>
                                </td>
                                <td class="py-4 px-5 text-center">
                                    <form action="{{ route('admin.faqs.toggle-active', $faq) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 rounded-full text-[11px] font-semibold transition {{ $faq->is_active ? 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                            {{ $faq->is_active ? 'Active' : 'Hidden' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="py-4 px-5 text-right space-x-2">
                                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="inline-flex items-center text-xs font-semibold text-brand-600 hover:text-brand-700 bg-brand-50 px-3 py-1.5 rounded-lg transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this FAQ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center text-xs font-semibold text-rose-600 hover:text-rose-700 bg-rose-50 px-3 py-1.5 rounded-lg transition">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
