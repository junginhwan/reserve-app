
<div class="container max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <h2 class="text-lg text-center">자리 예약</h2>
    <div id='calendar'></div>
</div>
@include('calendar.partials.calendar-modal-form')

{{-- Scripts --}}
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    locale: 'ko',
    selectable: true,
    // selectHelper: true,
    select : function (event) {
        console.log(event);
    },
    eventClick: function (event) {
        console.log(event);
    }
  });
  calendar.render();
});

</script>