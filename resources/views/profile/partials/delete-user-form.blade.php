<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
            @csrf
            @method('delete')

            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full"
                    placeholder="{{ __('Enter your password to confirm deletion') }}"
                    required
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end">
                <x-danger-button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </div>
</section> 