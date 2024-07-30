@extends('layouts.template')

@section('title', 'Edit Event')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/gridjs/theme/mermaid.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('build/libs/flatpickr/flatpickr.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Event</h2>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('event.update', $event->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <input type="hidden" name="id" value="{{ $event->id }}"> <br/>

            <div class="col-md-6">
                <div class="form-group">
                    <strong>Event Name:</strong>
                    <input type="text" name="event" value="{{ $event->event }}" class="form-control" placeholder="Event Name" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Venue:</strong>
                    <input type="text" id="venue" name="venue" value="{{ $event->venue }}" class="form-control" placeholder="Type to search..." required>
                </div>
            </div>
            <div class="col-md-12">
                <div id="map" style="height: 400px;"></div>
                <input type="hidden" id="latitude" name="latitude" value="{{ $event->latitude }}">
                <input type="hidden" id="longitude" name="longitude" value="{{ $event->longitude }}">
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Date:</strong>
                    <input type="date" name="date" value="{{ $event->date->format('Y-m-d') }}" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Photo:</strong>
                    <input type="file" name="photo" class="form-control-file">
                    @if($event->photo)
                        <img src="{{ Storage::url($event->photo) }}" alt="{{ $event->event }}" width="100">
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Ticket Price:</strong>
                    <input type="text" name="ticket_price" value="{{ $event->ticket_price }}" class="form-control" placeholder="Ticket Price">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Ticket Stock:</strong>
                    <input type="text" name="ticket_stock" value="{{ $event->ticket_stock }}" class="form-control" placeholder="Ticket Stock">
                </div>
            </div>
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a class="btn btn-primary" href="{{ route('event.index') }}">Back</a>
            </div>
        </div>
    </form>

    <!-- Bootstrap modal for success message -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Event has been updated successfully.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent <!-- Keep existing scripts -->

    <!-- Additional script for showing success modal -->
    <script>
        $(document).ready(function() {
            @if(session('success'))
                $('#successModal').modal('show');
            @endif
        });
    </script>

    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/gridjs/gridjs.umd.js') }}"></script>
    <script src="{{ URL::asset('build/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/ecommerce-event.init.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAw0Fkkts_eDii1wNnpxis3J95bNLj_83A&callback=initMap&libraries=places" async defer></script>
    <script>
        function initMap() {
            const initialLocation = { lat: {{ $event->latitude ?? 3.1390 }}, lng: {{ $event->longitude ?? 101.6869 }} };
            const map = new google.maps.Map(document.getElementById('map'), {
                center: initialLocation,
                zoom: 15,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            const input = document.getElementById('venue');
            const autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.setFields(['geometry', 'name']);
            autocomplete.bindTo('bounds', map);

            const marker = new google.maps.Marker({
                map: map,
                position: initialLocation,
                draggable: true,
                anchorPoint: new google.maps.Point(0, -29)
            });

            autocomplete.addListener('place_changed', function() {
                marker.setVisible(false);
                const place = autocomplete.getPlace();
                if (!place.geometry) {
                    window.alert("No details available for input: '" + place.name + "'");
                    return;
                }

                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }

                marker.setPosition(place.geometry.location);
                marker.setVisible(true);

                document.getElementById('latitude').value = place.geometry.location.lat();
                document.getElementById('longitude').value = place.geometry.location.lng();
            });

            google.maps.event.addListener(marker, 'dragend', function() {
                const latLng = marker.getPosition();
                document.getElementById('latitude').value = latLng.lat();
                document.getElementById('longitude').value = latLng.lng();
            });
        }
    </script>
@endsection
