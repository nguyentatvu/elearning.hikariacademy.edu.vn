@extends('client.app')

@section('styles')
    <link href="{{ asset('css/pages/mypage.css') }}" rel="stylesheet">
    @yield('mypage-styles')
@endsection

@section('content')
    <div class="w-100">
        <div class="container pb-5">
            <a href="/" class="navigate-back">
                <i class="bi bi-chevron-left fs-4"></i>
                <span class="fs-3">Điểm tích lũy</span>
            </a>
            <div class="mypage-navigation">
                <nav class="mypage-navigation__list">
                    <ul>
                        <li><a href="{{ url('mypage/my-personal') }}" class="{{ setActiveClass('mypage/my-personal') }}">Trang cá nhân</a></li>
                        <li><a href="{{ url('mypage/reward-point') }}" class="{{ setActiveClass('mypage/reward-point') }}">Điểm tích lũy</a></li>
                        <li><a href="{{ url('mypage/leaderboard') }}" class="{{ setActiveClass('mypage/leaderboard') }}">Bảng xếp hạng</a></li>
                        <li><a href="{{ url('mypage/my-courses') }}" class="{{ setActiveClass('mypage/my-courses') }}">Khóa học</a></li>
                        <li><a href="{{ url('mypage/my-exams') }}" class="{{ setActiveClass('mypage/my-exams') }}">Khóa luyện thi</a></li>
                        <li><a href="{{ url('mypage/my-comments') }}" class="{{ setActiveClass('mypage/my-comments') }}">Câu hỏi của bạn</a></li>
                        <li><a href="{{ url('mypage/leaderboard') }}" class="{{ setActiveClass('mypage/leaderboard') }}">Phòng thi của bạn</a></li>
                        <li><a href="{{ url('mypage/my-result-exam') }}" class="{{ setActiveClass('mypage/my-result-exam') }}">Kết quả thi</a></li>
                        <li><a href="{{ url('mypage/payment-management') }}" class="{{ setActiveClass('mypage/payment-management') }}">Quản lý thanh toán</a></li>
                        <li><a href="{{ url('mypage/recharge-point') }}" class="{{ setActiveClass('mypage/recharge-point') }}">Nạp</a></li>
                    </ul>
                </nav>
            </div>
            <div class="mypage-content">
                @yield('mypage-content')
            </div>
        </div>
    </div>
@endsection