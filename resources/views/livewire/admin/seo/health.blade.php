<div>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">SEO Health Dashboard</h1>
            <p class="text-gray-500 text-sm">Automated diagnostic checks for the website's technical SEO foundation.</p>
        </div>
        <div>
            <button wire:click="runChecks" class="px-4 py-2 bg-white border border-gray-300 rounded text-xs font-bold uppercase tracking-wider text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                Run Diagnostics &rarr;
            </button>
        </div>
    </div>

    @if(empty($issues))
        <div class="bg-green-50 border border-green-200 rounded-xl p-8 text-center shadow-sm">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 text-green-600 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h2 class="text-xl font-bold text-green-900 mb-2">Excellent SEO Health!</h2>
            <p class="text-green-700 text-sm">No critical issues or warnings detected across your configured settings and published content.</p>
        </div>
    @else
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-bold text-gray-900 uppercase tracking-wider text-sm flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-600 text-xs">{{ count($issues) }}</span>
                    Detected Issues
                </h2>
            </div>
            <ul class="divide-y divide-gray-100">
                @foreach($issues as $issue)
                    <li class="p-6 hover:bg-gray-50 transition-colors flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <div class="flex items-start gap-4">
                            @if($issue['type'] === 'critical')
                                <div class="mt-0.5 shrink-0 w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                            @elseif($issue['type'] === 'warning')
                                <div class="mt-0.5 shrink-0 w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                            @else
                                <div class="mt-0.5 shrink-0 w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            @endif
                            
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">{{ $issue['message'] }}</h3>
                                <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider font-bold">{{ ucfirst($issue['type']) }}</p>
                            </div>
                        </div>
                        
                        <div class="shrink-0">
                            <a href="{{ $issue['action'] }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded text-[10px] font-bold uppercase tracking-wider text-gray-700 hover:bg-gray-50 transition-colors">
                                {{ $issue['action_text'] }}
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
