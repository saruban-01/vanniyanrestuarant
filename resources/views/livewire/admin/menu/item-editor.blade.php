<div>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <a href="{{ route('admin.menu') }}" class="text-gray-500 hover:text-gray-900 text-sm font-medium mb-2 inline-flex items-center">
                &larr; Back to Menu
            </a>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">
                {{ $item ? 'Edit Menu Item' : 'New Menu Item' }}
            </h1>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="save" class="px-6 py-2 bg-vanniyan-green-900 text-white rounded text-sm font-bold uppercase tracking-wider hover:bg-vanniyan-green-800 transition-colors shadow-sm">
                Save Item
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 uppercase tracking-wide mb-6">Item Details</h2>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Item Name *</label>
                        <input type="text" wire:model="name" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Description</label>
                        <textarea wire:model="description" rows="4" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm"></textarea>
                        @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Category *</label>
                            <select wire:model="menu_category_id" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('menu_category_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Price (Rs.) *</label>
                            <input type="number" step="0.01" wire:model="price" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                            @error('price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Status & Settings -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 uppercase tracking-wide mb-6">Settings</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input id="is_active" type="checkbox" wire:model="is_active" class="h-4 w-4 text-vanniyan-green-900 focus:ring-vanniyan-green-900 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900 font-medium">
                            Active (Visible on Menu)
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input id="is_signature" type="checkbox" wire:model="is_signature" class="h-4 w-4 text-vanniyan-green-900 focus:ring-vanniyan-green-900 border-gray-300 rounded">
                        <label for="is_signature" class="ml-2 block text-sm text-gray-900 font-medium">
                            Signature Dish
                        </label>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Sort Order</label>
                        <input type="number" wire:model="sort_order" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">Lower numbers appear first.</p>
                        @error('sort_order') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Image (Optional placeholder for phase) -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 uppercase tracking-wide mb-6">Image URL</h2>
                <div>
                    <input type="text" wire:model="image_url" placeholder="https://..." class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                    <p class="mt-1 text-xs text-gray-500">Optional external image URL.</p>
                </div>
                @if($image_url)
                    <div class="mt-4 rounded-lg overflow-hidden border border-gray-200 aspect-video">
                        <img src="{{ $image_url }}" class="w-full h-full object-cover">
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>