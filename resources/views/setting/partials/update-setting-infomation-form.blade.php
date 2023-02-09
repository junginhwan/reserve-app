<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Setting Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's setting information and MQV account's.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="mqv_id" :value="__('MQV_ID')" />
            <x-text-input id="mqv_id" name="mqv_id" type="text" class="mt-1 block w-full" :value="old('mqv_id', $user->setting()?->mqv_id ?? '')" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('mqv_id')" />
        </div>

        <div>
            <x-input-label for="mqv_password" :value="__('MQV_PASSWORD')" />
            <x-text-input id="mqv_password" name="mqv_password" type="password" class="mt-1 block w-full" :value="old('mqv_password', $user->setting()?->mqv_password ?? '')" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('mqv_password')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>