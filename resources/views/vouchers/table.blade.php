@extends('layouts.master')

@section('title', 'Voucher List')

@section('content')
    <div class="container">
        <h1>Voucher List</h1>
        <a href="{{ route('vouchers.create') }}" class="btn btn-primary mb-3">Add New Voucher</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Discount</th>
                    <th>Expiry Date</th>
                    <th>Photo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vouchers as $voucher)
                    <tr>
                        <td>{{ $voucher->code }}</td>
                        <td>{{ $voucher->discount }}</td>
                        <td>{{ $voucher->expiry_date }}</td>
                        <td>
                            @if($voucher->photo)
                                <img src="{{ Storage::url($voucher->photo) }}" alt="{{ $voucher->code }}" width="100">
                            @else
                                No photo
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('vouchers.destroy', $voucher->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
