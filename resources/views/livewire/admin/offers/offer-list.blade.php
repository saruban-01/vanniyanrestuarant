<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Offers</h1>
        <a href="{{ route('admin.offers.create') }}" class="bg-vanniyan-green-900 text-white px-4 py-2 rounded shadow font-bold text-sm hover:bg-vanniyan-green-800 transition-colors">
            + New Offer
        </a>
    </div>

    <!-- Filters & Tabs -->
    <div class="bg-white rounded-t-xl shadow-sm border border-gray-200 border-b-0 p-4 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex gap-2 bg-gray-100 p-1 rounded-lg">
            @foreach(['ALL', 'DRAFT', 'SCHEDULED', 'ACTIVE', 'EXPIRED'] as $t)
                <button wire:click="$set('tab', '{{ $t }}')" 
                        class="px-4 py-1.5 rounded-md text-sm font-bold transition-colors {{ $tab === $t ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700' }}">
                    {{ $t }}
                </button>
            @endforeach
        </div>
        
        <div class="w-full sm:w-64">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search offers..." class="w-full border-gray-300 rounded-lg text-sm shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900">
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-b-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="p-4 font-bold">Offer</th>
                    <th class="p-4 font-bold">Type</th>
                    <th class="p-4 font-bold">Status</th>
                    <th class="p-4 font-bold">Validity</th>
                    <th class="p-4 font-bold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($offers as $offer)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded bg-gray-100 shrink-0 overflow-hidden">
                                    @if($offer->image_url)
                                        <img src="{{ $offer->image_url }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $offer->title }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $offer->is_featured ? 'Featured • ' : '' }}
                                        {{ $offer->is_dine_in ? 'Dine-In' : '' }}
                                        {{ $offer->is_takeaway ? ($offer->is_dine_in ? ' & Takeaway' : 'Takeaway') : '' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="text-sm text-gray-600 capitalize">{{ str_replace('_', ' ', $offer->type) }}</span>
                            <div class="font-bold text-gray-900 text-sm mt-0.5">{{ $offer->price_or_discount }}</div>
                        </td>
                        <td class="p-4">
                            @if(!$offer->is_published)
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-bold uppercase tracking-wider">Draft</span>
                            @elseif($offer->valid_until && $offer->valid_until->isPast())
                                <span class="bg-red-50 text-red-600 px-2 py-1 rounded text-xs font-bold uppercase tracking-wider">Expired</span>
                            @elseif($offer->valid_from && $offer->valid_from->isFuture())
                                <span class="bg-yellow-50 text-yellow-600 px-2 py-1 rounded text-xs font-bold uppercase tracking-wider">Scheduled</span>
                            @else
                                <span class="bg-green-50 text-green-600 px-2 py-1 rounded text-xs font-bold uppercase tracking-wider">Active</span>
                            @endif
                        </td>
                        <td class="p-4 text-sm text-gray-600">
                            <div>From: {{ $offer->valid_from ? $offer->valid_from->format('Y-m-d H:i') : 'Always' }}</div>
                            <div>Until: {{ $offer->valid_until ? $offer->valid_until->format('Y-m-d H:i') : 'Forever' }}</div>
                        </td>
                        <td class="p-4 text-right space-x-3">
                            <a href="{{ route('admin.offers.edit', $offer) }}" class="text-vanniyan-gold hover:text-yellow-700 font-medium text-sm">Edit</a>
                            <button wire:click="delete({{ $offer->id }})" wire:confirm="Are you sure?" class="text-red-500 hover:text-red-700 font-medium text-sm">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-500">
                            No offers found matching your criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($offers->hasPages())
            <div class="p-4 border-t border-gray-200">
                {{ $offers->links() }}
            </div>
        @endif
    </div>
</div>
