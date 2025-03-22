@extends('layouts.sidebar')

@section('content')
    @include('components.category.list')

    @include('components.category.create')

    @include('components.category.update')

    @include('components.category.delete')
@endsection
