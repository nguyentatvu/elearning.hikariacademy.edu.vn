@extends('app')

@section('styles')
<link href="{{ mix('css/pages/auth.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="w-100">
    <div class="container pb-5 mt-5">
        @yield('auth-content')
    </div>
</div>
@endsection