@extends('layouts.template')

@section('content')
<div class="container">
        <h2>User Management</h2>

        @if ($view === 'admin')
            @include('users.admintable', ['users' => $users])
        @else
            @include('users.usertable', ['users' => $users])
        @endif
    </div>
@endsection 