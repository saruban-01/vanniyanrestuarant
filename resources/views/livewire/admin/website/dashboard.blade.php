<div>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">Website CMS</h1>
            <p class="text-gray-600">Manage Vanniyan's public website content.</p>
        </div>
        <a href="/" target="_blank" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded text-sm font-bold uppercase tracking-wider hover:bg-gray-50 transition-colors shadow-sm flex items-center gap-2">
            View Live Website
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
        </a>
    </div>

    <!-- Health Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Published Pages</h3>
            <div class="text-3xl font-serif font-bold text-vanniyan-green-900">{{ $publishedCount }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Draft Changes</h3>
            <div class="text-3xl font-serif font-bold text-yellow-600">{{ $draftCount }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <!-- Pages CMS -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Pages</h2>
                </div>
                <div class="divide-y divide-gray-100">
                    <!-- Homepage -->
                    <div class="p-6 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1">Homepage</h3>
                            <p class="text-xs text-gray-500">Manage Hero, Signature Dishes, Offers, and CTAs.</p>
                        </div>
                        <div class="flex items-center gap-4">
                            @if($pages->where('slug', 'home')->first()?->draftVersion()->exists())
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-[10px] font-bold uppercase tracking-wider rounded">Draft Changes</span>
                            @elseif($pages->where('slug', 'home')->first()?->is_published)
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-[10px] font-bold uppercase tracking-wider rounded">Published</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold uppercase tracking-wider rounded">Unpublished</span>
                            @endif
                            <a href="{{ route('admin.website.home') }}" class="text-sm font-bold text-vanniyan-gold hover:text-yellow-600 uppercase tracking-wider">Edit</a>
                        </div>
                    </div>
                    <!-- Menu -->
                    <div class="p-6 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1">Menu</h3>
                            <p class="text-xs text-gray-500">Manage Categories, Items and Availability.</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <a href="#" class="text-sm font-bold text-vanniyan-gold hover:text-yellow-600 uppercase tracking-wider">Manage</a>
                        </div>
                    </div>
                    <!-- Offers -->
                    <div class="p-6 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1">Our Deals</h3>
                            <p class="text-xs text-gray-500">Manage Specials and Physical Loyalty Card info.</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <a href="#" class="text-sm font-bold text-vanniyan-gold hover:text-yellow-600 uppercase tracking-wider">Manage</a>
                        </div>
                    </div>
                    <!-- Events -->
                    <div class="p-6 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1">Events</h3>
                            <p class="text-xs text-gray-500">Manage Event pages and booking settings.</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <a href="#" class="text-sm font-bold text-vanniyan-gold hover:text-yellow-600 uppercase tracking-wider">Manage</a>
                        </div>
                    </div>
                    <!-- Our Story & Cultural -->
                    <div class="p-6 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1">Our Story & Culture</h3>
                            <p class="text-xs text-gray-500">Manage Cultural Stories and QR code links.</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <a href="#" class="text-sm font-bold text-vanniyan-gold hover:text-yellow-600 uppercase tracking-wider">Manage</a>
                        </div>
                    </div>
                    <!-- Contact -->
                    <div class="p-6 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1">Contact & Info</h3>
                            <p class="text-xs text-gray-500">Manage Hero, FAQs.</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <a href="#" class="text-sm font-bold text-vanniyan-gold hover:text-yellow-600 uppercase tracking-wider">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Global CMS -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Global Configuration</h2>
                </div>
                <div class="divide-y divide-gray-100">
                    <a href="#" class="block p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-gray-900 text-sm">Navigation</h3>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </a>
                    <a href="#" class="block p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-gray-900 text-sm">Footer</h3>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </a>
                </div>
            </div>

            <!-- System -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">System</h2>
                </div>
                <div class="divide-y divide-gray-100">
                    <a href="{{ route('admin.media') }}" class="block p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-gray-900 text-sm">Media Library</h3>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </a>
                    <a href="#" class="block p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-gray-900 text-sm">Global SEO</h3>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
