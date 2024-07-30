@extends('layouts.template')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h2>Event Ticket</h2>
            <p><strong>Event:</strong> {{ $ticket->event->name }}</p>
            <p><strong>Location:</strong> {{ $ticket->event->location }}</p>
            <p><strong>Date:</strong> {{ $ticket->event->date }}</p>
            <img src="{{ asset($ticket->ticket_path) }}" alt="Ticket">
        </div>
    </div>
</div>
@endsection