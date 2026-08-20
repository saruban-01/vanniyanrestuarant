<div>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">Global Settings</h1>
            <p class="text-gray-500 text-sm">Manage contact info, footer content, social links, Google Reviews, and global SEO.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-xs text-gray-500 mr-2 flex flex-col items-end">
                <span>Status: <span class="font-bold {{ $status === 'DRAFT' ? 'text-yellow-600' : 'text-green-600' }}">{{ $status }}</span></span>
                <span class="text-[10px]">Last Published: {{ $lastPublishedAt ?? 'Never' }}</span>
            </div>
            <button wire:click="saveDraft" class="px-4 py-2 bg-white border border-gray-300 rounded text-xs font-bold text-gray-700 uppercase tracking-wider shadow-sm hover:bg-gray-50 transition-colors">
                Save Draft
            </button>
            <button wire:click="publish" class="px-6 py-2 bg-vanniyan-gold text-white rounded text-sm font-bold uppercase tracking-wider hover:bg-yellow-600 transition-colors shadow-sm">
                Publish to Live
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-8 p-4 bg-green-50 text-green-800 text-sm font-medium border border-green-200 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-32">
        <div class="lg:col-span-2 space-y-6">
            <!-- Contact Information -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 uppercase tracking-wide mb-6">Contact Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Phone Number</label>
                        <input type="text" wire:model="content.contact_phone" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Email Address</label>
                        <input type="email" wire:model="content.contact_email" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Physical Address</label>
                        <textarea wire:model="content.contact_address" rows="2" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm"></textarea>
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 uppercase tracking-wide mb-6">Social Media Links</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Instagram URL</label>
                        <input type="url" wire:model="content.social_instagram" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Facebook URL</label>
                        <input type="url" wire:model="content.social_facebook" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">WhatsApp Number (with country code)</label>
                        <input type="text" wire:model="content.social_whatsapp" placeholder="e.g. 94771234567" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        <p class="mt-1 text-xs text-gray-400">Digits only, no + or spaces. Used for the WhatsApp chat link.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">TikTok URL</label>
                        <input type="url" wire:model="content.social_tiktok" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 uppercase tracking-wide mb-6">Footer Settings</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Footer Description</label>
                        <textarea wire:model="content.footer_text" rows="3" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Copyright Text</label>
                        <input type="text" wire:model="content.footer_copyright" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        <p class="mt-1 text-[10px] text-gray-500">The current year is automatically prepended.</p>
                    </div>
                </div>
            </div>
        <!-- Google Reviews -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 uppercase tracking-wide mb-6">Google Reviews</h2>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" wire:model="content.google_reviews_enabled" value="1" class="h-4 w-4 rounded border-gray-300 text-vanniyan-green-900 focus:ring-vanniyan-green-900">
                        <label class="text-sm font-bold text-gray-700">Enable Google Reviews section on homepage</label>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Section Heading</label>
                        <input type="text" wire:model="content.google_reviews_heading" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Supporting Text</label>
                        <textarea wire:model="content.google_reviews_subtitle" rows="2" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Google Place ID</label>
                        <input type="text" wire:model="content.google_reviews_place_id" placeholder="e.g. ChIJxxxxxxxxxxxxxxxx" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">Used when GOOGLE_PLACE_ID is not set in .env. Review text and ratings always come from Google — they cannot be edited here.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Read All Reviews URL (Google Maps listing)</label>
                        <input type="url" wire:model="content.google_reviews_url" placeholder="https://www.google.com/maps/place/..." class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Write a Review URL (Google review flow)</label>
                        <input type="url" wire:model="content.google_reviews_write_url" placeholder="https://search.google.com/local/writereview?placeid=..." class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Reviews to Display</label>
                            <input type="number" min="1" max="5" wire:model="content.google_reviews_count" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Cache Duration (minutes)</label>
                            <input type="number" min="1" wire:model="content.google_reviews_cache_minutes" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        </div>
                    </div>
                    <div class="p-4 bg-vanniyan-green-900/5 border border-vanniyan-green-900/20 rounded-lg">
                        <p class="text-xs text-gray-600 leading-relaxed">
                            <span class="font-bold text-vanniyan-green-900 uppercase tracking-wider">Security</span><br>
                            The Google API key is stored only in <code class="bg-gray-100 px-1 rounded">.env</code>
                            (<code class="bg-gray-100 px-1 rounded">GOOGLE_MAPS_API_KEY</code>) and is never editable here or exposed to the browser.
                            If the API is unavailable, the homepage shows a simple "Read Google Reviews" fallback — it never breaks.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Global SEO -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 uppercase tracking-wide mb-6">Global SEO Default</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Default Title</label>
                        <input type="text" wire:model="seoMeta.title" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Default Description</label>
                        <textarea wire:model="seoMeta.description" rows="4" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
