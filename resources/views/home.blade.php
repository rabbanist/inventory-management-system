@extends('layouts.app')

@section('content')
    {{-- Navbar  --}}
    @include('components.home.navbar')

    {{-- Hero  --}}
    @include('components.home.hero')

    {{-- Testimonial  --}}
    @include('components.home.testimonial')

    {{-- Footer  --}}
    @include('components.home.footer')
@endsection
