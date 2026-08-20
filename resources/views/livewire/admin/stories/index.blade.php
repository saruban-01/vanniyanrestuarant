<div>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">Stories Management</h1>
            <p class="text-gray-500 text-sm">Manage cultural stories and generate QR codes.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.stories.create') }}" class="px-6 py-2 bg-vanniyan-green-900 text-white rounded text-sm font-bold uppercase tracking-wider hover:bg-vanniyan-green-800 transition-colors shadow-sm">
                Add Story
            </a>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-8 p-4 bg-green-50 text-green-800 text-sm font-medium border border-green-200 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        @if($stories->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-bold">
                        <tr>
                            <th class="px-6 py-4">Title & Slug</th>
                            <th class="px-6 py-4">Category</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Featured</th>
                            <th class="px-6 py-4">QR Code</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($stories as $story)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $story->title }}</div>
                                    <div class="text-xs text-gray-500 mt-1">/our-stories/{{ $story->slug }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $story->category ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <button wire:click="togglePublish({{ $story->id }})" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $story->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $story->is_published ? 'Published' : 'Draft' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    <button wire:click="toggleFeatured({{ $story->id }})" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $story->is_featured ? 'bg-vanniyan-gold text-white' : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }}">
                                        &starf;
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $url = urlencode(route('our-stories.show', $story->slug));
                                        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={$url}";
                                    @endphp
                                    <a href="{{ $qrUrl }}" target="_blank" class="text-vanniyan-green-900 hover:text-vanniyan-gold text-xs font-bold uppercase flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                        QR
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-right text-xs font-medium">
                                    <a href="{{ route('admin.stories.edit', $story) }}" class="text-vanniyan-green-900 hover:text-vanniyan-gold mr-3">Edit</a>
                                    <button wire:click="deleteStory({{ $story->id }})" wire:confirm="Are you sure you want to delete this story?" class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <h3 class="text-lg font-serif font-bold text-gray-900 mb-2">No Stories Found</h3>
                <p class="text-gray-500 mb-6">Create cultural stories to display on the website and QR codes.</p>
                <a href="{{ route('admin.stories.create') }}" class="px-6 py-2 bg-vanniyan-green-900 text-white rounded text-sm font-bold uppercase tracking-wider hover:bg-vanniyan-green-800 transition-colors shadow-sm inline-block">
                    Add Story
                </a>
            </div>
        @endif
    </div>
</div>
