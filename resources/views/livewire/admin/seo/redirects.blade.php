<div>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">URL Redirects</h1>
            <p class="text-gray-500 text-sm">Manage 301/302 redirects for old URLs to preserve SEO ranking.</p>
        </div>
        <div>
            <button wire:click="create" class="px-4 py-2 bg-vanniyan-green-900 hover:bg-vanniyan-green-800 text-white rounded text-xs font-bold uppercase tracking-wider shadow-sm transition-colors">
                + Add Redirect
            </button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if($editingId)
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-8 max-w-4xl">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h2 class="font-bold text-gray-900 uppercase tracking-wider text-sm">{{ $editingId === 'new' ? 'Create Redirect' : 'Edit Redirect' }}</h2>
                <button wire:click="cancel" class="text-gray-400 hover:text-gray-600">&times; Cancel</button>
            </div>
            <form wire:submit="save" class="p-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Old Path</label>
                        <input type="text" wire:model="old_path" placeholder="e.g. old-menu-page" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        <p class="mt-1 text-[10px] text-gray-400">Without leading slash.</p>
                        @error('old_path') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">New Path (Destination)</label>
                        <input type="text" wire:model="new_path" placeholder="e.g. /menu" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        <p class="mt-1 text-[10px] text-gray-400">Can be relative (/menu) or absolute (https://...).</p>
                        @error('new_path') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Status Code</label>
                        <select wire:model="status_code" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                            <option value="301">301 Moved Permanently (SEO Recommended)</option>
                            <option value="302">302 Found (Temporary)</option>
                        </select>
                        @error('status_code') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center pt-6">
                        <input id="is_active" type="checkbox" wire:model="is_active" class="h-4 w-4 text-vanniyan-green-900 focus:ring-vanniyan-green-900 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900 font-medium">Active</label>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex gap-3">
                    <button type="submit" class="px-6 py-2 bg-vanniyan-green-900 hover:bg-vanniyan-green-800 text-white rounded text-xs font-bold uppercase tracking-wider shadow-sm transition-colors">
                        Save Redirect
                    </button>
                    <button type="button" wire:click="cancel" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded text-xs font-bold uppercase tracking-wider shadow-sm transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-4">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search redirects..." class="block w-64 border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
        </div>
        
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-bold">
                <tr>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Old Path</th>
                    <th class="px-6 py-4">Destination</th>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($redirects as $redirect)
                    <tr class="hover:bg-gray-50 transition-colors {{ !$redirect->is_active ? 'opacity-60' : '' }}">
                        <td class="px-6 py-4">
                            <button wire:click="toggleActive({{ $redirect->id }})" class="focus:outline-none">
                                @if($redirect->is_active)
                                    <span class="text-green-600 font-bold text-[10px] uppercase tracking-wider flex items-center gap-1 bg-green-50 px-2 py-1 rounded border border-green-200">Active</span>
                                @else
                                    <span class="text-gray-500 font-bold text-[10px] uppercase tracking-wider flex items-center gap-1 bg-gray-100 px-2 py-1 rounded border border-gray-200">Inactive</span>
                                @endif
                            </button>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-gray-900">/{{ $redirect->old_path }}</td>
                        <td class="px-6 py-4 font-mono text-xs text-gray-500">&rarr; {{ $redirect->new_path }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold text-gray-500">{{ $redirect->status_code }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="edit({{ $redirect->id }})" class="text-vanniyan-green-900 hover:text-vanniyan-gold font-bold uppercase tracking-wider text-xs transition-colors mr-3">Edit</button>
                            <button wire:confirm="Are you sure you want to delete this redirect?" wire:click="delete({{ $redirect->id }})" class="text-red-600 hover:text-red-800 font-bold uppercase tracking-wider text-xs transition-colors">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            No redirects found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div>
        {{ $redirects->links() }}
    </div>
</div>
