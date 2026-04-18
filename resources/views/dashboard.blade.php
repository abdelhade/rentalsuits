@extends('layouts.app')

@section('content')
<div class="page-header">
    <h2 class="page-title">التقويم وجدولة التأجير</h2>
    <p class="text-muted">قم بإدارة الحجوزات والعمليات اليومية بسهولة</p>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-glass border-0">
            <div class="card-body p-4">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@stack('styles')
<style>
    .fc-theme-standard td, .fc-theme-standard th {
        border-color: rgba(0,0,0,0.05) !important;
    }
    .fc-day-today {
        background-color: rgba(230, 179, 37, 0.1) !important; /* Secondary color light */
    }
    .fc-event {
        border: none;
        border-radius: 4px;
        padding: 3px;
        cursor: pointer;
    }
    .fc-event-rent {
        background-color: var(--primary) !important;
    }
    .fc-event-receipt {
        background-color: #28a745 !important;
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'ar',
            direction: 'rtl',
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            selectable: true,
            select: function(info) {
                window.location.href = '/rentals/create?date=' + info.startStr;
            },
            events: '/api/calendar/events', // endpoint for events
        });
        
        calendar.render();
    });
</script>
@endpush
