@extends('layouts.sidebar')

@section('content')
    @include('components.customer.list')
    @include('components.customer.create')
    @include('components.customer.update')
    @include('components.customer.delete')
@endsection
