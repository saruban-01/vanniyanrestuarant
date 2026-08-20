<div>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">Table Management</h1>
            <p class="text-gray-500 text-sm">Manage physical restaurant tables and capacities.</p>
        </div>
        <button wire:click="createTable" class="px-6 py-2 bg-vanniyan-green-900 text-white rounded text-sm font-bold uppercase tracking-wider hover:bg-vanniyan-green-800 transition-colors shadow-sm">
            + New Table
        </button>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @foreach($tables as $table)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col transition-shadow hover:shadow-md">
                <div class="p-6 flex-grow flex flex-col items-center justify-center text-center border-b border-gray-100">
                    <div class="w-16 h-16 bg-gray-50 border-2 {{ $table->is_active ? 'border-vanniyan-green-900 text-vanniyan-green-900' : 'border-gray-300 text-gray-400' }} rounded-full flex items-center justify-center text-xl font-bold mb-3 shadow-sm">
                        {{ $table->table_number }}
                    </div>
                    <h3 class="font-bold text-gray-900">Table {{ $table->table_number }}</h3>
                    <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider">{{ $table->location ?? 'Main Floor' }}</p>
                    <div class="mt-4 flex items-center gap-2 text-sm text-gray-700">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Up to {{ $table->capacity }} guests
                    </div>
                </div>
                <div class="bg-gray-50 p-4 flex justify-between items-center">
                    @if($table->is_active)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold text-green-800 bg-green-100 uppercase tracking-wider border border-green-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Active
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold text-gray-600 bg-gray-100 uppercase tracking-wider border border-gray-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactive
                        </span>
                    @endif
                    
                    <button wire:click="editTable({{ $table->id }})" class="text-xs font-bold text-vanniyan-green-900 hover:text-vanniyan-gold uppercase tracking-wider underline transition-colors">
                        Edit
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Table Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-serif font-bold text-gray-900 text-lg uppercase tracking-wide">{{ $tableId ? 'Edit Table' : 'Add New Table' }}</h3>
                <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form wire:submit.prevent="saveTable" class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Table Number / Name <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="tableNumber" placeholder="e.g. 12, Window 1" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                    @error('tableNumber') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Capacity <span class="text-red-500">*</span></label>
                    <input type="number" wire:model="capacity" min="1" placeholder="e.g. 4" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                    @error('capacity') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Location / Zone</label>
                    <input type="text" wire:model="location" placeholder="e.g. Main Dining, Patio" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                    @error('location') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="pt-2">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="isActive" class="rounded border-gray-300 text-vanniyan-green-900 shadow-sm focus:border-vanniyan-green-900 focus:ring focus:ring-vanniyan-green-900 focus:ring-opacity-50 h-4 w-4">
                        <span class="ml-2 text-sm text-gray-700 font-bold">Table is Active and Bookable</span>
                    </label>
                </div>

                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-md text-sm font-bold uppercase tracking-wider hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-vanniyan-green-900 text-white rounded-md text-sm font-bold uppercase tracking-wider hover:bg-vanniyan-green-800">Save Table</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
