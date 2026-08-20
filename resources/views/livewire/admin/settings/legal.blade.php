<div>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">Legal Documents</h1>
            <p class="text-gray-500 text-sm">Privacy Policy, Terms &amp; Conditions, governing law and the public sitemap links.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-8 p-4 bg-green-50 text-green-800 text-sm font-medium border border-green-200 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-8 p-4 bg-red-50 text-red-800 text-sm font-medium border border-red-200 rounded-lg">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Review disclaimer -->
    <div class="mb-8 p-5 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-900 leading-relaxed">
        <p class="font-bold mb-1">Before you publish</p>
        <p>This content should be reviewed and approved by the business/legal adviser before production publication. The generated legal documents are website drafts and must be reviewed by an appropriately qualified Sri Lankan legal professional before Vanniyan relies on them as formal legal terms. HTML is sanitised on save — scripts, iframes and event handlers are always removed.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-32">
        <div class="lg:col-span-2 space-y-6">

            <!-- Privacy Policy -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <div class="flex items-center justify-between mb-1">
                    <h2 class="text-lg font-bold text-gray-900">Privacy Policy</h2>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $privacyStatus !== '' && str_starts_with($privacyStatus, 'Published') ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' }}">
                        {{ str_starts_with($privacyStatus, 'Published') ? 'Published' : 'Draft' }}
                    </span>
                </div>
                <p class="text-gray-500 text-sm mb-5">Public at <span class="font-mono text-xs bg-gray-100 px-1.5 py-0.5 rounded">/privacy-policy</span>. Publishing replaces the live version and clears the sitemap cache.</p>

                <div class="mb-4 p-3 bg-gray-50 border border-gray-200 rounded-lg text-xs text-gray-600">
                    <span class="font-bold text-gray-800">Status:</span> {{ $privacyStatus }}
                </div>

                <label class="block text-sm font-bold text-gray-700 mb-2">Content (HTML)</label>
                <textarea wire:model="privacyDraft" rows="18" class="w-full px-4 py-3 border border-gray-300 rounded font-mono text-xs leading-relaxed focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 bg-gray-50"></textarea>
                <p class="text-xs text-gray-500 mt-1">Allowed tags: h2, h3, h4, p, ul, ol, li, strong, em, b, i, u, small, br, a, blockquote, span. Everything else is removed on save.</p>

                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Last updated date</label>
                        <input type="date" wire:model="privacyPublishedAt" class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 bg-gray-50">
                    </div>
                    <div class="flex gap-3">
                        <button wire:click="savePrivacyDraft" class="flex-1 px-4 py-2 bg-white border border-gray-300 rounded text-xs font-bold text-gray-700 uppercase tracking-wider shadow-sm hover:bg-gray-50 transition-colors">
                            Save Draft
                        </button>
                        <button wire:click="publishPrivacy" class="flex-1 px-4 py-2 bg-vanniyan-gold text-white rounded text-xs font-bold uppercase tracking-wider hover:bg-yellow-600 transition-colors shadow-sm">
                            Publish
                        </button>
                    </div>
                </div>
            </div>

            <!-- Terms & Conditions -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <div class="flex items-center justify-between mb-1">
                    <h2 class="text-lg font-bold text-gray-900">Terms &amp; Conditions</h2>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ str_starts_with($termsStatus, 'Published') ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' }}">
                        {{ str_starts_with($termsStatus, 'Published') ? 'Published' : 'Draft' }}
                    </span>
                </div>
                <p class="text-gray-500 text-sm mb-5">Public at <span class="font-mono text-xs bg-gray-100 px-1.5 py-0.5 rounded">/terms-and-conditions</span>. Publishing replaces the live version and clears the sitemap cache.</p>

                <div class="mb-4 p-3 bg-gray-50 border border-gray-200 rounded-lg text-xs text-gray-600">
                    <span class="font-bold text-gray-800">Status:</span> {{ $termsStatus }}
                </div>

                <label class="block text-sm font-bold text-gray-700 mb-2">Content (HTML)</label>
                <textarea wire:model="termsDraft" rows="18" class="w-full px-4 py-3 border border-gray-300 rounded font-mono text-xs leading-relaxed focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 bg-gray-50"></textarea>
                <p class="text-xs text-gray-500 mt-1">Allowed tags: h2, h3, h4, p, ul, ol, li, strong, em, b, i, u, small, br, a, blockquote, span. Everything else is removed on save.</p>

                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Last updated date</label>
                        <input type="date" wire:model="termsPublishedAt" class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 bg-gray-50">
                    </div>
                    <div class="flex gap-3">
                        <button wire:click="saveTermsDraft" class="flex-1 px-4 py-2 bg-white border border-gray-300 rounded text-xs font-bold text-gray-700 uppercase tracking-wider shadow-sm hover:bg-gray-50 transition-colors">
                            Save Draft
                        </button>
                        <button wire:click="publishTerms" class="flex-1 px-4 py-2 bg-vanniyan-gold text-white rounded text-xs font-bold uppercase tracking-wider hover:bg-yellow-600 transition-colors shadow-sm">
                            Publish
                        </button>
                    </div>
                </div>
            </div>

            <!-- Governing Law -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 mb-1">Governing Law</h2>
                <p class="text-gray-500 text-sm mb-5">The jurisdiction shown in the Terms &amp; Conditions document. Confirm the correct jurisdiction with the restaurant owner or legal adviser.</p>
                <div class="flex gap-3 items-end">
                    <div class="flex-1">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jurisdiction</label>
                        <input type="text" wire:model="governingLaw" maxlength="255" placeholder="Sri Lanka" class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 bg-gray-50">
                    </div>
                    <button wire:click="saveGoverningLaw" class="px-6 py-2 bg-vanniyan-green-900 text-white rounded text-xs font-bold uppercase tracking-wider hover:bg-vanniyan-green-800 transition-colors shadow-sm">
                        Save
                    </button>
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Public Pages</h3>
                <ul class="space-y-3 text-sm">
                    <li>
                        <a href="{{ route('privacy-policy') }}" target="_blank" rel="noopener" class="flex items-center justify-between text-gray-700 hover:text-vanniyan-green-900 transition-colors">
                            <span>Privacy Policy</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('terms-and-conditions') }}" target="_blank" rel="noopener" class="flex items-center justify-between text-gray-700 hover:text-vanniyan-green-900 transition-colors">
                            <span>Terms &amp; Conditions</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('sitemap.page') }}" target="_blank" rel="noopener" class="flex items-center justify-between text-gray-700 hover:text-vanniyan-green-900 transition-colors">
                            <span>Sitemap</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('sitemap') }}" target="_blank" rel="noopener" class="flex items-center justify-between text-gray-700 hover:text-vanniyan-green-900 transition-colors">
                            <span>XML sitemap</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Content rules</h3>
                <ul class="text-xs text-gray-600 space-y-2 leading-relaxed">
                    <li>• Content is sanitised on every save — scripts, iframes, objects, embeds and event handlers are always removed.</li>
                    <li>• Only published content is visible to visitors; drafts never appear publicly.</li>
                    <li>• Publishing records the admin and date, and clears the XML sitemap cache.</li>
                    <li>• Contact details on the legal pages come from Restaurant Settings — do not hard-code them in the text.</li>
                </ul>
            </div>
        </div>
    </div>
</div>