@extends('layouts.master')

@section('title', 'Product Management')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/gridjs/theme/mermaid.min.css') }}">
    <!-- datepicker css -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/flatpickr/flatpickr.min.css') }}">
    <style>
        .dropdown-menu {
            display: none; /* Ensure this only changes on dropdown click */
        }
    </style>

@endsection

@section('page-title', 'List of Products')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="position-relative">
            <div class="modal-button mt-2 mb-5"> 
                <button type="button" class="btn btn-success btn-rounded waves-effect waves-light"
                        data-bs-toggle="modal" data-bs-target=".add-new-order"
                        onclick="window.location.href='{{ route('products.create') }}'">
                    <i class="mdi mdi-plus me-1"></i> Add New Product
                </button>
            </div>
        </div>
    </div>
</div>
<div class="d-flex justify-content-start mb-4">

    <!-- Sorting by other criteria -->
    <form action="{{ route('products.index') }}" method="GET" class="form-inline">
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

<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>No</th>
            <th>Product Name</th>
            <th>
                Price
            </th>
            <th>Category</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @php $count = 1; @endphp

        @foreach ($products as $product)
        <tr>
            <td>{{ $count++ }}</td>
            <td>{{ $product->name }}</td>
            <td>RM{{ number_format($product->price, 2) }}</td>
            <td>{{ $product->category->name ?? 'No category' }}</td>
            <td>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                    <a class="btn btn-primary" href="{{ route('products.show', $product->id) }}">Details</a>
                    <a class="btn btn-primary" href="{{ route('products.edit', $product->id) }}">Edit</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

@section('scripts')
    <!-- apexcharts -->
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <!-- gridjs js -->
    <script src="{{ URL::asset('build/libs/gridjs/gridjs.umd.js') }}"></script>
    <!-- datepicker js -->
    <script src="{{ URL::asset('build/libs/flatpickr/flatpickr.min.js') }}"></script>
    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Initialize Dropdown -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });

            // Optionally, to manually toggle dropdown for testing:
            // var myDropdown = document.getElementById('dropdownMenuLink');
            // var dropdown = new bootstrap.Dropdown(myDropdown);
            // dropdown.toggle(); // Manually toggle dropdown to test
        });
    </script>
@endsection
