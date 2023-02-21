<div class="container max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <h2 class="text-lg text-center">자리 예약</h2>
    <div class="flex flex-wrap justify-around">
      <div id='calendar' class="flex-auto w-3/4"></div>
      <div class="flex-auto w-48 ml-3 mt-3">
        @include('calendar.partials.calendar-modal-form')
      </div>
    </div>
    <div class="rounded-md bg-gray-100 p-4 alert">
      <div class="flex">
        <div class="ml-3">
          <div class="mt-2 text-sm text-write">
            <ul role="list" class="list-disc space-y-1 pl-5" id="alert-message">
              <li><a href="{{ route('setting.edit') }}">[Setting]</a> 에서 MQV 정보를 설정해 주세요.</li>
              <li>달력은 마우스 드래그를 통해 일괄로 선택 한 후 저장할 수 있습니다.</li>
              <li>토요일 / 일요일은 저장되지 않습니다.</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
</div>

{{-- Scripts --}}
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js'></script>
<script>

let start_str = '';
let end_str = '';
const reservations = <?php echo json_encode($reservations); ?>;

const default_value = {
  start_time: "{{ $user->setting?->start_time ?? '09:30' }}",
  end_time: "{{ $user->setting?->end_time ?? '09:30' }}",
  user_seats: <?php echo json_encode($user_seats); ?>,
};

document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    locale: 'ko',
    selectable: true,
    longPressDelay: 1,
    unselectAuto: false,
    select : function (event) {
      start_str = event.startStr;
      end_str = event.endStr;
    },
    dateClick: function (event) {
      document.querySelector('#calendar-form [name=start_time]').value = default_value.start_time;
      document.querySelector('#calendar-form [name=end_time]').value = default_value.end_time;
      for (let i=0; i<3; i++) {
        document.querySelectorAll("#calendar-form [name='user_seats[]']")[i].value = (default_value.user_seats[i]?.seat_id) ? default_value.user_seats[i]?.seat_id : '';
      }

      for (let i=0; i<reservations.length; i++) {
        if (reservations[i].start === event.dateStr) {
          document.querySelector('#calendar-form [name=start_time]').value = reservations[i].start_time;
          document.querySelector('#calendar-form [name=end_time]').value = reservations[i].end_time;
          for (let j=0; j<reservations[i].seats.length; j++) {
            document.querySelectorAll("#calendar-form [name='user_seats[]']")[j].value = reservations[i].seats[j].seat_id;
          }
          break;
        }
      }
    },
    selectAllow: function(info) {
        if (new Date(info.start) <= new Date()){
          return false;
        }
        return true;
    },
    events: reservations,
  });
  calendar.render();

  const calendarForm = new CalendarForm(calendar);
  calendarForm.init();
});

  function CalendarForm(calendar) {
    this.calendar = calendar;
    this.reservations = [];
  }
  CalendarForm.prototype.init = function () {
    this.event();
  }
  CalendarForm.prototype.event = function () {
    document.querySelector('#save-btn').addEventListener('click', function () {
      this.save();
    }.bind(this));

    document.querySelector('#delete-btn').addEventListener('click', function () {
      this.delete();
    }.bind(this));
  }

  CalendarForm.prototype.save = function () {
    if (!start_str) {
      alert('날짜를 선택해 주세요.');
      return;
    }
    const user_seats = [];
    document.querySelectorAll("[name='user_seats[]']").forEach(function (user_seat) {
        if (user_seat.value) {
          user_seats.push(user_seat.value);
        }
    });

    if (user_seats.length < 1) {
      alert('선호 좌석을 선택해 주세요.');
      return;
    }
    
    document.querySelector('#calendar-form [name=start_date]').value = start_str;
    document.querySelector('#calendar-form [name=end_date]').value = end_str;
    document.querySelector('#calendar-form').submit();
  }

  CalendarForm.prototype.delete = function () {
    if (!start_str) {
      alert('날짜를 선택해 주세요.');
      return;
    }

    if (confirm('예약을 삭제하시겠습니까?')) {
      document.querySelector('#delete-form [name=start_date]').value = start_str;
      document.querySelector('#delete-form [name=end_date]').value = end_str;
      document.querySelector('#delete-form').submit();
    }
  }
</script>