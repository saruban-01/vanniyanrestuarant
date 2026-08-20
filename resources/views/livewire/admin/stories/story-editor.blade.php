<div>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <a href="{{ route('admin.stories') }}" class="text-gray-500 hover:text-gray-900 text-sm font-medium mb-2 inline-flex items-center">
                &larr; Back to Stories
            </a>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">
                {{ $story ? 'Edit Story' : 'New Story' }}
            </h1>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="save" class="px-6 py-2 bg-vanniyan-green-900 text-white rounded text-sm font-bold uppercase tracking-wider hover:bg-vanniyan-green-800 transition-colors shadow-sm">
                Save Story
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-32">
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Details -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 uppercase tracking-wide mb-6">Story Details</h2>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Title *</label>
                        <input type="text" wire:model.live.debounce.500ms="title" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Slug *</label>
                        <input type="text" wire:model="slug" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">URL path: /our-stories/<strong>{{ $slug }}</strong></p>
                        @error('slug') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Category</label>
                            <input type="text" wire:model="category" placeholder="e.g. History, Ingredients" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                            @error('category') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Reading Time (Mins) *</label>
                            <input type="number" wire:model="reading_time_minutes" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                            @error('reading_time_minutes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Excerpt</label>
                        <textarea wire:model="excerpt" rows="3" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm"></textarea>
                        @error('excerpt') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 uppercase tracking-wide mb-6">Main Content</h2>
                
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Content (Markdown/HTML) *</label>
                    <textarea wire:model="content" rows="15" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm font-mono"></textarea>
                    @error('content') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <!-- Advanced Blocks -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 uppercase tracking-wide mb-6">Advanced Blocks (JSON)</h2>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Content Blocks</label>
                        <textarea wire:model="blocks_json" rows="10" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm font-mono" placeholder="[{&quot;type&quot;: &quot;image&quot;, &quot;url&quot;: &quot;...&quot;}]"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Sources / References</label>
                        <textarea wire:model="sources_json" rows="5" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm font-mono" placeholder="[{&quot;title&quot;: &quot;...&quot;, &quot;url&quot;: &quot;...&quot;}]"></textarea>
                    </div>
                </div>
            </div>

            <!-- Publishing & SEO -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                <div class="p-6 md:p-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 uppercase tracking-widest text-xs">4. Publishing & SEO</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-6 rounded-xl border border-gray-200 mb-8">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" wire:model="is_published" class="w-5 h-5 text-vanniyan-green-900 rounded border-gray-300 focus:ring-vanniyan-green-900">
                            <span class="font-bold text-gray-900">Published (Visible on site)</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" wire:model="is_featured" class="w-5 h-5 text-vanniyan-gold rounded border-gray-300 focus:ring-vanniyan-gold">
                            <span class="font-bold text-gray-900">Featured Story</span>
                        </label>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Meta Title</label>
                                <input type="text" wire:model="meta_title" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900">
                                <p class="mt-1 text-[10px] text-gray-400">Leave blank to use Story Title + Default Site Title.</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Meta Description</label>
                                <textarea wire:model="meta_description" rows="2" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900"></textarea>
                                <p class="mt-1 text-[10px] text-gray-400">Leave blank to use Story Excerpt.</p>
                            </div>
                        </div>

                        <div class="mb-6 pb-6 border-b border-gray-100">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Canonical URL</label>
                            <input type="text" wire:model="canonical_url" placeholder="https://..." class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                            <p class="mt-1 text-[10px] text-gray-400">Leave blank to use default story URL.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">OG Title</label>
                                <input type="text" wire:model="og_title" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">OG Image URL</label>
                                <input type="text" wire:model="og_image" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                                <p class="mt-1 text-[10px] text-gray-400">Leave blank to use Cover Image.</p>
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
        </div>

        <div class="space-y-6">
            <!-- Settings -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 uppercase tracking-wide mb-6">Settings</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input id="is_published" type="checkbox" wire:model="is_published" class="h-4 w-4 text-vanniyan-green-900 focus:ring-vanniyan-green-900 border-gray-300 rounded">
                        <label for="is_published" class="ml-2 block text-sm text-gray-900 font-medium">
                            Published (Visible to public)
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input id="is_featured" type="checkbox" wire:model="is_featured" class="h-4 w-4 text-vanniyan-green-900 focus:ring-vanniyan-green-900 border-gray-300 rounded">
                        <label for="is_featured" class="ml-2 block text-sm text-gray-900 font-medium">
                            Featured Story
                        </label>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Display Order</label>
                        <input type="number" wire:model="order" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        @error('order') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Image -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 uppercase tracking-wide mb-6">Cover Image</h2>
                <div>
                    <input type="text" wire:model="image" placeholder="https://..." class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                    <p class="mt-1 text-xs text-gray-500">URL to the main cover image.</p>
                </div>
                @if($image)
                    <div class="mt-4 rounded-lg overflow-hidden border border-gray-200 aspect-[4/3]">
                        <img src="{{ $image }}" class="w-full h-full object-cover">
                    </div>
                @endif
            </div>
            
            <!-- QR Generation -->
            @if($story)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 uppercase tracking-wide mb-6">QR Code</h2>
                <p class="text-sm text-gray-600 mb-4">Print this QR code to place near restaurant artwork.</p>
                
                @php
                    $url = urlencode(route('our-stories.show', $story->slug));
                    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={$url}";
                @endphp
                
                <div class="flex justify-center mb-4">
                    <img src="{{ $qrUrl }}" alt="QR Code" class="w-48 h-48 border border-gray-200 rounded-lg p-2 bg-white">
                </div>
                
                <a href="{{ $qrUrl }}" target="_blank" download="Vanniyan-QR-{{ $story->slug }}.png" class="block w-full text-center px-4 py-2 bg-gray-100 text-gray-700 rounded text-sm font-bold uppercase tracking-wider hover:bg-gray-200 transition-colors shadow-sm">
                    Download QR Code
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
