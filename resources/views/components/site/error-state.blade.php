@props(['title' => 'We Couldn\'t Load This Content', 'description' => 'Please try again.'])

<div class="w-full bg-red-50 border border-red-100 rounded-lg p-8 text-center flex flex-col items-center justify-center">
    <div class="text-red-400 mb-4">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
    </div>
    <h3 class="text-lg font-bold text-red-900 mb-1">{{ $title }}</h3>
    <p class="text-red-700 text-sm mb-6">{{ $description }}</p>
    
    <button onclick="window.location.reload()" class="px-6 py-2 bg-white text-red-900 border border-red-200 font-bold uppercase tracking-wider text-xs rounded hover:bg-red-100 transition-colors">
        Try Again
    </button>
</div>
