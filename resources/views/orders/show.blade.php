@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h2>Order Details</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Order Information</h5>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Name:</strong> {{ $orders->name }}</p>
                    <p><strong>Phone Number:</strong> {{ $orders->phone_number }}</p>
                    <p><strong>Email:</strong> {{ $orders->email }}</p>
                    <p><strong>Address:</strong> {{ $orders->address }}</p>
                    <p><strong>Payment ID:</strong> {{ $orders->payment_id }}</p>
                    <p><strong>Order Created At:</strong> {{ $orders->created_at->toDayDateTimeString() }}</p>
                </div>
                <div class="col-md-6">
                    <h5 class="mt-2">Products Details</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Size</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $products = explode(', ', $orders->product);
                                $quantities = explode(', ', $orders->quantity);
                                $sizes = explode(', ', $orders->size);
                            @endphp
                            @foreach ($products as $index => $product)
                                <tr>
                                    <td>{{ $product }}</td>
                                    <td>{{ $quantities[$index] ?? 'N/A' }}</td>
                                    <td>{{ $sizes[$index] ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p><strong>Total Paid:</strong> RM{{ number_format($orders->total_paid, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
