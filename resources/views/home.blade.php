@extends('layouts.master')

@section('title', 'Dashboard')

@section('css')
    <!-- Common CSS -->
    <link href="{{ asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .product-link {
            display: block;
            text-align: center;
            color: inherit; /* Ensure text color inherits from parent or set as needed */
            text-decoration: none; /* Remove underline from links */
        }

        .product-link img {
            width: 100%; /* Responsive image width */
            max-height: 250px; /* Fixed max-height for uniformity */
            object-fit: cover; /* Ensure images cover the area nicely */
            margin-bottom: 10px; /* Space between image and text */
        }

        .product-link h5, .product-link p {
            margin: 0; /* Remove margin around text for tighter spacing */
        }
    </style>
@endsection

@section('page-title')
    @if(Auth::user()->user_type === 'admin')
        Admin Dashboard
    @else
        1936BOIS
    @endif
@endsection

@section('body')

<body>
@endsection

@section('content')
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if(Auth::user()->user_type === 'admin')
    You are logged in as an administrator!
@else
    <div class="container">
        <h2>Welcome, {{ Auth::user()->name }}!</h2>
        <h3>TOP SELLING</h3>
        <div class="row">
            @foreach($products as $product)
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img class="card-img-top" src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ $product->description }}</p>
                            <p class="card-text"><strong>Price:</strong> RM{{ number_format($product->price, 2) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
    
@endsection

@section('scripts')
    <!-- Common Scripts -->
    <script src="{{ asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('build/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('build/libs/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ asset('build/js/pages/dashboard.init.js') }}"></script>
@endsection
