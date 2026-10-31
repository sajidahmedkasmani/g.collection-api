@extends('layouts.app')
@section('title', 'Contact Us — G. Collection')

@section('content')
@php
    $wa = '923347779231';
    $email = 'support@gdotcollection.com';
    $subjects = ['Order help', 'Returns & exchange', 'Product question', 'Bulk / wholesale', 'Something else'];
@endphp

{{-- ============ HERO ============ --}}
<section class="bg-plum text-white relative overflow-hidden">
    <p class="absolute -top-8 -left-4 font-serif italic text-[200px] md:text-[300px] leading-none text-white/[0.06] select-none pointer-events-none whitespace-nowrap">Hello.</p>
    <div class="absolute -right-24 -bottom-24 w-80 h-80 rounded-full bg-rose/25 blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-6 pt-16 md:pt-24 pb-40 md:pb-48 relative">
        <div x-data="{ open:false,
                       init(){ const t=()=>{ const d=new Date(new Date().toLocaleString('en-US',{timeZone:'Asia/Karachi'})); const day=d.getDay(), h=d.getHours(); this.open = day!==0 && h>=11 && h<21 }; t(); setInterval(t,60000) } }"
             class="inline-flex items-center gap-2 border border-white/25 rounded-full px-4 py-1.5 text-[11px] uppercase tracking-[0.2em] mb-8">
            <span class="relative flex h-2 w-2">
                <span class="absolute inline-flex h-full w-full rounded-full opacity-75 animate-ping" :class="open ? 'bg-emerald-400' : 'bg-rose'"></span>
                <span class="relative inline-flex rounded-full h-2 w-2" :class="open ? 'bg-emerald-400' : 'bg-rose'"></span>
            </span>
            <span x-text="open ? 'We\'re online now' : 'Currently away · we reply next opening'">We're online now</span>
        </div>

        <h1 class="font-serif text-5xl md:text-8xl leading-[0.98] max-w-4xl">
            Let's talk.<br><span class="italic text-rose">We'd love</span> to hear from you.
        </h1>
        <p class="text-sm md:text-base text-white/70 max-w-lg mt-8 leading-relaxed">
            Order questions, sizing help, a custom request or just a hello. Our Karachi team replies personally, usually within a few hours.
        </p>
    </div>
</section>

{{-- ============ CONTACT CARDS (overlapping hero) ============ --}}
<section class="max-w-7xl mx-auto px-6 -mt-28 md:-mt-32 relative z-10">
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener"
           class="group rounded-3xl p-6 bg-gradient-to-br from-rose to-[#f0719d] text-white shadow-[0_20px_40px_-18px_rgba(217,85,138,.7)] hover:-translate-y-1 transition duration-300">
            <div class="w-11 h-11 rounded-2xl bg-white/20 flex items-center justify-center mb-8">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 20l1.3-4.2A8.5 8.5 0 1112 20.500a8.400 8.400 0 01-4-1L4 20z"/><path d="M9 10c0 3 2 5 5 5l1.200-1.200-1.800-1-.9.800a3.500 3.500 0 01-1.900-1.900l.8-.9-1-1.800L9 10z"/></svg>
            </div>
            <p class="text-[11px] uppercase tracking-[0.2em] text-white/80 mb-1">Fastest reply</p>
            <p class="font-serif text-2xl mb-1">WhatsApp</p>
            <p class="text-sm text-white/85">+92 334 7779231</p>
            <p class="text-xs mt-5 inline-flex items-center gap-2 group-hover:gap-3 transition-all">Chat now <span>→</span></p>
        </a>

        <a href="mailto:{{ $email }}" class="group rounded-3xl p-6 bg-white border border-black/5 shadow-[0_20px_40px_-24px_rgba(91,26,74,.35)] hover:-translate-y-1 transition duration-300">
            <div class="w-11 h-11 rounded-2xl bg-blush text-plum flex items-center justify-center mb-8">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <p class="text-[11px] uppercase tracking-[0.2em] text-muted mb-1">Write to us</p>
            <p class="font-serif text-2xl mb-1">Email</p>
            <p class="text-sm text-muted break-all">{{ $email }}</p>
            <p class="text-xs mt-5 text-plum inline-flex items-center gap-2 group-hover:gap-3 transition-all">Send email <span>→</span></p>
        </a>

        <div class="rounded-3xl p-6 bg-white border border-black/5 shadow-[0_20px_40px_-24px_rgba(91,26,74,.35)]">
            <div class="w-11 h-11 rounded-2xl bg-blush text-plum flex items-center justify-center mb-8">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <p class="text-[11px] uppercase tracking-[0.2em] text-muted mb-1">Team hours</p>
            <p class="font-serif text-2xl mb-1">Mon – Sat</p>
            <p class="text-sm text-muted">11:00 AM – 9:00 PM</p>
            <p class="text-xs mt-5 text-muted">Sunday: closed</p>
        </div>

        <div class="rounded-3xl p-6 bg-ink text-white shadow-[0_20px_40px_-18px_rgba(31,18,21,.6)]">
            <div class="w-11 h-11 rounded-2xl bg-white/10 text-rose flex items-center justify-center mb-8">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path d="M12 21s7-6.1 7-11a7 7 0 10-14 0c0 4.900 7 11 7 11z"/><circle cx="12" cy="10" r="2.500"/></svg>
            </div>
            <p class="text-[11px] uppercase tracking-[0.2em] text-white/50 mb-1">Based in</p>
            <p class="font-serif text-2xl mb-1">Karachi</p>
            <p class="text-sm text-white/70">Pakistan · delivering nationwide</p>
            <p class="text-xs mt-5 text-rose">Free shipping over Rs. 10,000</p>
        </div>
    </div>
</section>

{{-- ============ FORM + SIDE ============ --}}
<section class="max-w-7xl mx-auto px-6 py-24 grid lg:grid-cols-[1.25fr_1fr] gap-10 lg:gap-14">

    {{-- FORM --}}
    <div x-data="{
            tab: '{{ $errors->any() ? 'mail' : 'mail' }}',
            subject: @js(old('subject', $subjects[0])),
            name: @js(old('name', auth()->user()->name ?? '')),
            message: @js(old('message', '')),
            get waLink(){
                const t = `Hi G. Collection! ${this.name ? 'I\'m ' + this.name + '. ' : ''}(${this.subject}) ${this.message}`.trim();
                return 'https://wa.me/{{ $wa }}?text=' + encodeURIComponent(t);
            }
        }">
        <p class="text-[11px] uppercase tracking-[0.2em] text-muted mb-4">Send a message</p>
        <h2 class="font-serif text-4xl md:text-6xl leading-[1.02] mb-8">Tell us <span class="italic text-plum">everything.</span></h2>

        @if(session('contact_sent'))
            <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-800 px-5 py-4 text-sm flex items-start gap-3">
                <span class="text-lg leading-none">✓</span>
                <span><strong class="font-medium">Message received.</strong> Thank you! We'll get back to you within a few hours (Mon–Sat, 11 AM – 9 PM).</span>
            </div>
        @endif

        {{-- tabs --}}
        <div class="inline-flex bg-black/5 rounded-full p-1 mb-6 text-xs">
            <button type="button" @click="tab='mail'" :class="tab==='mail' ? 'bg-plum text-white shadow' : 'text-ink/60'" class="px-5 py-2 rounded-full transition">Write to us</button>
            <button type="button" @click="tab='wa'" :class="tab==='wa' ? 'bg-rose text-white shadow' : 'text-ink/60'" class="px-5 py-2 rounded-full transition">Send via WhatsApp</button>
        </div>

        <form method="POST" action="{{ route('contact.send') }}" class="space-y-6" @submit="if(tab==='wa'){ $event.preventDefault(); window.open(waLink,'_blank') }">
            @csrf
            <input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">

            {{-- subject chips --}}
            <div>
                <p class="text-[11px] uppercase tracking-[0.2em] text-muted mb-3">What's it about?</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($subjects as $s)
                        <button type="button" @click="subject=@js($s)"
                                :class="subject===@js($s) ? 'bg-ink text-white border-ink' : 'border-black/15 text-ink/70 hover:border-plum hover:text-plum'"
                                class="text-xs rounded-full border px-4 py-2 transition">{{ $s }}</button>
                    @endforeach
                </div>
                <input type="hidden" name="subject" :value="subject">
            </div>

            <div class="grid md:grid-cols-2 gap-5">
                <label class="block">
                    <span class="text-[11px] uppercase tracking-[0.2em] text-muted">Your name</span>
                    <input name="name" x-model="name" required placeholder="Ayesha Khan"
                           class="mt-2 w-full bg-white rounded-2xl border border-black/10 px-5 py-4 text-sm focus:outline-none focus:border-plum focus:ring-4 ring-plum/10 transition placeholder:text-black/30">
                    @error('name')<span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>@enderror
                </label>
                <label class="block" x-show="tab==='mail'">
                    <span class="text-[11px] uppercase tracking-[0.2em] text-muted">Email</span>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" :required="tab==='mail'" placeholder="you@example.com"
                           class="mt-2 w-full bg-white rounded-2xl border border-black/10 px-5 py-4 text-sm focus:outline-none focus:border-plum focus:ring-4 ring-plum/10 transition placeholder:text-black/30">
                    @error('email')<span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>@enderror
                </label>
            </div>

            <label class="block" x-show="tab==='mail'">
                <span class="text-[11px] uppercase tracking-[0.2em] text-muted">Phone / WhatsApp <span class="normal-case tracking-normal">(optional)</span></span>
                <input name="phone" value="{{ old('phone') }}" placeholder="+92 3xx xxxxxxx"
                       class="mt-2 w-full bg-white rounded-2xl border border-black/10 px-5 py-4 text-sm focus:outline-none focus:border-plum focus:ring-4 ring-plum/10 transition placeholder:text-black/30">
            </label>

            <label class="block">
                <span class="text-[11px] uppercase tracking-[0.2em] text-muted">Message</span>
                <textarea name="message" x-model="message" rows="6" required maxlength="2000" placeholder="Tell us about your order, size or question..."
                          class="mt-2 w-full bg-white rounded-2xl border border-black/10 px-5 py-4 text-sm focus:outline-none focus:border-plum focus:ring-4 ring-plum/10 transition placeholder:text-black/30 resize-none"></textarea>
                <span class="flex justify-between text-[11px] text-muted mt-1">
                    @error('message')<span class="text-red-600">{{ $message }}</span>@else<span></span>@enderror
                    <span x-text="message.length + ' / 2000'">0 / 2000</span>
                </span>
            </label>

            <div class="flex flex-wrap items-center gap-4">
                <button x-show="tab==='mail'" class="inline-flex items-center gap-3 bg-plum text-white rounded-full pl-7 pr-2 py-2 text-sm hover:bg-ink transition group">
                    Send message
                    <span class="w-9 h-9 rounded-full bg-white text-plum flex items-center justify-center group-hover:translate-x-1 transition">→</span>
                </button>
                <button x-cloak x-show="tab==='wa'" class="inline-flex items-center gap-3 bg-rose text-white rounded-full pl-7 pr-2 py-2 text-sm hover:opacity-90 transition group">
                    Open WhatsApp
                    <span class="w-9 h-9 rounded-full bg-white text-rose flex items-center justify-center group-hover:translate-x-1 transition">→</span>
                </button>
                <p class="text-xs text-muted" x-text="tab==='mail' ? 'We reply within a few hours, Mon–Sat.' : 'Your message will be prefilled in WhatsApp.'">We reply within a few hours, Mon–Sat.</p>
            </div>
        </form>
    </div>

    {{-- SIDE --}}
    <div class="space-y-6">
        <div class="rounded-[32px] overflow-hidden bg-blush p-3">
            <div class="rounded-[24px] overflow-hidden aspect-[4/3] bg-white/50">
                <iframe title="Karachi, Pakistan" class="w-full h-full grayscale-[30%]" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.google.com/maps?q=Karachi,Pakistan&output=embed"></iframe>
            </div>
            <div class="px-4 py-4 flex items-center justify-between text-sm">
                <span class="font-serif text-xl">Karachi, Pakistan</span>
                <span class="text-[11px] uppercase tracking-[0.2em] text-plum">Online-first store</span>
            </div>
        </div>

        <div class="rounded-[32px] bg-ink text-cream p-7">
            <p class="text-[11px] uppercase tracking-[0.2em] text-rose mb-4">Good to know</p>
            <ul class="divide-y divide-cream/10 text-sm">
                @foreach([
                    ['Free shipping','On bills of Rs. 10,000 and above'],
                    ['Pay your way','Cash on Delivery · Bank Transfer'],
                    ['Returns & exchanges','WhatsApp us within 48 hours of delivery'],
                ] as [$t,$d])
                    <li class="py-4 flex items-start justify-between gap-6">
                        <span class="font-serif text-lg">{{ $t }}</span>
                        <span class="text-cream/60 text-xs text-right max-w-[170px]">{{ $d }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

{{-- ============ QUICK ANSWERS ============ --}}
<section class="border-t border-black/10">
    <div class="max-w-7xl mx-auto px-6 py-20">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-10">
            <h2 class="font-serif text-4xl md:text-5xl">Quick <span class="italic text-plum">answers.</span></h2>
            <a href="{{ route('home') }}#faq" class="text-xs uppercase tracking-[0.2em] border-b border-ink pb-1 hover:text-rose hover:border-rose transition self-start">All FAQs</a>
        </div>
        <div class="grid md:grid-cols-3 gap-5">
            @foreach([
                ['How long does delivery take?','Orders usually reach you within a few working days, depending on your city.'],
                ['Can I change or cancel my order?','Message us on WhatsApp as soon as possible and we will help before it ships.'],
                ['How do I pay by bank transfer?','Bank details appear on your confirmation page. Share a screenshot after transferring.'],
            ] as [$q,$a])
                <div class="rounded-3xl bg-white border border-black/5 p-7 hover:-translate-y-1 hover:shadow-[0_20px_40px_-24px_rgba(91,26,74,.4)] transition duration-300">
                    <p class="font-serif text-xl mb-3">{{ $q }}</p>
                    <p class="text-sm text-muted leading-relaxed">{{ $a }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ CLOSING CTA ============ --}}
<section class="bg-blush overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 py-20 md:py-24 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <h2 class="font-serif text-5xl md:text-7xl leading-[1.02] mb-6">Still<br>wondering?<br><span class="italic text-plum">Say hi.</span></h2>
            <p class="text-sm text-ink/70 max-w-sm mb-8">A real person from our team is a message away. Mon–Sat, 11:00 AM – 9:00 PM.</p>
            <div class="flex flex-wrap gap-3">
                <a href="https://wa.me/{{ $wa }}" class="bg-ink text-white text-xs rounded-full px-7 py-3.5 hover:bg-plum transition">WhatsApp us</a>
                <a href="{{ route('shop') }}" class="border border-ink/30 text-xs rounded-full px-7 py-3.5 hover:bg-white/60 transition">Continue shopping</a>
            </div>
        </div>
        <div class="flex justify-center">
            <div class="w-56 md:w-72 aspect-[3/4] bg-plum rounded-t-full flex items-center justify-center shadow-[0_30px_60px_-30px_rgba(91,26,74,.8)]">
                <span class="font-serif italic text-white text-[140px] md:text-[180px] leading-none">G<span class="text-rose">.</span></span>
            </div>
        </div>
    </div>
</section>
@endsection