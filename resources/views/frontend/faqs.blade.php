@extends('frontend.layout.master')
@section('title', 'FAQs — G. Collection')


@section('content')
    @php
        if (method_exists($products, 'withQueryString')) { $products = $products->withQueryString(); }

        $cat   = request('category');
        $q     = trim((string) request('q'));
        $sort  = request('sort', 'newest');
        $cats  = ['' => 'All', 'stitched' => 'Stitched', 'unstitched' => 'Unstitched', 'accessories' => 'Accessories'];
        $total = method_exists($products, 'total') ? $products->total() : $products->count();
        $heading = $cat ? ($cats[$cat] ?? ucfirst($cat)) : 'Frequently Asked Questions';
        $chipUrl = fn($c) => route('shop', array_filter(['category' => $c, 'q' => $q, 'sort' => request('sort')]));
        $paged = method_exists($products, 'hasPages') && $products->hasPages();

        $faqList = isset($faqs) ? $faqs : \App\Models\Faq::active()->get();
    @endphp

    {{-- ============ HEADER BAND ============ --}}
    <section class="bg-blush relative overflow-hidden">
        <p class="absolute -bottom-10 right-0 font-serif italic text-[160px] md:text-[240px] leading-none text-white/40 select-none pointer-events-none">FAQs</p>
        <div class="max-w-7xl mx-auto px-5 md:px-6 pt-8 md:pt-14 pb-10 md:pb-14 relative">
            <nav class="text-[11px] uppercase tracking-[0.2em] text-plum/70 mb-5 flex items-center gap-2">
                <a href="{{ route('home') }}" class="hover:text-plum">Home</a><span>/</span>
                <a href="{{ route('faqs') }}" class="hover:text-plum {{ $cat ? '' : 'text-plum' }}">FAQs</a>
                @if($cat)<span>/</span><span class="text-plum">{{ $cats[$cat] ?? ucfirst($cat) }}</span>@endif
            </nav>

            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                <div>
                    <h1 class="font-serif text-5xl md:text-7xl leading-[0.98]">{{ $heading }}</h1>
                    <p class="text-sm text-ink/60 mt-4">
                        {{ $faqList->count() }} {{ \Illuminate\Support\Str::plural('question', $faqList->count()) }}
                    </p>
                </div>

                <form method="GET" action="{{ route('shop') }}" class="flex items-center gap-2 bg-white rounded-full pl-5 pr-1.5 py-1.5 w-full md:w-80 shadow-[0_10px_30px_-18px_rgba(91,26,74,.5)] focus-within:ring-4 ring-plum/10 transition">
                    @if($cat)<input type="hidden" name="category" value="{{ $cat }}">@endif
                    @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
                    <svg class="w-4 h-4 text-muted shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5" stroke-linecap="round"/></svg>
                    <input type="search" name="q" value="{{ $q }}" placeholder="Search kurtas, lace, lawn..." class="flex-1 bg-transparent text-sm py-2 focus:outline-none placeholder:text-black/30 min-w-0">
                    <button class="bg-plum text-white text-xs rounded-full px-5 py-2.5 hover:bg-ink transition">Search</button>
                </form>
            </div>
        </div>
    </section>



    @php
        $faqList = isset($faqs) ? $faqs : \App\Models\Faq::active()->get();
        $faqCategories = $faqList->pluck('category')->unique()->values();
    @endphp

    <section id="faq" class="bg-[#fbeef3]" x-data="{ activeCat: 'All questions', o: null }">
        <div class="max-w-7xl mx-auto px-6 py-24 grid md:grid-cols-[320px_1fr] gap-12">
            <div>
                <h2 class="font-serif text-6xl mb-4">
                    {{ setting('faq_section_title', 'Questions, answered.') }}
                </h2>
                <p class="text-xs text-muted mb-6 max-w-[240px]">{{ setting('faq_section_subtitle', "Can't find what you need? Talk to us on WhatsApp.") }}</p>
                <ul class="text-xs space-y-2">
                    <li @click="activeCat = 'All questions'; o = null"
                        :class="activeCat === 'All questions' ? 'bg-plum text-white font-medium' : 'bg-black/5 text-ink hover:bg-black/10'"
                        class="px-3.5 py-2.5 rounded-xl cursor-pointer transition">
                        All questions ({{ $faqList->count() }})
                    </li>
                    @foreach($faqCategories as $catName)
                        <li @click="activeCat = '{{ addslashes($catName) }}'; o = null"
                            :class="activeCat === '{{ addslashes($catName) }}' ? 'bg-plum text-white font-medium' : 'bg-black/5 text-ink hover:bg-black/10'"
                            class="px-3.5 py-2.5 rounded-xl cursor-pointer transition">
                            {{ $catName }}
                        </li>
                    @endforeach
                </ul>
                <div class="mt-8 text-xs text-muted space-y-1">
                    <p class="text-ink font-medium">Still wondering?</p>
                    <p>{{ setting('faq_timing', 'Mon–Sat, 11:00 AM – 9:00 PM') }}</p>
                    <a href="{{ whatsapp_link() }}" target="_blank" rel="noopener" class="text-plum underline block font-medium">WhatsApp {{ setting('contact_phone', '+92 334 7779231') }}</a>
                    <a href="mailto:{{ setting('contact_email', 'support@gdotcollection.com') }}" class="hover:text-rose block">{{ setting('contact_email', 'support@gdotcollection.com') }}</a>
                </div>
            </div>
    
            <div class="grid md:grid-cols-2 gap-4 content-start">
                @forelse($faqList as $n => $faqItem)
                    <div x-show="activeCat === 'All questions' || activeCat === '{{ addslashes($faqItem->category) }}'"
                        class="bg-ink text-cream rounded-xl p-5 cursor-pointer self-start transition hover:ring-1 hover:ring-rose/40"
                        @click="o = (o === {{ $n }} ? null : {{ $n }})">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-rose mb-2">{{ $faqItem->category }}</p>
                        <div class="flex justify-between items-start gap-4">
                            <p class="font-serif text-lg leading-snug">{{ $faqItem->question }}</p>
                            <span class="w-6 h-6 shrink-0 rounded-full border border-cream/30 flex items-center justify-center text-sm" x-text="o === {{ $n }} ? '−' : '+'">+</span>
                        </div>
                        <p x-cloak x-show="o === {{ $n }}" x-collapse class="text-xs text-cream/70 mt-3 leading-relaxed">{{ $faqItem->answer }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No questions available right now.</p>
                @endforelse
            </div>
        </div>
    </section>

@endsection