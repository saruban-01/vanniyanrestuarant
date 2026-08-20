<div>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">Global SEO Settings</h1>
            <p class="text-gray-500 text-sm">Manage default meta tags, canonical base, and site-wide SEO fallbacks.</p>
        </div>
        <div>
            <a href="{{ route('admin.seo.health') }}" class="px-4 py-2 bg-white border border-gray-300 rounded text-xs font-bold uppercase tracking-wider text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                View SEO Health &rarr;
            </a>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="save" class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 max-w-3xl">
        
        <div class="space-y-6">
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Default Site Title</label>
                <input type="text" wire:model="seo_default_title" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                @error('seo_default_title') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                <p class="mt-1 text-xs text-gray-500">Appended to dynamic pages (e.g., "Mutton Biryani | {Default Site Title}").</p>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Default Meta Description</label>
                <textarea wire:model="seo_default_description" rows="3" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm"></textarea>
                @error('seo_default_description') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                <p class="mt-1 text-xs text-gray-500">Used when a page doesn't have a specific description.</p>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Canonical Base URL</label>
                <input type="text" wire:model="seo_canonical_base" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                @error('seo_canonical_base') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                <p class="mt-1 text-xs text-gray-500">The primary domain (e.g., https://vanniyan.com). Must include https://.</p>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Default Open Graph Image Path</label>
                <input type="text" wire:model="seo_default_og_image" placeholder="media/brand-og.jpg" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                @error('seo_default_og_image') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                <p class="mt-1 text-xs text-gray-500">Path to image in storage. Used when sharing links on social media if a specific image isn't set.</p>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100">
            <button type="submit" class="px-6 py-2 bg-vanniyan-green-900 hover:bg-vanniyan-green-800 text-white rounded text-xs font-bold uppercase tracking-wider shadow-sm transition-colors">
                Save Global Settings
            </button>
        </div>
    </form>
</div>
