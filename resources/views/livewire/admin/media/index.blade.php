<div>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center">
        <div>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">Media Library</h1>
            <p class="text-gray-600">Centralized image and media management.</p>
        </div>
    </div>

    <!-- Upload Section -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-8">
        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Upload New Media</h3>
        <form wire:submit="saveMedia" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Select Image</label>
                <input type="file" wire:model="upload" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-vanniyan-green-50 file:text-vanniyan-green-900 hover:file:bg-vanniyan-green-100 border border-gray-300 rounded-md p-1">
                @error('upload') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div class="flex-1 w-full">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Alt Text (Required for Accessibility)</label>
                <input type="text" wire:model="alt_text" placeholder="Description of the image..." class="appearance-none block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                @error('alt_text') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <button type="submit" class="px-6 py-2 bg-vanniyan-green-900 text-white rounded text-sm font-bold uppercase tracking-wider hover:bg-vanniyan-green-800 transition-colors shadow-sm" wire:loading.attr="disabled">
                    Upload
                </button>
            </div>
        </form>
        @if (session()->has('message'))
            <div class="mt-4 p-3 bg-green-50 text-green-800 text-sm rounded-md font-medium">
                {{ session('message') }}
            </div>
        @endif
    </div>

    <!-- Media Grid -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
        <div class="mb-6">
            <input type="text" wire:model.live="search" placeholder="Search media..." class="w-full max-w-sm px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
        </div>

        @if($media->isEmpty())
            <div class="text-center py-16">
                <p class="text-sm text-gray-500 uppercase font-bold tracking-wider">No media found.</p>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($media as $item)
                    <div class="group relative border border-gray-200 rounded-lg overflow-hidden bg-gray-50 aspect-square">
                        <img src="{{ $item->url }}" alt="{{ $item->alt_text }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all flex flex-col items-center justify-center opacity-0 group-hover:opacity-100">
                            <span class="text-white text-xs font-bold text-center px-2 truncate w-full">{{ $item->filename }}</span>
                            <button class="mt-2 text-xs font-bold bg-white text-red-600 px-3 py-1 rounded">Delete</button>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $media->links() }}
            </div>
        @endif
    </div>
</div>
