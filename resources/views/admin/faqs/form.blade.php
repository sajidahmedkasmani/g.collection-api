@extends('admin.layout')
@section('title', $faq->exists ? 'Edit FAQ' : 'Add FAQ')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-ink">{{ $faq->exists ? 'Edit FAQ' : 'Add New FAQ' }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">Define question, answer, category, and display order.</p>
        </div>
        <a href="{{ route('admin.faqs.index') }}" class="bg-white soft text-gray-700 hover:text-brand-600 px-4 py-2 rounded-xl text-xs font-medium transition">
            ← Back to FAQs
        </a>
    </div>

    <div class="bg-white rounded-2xl p-6 soft">
        <form action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" method="POST" class="space-y-5">
            @csrf
            @if($faq->exists)
                @method('PUT')
            @endif

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Category</label>
                <input type="text" name="category" list="cat-suggestions" value="{{ old('category', $faq->category ?? 'Ordering & payments') }}" required placeholder="e.g. Shipping & delivery" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
                <datalist id="cat-suggestions">
                    @foreach($categories as $c)
                        <option value="{{ $c }}">
                    @endforeach
                    <option value="Ordering & payments">
                    <option value="Shipping & delivery">
                    <option value="Sizes & products">
                    <option value="Returns & exchanges">
                    <option value="Customer support">
                </datalist>
                <span class="text-[11px] text-gray-400 mt-1 block">Type a new category or pick from existing suggestions.</span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Question</label>
                <input type="text" name="question" value="{{ old('question', $faq->question) }}" required placeholder="e.g. How do I pay?" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Answer</label>
                <textarea name="answer" rows="4" required placeholder="Detailed response for customer..." class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none leading-relaxed">{{ old('answer', $faq->answer) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $faq->sort_order ?? 0) }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 outline-none">
                    <span class="text-[11px] text-gray-400 mt-1 block">Lower numbers appear first.</span>
                </div>

                <div class="flex items-center pt-6">
                    <label class="inline-flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $faq->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500">
                        <span class="text-sm font-medium text-gray-700">Display this FAQ publicly</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.faqs.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-medium text-gray-600 hover:bg-gray-100 transition">Cancel</a>
                <button type="submit" class="btn-bloom text-white px-6 py-2.5 rounded-xl text-xs font-semibold hover:opacity-90 transition">
                    {{ $faq->exists ? 'Update FAQ' : 'Save FAQ' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
