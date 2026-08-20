<div>
    <div class="mb-8">
        <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">Security Profile</h1>
        <p class="text-gray-600">Manage your system administrator account security and active sessions.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Account Status -->
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <h3 class="font-serif font-bold text-lg text-vanniyan-green-900 mb-4 uppercase tracking-wider">Account Information</h3>
                
                <div class="mb-4">
                    <span class="block text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Status</span>
                    <div class="flex items-center">
                        <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                        <span class="text-sm font-bold text-gray-900">{{ $admin->is_active ? 'ACTIVE' : 'DISABLED' }}</span>
                    </div>
                </div>

                <div class="mb-4">
                    <span class="block text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Username</span>
                    <span class="text-sm font-medium text-gray-900">{{ $admin->username }}</span>
                </div>

                <div class="mb-4">
                    <span class="block text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Last Login</span>
                    <span class="text-sm font-medium text-gray-900">{{ $admin->last_login_at ? $admin->last_login_at->format('d M Y, h:i A') : 'Never' }}</span>
                </div>

                <div>
                    <span class="block text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Password Last Changed</span>
                    <span class="text-sm font-medium text-gray-900">{{ $admin->password_changed_at ? $admin->password_changed_at->diffForHumans() : 'Never' }}</span>
                </div>
            </div>
        </div>

        <!-- Change Password -->
        <div class="lg:col-span-2">
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <h3 class="font-serif font-bold text-lg text-vanniyan-green-900 mb-4 uppercase tracking-wider">Change Password</h3>
                
                @if (session()->has('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit.prevent="changePassword" class="space-y-6 max-w-md">
                    
                    <div>
                        <label for="current_password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Current Password</label>
                        <input wire:model="current_password" id="current_password" type="password" required class="appearance-none block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        @error('current_password') <span class="text-xs text-red-600 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="new_password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">New Password</label>
                        <input wire:model="new_password" id="new_password" type="password" required class="appearance-none block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                        @error('new_password') <span class="text-xs text-red-600 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Confirm New Password</label>
                        <input wire:model="new_password_confirmation" id="new_password_confirmation" type="password" required class="appearance-none block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                    </div>

                    <div>
                        <button type="submit" class="w-full sm:w-auto flex justify-center py-2 px-6 border border-transparent rounded-md shadow-sm text-sm font-bold uppercase tracking-wider text-white bg-vanniyan-green-900 hover:bg-vanniyan-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-vanniyan-green-900 transition-colors">
                            <span wire:loading.remove wire:target="changePassword">Update Password</span>
                            <span wire:loading wire:target="changePassword">Updating...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
