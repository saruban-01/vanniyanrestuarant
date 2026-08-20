<div>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">Contact Inbox</h1>
            <p class="text-gray-500 text-sm">Manage customer inquiries and messages.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4 items-end justify-between bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <div class="w-full sm:w-64">
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Search Messages</label>
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Name, Email, Subject..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="w-full sm:w-48">
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Status</label>
            <select wire:model.live="status" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                <option value="ALL">All Statuses</option>
                <option value="NEW">New (Unread)</option>
                <option value="READ">Read</option>
                <option value="REPLIED">Replied</option>
                <option value="ARCHIVED">Archived</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-8">
        @if($messages->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-bold">
                        <tr>
                            <th class="px-6 py-4">Sender</th>
                            <th class="px-6 py-4">Subject</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($messages as $message)
                            <tr class="hover:bg-gray-50 transition-colors {{ $message->status === 'new' ? 'bg-yellow-50/30 font-bold text-gray-900' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $message->name }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $message->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="{{ $message->status === 'new' ? 'font-bold text-gray-900' : 'text-gray-700' }} truncate max-w-xs">{{ $message->subject }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm">{{ $message->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $message->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusColors = [
                                            'new' => 'bg-yellow-100 text-yellow-800',
                                            'read' => 'bg-gray-100 text-gray-800',
                                            'replied' => 'bg-green-100 text-green-800',
                                            'archived' => 'bg-gray-100 text-gray-400',
                                        ];
                                        $color = $statusColors[$message->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $color }}">
                                        {{ $message->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-xs font-medium">
                                    <a href="{{ route('admin.contact.show', $message) }}" class="text-vanniyan-green-900 hover:text-vanniyan-gold font-bold uppercase tracking-wider underline">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($messages->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $messages->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                <h3 class="text-lg font-serif font-bold text-gray-900 mb-2">No Messages Found</h3>
                <p class="text-gray-500">No contact messages match your current filters.</p>
            </div>
        @endif
    </div>
</div>
