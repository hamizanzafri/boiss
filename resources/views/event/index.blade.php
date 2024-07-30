@extends('layouts.template')

@section('content')
    @if(auth()->user() && auth()->user()->user_type == 'admin')
        @include('event.table')
    @elseif(auth()->user() && auth()->user()->user_type == 'general')
        @include('event.list')
    @endif
@endsection