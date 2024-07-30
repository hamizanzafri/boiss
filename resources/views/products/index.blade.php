@extends('layouts.template')

@section('content')
@if(auth()->user() && auth()->user()->user_type == 'admin')
        @include('products.table')
    @elseif(auth()->user() && auth()->user()->user_type == 'general')
        @include('products.list')
    @endif
@endsection 