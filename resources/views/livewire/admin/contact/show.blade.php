<div>
    <div class="mb-6">
        <a href="{{ route('admin.contact.messages') }}" class="text-xs font-bold text-gray-500 uppercase tracking-wider hover:text-vanniyan-green-900 transition-colors">&larr; Back to Inbox</a>
    </div>

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900">{{ $message->subject }}</h1>
                @php
                    $statusColors = [
                        'new' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'read' => 'bg-gray-100 text-gray-800 border-gray-200',
                        'replied' => 'bg-green-100 text-green-800 border-green-200',
                        'archived' => 'bg-gray-100 text-gray-400 border-gray-200',
                    ];
                    $color = $statusColors[$message->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $color }}">
                    {{ $message->status }}
                </span>
            </div>
            <p class="text-gray-500 text-sm">Received on {{ $message->created_at->format('d M Y, h:i A') }}</p>
        </div>
        
        <div class="flex items-center gap-3">
            @if($message->status !== 'archived')
                <button wire:click="updateStatus('archived')" class="px-4 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 rounded text-xs font-bold uppercase tracking-wider shadow-sm transition-colors">
                    Archive Message
                </button>
            @endif
            @if($message->status !== 'replied')
                <button wire:click="updateStatus('replied')" class="px-4 py-2 bg-vanniyan-green-900 hover:bg-vanniyan-green-800 text-white rounded text-xs font-bold uppercase tracking-wider shadow-sm transition-colors">
                    Mark as Replied
                </button>
            @endif
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-12">
        
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Message Content -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <div>
                        <div class="font-bold text-gray-900 text-lg">{{ $message->name }}</div>
                        <div class="text-sm text-gray-500 mt-1 flex items-center gap-4">
                            @if($message->email)
                                <a href="mailto:{{ $message->email }}" class="flex items-center gap-1 hover:text-vanniyan-green-900 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    {{ $message->email }}
                                </a>
                            @endif
                            @if($message->phone)
                                <a href="tel:{{ $message->phone }}" class="flex items-center gap-1 hover:text-vanniyan-green-900 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $message->phone }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="p-6 sm:p-8">
                    <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $message->message }}</p>
                </div>
            </div>

            <!-- Note about replies -->
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800 flex gap-3">
                <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p>Replies to customer messages should be sent via email or phone call using the contact information provided above. The "Mark as Replied" button is for internal tracking purposes only.</p>
            </div>

        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            
            <!-- Admin Note -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Internal Admin Note</h2>
                <textarea wire:model="adminNote" rows="6" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-vanniyan-green-900 focus:ring-vanniyan-green-900 mb-3" placeholder="Add a private note about this inquiry (not visible to customer)..."></textarea>
                <button wire:click="saveAdminNote" class="w-full px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded text-xs font-bold uppercase tracking-wider transition-colors shadow-sm">Save Note</button>
            </div>

        </div>
    </div>
</div>
