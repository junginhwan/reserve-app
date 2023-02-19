<section>
    <header class="space-y-4">
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Setting Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's setting information and MQV account's.") }}
        </p>

        @if (session('status') === 'setting-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Saved.') }}</p>
        @endif
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('setting.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="mqv_id" :value="__('MQV_ID')" />
            <x-text-input id="mqv_id" name="mqv_id" type="text" class="mt-1 block w-full" :value="old('mqv_id', $user->setting?->mqv_id ?? '')" required autofocus autocomplete="mqv_id" />
            <x-input-error class="mt-2" :messages="$errors->get('mqv_id')" />
        </div>

        <div>
            <x-input-label for="mqv_password" :value="__('MQV_PASSWORD')" />
            <x-text-input id="mqv_password" name="mqv_password" type="password" class="mt-1 block w-full" :value="old('mqv_password', $user->setting?->mqv_password ?? '')" required />
            <x-input-error class="mt-2" :messages="$errors->get('mqv_password')" />
        </div>

        <div>
            <x-input-label for="start_time" :value="__('예약 시작 시간')" />
            <x-select name="start_time" :options="$reservationTimes" :value="old('start_time', $user->setting?->start_time ?? '09:30')" />
            <x-input-error class="mt-2" :messages="$errors->get('start_time')" />
        </div>

        <div>
            <x-input-label for="end_time" :value="__('예약 종료 시간')" />
            <x-select name="end_time" :options="$reservationTimes" :value="old('end_time', $user->setting?->end_time ?? '18:30')" />
            <x-input-error class="mt-2" :messages="$errors->get('end_time')" />
        </div>

        <div>
            <x-input-label for="meeting_seat_reservation" :value="__('미팅 자리 예약')" />
            <x-checkbox name="meeting_seat_reservation" :description="__('회의실 미팅이 예약되어 있는 경우 자리가 자동으로 예약됩니다.')" :value="1" :checked="old('meeting_seat_reservation', $user->setting?->meeting_seat_reservation ?? 0)"/>
            <x-input-error class="mt-2" :messages="$errors->get('meeting_seat_reservation')" />
        </div>

        @for($i=1, $j=0; $i<=3; $i++, $j++)
        <div>
            <x-input-label for="user_seats" :value="__('선호 좌석 ').$i" />
            <x-select name="user_seats[]" :options="$seatOptions" :value="old('user_seats[]', (!empty($user->user_seats[$j])) ? $user->user_seats[$j]?->seat_id : '')" />
            <x-input-error class="mt-2" :messages="$errors->get('user_seats')" />
        </div>
        @endfor

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>
    </form>
</section>