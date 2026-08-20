<div class="sm:mx-auto sm:w-full sm:max-w-md px-4">
    <div class="bg-white py-10 px-6 sm:px-10 shadow-lg shadow-gray-200/50 rounded-2xl border border-gray-100">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <img src="{{ asset('images/logo.png') }}" alt="Vanniyan Restaurant" class="h-14 md:h-16 w-auto mx-auto mb-4">
            </a>
        </div>

        <form wire:submit.prevent="login" class="space-y-6">
            
            @if($errors->has('username'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-xs font-medium text-center">
                    {{ $errors->first('username') }}
                </div>
            @endif

            <div>
                <label for="username" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Username</label>
                <div class="mt-1">
                    <input 
                        wire:model="username" 
                        id="username" 
                        name="username" 
                        type="text" 
                        required 
                        class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm"
                    >
                </div>
            </div>

            <div x-data="{ showPassword: false }">
                <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Password</label>
                <div class="mt-1 relative">
                    <input 
                        wire:model="password" 
                        id="password" 
                        name="password" 
                        :type="showPassword ? 'text' : 'password'" 
                        required 
                        class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm pr-16"
                    >
                    <button 
                        type="button" 
                        @click="showPassword = !showPassword" 
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-xs font-bold text-gray-500 hover:text-gray-700 focus:outline-none uppercase tracking-wider"
                    >
                        <span x-show="!showPassword">Show</span>
                        <span x-show="showPassword" x-cloak>Hide</span>
                    </button>
                </div>
            </div>

            <div>
                <button 
                    type="submit" 
                    wire:loading.attr="disabled" 
                    class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-xs font-bold uppercase tracking-widest text-white bg-vanniyan-green-900 hover:bg-vanniyan-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-vanniyan-green-900 transition-colors disabled:opacity-75 cursor-pointer"
                >
                    <span wire:loading.remove wire:target="login">Sign In</span>
                    <span wire:loading wire:target="login" style="display: none;">Signing In...</span>
                </button>
            </div>
        </form>
    </div>

    <div class="text-center mt-6">
        <a href="{{ route('home') }}" class="text-xs text-gray-500 hover:text-vanniyan-green-900 font-medium transition-colors">
            &larr; Back to Public Website
        </a>
    </div>
</div>
