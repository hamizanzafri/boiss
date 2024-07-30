@extends('layouts.template')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Products</h2>
        <!-- Sorting Dropdown aligned to the right -->
        <form action="{{ route('products.index') }}" method="GET" class="form-inline">
            <div class="form-group mr-3">
                <label for="sort-category" class="mr-2">Sort by Category:</label>
                <select id="sort-category" name="category" class="form-control" onchange="this.form.submit()">
                    <option value="all">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="sort-order" class="mr-2">Sort by:</label>
                <select id="sort-order" name="sort" class="form-control" onchange="this.form.submit()">
                    <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Default</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                    <option value="low_high" {{ request('sort') == 'low_high' ? 'selected' : '' }}>Price Low to High</option>
                    <option value="high_low" {{ request('sort') == 'high_low' ? 'selected' : '' }}>Price High to Low</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Products Section -->
    <div class="row text-center">
        @foreach ($products as $product)
            <div class="col-md-4 col-sm-6 mb-4">
                <!-- Wrapping the image and text with an anchor -->
                <a href="{{ route('products.detail', ['id' => $product->id]) }}" class="product-link" style="text-decoration: none; color: inherit;">
                    <!-- Product Image -->
                    <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" style="width: 100%; height: auto; max-height: 300px; object-fit: cover; margin-bottom: 10px;">
                    <div>
                        <h5>{{ $product->name }}</h5>
                        <p>Price: RM{{ number_format($product->price, 2) }}</p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
