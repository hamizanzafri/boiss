@extends('layouts.master')

@section('title', 'Event Management')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/gridjs/theme/mermaid.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('build/libs/flatpickr/flatpickr.min.css') }}">
    <style>
        .event-card {
            height: 450px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .card-img-top {
            height: 60%;
            object-fit: cover;
            width: 100%;
        }
        .card-body {
            height: 40%;
            overflow-y: auto;
        }
        .ticket-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .buy-ticket-btn {
            padding: 0.375rem 0.75rem; /* Smaller padding */
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        @foreach ($events as $event)
        <div class="col-md-4 my-3">
            <div class="card event-card">
                <!-- Image link to the event detail page -->
                <a href="{{ route('event.show', $event->id) }}">
                    <img class="card-img-top" src="{{ asset('storage/' . $event->photo) }}" alt="{{ $event->event }}">
                </a>
                <div class="card-body">
                    <h5 class="card-title">{{ $event->event }}</h5>
                    <p class="card-text">{{ $event->venue }}</p>
                    <p class="card-text">{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</p>
                    <div class="ticket-info">
                        <span class="card-text">Ticket Price: RM{{ $event->ticket_price }}</span>
                        <button class="btn btn-primary buy-ticket-btn" data-event-id="{{ $event->id }}" data-event-name="{{ $event->event }}" data-event-price="{{ $event->ticket_price }}">Buy Ticket</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
    $('.buy-ticket-btn').click(function() {
        var eventId = $(this).data('event-id');
        var eventName = $(this).data('event-name');
        var eventPrice = $(this).data('event-price');
        var quantity = 1;

        $.ajax({
            url: '{{ route("cart.add") }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: eventId,
                name: eventName,
                price: eventPrice,
                quantity: quantity,
                type: 'event' // Add type to differentiate between products and events
            },
            success: function(response) {
                if (response.success) {
                    alert('Ticket added to cart!');
                    location.reload(); // Reload to show new items in the cart
                } else {
                    alert(response.message || 'Unable to add ticket to cart.');
                }
            },
            error: function(xhr) {
                alert('Error adding ticket to cart. Please try again.');
            }
        });
    });
});
    </script>
@endsection
