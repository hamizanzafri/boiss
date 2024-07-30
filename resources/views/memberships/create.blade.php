@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Register for Membership</h2>
    <form action="{{ route('memberships.store') }}" method="POST" id="membershipForm">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" id="name" name="name" required placeholder="Enter your name">
        </div>
        <!-- Address Fields -->
        <div class="mb-3">
            <label for="street" class="form-label">Street Line 1:</label>
            <input type="text" class="form-control" id="street" name="street" required placeholder="Street address">
        </div>
        <div class="mb-3">
            <label for="city" class="form-label">City:</label>
            <input type="text" class="form-control" id="city" name="city" required placeholder="City">
        </div>
        <div class="mb-3">
            <label for="state" class="form-label">State:</label>
            <input type="text" class="form-control" id="state" name="state" required placeholder="State">
        </div>
        <div class="mb-3">
            <label for="postcode" class="form-label">Postcode:</label>
            <input type="text" class="form-control" id="postcode" name="postcode" required placeholder="Postcode">
        </div>
        
        <!-- Hidden Field for Combined Address -->
        <input type="hidden" id="address" name="address">

        <button type="submit" class="btn btn-primary" onclick="prepareAddress()">Register</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function prepareAddress() {
        event.preventDefault();
        const street = document.getElementById('street').value;
        const city = document.getElementById('city').value;
        const state = document.getElementById('state').value;
        const postcode = document.getElementById('postcode').value;
        const fullAddress = `${street}, ${city}, ${state}, ${postcode}`;
        document.getElementById('address').value = fullAddress;
        document.getElementById('membershipForm').submit();
    }
</script>
@endsection
