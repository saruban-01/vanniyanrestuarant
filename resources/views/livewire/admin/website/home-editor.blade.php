<div>
    <!-- Sticky Header -->
    <div class="sticky top-0 z-40 bg-[#F7F7F5] pb-4 pt-4 border-b border-gray-200 mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">Homepage Editor</h1>
            <div class="flex items-center gap-4 text-xs font-bold uppercase tracking-wider">
                @if($status === 'PUBLISHED')
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded">Published</span>
                @elseif($status === 'DRAFT')
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded">Draft Changes</span>
                @else
                    <span class="px-2 py-1 bg-gray-200 text-gray-700 rounded">Unpublished</span>
                @endif
                <span class="text-gray-500">Last Published: {{ $lastPublishedAt ?? 'Never' }}</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="/?preview=1" target="_blank" class="px-4 py-2 bg-white border border-gray-300 rounded text-xs font-bold text-gray-700 uppercase tracking-wider shadow-sm hover:bg-gray-50 transition-colors">
                Preview
            </a>
            <button wire:click="saveDraft" class="px-4 py-2 bg-white border border-gray-300 rounded text-xs font-bold text-gray-700 uppercase tracking-wider shadow-sm hover:bg-gray-50 transition-colors">
                Save Draft
            </button>
            <button wire:click="publish" class="px-6 py-2 bg-vanniyan-green-900 text-white rounded text-sm font-bold uppercase tracking-wider hover:bg-vanniyan-green-800 transition-colors shadow-sm">
                Publish Changes
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-8 p-4 bg-green-50 text-green-800 text-sm font-medium border border-green-200 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Navigation (ScrollSpy concept) -->
        <div class="hidden lg:block lg:col-span-1">
            <nav class="sticky top-32 space-y-2 text-sm font-bold uppercase tracking-wider">
                <a href="#section-hero" class="block p-3 text-vanniyan-green-900 bg-white border border-gray-200 rounded-lg shadow-sm">Hero Section</a>
                <a href="#section-signature" class="block p-3 text-gray-500 hover:text-gray-900 transition-colors">Signature Dishes</a>
                <a href="#section-offers" class="block p-3 text-gray-500 hover:text-gray-900 transition-colors">Featured Offer</a>
                <a href="#section-experience" class="block p-3 text-gray-500 hover:text-gray-900 transition-colors">Experience Links</a>
                <a href="#section-story" class="block p-3 text-gray-500 hover:text-gray-900 transition-colors">Our Story</a>
                <a href="#section-seo" class="block p-3 text-gray-500 hover:text-gray-900 transition-colors">SEO Settings</a>
            </nav>
        </div>

        <!-- Editor Form -->
        <div class="lg:col-span-3 space-y-8 pb-32">

            <!-- Hero Section -->
            <div id="section-hero" class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-serif font-bold text-vanniyan-green-900 uppercase tracking-wider mb-6 border-b border-gray-100 pb-2">Hero Section</h2>
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Eyebrow Text</label>
                        <input type="text" wire:model="content.hero_eyebrow" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Headline (H1)</label>
                        <input type="text" wire:model="content.hero_h1" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Supporting Text</label>
                        <textarea wire:model="content.hero_text" rows="3" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Primary CTA Text</label>
                            <input type="text" wire:model="content.hero_cta_primary_text" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Primary CTA URL</label>
                            <input type="text" wire:model="content.hero_cta_primary_url" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Secondary CTA Text</label>
                            <input type="text" wire:model="content.hero_cta_secondary_text" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Secondary CTA URL</label>
                            <input type="text" wire:model="content.hero_cta_secondary_url" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Signature Dishes Section -->
            <div id="section-signature" class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-serif font-bold text-vanniyan-green-900 uppercase tracking-wider mb-6 border-b border-gray-100 pb-2">Signature Dishes</h2>
                <div class="space-y-4">
                    <p class="text-sm text-gray-500">Select up to 3 signature dishes to feature on the homepage.</p>
                    <select multiple wire:model="content.signature_dishes" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm h-32">
                        @foreach($menuItems as $item)
                            <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->price }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Featured Offer Section -->
            <div id="section-offers" class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-serif font-bold text-vanniyan-green-900 uppercase tracking-wider mb-6 border-b border-gray-100 pb-2">Featured Offer</h2>
                <div class="space-y-4">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Select Offer</label>
                    <select wire:model="content.featured_offer_id" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        <option value="">-- None --</option>
                        @foreach($offers as $offer)
                            <option value="{{ $offer->id }}">{{ $offer->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- SEO Settings -->
            <div id="section-seo" class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-serif font-bold text-vanniyan-green-900 uppercase tracking-wider mb-6 border-b border-gray-100 pb-2">SEO Settings</h2>
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Meta Title</label>
                        <input type="text" wire:model="seoMeta.title" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Meta Description</label>
                        <textarea wire:model="seoMeta.description" rows="3" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm"></textarea>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
