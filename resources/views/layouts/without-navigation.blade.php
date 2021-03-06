@extends('layouts.master')

@section('layout')

    @include('includes.header', ['navigation' => false])

    <div class="container">
        @yield('content')
    </div>

    @include('includes.footer')

@endsection
