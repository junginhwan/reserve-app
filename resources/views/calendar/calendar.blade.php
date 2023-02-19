
<div class="container max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <h2 class="text-lg text-center">자리 예약</h2>
    <div class="flex flex-wrap justify-around">
      <div id='calendar' class="flex-initial"></div>
      <div class="flex-initial">
        @include('calendar.partials.calendar-modal-form')
      </div>
    </div>
</div>

{{-- Scripts --}}
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js'></script>
<script>

let start_str = '';
let end_str = '';

document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    locale: 'ko',
    selectable: true,
    selectHelper: true,
    select : function (event) {
      start_str = event.startStr;
      end_str = event.endStr;
    },
    eventClick: function (event) {
        console.log(event);
    }
  });
  calendar.render();

  const calendarForm = new CalendarForm();
  calendarForm.init();
});

  function CalendarForm() {

  }
  CalendarForm.prototype.init = function () {
    this.event();
  }
  CalendarForm.prototype.event = function () {
    document.querySelector('#save-btn').addEventListener('click', function () {
      this.save();
    }.bind(this));
  }

  CalendarForm.prototype.save = function () {
    if (!start_str) {
      alert('날짜를 선택해 주세요.');
      return;
    }

    const formData = new FormData();
    formData.append('start_date', start_str);
    formData.append('end_date', end_str);
    formData.append('start_time', document.querySelector('[name=start_time]').value);
    formData.append('end_time', document.querySelector('[name=end_time]').value);
    formData.append('user_seat', document.querySelector('[name=user_seat]').value);
  }
</script>