@extends('layouts.template')

@section('title', 'Add New Admin')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/gridjs/theme/mermaid.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('build/libs/flatpickr/flatpickr.min.css') }}">
@endsection

@section('page-title', 'Create New Admin')

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

    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-12">
                <div class="form-group">
                    <strong>Admin Name:</strong>
                    <input type="text" name="name" class="form-control" placeholder="Admin Name" required>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-12">
            <div class="form-group">
                <strong>Admin Email:</strong>
                <input type="email" name="email" class="form-control" placeholder="Admin Email" required>
            </div>
        </div>
        <div class="form-group">
                <strong>Password:</strong>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
        <div class="form-group">
                <strong>Confirm Password:</strong>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
            </div>
        </div>
        <div class="form-group">
            <strong>User Type:</strong>
            <input type="text" name="user_type" class="form-control" value="admin" readonly>
        </div>
        <div class="form-group">
            <strong>Role:</strong>
            <select name="role" class="form-select" required>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
            </div>
        </div>
    </form>
    
@endsection

@section('scripts')
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/gridjs/gridjs.umd.js') }}"></script>
    <script src="{{ URL::asset('build/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/ecommerce-users.init.js') }}"></script>
@endsection
