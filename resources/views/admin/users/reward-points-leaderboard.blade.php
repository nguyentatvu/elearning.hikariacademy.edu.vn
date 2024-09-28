@extends($layout)

@section('header_scripts')
    <style>
        .reward-points-leaderboard .card-body {
            display: flex;
            justify-content: center;
            font-family: Arial, sans-serif;
        }

        .leaderboard {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-top: 70px;

            @media (max-width: 768px) {
                margin-top: 0;
            }
        }

        .reward-points-top {
            display: flex;
            justify-content: space-around;
            width: 100%;
            position: relative;
            margin-bottom: 20px;

            @media (max-width: 768px) {
                display: none;
            }
        }

        .student {
            text-align: center;
            color: #2d3847;
            font-weight: 600;
        }

        .student-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #fff;
            margin: 0 auto;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 30px;
            color: #fff;
        }

        .rank {
            position: absolute;
            top: -40px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 24px;
            font-weight: 700;
        }

        .rank-1 {
            color: #ec286b;
        }

        .rank-2 {
            color: #f39c12;
        }

        .rank-3 {
            color: #3498db;
        }

        #rank_1 {
            top: -40px;
            position: absolute;
        }

        .boder-rank-1 {
            border: 2px solid #ec286b;
        }

        .boder-rank-2 {
            border: 2px solid #f39c12;
        }

        .boder-rank-3 {
            border: 2px solid #3498db;
        }

        .student-circle img.avatar-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .crown {
            position: absolute;
            top: -40px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 60px;
        }

        .points-top {
            font-size: 24px;
            margin-top: 10px;
        }

        .reward-points-table,
        .my-reward-points {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 80%;

            @media (max-width: 768px) {
                width: 100%;
            }
        }

        .reward-points-table table,
        .my-reward-points table {
            width: 100%;
            box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
            border-radius: 0.5rem;
        }

        .table td {
            vertical-align: middle;
        }

        .reward-points-table table tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .reward-points-table img.avatar {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            background-color: #fff;
            border: 1px solid #b2b2b2;

            @media (max-width: 768px) {
                width: 32px;
                height: 32px;
            }
        }

        .my-reward-points img.avatar {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            background-color: #fff;
            border: 1px solid #ec286b;

            @media (max-width: 768px) {
                width: 32px;
                height: 32px;
            }
        }

        .reward-points-leaderboard tr {
            display: flex;
            transition: all 0.2s ease-in-out;
            border-radius: 0.2rem;
            align-items: center;
        }

        .reward-points-leaderboard tr:hover {
            background-color: #fff;
            transform: scale(1.1);
            -webkit-box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
            box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
        }

        .my-reward-points table tr {
            color: #ec286b;
        }

        td.number {
            width: 10%;
            text-align: center;

            @media (max-width: 768px) {
                padding-left: 5px;
                padding-right: 5px;
                width: 15%;
            }
        }

        td.image {
            width: 10%;

            @media (max-width: 768px) {
                width: 15%;
                padding-left: 5px;
                padding-right: 5px;
            }
        }

        td.name {
            width: 65%;
            text-align: left;

            @media (max-width: 768px) {
                width: 55%;
                display: -webkit-box;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 2;
                overflow: hidden;
                word-break: break-word;
                padding: 0px 10px;
            }
        }

        td.points {
            width: 15%;
            text-align: right;
            font-weight: 600;
            margin-right: 10px;

            @media (max-width: 768px) {
                width: 25%;
                padding-left: 0px;
                margin-right: 0px;
            }
        }

        .my-rank {
            display: flex;
            justify-content: flex-start;
            color: #ec286b;
            margin-left: 10px;
        }

        .name-top {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            max-width: 170px;

            @media (max-width: 1024px) {
                max-width: 120px;
            }
        }

        .reward-points-leaderboard .card-title {
            @media (max-width: 768px) {
                font-size: 14px;
            }
        }

        .reward-points-leaderboard .card-header {
            display: flex;
            justify-content: space-between;

            @media (max-width: 768px) {
                display: flex;
                flex-direction: column;
            }
        }

        #countdown {
            font-size: 18px;
            color: #ec286b;
            text-align: right;

            @media (max-width: 768px) {
                font-size: 14px;
            }
        }
    </style>
@endsection

@section('content')
    <div id="page-wrapper" class="reward-points-leaderboard">
        <div class="card mb-0">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h3 class="card-title mb-0">
                        {{ $title }}
                    </h3>
                    <i class="fa fa-exclamation-circle text-primary ml-2" data-toggle="tooltip" data-placement="top"
                        title="Đây là bảng xếp hạng dựa trên điểm tích lũy của học sinh. Điểm tích lũy được tính từ các hoạt động xem video và làm bài tập, bài kiểm tra.">
                    </i>
                </div>
                <div class="d-flex align-items-center justify-content-end">
                    <span>Kết thúc trong: </span>
                    <div id="countdown" class="ml-3"></div>
                </div>
            </div>
            <div class="card-body">
                @if (isset($leaderboard) && isset($user))
                    <div class="leaderboard">
                        <div class="reward-points-top">
                            <div class="student" id="rank_2">
                                <div class="student-circle boder-rank-2">
                                    <div class="rank rank-2">
                                        {{ $leaderboard[1]->rank }}
                                    </div>
                                    <img src="{{ getProfilePath($leaderboard[1]->user->image) }}" class="avatar-top"
                                        alt="Avatar">
                                </div>
                                <div class="points-top rank-2">{{ $leaderboard[1]->reward_point }}</div>
                                <div class="name-top">{{ $leaderboard[1]->user->name }}</div>
                            </div>
                            <div class="student" id="rank_1">
                                <div class="student-circle boder-rank-1">
                                    <img src="/public/images/reward-point/crown.png" class="crown" alt="Crown" />
                                    <img src="{{ getProfilePath($leaderboard[0]->user->image) }}" class="avatar-top"
                                        alt="Avatar">
                                </div>
                                <div class="points-top rank-1">{{ $leaderboard[0]->reward_point }}</div>
                                <div class="name-top">{{ $leaderboard[0]->user->name }}</div>
                            </div>
                            <div class="student" id="rank_3">
                                <div class="student-circle boder-rank-3">
                                    <div class="rank rank-3">
                                        {{ $leaderboard[2]->rank }}
                                    </div>
                                    <img src="{{ getProfilePath($leaderboard[2]->user->image) }}" class="avatar-top"
                                        alt="Avatar">
                                </div>
                                <div class="points-top rank-3">{{ $leaderboard[2]->reward_point }}</div>
                                <div class="name-top">{{ $leaderboard[2]->user->name }}</div>
                            </div>
                        </div>
                        <div class="reward-points-table">
                            <table class="table">
                                @foreach ($leaderboard as $index => $student)
                                    <tr>
                                        <td class="number">{{ $student->rank }}</td>
                                        <td class="image"><img src="{{ getProfilePath($student->user->image) }}"
                                                class="avatar" alt="Avatar">
                                        </td>
                                        <td class="name">{{ $student->user->name }}</td>
                                        <td class="points">{{ $student->reward_point }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="my-rank">
                            <span class="icon mr-2">
                                <i class="icon icon-trophy">
                                </i>
                            </span>
                            <p>Thứ hạng của bạn</p>
                        </div>
                        <div class="my-reward-points">
                            <table class="table">
                                <tr>
                                    <td class="number">{{ $user->rank }}</td>
                                    <td class="image"><img src="{{ getProfilePath($user->user->image) }}" class="avatar"
                                            alt="Avatar"></td>
                                    <td class="name">{{ $user->user->name }}</td>
                                    <td class="points">{{ $user->reward_point }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="no-data">
                        Không có dữ liệu
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
            updateCountdown();
        })

        // Create countdown starting from monday every week
        function updateCountdown() {
            const now = new Date();
            const dayOfWeek = now.getDay();

            // Calculate the number of seconds from the current time to 0:00 Monday
            let targetDate = new Date(now);
            targetDate.setDate(now.getDate() + ((7 - dayOfWeek + 1) % 7));
            targetDate.setHours(0, 0, 0, 0);

            // If it's 0:00 Monday, set a goal for next week
            if (now >= targetDate) {
                targetDate.setDate(targetDate.getDate() + 7);
            }

            const difference = targetDate - now;
            const days = Math.floor(difference / (1000 * 60 * 60 * 24));
            const hoursRemaining = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutesRemaining = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
            const secondsRemaining = Math.floor((difference % (1000 * 60)) / 1000);

            $('#countdown').html(
                `${days} ngày ${hoursRemaining} giờ ${minutesRemaining} phút ${secondsRemaining} giây`
            );

            setTimeout(updateCountdown, 1000);
        }
    </script>
@endsection
