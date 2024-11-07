@extends('client.shared.mypage')
@section('mypage-styles')
    <style>
        .modal-header {
            border: 0;
        }

        .reward-point__detail-item.streak-point {
            cursor: pointer;
        }
    </style>
@endsection
@section('mypage-content')
    <div class="reward-point">
        <div class="reward-point__wrapper">
            <div class="reward-point__overview">
                <div class="reward-point__overview-item">
                    <div class="d-flex align-items-center gap-1 fs-2 lh-1">
                        <img src="{{ asset('images/icons/coin.svg') }}" class="rounded-circle coin-size-lg">
                        {{ formatNumber($point_history['total'], '.') }}
                    </div>
                    <span>Tổng điểm</span>
                </div>
                <div class="reward-point__overview-item">
                    <div class="d-flex align-items-center gap-1 fs-2 lh-1">
                        <img src="{{ asset('images/icons/coin.svg') }}" class="rounded-circle coin-size-lg">
                        {{ formatNumber($point_history['used'], '.') }}
                    </div>
                    <span>Điểm đã sử dụng</span>
                </div>
            </div>
            <div class="reward-point__detail">
                <div class="reward-point__detail-item">
                    <img src="{{ asset('images/mypage/do-exercise.png') }}" alt="Do exercise">
                    <div class="d-flex gap-1 align-items-center">
                        <img src="{{ asset('images/icons/coin.svg') }}" class="rounded-circle coin-size">
                        <span>{{ formatNumber($point_history['exercise_test'], '.') }}</span>
                    </div>
                    <span>Bài tập & kiểm tra</span>
                </div>
                <div class="reward-point__detail-item">
                    <img src="{{ asset('images/mypage/watch-video.png') }}" alt="Watch video">
                    <div class="d-flex gap-1 align-items-center">
                        <img src="{{ asset('images/icons/coin.svg') }}" class="rounded-circle coin-size">
                        <span>{{ formatNumber($point_history['video'], '.') }}</span>
                    </div>
                    <span>Xem video</span>
                </div>
                <div class="reward-point__detail-item streak-point" onclick="openModalStreak()">
                    <img src="{{ asset('images/mypage/login-streak.png') }}" alt="Login streak">
                    <div class="d-flex gap-1 align-items-center">
                        <img src="{{ asset('images/icons/coin.svg') }}" class="rounded-circle coin-size">
                        <span>{{ formatNumber($point_history['streak'], '.') }}</span>
                    </div>
                    <span>Chuỗi</span>
                </div>
                <div class="reward-point__detail-item">
                    <img src="{{ asset('images/mypage/recharge-coin.png') }}" alt="Recharge coin">
                    <div class="d-flex gap-1 align-items-center">
                        <img src="{{ asset('images/icons/coin.svg') }}" class="rounded-circle coin-size">
                        <span>{{ formatNumber($point_history['recharge'], '.') }}</span>
                    </div>
                    <span>Nạp</span>
                </div>
            </div>
        </div>
        <div class="redeem-reward">
            <h4>Quy đổi điểm</h4>
            <div class="redeem-reward__list">
                @foreach ($redeemed_series_combo as $series)
                    <div class="redeem-reward__item {{ $series->is_payable ? '' : 'gray-filter' }}">
                        <div class="redeem-reward__img">
                            <div class="dark-overlay">
                                <div class="off-price-percent">Giảm {{ $series->redeemed_percent }}%</div>
                            </div>
                            <img src="{{ asset($series_combo_image_url . $series->image) }}" alt="series image">
                        </div>
                        <div class="redeem-reward__info">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="redeem-reward__title">{{ $series->title }}</span>
                                <div class="d-flex align-items-center">
                                    <div class="redeem-reward__score font-weight-bold">
                                        {{ $series->redeem_point }}
                                    </div>
                                    <img src="{{ asset('images/icons/coin.svg') }}" class="rounded-circle coin-size">
                                </div>
                            </div>
                            <div class="line-clamp-3 redeem-reward__description">
                                {!! $series->short_description !!}
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="font-weight-semibold fs-14">Giá:</span>
                                <div class="d-flex align-items-center">
                                    <span class="redeem-reward__new-price ms-1">
                                        {{ formatCurrencyVND($series->actualCost - $series->redeemed_amount) }}
                                    </span>
                                    <span class="redeem-reward__old-price">{{ formatCurrencyVND($series->actualCost) }}</span>
                                </div>
                            </div>
                        </div>
                        @if ($series->is_payable)
                            <div class="redeem-reward__submit">
                                <a href="{{ url('payments/lms/' . $series->slug . '?is_redeemed=1') }}">Quy đổi</a>
                            </div>
                        @else
                            <div class="redeem-reward__submit not-allowed">
                                <a href="#">Quy đổi</a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('mypage-scripts')
    <script>
        function openModalStreak() {
            $('#modalLoginStreak').modal('show');
        }
    </script>
    @include('client.components.streak');
@endsection
