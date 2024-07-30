@extends('layouts.template')

@section('content')
<div class="container mt-5">
    <div class="card mb-4">
        <div class="card-header text-center">
            <h1 class="mt-3">Receipt</h1>
        </div>
        <div class="card-body">
            <div class="card-header d-flex justify-content-between">
                <div class="left-side">
                    <!-- Receipt Details and Transaction ID -->
                    <div><strong>Receipt Details</strong></div>
                    @if(isset($payment->payment_id))
                        <div><strong>Transaction ID:</strong> {{ $payment->payment_id }}</div>
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
    </div>

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
