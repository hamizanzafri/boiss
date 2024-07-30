@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header text-center">
            <h1 class="mt-3">Event Ticket</h1>
        </div>
        <div class="card-body text-center">
            @if ($ticket)
                <img src="{{ asset('storage/' . $ticket->ticket_path) }}" alt="Event Ticket">
            @else
                <p>No ticket found for this order.</p>
            @endif
        </div>
    </div>
</div>
@endsection
