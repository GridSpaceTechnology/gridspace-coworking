@extends('layouts.dashboard')

@section('content')
    @include('admin.partials.subnav')
    @yield('admin_content')
@endsection
