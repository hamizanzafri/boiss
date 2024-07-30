@extends('layouts.master')

@section('title', 'Event Management')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/gridjs/theme/mermaid.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('build/libs/flatpickr/flatpickr.min.css') }}">
@endsection

@section('page-title', 'Event Management')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Toolbar for adding new events -->
        <div class="mb-3 d-flex justify-content-end">
            <button type="button" class="btn btn-success btn-rounded waves-effect waves-light"
                    onclick="window.location.href='{{ route('event.create') }}'">
                <i class="mdi mdi-plus me-1"></i> Add New Event
            </button>
        </div>
        <!-- Calendar Container -->
        <div id='calendar'></div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/gridjs/gridjs.umd.js') }}"></script>
    <script src="{{ URL::asset('build/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <!-- FullCalendar JS -->
    <script src='{{ URL::asset('build/libs/fullcalendar/index.global.min.js') }}'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    @foreach ($events as $event)
                    {
                        title: '{{ $event->event }}',
                        start: '{{ $event->date }}',
                        url: '{{ route('event.show', $event->id) }}',
                        allDay: true
                    },
                    @endforeach
                ],
                eventClick: function(info) {
                    info.jsEvent.preventDefault(); // Prevent the browser from following the link
                    window.open(info.event.url, '_blank'); // Open the event URL in a new tab
                }
            });
            calendar.render();
        });
    </script>
@endsection
