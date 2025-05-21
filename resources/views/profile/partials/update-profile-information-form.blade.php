<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-8 space-y-8" id="profile-update-form">
        @csrf
        @method('patch')

        @if (session('status') === 'profile-updated')
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ __('Profile updated successfully.') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="space-y-6">
            <div class="space-y-2">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full px-4 py-3 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :value="old('name', $user->name)" required autofocus autocomplete="name" placeholder="Enter your name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div class="space-y-2">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full px-4 py-3 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :value="old('email', $user->email)" required autocomplete="username" placeholder="Enter your email" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-4 p-4 bg-yellow-50 rounded-xl border border-yellow-200">
                        <p class="text-sm text-yellow-700">
                            {{ __('Your email address is unverified.') }}
                            <a href="{{ route('verification.notice') }}" class="underline text-sm text-yellow-700 hover:text-yellow-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                {{ __('Click here to re-send the verification email.') }}
                            </a>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-sm text-yellow-700">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="flex items-center justify-end">
            <x-primary-button type="submit" class="px-8 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                {{ __('Save Changes') }}
            </x-primary-button>
        </div>
    </form>
</section> 