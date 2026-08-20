<div 
    x-data="{ show: false, message: '', type: 'success' }"
    x-on:notify.window="show = true; message = $event.detail.message; type = $event.detail.type || 'success'; setTimeout(() => { show = false }, 4000)"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
    x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0 sm:translate-x-0"
    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
    class="fixed bottom-4 sm:bottom-auto sm:top-24 right-4 sm:right-8 z-50 pointer-events-none"
    style="display: none;"
    role="status"
>
    <div 
        class="max-w-sm w-full shadow-lg rounded-lg pointer-events-auto border flex items-center p-4"
        :class="{
            'bg-vanniyan-green-900 border-vanniyan-green-800 text-white': type === 'success',
            'bg-red-50 border-red-200 text-red-900': type === 'error',
            'bg-white border-gray-200 text-gray-900': type === 'info'
        }"
    >
        <div class="flex-shrink-0 mr-3">
            <template x-if="type === 'success'">
                <svg class="h-6 w-6 text-vanniyan-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            </template>
            <template x-if="type === 'error'">
                <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </template>
        </div>
        <div class="flex-1 w-0">
            <p class="text-sm font-bold" x-text="message"></p>
        </div>
        <div class="ml-4 flex-shrink-0 flex">
            <button @click="show = false" class="rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                <span class="sr-only">Close</span>
                <svg class="h-5 w-5" x-show="type === 'error'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
            </button>
        </div>
    </div>
</div>
