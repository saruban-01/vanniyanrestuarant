<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.offers') }}" class="text-gray-500 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">
            {{ $offer ? 'Edit Offer' : 'Create Offer' }}
        </h1>
        <div class="ml-auto flex gap-4">
            <a href="{{ route('offers') }}" target="_blank" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded shadow-sm font-bold text-sm hover:bg-gray-50 transition-colors">
                Preview
            </a>
            <button wire:click="save" class="bg-vanniyan-green-900 text-white px-6 py-2 rounded shadow font-bold text-sm hover:bg-vanniyan-green-800 transition-colors">
                Save Offer
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden divide-y divide-gray-100">
        <!-- Basic Info -->
        <div class="p-6 md:p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-6 uppercase tracking-widest text-xs">1. Basic Info</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Title</label>
                    <input type="text" wire:model.blur="title" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900">
                    @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Slug</label>
                    <input type="text" wire:model="slug" class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-50 focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900">
                    @error('slug') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                    <textarea wire:model="description" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Image URL</label>
                    <input type="text" wire:model="image_url" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900">
                </div>
            </div>
        </div>

        <!-- Offer Details -->
        <div class="p-6 md:p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-6 uppercase tracking-widest text-xs">2. Offer Mechanics</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Type</label>
                    <select wire:model="type" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900">
                        <option value="discount">Discount (e.g. 20% OFF)</option>
                        <option value="free_item">Free Item (e.g. Free Drink)</option>
                        <option value="bundle">Bundle (e.g. Rs. 5000 Meal)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Price / Discount Text</label>
                    <input type="text" wire:model="price_or_discount" placeholder="e.g. 20% OFF or Rs. 1500" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Button Text</label>
                    <input type="text" wire:model="cta_text" placeholder="e.g. Order Now" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Button Link</label>
                    <input type="text" wire:model="cta_url" placeholder="https://... or /menu or /reservation" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900">
                    <p class="text-xs text-gray-500 mt-1">Leave blank to use the automatic button (Order Takeaway / View Menu).</p>
                </div>
            </div>
        </div>

        <!-- Validity & Status -->
        <div class="p-6 md:p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-6 uppercase tracking-widest text-xs">3. Validity & Publishing</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Start Date/Time</label>
                    <input type="datetime-local" wire:model="valid_from" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">End Date/Time</label>
                    <input type="datetime-local" wire:model="valid_until" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-6 rounded-xl border border-gray-200">
                <div class="space-y-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="is_published" class="w-5 h-5 text-vanniyan-green-900 rounded border-gray-300 focus:ring-vanniyan-green-900">
                        <span class="font-bold text-gray-900">Published (Visible if valid dates allow)</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="is_featured" class="w-5 h-5 text-vanniyan-gold rounded border-gray-300 focus:ring-vanniyan-gold">
                        <span class="font-bold text-gray-900">Featured Special</span>
                    </label>
                </div>
                <div class="space-y-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="is_dine_in" class="w-5 h-5 text-vanniyan-green-900 rounded border-gray-300 focus:ring-vanniyan-green-900">
                        <span class="font-medium text-gray-700">Eligible for Dine-In</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="is_takeaway" class="w-5 h-5 text-vanniyan-green-900 rounded border-gray-300 focus:ring-vanniyan-green-900">
                        <span class="font-medium text-gray-700">Eligible for Takeaway</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Terms & SEO -->
        <div class="p-6 md:p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-6 uppercase tracking-widest text-xs">4. Terms & SEO</h3>
            <div class="px-6 py-4 md:px-8 bg-gray-50 border-b border-gray-200">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest">Search Engine Optimization</h3>
            <p class="text-xs text-gray-500 mt-1">Control how this offer appears in search results and social media.</p>
        </div>
        <div class="p-6 md:p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="meta_title" class="block text-xs font-bold text-gray-700 mb-1">Meta Title</label>
                    <input type="text" id="meta_title" wire:model="meta_title" class="w-full rounded-md border-gray-300 shadow-sm focus:border-vanniyan-green-900 focus:ring-vanniyan-green-900 sm:text-sm" placeholder="Leave blank to use Offer Title">
                </div>

                <div class="md:col-span-2">
                    <label for="meta_description" class="block text-xs font-bold text-gray-700 mb-1">Meta Description</label>
                    <textarea id="meta_description" wire:model="meta_description" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-vanniyan-green-900 focus:ring-vanniyan-green-900 sm:text-sm" placeholder="Leave blank to use short description"></textarea>
                </div>
            </div>

            <div class="mb-6 pb-6 border-b border-gray-100">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Canonical URL</label>
                <input type="text" wire:model="canonical_url" placeholder="https://..." class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                <p class="mt-1 text-[10px] text-gray-400">Leave blank to use default URL.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">OG Title</label>
                    <input type="text" wire:model="og_title" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">OG Image URL</label>
                    <input type="text" wire:model="og_image" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                    <p class="mt-1 text-[10px] text-gray-400">Leave blank to use Main Image.</p>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">OG Description</label>
                    <textarea wire:model="og_description" rows="2" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm"></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-100">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Robots</label>
                    <select wire:model="robots" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        <option value="index, follow">Index, Follow (Default)</option>
                        <option value="noindex, follow">NoIndex, Follow</option>
                        <option value="noindex, nofollow">NoIndex, NoFollow</option>
                    </select>
                </div>
            </div>
        </div>

    </div>
</div>
