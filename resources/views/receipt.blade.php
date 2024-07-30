@extends('layouts.template')

@section('content')
<div class="container mt-5 receipt-container">
    <div class="card">
        <div class="card-header text-center">
            <img src="{{ URL::asset('build/images/F_black.png') }}" alt="1936Bois Logo" style="width: 100px;">
            <h1 class="mt-3">Receipt</h1>
        </div>
        <div class="card-body">
            <div class="card-header d-flex justify-content-between">
                <div class="left-side">
                    <!-- Receipt Details and Transaction ID -->
                    <div><strong>Receipt Details</strong></div>
                    @if(isset($order->payment->payment_id))
                        <div><strong>Transaction ID:</strong> {{ $order->payment->payment_id }}</div>
                    @endif
                    <div><strong>Membership ID:</strong> {{ $membership ? $membership->membership_id : 'N/A' }}</div>
                </div>
                <div class="right-side">
                    <!-- Bill To and Payment Date -->
                    @if(auth()->check())
                        <div><strong>Bill To:</strong> {{ auth()->user()->name }}</div>
                    @else
                        <div><strong>Bill To:</strong> Guest</div>
                    @endif
                    <div><strong>Payment Date:</strong> {{ $order->created_at->format('F d, Y') }}</div>
                </div>
            </div>

            @php
                $total = 0;
            @endphp

            @if($orderDetails && count($orderDetails) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Size</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($orderDetails as $details)
                        <tr>
                            <td>{{ $details['name'] }}</td>
                            <td>{{ $details['quantity'] }}</td>
                            <td>{{ $details['size'] ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <h3 class="text-right">Total Paid: RM{{ number_format($totalPaid, 2) }}</h3>
            @else
                <p class="text-center">No products found in your transaction.</p>
            @endif
        </div>
        <div class="card-footer text-muted text-center">
            Date: {{ now()->format('F d, Y H:i:s') }}
        </div>
    </div>
</div>
@endsection
