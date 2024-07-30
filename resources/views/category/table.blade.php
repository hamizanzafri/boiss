@extends('layouts.master')

@section('title', 'Category Management')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/gridjs/theme/mermaid.min.css') }}">
    <!-- datepicker css -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/flatpickr/flatpickr.min.css') }}">
@endsection

@section('page-title', 'List of Categories')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="position-relative">
            <div class="modal-button mt-2 mb-5"> 
                <button type="button" class="btn btn-success btn-rounded waves-effect waves-light"
                        data-bs-toggle="modal" data-bs-target=".add-new-order"
                        onclick="window.location.href='{{ route('category.create') }}'">
                    <i class="mdi mdi-plus me-1"></i> Add New Category
                </button>
            </div>
        </div>
    </div>
</div>

<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>No</th>
            <th>Category Name</th>
            <th width="280px">Action</th>
        </tr>
    </thead>
    <tbody>
        @php $count = 1; @endphp

        @foreach ($categories as $category)
        <tr>
            <td>{{ $count++ }}</td>
            <td>{{ $category->name }}</td>
            <td>
                <form action="{{ route('category.destroy', $category->id) }}" method="POST">
                    <a class="btn btn-primary" href="{{ route('category.edit', $category->id) }}">Edit</a>
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
@endsection
