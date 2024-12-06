@extends('client.app')

@section('content')
    <div class="stie-container">
        <h1 class="text-center mt-4 text-primary">{{ $title }}</h1>
        <div class="p-2">
            @switch($key)
                @case('privacy-policy')
                    @include('admin.site.privacy')
                @break;
                @case('terms-conditions')
                    @include('admin.site.termsconditions')
                @break;
                @case('payment-instructions-vnpay')
                    @include('admin.site.payment-instructions-vnpay')
                @break;
                @case('payment-instructions-momo')
                    @include('admin.site.payment-instructions-momo')
                @break;
            @endswitch
        </div>
    </div>
@endsection
