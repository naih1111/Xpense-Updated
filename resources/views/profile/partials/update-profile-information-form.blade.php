<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and settings.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Picture -->
        <div class="flex items-center space-x-6">
            <div class="shrink-0">
                <img class="h-16 w-16 object-cover rounded-full" 
                     src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default-avatar.png') }}" 
                     alt="{{ $user->name }}" />
            </div>
            <div>
                <x-input-label for="profile_picture" :value="__('Profile Picture')" />
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*"
                       class="mt-1 block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0
                              file:text-sm file:font-semibold
                              file:bg-violet-50 file:text-violet-700
                              hover:file:bg-violet-100" />
                <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
            </div>
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <a href="{{ route('verification.send') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </a>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Phone -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone', $user->phone)" placeholder="+63 XXX XXX XXXX" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <!-- Monthly Income -->
        <div class="mt-4">
            <x-input-label for="monthly_income" :value="__('Monthly Income')" />
            <div class="relative mt-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                    {{ $user->currency_preference ?? '₱' }}
                </span>
                <x-text-input 
                    id="monthly_income" 
                    name="monthly_income" 
                    type="number" 
                    class="mt-1 block w-full pl-8" 
                    :value="old('monthly_income', $user->monthly_income)" 
                    step="0.01"
                    min="0"
                    placeholder="0.00"
                />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('monthly_income')" />
            <p class="mt-1 text-sm text-gray-500">{{ __('Your regular monthly income helps us track your savings goals better.') }}</p>
        </div>

        <!-- Currency Preference -->
        <div class="mt-4">
            <x-input-label for="currency_preference" :value="__('Preferred Currency')" />
            <select id="currency_preference" name="currency_preference" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="PHP" {{ (old('currency_preference', $user->currency_preference) == 'PHP') ? 'selected' : '' }}>PHP (₱)</option>
                <option value="USD" {{ (old('currency_preference', $user->currency_preference) == 'USD') ? 'selected' : '' }}>USD ($)</option>
                <option value="EUR" {{ (old('currency_preference', $user->currency_preference) == 'EUR') ? 'selected' : '' }}>EUR (€)</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('currency_preference')" />
        </div>

        <!-- Bio -->
        <div class="mt-4">
            <x-input-label for="bio" :value="__('About Me')" />
            <textarea id="bio" name="bio" rows="4" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="Tell us a bit about yourself...">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <!-- Occupation -->
        <div class="mt-4">
            <x-input-label for="occupation" :value="__('Occupation')" />
            <x-text-input id="occupation" name="occupation" type="text" class="mt-1 block w-full" :value="old('occupation', $user->occupation)" placeholder="e.g. Software Developer" />
            <x-input-error class="mt-2" :messages="$errors->get('occupation')" />
        </div>

        <!-- Location -->
        <div class="mt-4">
            <x-input-label for="location" :value="__('Location')" />
            <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location', $user->location)" placeholder="e.g. Manila, Philippines" />
            <x-input-error class="mt-2" :messages="$errors->get('location')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section> 