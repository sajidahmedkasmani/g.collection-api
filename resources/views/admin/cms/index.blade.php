@extends('admin.layout')
@section('title', 'Website Content (CMS)')

@section('content')
<div class="max-w-6xl mx-auto" x-data="{ tab: '{{ $activeTab }}' }">

    {{-- HEADER & TABS --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-ink">Website Content Management</h1>
            <p class="text-sm text-gray-500 mt-0.5">Customize your home page sections, footer, contact info, and top bar dynamically.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-2 bg-white soft text-gray-700 hover:text-brand-600 px-4 py-2 rounded-xl text-xs font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Preview Live Site
            </a>
        </div>
    </div>

    {{-- TAB BAR --}}
    <div class="flex flex-wrap gap-2 p-1.5 bg-white/70 backdrop-blur rounded-2xl soft mb-6">
        <button type="button" @click="tab = 'home'"
                :class="tab === 'home' ? 'bg-brand-500 text-white shadow-sm' : 'text-gray-600 hover:bg-white'"
                class="px-5 py-2.5 rounded-xl text-xs font-semibold transition flex items-center gap-2">
            <span>🏠</span> Home Page Content
        </button>
        <button type="button" @click="tab = 'footer'"
                :class="tab === 'footer' ? 'bg-brand-500 text-white shadow-sm' : 'text-gray-600 hover:bg-white'"
                class="px-5 py-2.5 rounded-xl text-xs font-semibold transition flex items-center gap-2">
            <span>🦶</span> Footer & Contact Info
        </button>
        <button type="button" @click="tab = 'announcement'"
                :class="tab === 'announcement' ? 'bg-brand-500 text-white shadow-sm' : 'text-gray-600 hover:bg-white'"
                class="px-5 py-2.5 rounded-xl text-xs font-semibold transition flex items-center gap-2">
            <span>📢</span> Announcement Bar
        </button>
        <button type="button" @click="tab = 'bank'"
                :class="tab === 'bank' ? 'bg-brand-500 text-white shadow-sm' : 'text-gray-600 hover:bg-white'"
                class="px-5 py-2.5 rounded-xl text-xs font-semibold transition flex items-center gap-2">
            <span>🏦</span> Bank Details
        </button>
        <a href="{{ route('admin.faqs.index') }}"
           class="px-5 py-2.5 rounded-xl text-xs font-semibold text-gray-600 hover:bg-white transition flex items-center gap-2 ml-auto">
            <span>❓</span> Manage FAQs →
        </a>
    </div>

    {{-- ==================== TAB 1: HOME PAGE ==================== --}}
    <div x-show="tab === 'home'" x-cloak>
        <form action="{{ route('admin.cms.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="_tab" value="home">

            {{-- 1. COLLECTIONS SECTION --}}
            <div class="bg-white rounded-2xl p-6 soft">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-5">
                    <div>
                        <h2 class="text-base font-bold text-ink flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span>
                            Collections Section ("Three ways to dress the day")
                        </h2>
                        <p class="text-xs text-gray-400 mt-0.5">Customize the 3 main collection banner cards on the home page.</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Section Heading (allows line breaks)</label>
                        <textarea name="collections_title" rows="2" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">{{ old('collections_title', $settings['collections_title'] ?? "Three ways\nto dress the day.") }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Section Subtitle / Description</label>
                        <textarea name="collections_subtitle" rows="2" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">{{ old('collections_subtitle', $settings['collections_subtitle'] ?? "Stitched, unstitched and everything that goes with it. Pick a collection to start.") }}</textarea>
                    </div>
                </div>

                {{-- 3 Collection Cards --}}
                <div class="grid md:grid-cols-3 gap-5">
                    {{-- Card 1 --}}
                    <div class="bg-brand-50/50 border border-brand-100/60 rounded-xl p-4">
                        <span class="text-[11px] font-semibold text-brand-700 uppercase tracking-wider block mb-2">Card 1 (Left)</span>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-[11px] text-gray-600 font-medium mb-1">Title</label>
                                <input type="text" name="collection_1_title" value="{{ old('collection_1_title', $settings['collection_1_title'] ?? 'Stitched') }}" class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-xs bg-white">
                            </div>
                            <div>
                                <label class="block text-[11px] text-gray-600 font-medium mb-1">Category Slug / Filter</label>
                                <input type="text" name="collection_1_slug" value="{{ old('collection_1_slug', $settings['collection_1_slug'] ?? 'stitched') }}" class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-xs bg-white">
                            </div>
                            <div>
                                <label class="block text-[11px] text-gray-600 font-medium mb-1">Custom Image (Optional)</label>
                                @if(!empty($settings['collection_1_image']))
                                    <div class="mb-2 relative w-20 h-24 rounded-lg overflow-hidden border">
                                        <img src="{{ cms_image('collection_1_image') }}" class="w-full h-full object-cover">
                                    </div>
                                    <label class="inline-flex items-center gap-1.5 text-[11px] text-red-600 mb-1">
                                        <input type="checkbox" name="remove_collection_1_image" value="1" class="rounded text-red-600"> Remove custom image
                                    </label>
                                @endif
                                <input type="file" name="collection_1_image_file" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:bg-white file:text-brand-600 hover:file:bg-brand-50">
                                <span class="text-[10px] text-gray-400 block mt-0.5">Leaves fallback to first product image if empty</span>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2 --}}
                    <div class="bg-brand-50/50 border border-brand-100/60 rounded-xl p-4">
                        <span class="text-[11px] font-semibold text-brand-700 uppercase tracking-wider block mb-2">Card 2 (Center)</span>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-[11px] text-gray-600 font-medium mb-1">Title</label>
                                <input type="text" name="collection_2_title" value="{{ old('collection_2_title', $settings['collection_2_title'] ?? 'Unstitched') }}" class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-xs bg-white">
                            </div>
                            <div>
                                <label class="block text-[11px] text-gray-600 font-medium mb-1">Category Slug / Filter</label>
                                <input type="text" name="collection_2_slug" value="{{ old('collection_2_slug', $settings['collection_2_slug'] ?? 'unstitched') }}" class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-xs bg-white">
                            </div>
                            <div>
                                <label class="block text-[11px] text-gray-600 font-medium mb-1">Custom Image (Optional)</label>
                                @if(!empty($settings['collection_2_image']))
                                    <div class="mb-2 relative w-20 h-24 rounded-lg overflow-hidden border">
                                        <img src="{{ cms_image('collection_2_image') }}" class="w-full h-full object-cover">
                                    </div>
                                    <label class="inline-flex items-center gap-1.5 text-[11px] text-red-600 mb-1">
                                        <input type="checkbox" name="remove_collection_2_image" value="1" class="rounded text-red-600"> Remove custom image
                                    </label>
                                @endif
                                <input type="file" name="collection_2_image_file" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:bg-white file:text-brand-600 hover:file:bg-brand-50">
                                <span class="text-[10px] text-gray-400 block mt-0.5">Leaves fallback to product image if empty</span>
                            </div>
                        </div>
                    </div>

                    {{-- Card 3 --}}
                    <div class="bg-brand-50/50 border border-brand-100/60 rounded-xl p-4">
                        <span class="text-[11px] font-semibold text-brand-700 uppercase tracking-wider block mb-2">Card 3 (Right)</span>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-[11px] text-gray-600 font-medium mb-1">Title</label>
                                <input type="text" name="collection_3_title" value="{{ old('collection_3_title', $settings['collection_3_title'] ?? 'Accessories') }}" class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-xs bg-white">
                            </div>
                            <div>
                                <label class="block text-[11px] text-gray-600 font-medium mb-1">Category Slug / Filter</label>
                                <input type="text" name="collection_3_slug" value="{{ old('collection_3_slug', $settings['collection_3_slug'] ?? 'accessories') }}" class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-xs bg-white">
                            </div>
                            <div>
                                <label class="block text-[11px] text-gray-600 font-medium mb-1">Custom Image (Optional)</label>
                                @if(!empty($settings['collection_3_image']))
                                    <div class="mb-2 relative w-20 h-24 rounded-lg overflow-hidden border">
                                        <img src="{{ cms_image('collection_3_image') }}" class="w-full h-full object-cover">
                                    </div>
                                    <label class="inline-flex items-center gap-1.5 text-[11px] text-red-600 mb-1">
                                        <input type="checkbox" name="remove_collection_3_image" value="1" class="rounded text-red-600"> Remove custom image
                                    </label>
                                @endif
                                <input type="file" name="collection_3_image_file" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:bg-white file:text-brand-600 hover:file:bg-brand-50">
                                <span class="text-[10px] text-gray-400 block mt-0.5">Leaves fallback to product image if empty</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. MARQUEE SECTION --}}
            <div class="bg-white rounded-2xl p-6 soft">
                <div class="border-b border-gray-100 pb-3 mb-4">
                    <h2 class="text-base font-bold text-ink flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-bloom-500"></span>
                        Big Animated Marquee
                    </h2>
                    <p class="text-xs text-gray-400">Words or slogans scrolling continuously across the home screen.</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Marquee Items (separated by dots or spaces)</label>
                    <input type="text" name="marquee_items" value="{{ old('marquee_items', $settings['marquee_items'] ?? 'Embroidery · Lace · Lawn · Cutwork') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 outline-none">
                    <span class="text-[11px] text-gray-400 mt-1 block">Example: <code>Embroidery · Lace · Lawn · Cutwork · Karachi Heritage</code></span>
                </div>
            </div>

            {{-- 3. PLUM FEATURE SECTION --}}
            <div class="bg-white rounded-2xl p-6 soft">
                <div class="border-b border-gray-100 pb-3 mb-5">
                    <h2 class="text-base font-bold text-ink flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-700"></span>
                        Plum Feature Section
                    </h2>
                    <p class="text-xs text-gray-400">The signature dark plum section showcasing handcrafted silhouettes.</p>
                </div>

                <div class="mb-5">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Background Watermark Large Text</label>
                    <input type="text" name="plum_watermark" value="{{ old('plum_watermark', $settings['plum_watermark'] ?? 'Lace. Fringe.') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-sm">
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    {{-- Block 1 --}}
                    <div class="border border-gray-200 rounded-xl p-4 bg-gray-50/50">
                        <p class="font-semibold text-xs text-gray-800 uppercase tracking-wider mb-3">Feature Block 1 (Top)</p>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-[11px] text-gray-600 font-medium mb-1">Title (supports HTML like &lt;span class="italic text-rose"&gt;)</label>
                                <textarea name="plum_b1_title" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-xs bg-white font-mono">{{ old('plum_b1_title', $settings['plum_b1_title'] ?? 'Lace.<br><span class="italic text-rose">Fringe.</span><br>Cutwork.') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[11px] text-gray-600 font-medium mb-1">Description</label>
                                <textarea name="plum_b1_desc" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-xs bg-white">{{ old('plum_b1_desc', $settings['plum_b1_desc'] ?? 'Three details, one silhouette. Each piece is finished by hand in our Karachi workshop.') }}</textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[11px] text-gray-600 font-medium mb-1">Button Text</label>
                                    <input type="text" name="plum_b1_btn_text" value="{{ old('plum_b1_btn_text', $settings['plum_b1_btn_text'] ?? 'Add to bag') }}" class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-xs bg-white">
                                </div>
                                <div>
                                    <label class="block text-[11px] text-gray-600 font-medium mb-1">Button URL</label>
                                    <input type="text" name="plum_b1_btn_url" value="{{ old('plum_b1_btn_url', $settings['plum_b1_btn_url'] ?? '/shop') }}" class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-xs bg-white">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] text-gray-600 font-medium mb-1">Custom Arch Image (Optional)</label>
                                @if(!empty($settings['plum_b1_image']))
                                    <div class="mb-2 w-16 h-20 rounded-t-full overflow-hidden border">
                                        <img src="{{ cms_image('plum_b1_image') }}" class="w-full h-full object-cover">
                                    </div>
                                    <label class="inline-flex items-center gap-1.5 text-[11px] text-red-600 mb-1">
                                        <input type="checkbox" name="remove_plum_b1_image" value="1"> Remove custom image
                                    </label>
                                @endif
                                <input type="file" name="plum_b1_image_file" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:bg-white file:text-brand-600">
                            </div>
                        </div>
                    </div>

                    {{-- Block 2 --}}
                    <div class="border border-gray-200 rounded-xl p-4 bg-gray-50/50">
                        <p class="font-semibold text-xs text-gray-800 uppercase tracking-wider mb-3">Feature Block 2 (Bottom)</p>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-[11px] text-gray-600 font-medium mb-1">Title (supports HTML)</label>
                                <textarea name="plum_b2_title" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-xs bg-white font-mono">{{ old('plum_b2_title', $settings['plum_b2_title'] ?? 'Lace.<br><span class="italic text-rose">Fringe.</span><br>Cutwork.') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[11px] text-gray-600 font-medium mb-1">Description</label>
                                <textarea name="plum_b2_desc" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-xs bg-white">{{ old('plum_b2_desc', $settings['plum_b2_desc'] ?? 'These details make these pieces fit the collection, wear after wear.') }}</textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[11px] text-gray-600 font-medium mb-1">Button Text</label>
                                    <input type="text" name="plum_b2_btn_text" value="{{ old('plum_b2_btn_text', $settings['plum_b2_btn_text'] ?? 'Shop the edit') }}" class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-xs bg-white">
                                </div>
                                <div>
                                    <label class="block text-[11px] text-gray-600 font-medium mb-1">Button URL</label>
                                    <input type="text" name="plum_b2_btn_url" value="{{ old('plum_b2_btn_url', $settings['plum_b2_btn_url'] ?? '/shop') }}" class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-xs bg-white">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] text-gray-600 font-medium mb-1">Custom Image 1 (Main)</label>
                                @if(!empty($settings['plum_b2_image_1']))
                                    <div class="mb-2 w-16 h-20 rounded-lg overflow-hidden border">
                                        <img src="{{ cms_image('plum_b2_image_1') }}" class="w-full h-full object-cover">
                                    </div>
                                    <label class="inline-flex items-center gap-1.5 text-[11px] text-red-600 mb-1">
                                        <input type="checkbox" name="remove_plum_b2_image_1" value="1"> Remove
                                    </label>
                                @endif
                                <input type="file" name="plum_b2_image_1_file" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:bg-white file:text-brand-600">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. THE EDIT SECTION (BLUSH) --}}
            <div class="bg-white rounded-2xl p-6 soft">
                <div class="border-b border-gray-100 pb-3 mb-5">
                    <h2 class="text-base font-bold text-ink flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-pink-400"></span>
                        The Edit Section (Blush Background)
                    </h2>
                    <p class="text-xs text-gray-400">Editorial spotlight section showcasing delicate stitches and kurti designs.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Badge 1</label>
                        <input type="text" name="edit_badge_1" value="{{ old('edit_badge_1', $settings['edit_badge_1'] ?? 'The Edit') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Badge 2</label>
                        <input type="text" name="edit_badge_2" value="{{ old('edit_badge_2', $settings['edit_badge_2'] ?? 'Embroidery · Lace · Lawn') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Badge 3</label>
                        <input type="text" name="edit_badge_3" value="{{ old('edit_badge_3', $settings['edit_badge_3'] ?? 'Karachi') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-sm">
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Title (supports HTML)</label>
                        <textarea name="edit_title" rows="2" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-sm font-mono">{{ old('edit_title', $settings['edit_title'] ?? 'Made<br><span class="italic">by hand,</span><br>worn by<br>you.') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="edit_desc" rows="2" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-sm">{{ old('edit_desc', $settings['edit_desc'] ?? 'Soft lace sleeves, fringed hems, scalloped cutwork borders. The details that make a hand-finished kurta.') }}</textarea>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Button Text</label>
                        <input type="text" name="edit_btn_text" value="{{ old('edit_btn_text', $settings['edit_btn_text'] ?? 'Shop the edit') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Button URL</label>
                        <input type="text" name="edit_btn_url" value="{{ old('edit_btn_url', $settings['edit_btn_url'] ?? '/shop') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-sm">
                    </div>
                </div>
            </div>

            {{-- 5. GOOD TO KNOW SECTION --}}
            <div class="bg-white rounded-2xl p-6 soft">
                <div class="border-b border-gray-100 pb-3 mb-5">
                    <h2 class="text-base font-bold text-ink flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        Good to Know (Shopping Features)
                    </h2>
                    <p class="text-xs text-gray-400">Highlights delivery, payments, customer support, and brand location.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-4 mb-5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Tagline Badge</label>
                        <input type="text" name="good_to_know_badge" value="{{ old('good_to_know_badge', $settings['good_to_know_badge'] ?? 'Good to know') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Section Title (supports &lt;br&gt;)</label>
                        <input type="text" name="good_to_know_title" value="{{ old('good_to_know_title', $settings['good_to_know_title'] ?? 'Shopping with<br>G. Collection') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Short Description</label>
                        <textarea name="good_to_know_desc" rows="2" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-xs">{{ old('good_to_know_desc', $settings['good_to_know_desc'] ?? "A full collection of stitched and unstitched ladies' clothing, based in Karachi, Pakistan. Our team is available Mon–Sat 11:00 AM – 9:00 PM.") }}</textarea>
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @for($i = 1; $i <= 4; $i++)
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-3">
                            <span class="text-[10px] font-semibold text-gray-500 uppercase block mb-1.5">Point #{{ $i }}</span>
                            <div class="space-y-2">
                                <div>
                                    <label class="block text-[11px] text-gray-600 font-medium">Title</label>
                                    <input type="text" name="good_to_know_{{ $i }}_title" value="{{ old('good_to_know_'.$i.'_title', $settings['good_to_know_'.$i.'_title'] ?? '') }}" class="w-full border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs bg-white">
                                </div>
                                <div>
                                    <label class="block text-[11px] text-gray-600 font-medium">Subtitle</label>
                                    <input type="text" name="good_to_know_{{ $i }}_desc" value="{{ old('good_to_know_'.$i.'_desc', $settings['good_to_know_'.$i.'_desc'] ?? '') }}" class="w-full border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs bg-white">
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- 6. NEWSLETTER SECTION --}}
            <div class="bg-white rounded-2xl p-6 soft">
                <div class="border-b border-gray-100 pb-3 mb-5">
                    <h2 class="text-base font-bold text-ink flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        Newsletter Section
                    </h2>
                    <p class="text-xs text-gray-400">Invite shoppers to subscribe for latest arrivals.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Title (supports HTML)</label>
                        <textarea name="newsletter_title" rows="2" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-sm font-mono">{{ old('newsletter_title', $settings['newsletter_title'] ?? "Be first<br>to see<br><span class=\"italic text-plum\">what's new.</span>") }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="newsletter_desc" rows="2" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-sm">{{ old('newsletter_desc', $settings['newsletter_desc'] ?? 'New arrivals and news from G. Collection, in your inbox. Just your email, nothing else.') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Monogram Badge</label>
                        <input type="text" name="newsletter_monogram" value="{{ old('newsletter_monogram', $settings['newsletter_monogram'] ?? 'G.') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-sm">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-3">
                <button type="submit" class="btn-bloom text-white px-8 py-3 rounded-xl text-sm font-medium hover:opacity-90 transition">
                    Save Home Page Changes
                </button>
            </div>
        </form>
    </div>

    {{-- ==================== TAB 2: FOOTER & CONTACT ==================== --}}
    <div x-show="tab === 'footer'" x-cloak>
        <form action="{{ route('admin.cms.update') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="_tab" value="footer">

            {{-- BRAND & FOOTER ABOUT --}}
            <div class="bg-white rounded-2xl p-6 soft">
                <div class="border-b border-gray-100 pb-3 mb-5">
                    <h2 class="text-base font-bold text-ink flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span>
                        Brand & Footer About
                    </h2>
                    <p class="text-xs text-gray-400">Displayed in the footer brand column across the entire website.</p>
                </div>

                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Store / Brand Name</label>
                        <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? 'G. Collection') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Tagline</label>
                        <input type="text" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline'] ?? 'Wear the Story, Every Stitch') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 outline-none">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Footer About Description</label>
                    <textarea name="footer_about" rows="3" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 outline-none">{{ old('footer_about', $settings['footer_about'] ?? "A full collection of stitched and unstitched ladies' clothing from Karachi, Pakistan.") }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Copyright Line</label>
                    <input type="text" name="copyright_text" value="{{ old('copyright_text', $settings['copyright_text'] ?? 'G. Collection. All rights reserved.') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 outline-none">
                    <span class="text-[10px] text-gray-400 mt-1 block">Year is appended automatically: © {{ date('Y') }} [Your text]</span>
                </div>
            </div>

            {{-- CONTACT DETAILS --}}
            <div class="bg-white rounded-2xl p-6 soft">
                <div class="border-b border-gray-100 pb-3 mb-5">
                    <h2 class="text-base font-bold text-ink flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        Contact & Support Channels
                    </h2>
                    <p class="text-xs text-gray-400">Used across footer, contact page, checkout help, and FAQ WhatsApp links.</p>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">WhatsApp Number (e.g. 923347779231)</label>
                        <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '923347779231') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 outline-none">
                        <span class="text-[11px] text-gray-400 mt-1 block">Used for one-click chat links: <code>https://wa.me/923347779231</code></span>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Phone Display (e.g. +92 334 7779231)</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '+92 334 7779231') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Support Email</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? 'support@gdotcollection.com') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 outline-none">
                        <span class="text-[11px] text-gray-400 mt-1 block">Customer contact inquiries are forwarded to this address.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Working Hours (e.g. Mon–Sat, 11:00 AM – 9:00 PM)</label>
                        <input type="text" name="contact_hours" value="{{ old('contact_hours', $settings['contact_hours'] ?? 'Mon–Sat, 11:00 AM – 9:00 PM') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 outline-none">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Location / City / Address</label>
                        <input type="text" name="contact_address" value="{{ old('contact_address', $settings['contact_address'] ?? 'Karachi, Pakistan') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 outline-none">
                    </div>
                </div>
            </div>

            {{-- SOCIAL MEDIA --}}
            <div class="bg-white rounded-2xl p-6 soft">
                <div class="border-b border-gray-100 pb-3 mb-5">
                    <h2 class="text-base font-bold text-ink flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-bloom-500"></span>
                        Social Media Links
                    </h2>
                    <p class="text-xs text-gray-400">Leave blank if you don't wish to show a specific platform.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Instagram URL</label>
                        <input type="url" name="social_instagram" value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}" placeholder="https://instagram.com/..." class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Facebook URL</label>
                        <input type="url" name="social_facebook" value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}" placeholder="https://facebook.com/..." class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">TikTok URL</label>
                        <input type="url" name="social_tiktok" value="{{ old('social_tiktok', $settings['social_tiktok'] ?? '') }}" placeholder="https://tiktok.com/@..." class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-3">
                <button type="submit" class="btn-bloom text-white px-8 py-3 rounded-xl text-sm font-medium hover:opacity-90 transition">
                    Save Footer & Contact Changes
                </button>
            </div>
        </form>
    </div>

    {{-- ==================== TAB 3: ANNOUNCEMENT BAR ==================== --}}
    <div x-show="tab === 'announcement'" x-cloak>
        <form action="{{ route('admin.cms.update') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="_tab" value="announcement">
            <input type="hidden" name="_has_announcement" value="1">

            <div class="bg-white rounded-2xl p-6 soft max-w-3xl">
                <div class="border-b border-gray-100 pb-3 mb-5">
                    <h2 class="text-base font-bold text-ink flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                        Top Announcement Banner & Free Shipping Threshold
                    </h2>
                    <p class="text-xs text-gray-400">The top bar displayed at the very top of every page.</p>
                </div>

                <div class="space-y-5">
                    <div class="flex items-center gap-3 p-4 bg-brand-50 rounded-xl">
                        <input type="checkbox" id="announcement_enabled" name="announcement_enabled" value="1"
                               {{ !empty($settings['announcement_enabled']) && $settings['announcement_enabled'] == '1' ? 'checked' : '' }}
                               class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500">
                        <label for="announcement_enabled" class="text-sm font-medium text-gray-800 cursor-pointer">
                            Enable Announcement Bar on frontend
                        </label>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Announcement Message</label>
                        <input type="text" name="announcement_text" value="{{ old('announcement_text', $settings['announcement_text'] ?? 'Free shipping on orders above Rs. 10,000') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 outline-none">
                        <span class="text-[11px] text-gray-400 mt-1 block">Displayed in uppercase with letter-spacing in the plum top bar.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Free Shipping Threshold (in PKR Rs.)</label>
                        <input type="number" name="free_shipping_threshold" value="{{ old('free_shipping_threshold', $settings['free_shipping_threshold'] ?? '10000') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 outline-none">
                        <span class="text-[11px] text-gray-400 mt-1 block">Used by cart progress bar to show "Add Rs. X more for free shipping".</span>
                    </div>
                </div>

                <div class="flex justify-end pt-6">
                    <button type="submit" class="btn-bloom text-white px-8 py-3 rounded-xl text-sm font-medium hover:opacity-90 transition">
                        Save Announcement Settings
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ==================== TAB 4: BANK DETAILS ==================== --}}
    <div x-show="tab === 'bank'" x-cloak>
        <form action="{{ route('admin.cms.update') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="_tab" value="bank">

            <div class="bg-white rounded-2xl p-6 soft max-w-3xl">
                <div class="border-b border-gray-100 pb-3 mb-5">
                    <h2 class="text-base font-bold text-ink flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span>
                        Bank Transfer Details
                    </h2>
                    <p class="text-xs text-gray-400">Shown to customers who choose Direct Bank Transfer during checkout.</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Bank Name</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $settings['bank_name'] ?? '') }}" placeholder="e.g. Meezan Bank LTD" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Account Title</label>
                        <input type="text" name="bank_account_title" value="{{ old('bank_account_title', $settings['bank_account_title'] ?? '') }}" placeholder="e.g. G. Collection" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Account Number</label>
                        <input type="text" name="bank_account_no" value="{{ old('bank_account_no', $settings['bank_account_no'] ?? '') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">IBAN Number</label>
                        <input type="text" name="bank_iban" value="{{ old('bank_iban', $settings['bank_iban'] ?? '') }}" placeholder="PK..." class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:border-brand-500 outline-none">
                    </div>
                </div>

                <div class="flex justify-end pt-6">
                    <button type="submit" class="btn-bloom text-white px-8 py-3 rounded-xl text-sm font-medium hover:opacity-90 transition">
                        Save Bank Details
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection
