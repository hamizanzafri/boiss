@extends('layouts.template')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Show Product Details</h2>
            </div>
        </div>
    </div>
   
    <div class="row">
        <!-- Photo Column -->
        <div class="col-md-6">
            <div class="form-group">
                <img src="{{ Storage::url($product->photo) }}" width="70%" alt="Product Photo" style="max-width: 500px;">
            </div>
        </div>
        
        <!-- Details Column -->
        <div class="col-md-6">
            <div class="form-group">
                <strong>Product Name:</strong>
                <div style="font-size: 1.5em;">{{ $product->name }}</div>
            </div>
            <div class="form-group">
                <strong>Price:</strong>
                <div style="font-size: 1.5em;">RM{{ number_format($product->price, 2) }}</div>
            </div>
            <div class="form-group">
                <strong>Category:</strong>
                <div style="font-size: 1.5em;">{{ $product->category->name }}</div>
            </div>
            <div class="form-group">
                <strong>Details:</strong>
                <div style="font-size: 1.2em;">{{ $product->details }}</div>
            </div>
        </div>
    </div>
<!-- Sizes and Stocks Table -->
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Sizes and Stock:</strong>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Size</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($product->stocks as $stock)
                    <tr>
                        <td>{{ $stock->size }}</td>
                        <td>{{ $stock->quantity }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2">No stock data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

    
    <div class="pull-right">
        <a class="btn btn-primary" href="{{ route('products.index') }}"> Back</a>
    </div>
@endsection
