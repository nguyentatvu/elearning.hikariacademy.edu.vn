@extends('app')

@section('styles')
    <link href="{{ mix('css/pages/mypage.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="w-100">
        <div class="container pb-5">
            <a href="#" class="navigate-back">
                <i class="bi bi-chevron-left fs-4"></i>
                <span class="fs-3">Điểm tích lũy</span>
            </a>
            <div class="mypage-navigation">
                <nav class="mypage-navigation__list">
                    <ul>
                        <li><a href="#">Trang cá nhân</a></li>
                        <li><a href="{{ url('mypage/reward-point') }}" class="{{ setActiveClass('mypage/reward-point') }}">Điểm tích lũy</a></li>
                        <li><a href="{{ url('mypage/leaderboard') }}" class="{{ setActiveClass('mypage/leaderboard') }}">Bảng xếp hạng</a></li>
                        <li><a href="#">Khóa học</a></li>
                        <li><a href="#">Khóa luyện thi</a></li>
                        <li><a href="#">Câu hỏi của bạn</a></li>
                        <li><a href="#">Phòng thi của bạn</a></li>
                        <li><a href="#">Kết quả thi</a></li>
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