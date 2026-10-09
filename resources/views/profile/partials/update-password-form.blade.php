<section>
    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')
        <div class="flex flex-col gap-4">
            <div>
                <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Current Password') }}</label>
                <div class="relative" x-data="{ show: false }">
                    <input :type="show ? 'text' : 'password'" id="update_password_current_password" name="current_password" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm pr-10" autocomplete="current-password" />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-indigo-600 focus:outline-none">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
            </div>
            <div>
                <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('New Password') }}</label>
                <div class="relative" x-data="{ show: false }">
                    <input :type="show ? 'text' : 'password'" id="update_password_password" name="password" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm pr-10" autocomplete="new-password" />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-indigo-600 focus:outline-none">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
            </div>
            <div>
                <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Confirm Password') }}</label>
                <div class="relative" x-data="{ show: false }">
                    <input :type="show ? 'text' : 'password'" id="update_password_password_confirmation" name="password_confirmation" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm pr-10" autocomplete="new-password" />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-indigo-600 focus:outline-none">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
            </div>
            <div class="flex items-center gap-4">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-md shadow-indigo-500/20 transition-all">{{ __('Save') }}</button>
                @if (session('status') === 'password-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                @endif
            </div>
        </div>
    </form>
</section>
