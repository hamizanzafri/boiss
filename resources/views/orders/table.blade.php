@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h2>All Orders</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Name</th>
                    <th>Products</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->name }}</td>
                        <td>{{ $order->product }}</td>
                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                        <td>
                            <form action="{{ route('orders.update.status', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-control" onchange="this.form.submit()">
                                    <option {{ $order->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option {{ $order->status == 'Item Prepared' ? 'selected' : '' }}>Item Prepared</option>
                                    <option {{ $order->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">View</a>
                            <!-- More actions can be added here -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
