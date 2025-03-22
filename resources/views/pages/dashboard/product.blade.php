@extends('layouts.sidebar')

@section('content')
    @include('components.product.list')
    @include('components.product.create')
    @include('components.product.update')
    @include('components.product.delete')
@endsection
