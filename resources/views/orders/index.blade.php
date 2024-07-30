@extends('layouts.template')

@section('content')
    <div class="container">
        @if ($view === 'admin')
            @include('orders.table', ['orders' => $orders])
        @else
            @include('orders.customerorder', ['orders' => $orders])
        @endif
    </div>
@endsection