@extends('client.shared.mypage')

@section('mypage-content')
    <div class="reward-point">
        <div class="reward-point__overview">
            <div class="reward-point__overview-item">
                <div class="d-flex align-items-center gap-1 fs-2 lh-1">
                    <img src="{{ asset('images/coin.jpg') }}" class="rounded-circle coin-size-lg">
                    3.000
                </div>
                <span>Tổng điểm</span>
            </div>
            <div class="reward-point__overview-item">
                <div class="d-flex align-items-center gap-1 fs-2 lh-1">
                    <img src="{{ asset('images/coin.jpg') }}" class="rounded-circle coin-size-lg">
                    2.000
                </div>
                <span>Điểm đã sử dụng</span>
            </div>
        </div>
        <div class="reward-point__detail">
            <div class="reward-point__detail-item">
                <img src="{{ asset('images/mypage/do-exercise.png') }}" alt="Do exercise">
                <div class="d-flex gap-1 align-items-center">
                    <img src="{{ asset('images/coin.jpg') }}" class="rounded-circle coin-size">
                    <span>20</span>
                </div>
                <span>Bài tập & kiểm tra</span>
            </div>
            <div class="reward-point__detail-item">
                <img src="{{ asset('images/mypage/watch-video.png') }}" alt="Watch video">
                <div class="d-flex gap-1 align-items-center">
                    <img src="{{ asset('images/coin.jpg') }}" class="rounded-circle coin-size">
                    <span>40</span>
                </div>
                <span>Xem video</span>
            </div>
            <div class="reward-point__detail-item">
                <img src="{{ asset('images/mypage/login-streak.png') }}" alt="Login streak">
                <div class="d-flex gap-1 align-items-center">
                    <img src="{{ asset('images/coin.jpg') }}" class="rounded-circle coin-size">
                    <span>50</span>
                </div>
                <span>Chuỗi</span>
            </div>
            <div class="reward-point__detail-item">
                <img src="{{ asset('images/mypage/recharge-coin.png') }}" alt="Recharge coin">
                <div class="d-flex gap-1 align-items-center">
                    <img src="{{ asset('images/coin.jpg') }}" class="rounded-circle coin-size">
                    <span>60</span>
                </div>
                <span>Nạp</span>
            </div>
        </div>
        <div class="redeem-reward">
            <h4>Quy đổi điểm</h4>
            <div class="redeem-reward__list">
                <div class="redeem-reward__item">
                    <div class="redeem-reward__img">
                        <div class="dark-overlay">
                            <div class="off-price-percent">Giảm 50%</div>
                        </div>
                        <img src="https://staging.hikariacademy.edu.vn/public/uploads/lms/combo/3-image.png"
                            alt="series image">
                    </div>
                    <div class="redeem-reward__info">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="redeem-reward__title">Khóa luyện thi N4</span>
                            <div class="d-flex align-items-center">
                                <div class="redeem-reward__score font-weight-bold">550</div>
                                <img src="{{ asset('images/coin.jpg') }}" class="rounded-circle coin-size">
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="font-weight-semibold fs-14">Giá:</span>
                            <div class="d-flex align-items-center">
                                <span class="redeem-reward__new-price ms-1">50.000đ</span>
                                <span class="redeem-reward__old-price">600.000đ</span>
                            </div>
                        </div>
                    </div>
                    <div class="redeem-reward__submit">
                        <a href="#">Quy đổi</a>
                    </div>
                </div>
                <div class="redeem-reward__item">
                    <div class="redeem-reward__img">
                        <div class="dark-overlay">
                            <div class="off-price-percent">Giảm 50%</div>
                        </div>
                        <img src="https://staging.hikariacademy.edu.vn/public/uploads/lms/combo/3-image.png"
                            alt="series image">
                    </div>
                    <div class="redeem-reward__info">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="redeem-reward__title">Khóa luyện thi N4</span>
                            <div class="d-flex align-items-center">
                                <div class="redeem-reward__score font-weight-bold">550</div>
                                <img src="{{ asset('images/coin.jpg') }}" class="rounded-circle coin-size">
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="font-weight-semibold fs-14">Giá:</span>
                            <div class="d-flex align-items-center">
                                <span class="redeem-reward__new-price ms-1">50.000đ</span>
                                <span class="redeem-reward__old-price">600.000đ</span>
                            </div>
                        </div>
                    </div>
                    <div class="redeem-reward__submit">
                        <a href="#">Quy đổi</a>
                    </div>
                </div>
                <div class="redeem-reward__item">
                    <div class="redeem-reward__img">
                        <div class="dark-overlay">
                            <div class="off-price-percent">Giảm 50%</div>
                        </div>
                        <img src="https://staging.hikariacademy.edu.vn/public/uploads/lms/combo/3-image.png"
                            alt="series image">
                    </div>
                    <div class="redeem-reward__info">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="redeem-reward__title">Khóa luyện thi N4</span>
                            <div class="d-flex align-items-center">
                                <div class="redeem-reward__score font-weight-bold">550</div>
                                <img src="{{ asset('images/coin.jpg') }}" class="rounded-circle coin-size">
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="font-weight-semibold fs-14">Giá:</span>
                            <div class="d-flex align-items-center">
                                <span class="redeem-reward__new-price ms-1">50.000đ</span>
                                <span class="redeem-reward__old-price">600.000đ</span>
                            </div>
                        </div>
                    </div>
                    <div class="redeem-reward__submit">
                        <a href="#">Quy đổi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection