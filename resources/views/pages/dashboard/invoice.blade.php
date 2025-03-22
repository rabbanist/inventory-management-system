@extends('layouts.sidebar')

@section('content')
    @include('components.invoice.list')
    @include('components.invoice.details')
    @include('components.invoice.delete')
@endsection
