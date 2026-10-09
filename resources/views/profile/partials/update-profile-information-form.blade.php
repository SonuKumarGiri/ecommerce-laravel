<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')
        <div class="flex flex-col gap-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Name') }}</label>
                <input id="name" name="name" type="text" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                <x-input-error class="mt-1" :messages="$errors->get('name')" />
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email') }}</label>
                <input id="email" name="email" type="email" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                <x-input-error class="mt-1" :messages="$errors->get('email')" />
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-sm text-gray-800">
                            {{ __('Your email address is unverified.') }}
                            <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
            <div class="flex items-center gap-4">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-md shadow-indigo-500/20 transition-all">{{ __('Save') }}</button>
                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                @endif
            </div>
        </div>
    </form>
</section>
