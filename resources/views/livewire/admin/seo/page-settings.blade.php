<div>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">Static Page SEO</h1>
            <p class="text-gray-500 text-sm">Manage SEO metadata for core pages. Dynamic pages (Events, Stories) are managed in their respective editors.</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if($editingRoute)
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-8 max-w-4xl">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h2 class="font-bold text-gray-900 uppercase tracking-wider text-sm">Editing: {{ $staticPages[$editingRoute] }}</h2>
                <button wire:click="cancelEdit" class="text-gray-400 hover:text-gray-600">&times; Cancel</button>
            </div>
            <form wire:submit="save" class="p-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Meta Title</label>
                        <input type="text" wire:model="meta_title" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        <p class="mt-1 text-[10px] text-gray-400">Leave blank to generate automatically.</p>
                        @error('meta_title') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Meta Description</label>
                        <textarea wire:model="meta_description" rows="3" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm"></textarea>
                        @error('meta_description') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-6 pb-6 border-b border-gray-100">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Canonical URL</label>
                    <input type="text" wire:model="canonical_url" placeholder="https://..." class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                    <p class="mt-1 text-[10px] text-gray-400">Leave blank to use current request URL.</p>
                    @error('canonical_url') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="md:col-span-2">
                        <h3 class="font-bold text-gray-900 uppercase tracking-wider text-xs mb-4">Social Media (Open Graph)</h3>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">OG Title</label>
                        <input type="text" wire:model="og_title" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        <p class="mt-1 text-[10px] text-gray-400">Leave blank to use Meta Title.</p>
                        @error('og_title') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">OG Image</label>
                        <input type="text" wire:model="og_image" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        <p class="mt-1 text-[10px] text-gray-400">Leave blank to use Global Default.</p>
                        @error('og_image') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">OG Description</label>
                        <textarea wire:model="og_description" rows="2" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm"></textarea>
                        @error('og_description') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pt-6 border-t border-gray-100">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Robots</label>
                        <select wire:model="robots" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                            <option value="index, follow">Index, Follow (Default)</option>
                            <option value="noindex, follow">NoIndex, Follow</option>
                            <option value="noindex, nofollow">NoIndex, NoFollow</option>
                        </select>
                        @error('robots') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        @if($robots !== 'index, follow')
                            <p class="mt-2 text-xs text-yellow-600 font-bold flex gap-1 items-start">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                This page will not be indexed by search engines.
                            </p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Schema Type (JSON-LD)</label>
                        <select wire:model="schema_type" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                            <option value="">None / Default</option>
                            <option value="Restaurant">Restaurant (Local Business)</option>
                        </select>
                        @error('schema_type') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex gap-3">
                    <button type="submit" class="px-6 py-2 bg-vanniyan-green-900 hover:bg-vanniyan-green-800 text-white rounded text-xs font-bold uppercase tracking-wider shadow-sm transition-colors">
                        Save SEO Settings
                    </button>
                    <button type="button" wire:click="cancelEdit" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded text-xs font-bold uppercase tracking-wider shadow-sm transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-bold">
                <tr>
                    <th class="px-6 py-4">Page</th>
                    <th class="px-6 py-4">Title Status</th>
                    <th class="px-6 py-4">Robots</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($staticPages as $route => $name)
                    @php
                        $data = $metadata[$route] ?? null;
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $name }}</div>
                            <div class="text-xs text-gray-500 font-mono mt-1">{{ $route }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($data && $data->meta_title)
                                <span class="text-green-600 font-bold text-xs uppercase tracking-wider flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Custom Set
                                </span>
                            @else
                                <span class="text-gray-400 font-bold text-xs uppercase tracking-wider">
                                    Using Defaults
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($data && $data->robots !== 'index, follow')
                                <span class="text-red-600 font-bold text-xs uppercase tracking-wider border border-red-200 bg-red-50 px-2 py-0.5 rounded-full">
                                    {{ $data->robots }}
                                </span>
                            @else
                                <span class="text-gray-500 text-xs font-bold uppercase tracking-wider">
                                    Index, Follow
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="editPage('{{ $route }}')" class="text-vanniyan-green-900 hover:text-vanniyan-gold font-bold uppercase tracking-wider text-xs transition-colors">Edit SEO</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
