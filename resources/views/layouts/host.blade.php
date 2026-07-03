@extends('layouts.dashboard')

@section('content')
    @include('host.partials.subnav')
    @yield('host_content')
@endsection
