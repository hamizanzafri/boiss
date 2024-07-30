@extends('layouts.template')

@section('title', 'Add New Products')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/gridjs/theme/mermaid.min.css') }}">
    <!-- datepicker css -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/flatpickr/flatpickr.min.css') }}">
@endsection

@section('page-title', 'Add New Products')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-12">
            <div class="form-group">
                <strong>Product Name:</strong>
                <input type="text" name="name" class="form-control" placeholder="Product Name">
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-12">
            <div class="form-group">
                <strong>Details:</strong>
                <input type="text" name="details" class="form-control" placeholder="Details">
            </div> 
        </div> 
        <div class="col-xs-6 col-sm-6 col-md-12">
            <div class="form-group">
                <strong>Price:</strong>
                <input type="text" name="price" class="form-control" placeholder="Price">
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-12">
            <div class="form-group">
                <strong>Photo:</strong>
                <input type="file" name="photo" class="form-control-file">
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <label for="category_id">Category:</label>
                <select class="form-control" name="category_id" id="category_id">
                    <option value="">-- Choose Category --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ (isset($timetable['category_id']) && $timetable['category_id'] == $category->id) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-12">
            <div class="form-group">
                <strong>Sizes and Stocks:</strong>
                <button type="button" class="btn btn-link" id="addSize">Add Size</button>
            </div>
            <div id="sizeContainer">
                <!-- Dynamic size input fields will appear here -->
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a class="btn btn-primary" href="{{ route('products.index') }}"> Back</a>
        </div>
    </div>
</form>

@endsection

@section('scripts')
    <!-- apexcharts -->
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <!-- gridjs js -->
    <script src="{{ URL::asset('build/libs/gridjs/gridjs.umd.js') }}"></script>
    <!-- datepicker js -->
    <script src="{{ URL::asset('build/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/ecommerce-products.init.js') }}"></script>
    <!-- App js -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('addSize').addEventListener('click', function() {
                const container = document.getElementById('sizeContainer');
                const index = container.children.length;

                const html = `
                    <div class="size-entry">
                        <div class="input-group mb-3">
                            <input type="text" name="sizes[${index}][size]" class="form-control" placeholder="Size" required>
                            <input type="number" name="sizes[${index}][stock]" class="form-control" placeholder="Stock" required>
                            <div class="input-group-append">
                                <button class="btn btn-danger" type="button" onclick="removeSize(this)">Remove</button>
                            </div>
                        </div>
                    </div>
                `;

                container.innerHTML += html;
            });
        });

        function removeSize(button) {
            button.closest('.size-entry').remove();
        }
    </script>
@endsection
