@extends('client.shared.mypage')

@section('mypage-styles')
    <link href="{{ asset('css/pages/mypage.css') }}" rel="stylesheet">
    <style>
        .leaderboard-wrapper__top--point {
            max-height: 320px;
        }

        @media (max-width: 500px) {
            .leaderboard-wrapper__top--point {
                max-height: 300px;
            }

            .leaderboard .leaderboard-wrapper__top--avatar {
                width: 70px;
                height: 70px;
            }

            .leaderboard .leaderboard-wrapper__top--point {
                width: 90%;
            }
        }

        @media (max-width: 378px) {
            .leaderboard .leaderboard-wrapper__top--point {
                width: 70px;
            }
        }

        .ctd2024_wrapper {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .ctd2024_container {
            background: linear-gradient(135deg, #fce4ec, #e3f2fd);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 100%;
            width: 100%;
            padding: 10px;
            animation: ctd2024_floatIn 1s ease-out;
        }

        .ctd2024_title {
            color: #5c6bc0;
            font-size: 1rem;
            margin-bottom: 5px;
            font-weight: bold;
            position: relative;
            display: inline-block;
        }

        .ctd2024_timer_grid {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .ctd2024_time_block {
            background: linear-gradient(135deg, #bbdefb, #90caf9);
            padding: 10px 5px;
            border-radius: 20px;
            min-width: 30px;
            width: 100px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .ctd2024_time_block::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
            animation: ctd2024_sparkle 2s infinite linear;
        }

        .ctd2024_time_block:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .ctd2024_number {
            font-size: 1.25rem;
            font-weight: bold;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 8px;
            position: relative;
        }

        .ctd2024_label {
            font-size: 1rem;
            color: #fff;
            letter-spacing: 1px;
            position: relative;
        }

        .ctd2024_label::after {
            font-size: 20px;
        }

        @keyframes ctd2024_floatIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes ctd2024_sparkle {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        @keyframes ctd2024_bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .ctd2024_ending_soon {
            animation: ctd2024_bounce 1s infinite;
            background: linear-gradient(135deg, #ffcdd2, #ef9a9a);
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .ctd2024_timer_grid {
                gap: 15px;
            }

            .ctd2024_time_block {
                min-width: 90px;
                padding: 15px 10px;
            }

            .ctd2024_number {
                font-size: 32px;
            }

            .ctd2024_title {
                font-size: 20px;
            }
        }
    </style>
@endsection

@section('mypage-content')
    <div class="leaderboard card-section">
        <div class="ctd2024_wrapper">
            <div class="ctd2024_container">
                <div class="ctd2024_title">Bảng xếp hạng sẽ được cập nhật sau:</div>
                <div class="ctd2024_timer_grid">
                    <div class="ctd2024_time_block" id="ctd2024_days_block">
                        <div class="ctd2024_number" id="ctd2024_days">00</div>
                        <div class="ctd2024_label">Ngày</div>
                    </div>
                    <div class="ctd2024_time_block" id="ctd2024_hours_block">
                        <div class="ctd2024_number" id="ctd2024_hours">00</div>
                        <div class="ctd2024_label">Giờ</div>
                    </div>
                    <div class="ctd2024_time_block" id="ctd2024_minutes_block">
                        <div class="ctd2024_number" id="ctd2024_minutes">00</div>
                        <div class="ctd2024_label">Phút</div>
                    </div>
                    <div class="ctd2024_time_block" id="ctd2024_seconds_block">
                        <div class="ctd2024_number" id="ctd2024_seconds">00</div>
                        <div class="ctd2024_label">Giây</div>
                    </div>
                </div>
            </div>
        </div>
        @if ($leaderboard->isEmpty())
            <span class="text-center highlight-message w-full">
                Hãy là người đầu tiên tích lũy điểm và dẫn đầu bảng xếp hạng!
            </span>
        @endif
        <div class="leaderboard-wrapper p-3">
            <div class="leaderboard-wrapper__top-3">
                @php
                    $userTop1 = $leaderboard[0]->user ?? null;
                    $userTop2 = $leaderboard[1]->user ?? null;
                    $userTop3 = $leaderboard[2]->user ?? null;
                @endphp
                <div class="leaderboard-wrapper__top py-4">
                    <div class="leaderboard-wrapper__top--avatar">
                        <img src="{{ $userTop2 && $userTop2->image ? getFullUserImage($userTop2->image) : asset('images/no-avatar.png') }}"
                            alt="student avatar" />
                        <span class="line-clamp-2">{{ $userTop2 ? $userTop2->name : '?' }}</span>
                    </div>
                    <div class="leaderboard-wrapper__top--point">
                        <img src="{{ asset('images/mypage/top-2.png') }}" alt="top 2">
                        <div class="leaderboard-wrapper__top--point-detail">
                            <span class="point">{{ $userTop2 ? $userTop2->reward_point : '--' }}</span>
                            <span class="">Điểm</span>
                        </div>
                    </div>
                </div>
                <div class="leaderboard-wrapper__top">
                    <div class="leaderboard-wrapper__top--avatar">
                        <img src="{{ $userTop1 && $userTop1->image ? getFullUserImage($userTop1->image) : asset('images/no-avatar.png') }}"
                            alt="student avatar" />
                        <span class="line-clamp-2">{{ $userTop1 ? $userTop1->name : '?' }}</span>
                    </div>
                    <div class="leaderboard-wrapper__top--point first-place">
                        <img src="{{ asset('images/mypage/top-1.png') }}" alt="top 1">
                        <div class="leaderboard-wrapper__top--point-detail">
                            <span class="point">{{ $userTop1 ? $userTop1->reward_point : '--' }}</span>
                            <span class="">Điểm</span>
                        </div>
                    </div>
                </div>
                <div class="leaderboard-wrapper__top py-4">
                    <div class="leaderboard-wrapper__top--avatar">
                        <img src="{{ $userTop3 && $userTop3->image ? getFullUserImage($userTop3->image) : asset('images/no-avatar.png') }}"
                            alt="student avatar" />
                        <span class="line-clamp-2">{{ $userTop3 ? $userTop3->name : '?' }}</span>
                    </div>
                    <div class="leaderboard-wrapper__top--point">
                        <img src="{{ asset('images/mypage/top-3.png') }}" alt="top 3">
                        <div class="leaderboard-wrapper__top--point-detail">
                            <span class="point">{{ $userTop3 ? $userTop3->reward_point : '--' }}</span>
                            <span class="">Điểm</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="leaderboard-wrapper__list">
                <table>
                    <thead>
                        <tr class="fw-bold">
                            <th>Hạng</th>
                            <th>Avatar</th>
                            <th>Học viên</th>
                            <th class="text-end">Tổng điểm</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leaderboard->slice(3) as $index => $ranking)
                            <tr>
                                <td class="rank">{{ $ranking->rank }}</td>
                                <td>
                                    @if ($ranking->user->image)
                                        <img src="{{ getFullUserImage($ranking->user->image) }}" alt="student avatar"
                                            class="avatar" />
                                    @else
                                        <img src="{{ asset('images/no-avatar.png') }}" alt="student avatar" class="avatar">
                                    @endif
                                </td>
                                <td>{{ $ranking->user->name }}</td>
                                <td class="score">{{ $ranking->reward_point }}</td>
                            </tr>
                        @endforeach
                        @for ($i = $leaderboard->count(); $i < 10; $i++)
                            <tr>
                                <td class="rank">{{ $i + 1 }}</td>
                                <td>
                                    <img src="{{ asset('images/no-avatar.png') }}" alt="student avatar" class="avatar">
                                </td>
                                <td class="text-primary">Vị trí này đang đợi người chinh phục!</td>
                                <td class="score">--</td>
                            </tr>
                        @endfor
                        <tr>
                            <td colspan="3">
                                <div class="fs-5 my-2">Hạng hiện tại</div>
                            </td>
                        </tr>
                        <tr class="current-user">
                            <td class="rank text-primary">{{ optional($userRank)->rank ?? 'Chưa xếp hạng' }}</td>
                            <td>
                                @if (Auth::user() && Auth::user()->image)
                                    <img src="{{ getFullUserImage(Auth::user()->image) }}" alt="student avatar"
                                        class="avatar" />
                                @else
                                    <img src="{{ asset('images/no-avatar.png') }}" alt="student avatar" class="avatar">
                                @endif
                            </td>
                            <td class="text-primary">Bạn</td>
                            <td class="score">{{ optional($userRank)->reward_point ?? '--' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('mypage-scripts')
    <script>
        $(document).ready(function() {
            function getNextMonday3PM() {
                let now = new Date();
                let nextMonday = new Date();
                nextMonday.setDate(now.getDate() + (8 - now.getDay()));
                nextMonday.setHours(15, 0, 0, 0);
                return nextMonday;
            }

            function updateCountdown() {
                let now = new Date();
                let end = getNextMonday3PM();
                let diff = end - now;

                if (diff < 0) {
                    end = getNextMonday3PM();
                    diff = end - now;
                }

                let days = Math.floor(diff / (1000 * 60 * 60 * 24));
                let hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((diff % (1000 * 60)) / 1000);

                $('#ctd2024_days').text(String(days).padStart(2, '0'));
                $('#ctd2024_hours').text(String(hours).padStart(2, '0'));
                $('#ctd2024_minutes').text(String(minutes).padStart(2, '0'));
                $('#ctd2024_seconds').text(String(seconds).padStart(2, '0'));

                if (diff < 24 * 60 * 60 * 1000) {
                    $('.ctd2024_time_block').addClass('ctd2024_ending_soon');
                } else {
                    $('.ctd2024_time_block').removeClass('ctd2024_ending_soon');
                }
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);
        });
    </script>
@endsection
