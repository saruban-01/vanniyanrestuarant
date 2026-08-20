<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Physical Loyalty Card Config</h1>
        <div class="flex gap-4 items-center">
            @if(session()->has('message'))
                <span class="text-sm font-bold text-green-600 bg-green-50 px-3 py-1 rounded">{{ session('message') }}</span>
            @endif
            <a href="{{ route('offers') }}#loyalty-card" target="_blank" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded shadow-sm font-bold text-sm hover:bg-gray-50 transition-colors">
                Preview
            </a>
            <button wire:click="save" class="bg-vanniyan-green-900 text-white px-6 py-2 rounded shadow font-bold text-sm hover:bg-vanniyan-green-800 transition-colors">
                Save Changes
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden divide-y divide-gray-100">
        
        <!-- Section Toggle -->
        <div class="p-6 md:p-8 bg-gray-50">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" wire:model="is_visible" class="w-6 h-6 text-vanniyan-green-900 rounded border-gray-300 focus:ring-vanniyan-green-900">
                <span class="font-bold text-xl text-gray-900">Show Physical Loyalty Card Section on Offers Page</span>
            </label>
        </div>

        <!-- Basic Details -->
        <div class="p-6 md:p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-6 uppercase tracking-widest text-xs">Section Header</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Heading</label>
                    <input type="text" wire:model="heading" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900">
                    @error('heading') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                    <textarea wire:model="description" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900"></textarea>
                </div>
            </div>
        </div>

        <!-- Rewards -->
        <div class="p-6 md:p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-6 uppercase tracking-widest text-xs">The Rewards</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2"><span class="bg-gray-200 px-2 py-1 rounded text-xs">5TH VISIT</span></h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Title (e.g. FREE DRINK)</label>
                            <input type="text" wire:model="visit_5_title" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                            <textarea wire:model="visit_5_reward" rows="2" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2"><span class="bg-gray-200 px-2 py-1 rounded text-xs">10TH VISIT</span></h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Title (e.g. RS. 1,000 FOOD COUPON)</label>
                            <input type="text" wire:model="visit_10_title" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                            <textarea wire:model="visit_10_reward" rows="2" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6 md:p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-6 uppercase tracking-widest text-xs">Content & Terms</h3>
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">How It Works (JSON format)</label>
                    <textarea wire:model="how_it_works" rows="8" class="w-full font-mono text-sm border-gray-300 rounded-lg shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900"></textarea>
                    @error('how_it_works') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Terms (One per line)</label>
                    <textarea wire:model="terms" rows="5" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Call to Action Button Text</label>
                    <input type="text" wire:model="cta_text" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900">
                </div>
            </div>
        </div>
    </div>
</div>
