<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" id="calendar-form" action="{{ route('reservation.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')
        <input type="hidden" name="start_date" value="">
        <input type="hidden" name="end_date" value="">
        <input type="hidden" name="mqv_id" value="{{ $user->setting?->mqv_id }}">

        <div>
            <x-input-label for="start_time" :value="__('예약 시작 시간')" />
            <x-select name="start_time" :options="$reservationTimes" :value="old('start_time', $user->setting?->start_time ?? '09:30')" />
        </div>

        <div>
            <x-input-label for="end_time" :value="__('예약 종료 시간')" />
            <x-select name="end_time" :options="$reservationTimes" :value="old('end_time', $user->setting?->end_time ?? '18:30')" />
        </div>
        
        @for($i=1, $j=0; $i<=3; $i++, $j++)
        <div>
            <x-input-label for="user_seats" :value="__('선호 좌석 ').$i" />
            <x-select name="user_seats[]" :options="$seatOptions" :value="old('user_seats[]', (!empty($user_seats[$j])) ? $user_seats[$j]?->seat_id : '')" />
        </div>
        @endfor

        <div class="flex items-center gap-4">
          <button type="button" id="delete-btn" class="inline-flex items-center rounded border border-transparent bg-red-600 px-2.5 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">{{ __('Delete') }}</button>
          <button type="button" id="save-btn" class="inline-flex items-center rounded border border-transparent bg-indigo-600 px-2.5 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">{{ __('Save') }}</button>
        </div>
    </form>
    <form id="delete-form" method="post" action="{{ route('reservation.delete') }}">
        @csrf
        @method('delete')
        <input type="hidden" name="start_date" value="">
        <input type="hidden" name="end_date" value="">
    </form>
</section>
