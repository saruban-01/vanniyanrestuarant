<div>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">Menu Management</h1>
            <p class="text-gray-500 text-sm">Manage categories and menu items.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.menu.category.create') }}" class="px-4 py-2 bg-white border border-gray-300 rounded text-xs font-bold text-gray-700 uppercase tracking-wider shadow-sm hover:bg-gray-50 transition-colors">
                Add Category
            </a>
            <a href="{{ route('admin.menu.item.create') }}" class="px-6 py-2 bg-vanniyan-green-900 text-white rounded text-sm font-bold uppercase tracking-wider hover:bg-vanniyan-green-800 transition-colors shadow-sm">
                Add Menu Item
            </a>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-8 p-4 bg-green-50 text-green-800 text-sm font-medium border border-green-200 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-8 p-4 bg-red-50 text-red-800 text-sm font-medium border border-red-200 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="space-y-8 pb-32">
        @forelse($categories as $category)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-serif font-bold text-vanniyan-green-900 uppercase tracking-wider">{{ $category->name }}</h2>
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-medium text-gray-500">{{ $category->items->count() }} items</span>
                        <a href="{{ route('admin.menu.category.edit', $category) }}" class="text-xs font-bold text-vanniyan-green-900 hover:text-vanniyan-gold uppercase tracking-wider">Edit</a>
                        <button wire:click="deleteCategory({{ $category->id }})" wire:confirm="Delete this category? Its menu items must be removed first." class="text-xs font-bold text-red-600 hover:text-red-900 uppercase tracking-wider">Delete</button>
                    </div>
                </div>
                
                @if($category->items->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="bg-white border-b border-gray-100 text-xs uppercase tracking-wider text-gray-400">
                                <tr>
                                    <th class="px-6 py-3 font-medium">Item Name</th>
                                    <th class="px-6 py-3 font-medium">Price</th>
                                    <th class="px-6 py-3 font-medium">Status</th>
                                    <th class="px-6 py-3 font-medium text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($category->items as $item)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900">{{ $item->name }}</div>
                                            @if($item->is_signature)
                                                <span class="mt-1 inline-block text-[10px] font-bold uppercase tracking-widest text-vanniyan-gold border border-vanniyan-gold px-2 py-0.5 rounded-sm">Signature</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">Rs. {{ number_format($item->price, 2) }}</td>
                                        <td class="px-6 py-4">
                                            <button wire:click="toggleItemStatus({{ $item->id }})" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $item->is_active ? 'Active' : 'Hidden' }}
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 text-right text-xs font-medium">
                                            <a href="{{ route('admin.menu.item.edit', $item) }}" class="text-vanniyan-green-900 hover:text-vanniyan-gold mr-3">Edit</a>
                                            <button wire:click="deleteItem({{ $item->id }})" wire:confirm="Are you sure you want to delete this item?" class="text-red-600 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-6 text-center text-sm text-gray-500">
                        No items in this category yet.
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                <h3 class="text-lg font-serif font-bold text-gray-900 mb-2">No Categories Found</h3>
                <p class="text-gray-500 mb-6">Create your first menu category to get started.</p>
                <a href="{{ route('admin.menu.category.create') }}" class="px-6 py-2 bg-vanniyan-green-900 text-white rounded text-sm font-bold uppercase tracking-wider hover:bg-vanniyan-green-800 transition-colors shadow-sm inline-block">
                    Add Category
                </a>
            </div>
        @endforelse
    </div>
</div>
