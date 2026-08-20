<div>
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">Notifications</h1>
            <p class="text-gray-600">Recent alerts, orders, and system updates.</p>
        </div>
        @if($notifications->count() > 0)
        <button class="px-4 py-2 bg-white border border-gray-300 rounded text-xs font-bold text-gray-700 uppercase tracking-wider hover:bg-gray-50 transition-colors">
            Mark All As Read
        </button>
        @endif
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        @if($notifications->isEmpty())
            <div class="text-center py-16 px-6">
                <div class="mx-auto h-12 w-12 text-gray-300 mb-4">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 mb-1 uppercase tracking-wider">ALL CAUGHT UP</h3>
                <p class="text-xs text-gray-500">You don't have any notifications.</p>
            </div>
        @else
            <ul class="divide-y divide-gray-100">
                @foreach($notifications as $notification)
                    <li class="p-6 {{ $notification->is_read ? 'bg-white' : 'bg-green-50' }} hover:bg-gray-50 transition-colors flex items-start justify-between">
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-wider mb-1 {{ $notification->is_read ? 'text-gray-500' : 'text-vanniyan-green-900' }}">{{ $notification->type }}</span>
                            <span class="block font-medium text-gray-900 text-sm mb-1">{{ $notification->title }}</span>
                            <span class="block text-sm text-gray-600 mb-2">{{ $notification->message }}</span>
                            <span class="block text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="ml-4 flex-shrink-0">
                            @if(!$notification->is_read)
                            <button class="text-xs font-bold text-vanniyan-gold hover:text-yellow-600 uppercase tracking-wider">Mark Read</button>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
