@extends('layouts.master')

@section('title', 'Event Details')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <!-- Event Image Display -->
            <div class="event-image">
                <img class="d-block w-100" src="{{ Storage::url($event->photo) }}" alt="{{ $event->event }}">
            </div>
        </div>
        <div class="col-md-6">
            <h1>{{ $event->event }}</h1>
            <p class="venue"><strong>Venue:</strong> {{ $event->venue }}</p>
            <p class="date"><strong>Date:</strong> {{ $event->date->format('d M Y') }}</p>
            <p class="price"><strong>Ticket Price:</strong> RM{{ number_format($event->ticket_price, 2) }}</p>

            @if(auth()->check() && auth()->user()->user_type === 'admin')
                <div class="ticket-stock mt-3">
                    <label for="ticket_stock"><strong>Ticket Stock:</strong></label>
                    <span class="form-control">{{ $event->ticket_stock }}</span>
                </div>
                <form action="{{ route('event.destroy', $event->id) }}" method="POST" class="mt-3">
                    <a class="btn btn-primary" href="{{ route('event.edit', $event->id) }}">Edit</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
                <a href="https://www.google.com/maps/search/?api=1&query={{ $event->latitude ?? 3.1390 }},{{ $event->longitude ?? 101.6869 }}" target="_blank" class="btn btn-info mt-2">Show in Google Map</a>
            @else
                <!-- General user view -->
                <div id="map" style="height: 300px;"></div> <!-- Google Map display -->
                <button id="buyTicketBtn" class="btn btn-primary mt-3">Buy Ticket</button>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAw0Fkkts_eDii1wNnpxis3J95bNLj_83A&callback=initMap" async defer></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    function initMap() {
        var venue = {
            lat: {{ $event->latitude ?? 3.1390 }},
            lng: {{ $event->longitude ?? 101.6869 }}
        };
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: venue
        });
        var marker = new google.maps.Marker({
            position: venue,
            map: map
        });
    }

    $(document).ready(function() {
        $('#buyTicketBtn').click(function() {
            $.ajax({
                url: '{{ route("cart.add") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: '{{ $event->id }}',
                    name: '{{ $event->event }}',
                    price: '{{ $event->ticket_price }}',
                    quantity: 1,
                    type: 'event' // Add a type to differentiate between products and events
                },
                success: function(response) {
                    alert(response.message);
                    if (response.success) {
                        location.reload(); // Refresh page to update cart display
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
