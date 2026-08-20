<div>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <a href="{{ route('admin.menu') }}" class="text-gray-500 hover:text-gray-900 text-sm font-medium mb-2 inline-flex items-center">
                &larr; Back to Menu
            </a>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">
                {{ $category ? 'Edit Category' : 'New Category' }}
            </h1>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="save" class="px-6 py-2 bg-vanniyan-green-900 text-white rounded text-sm font-bold uppercase tracking-wider hover:bg-vanniyan-green-800 transition-colors shadow-sm">
                Save Category
            </button>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8 max-w-2xl">
        <form wire:submit="save" class="space-y-6">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Category Name *</label>
                <input type="text" wire:model="name" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Description</label>
                <textarea wire:model="description" rows="3" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm"></textarea>
                <p class="mt-1 text-xs text-gray-500">Optional description displayed below the category name.</p>
                @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Sort Order</label>
                <input type="number" wire:model="sort_order" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                <p class="mt-1 text-xs text-gray-500">Lower numbers appear first (e.g., 10, 20, 30).</p>
                @error('sort_order') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </form>
    </div>
</div>
