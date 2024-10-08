@extends('client.shared.mypage')

@section('mypage-content')
    <div class="leaderboard">
        <div class="leaderboard__top-3">
            <div class="leaderboard__top py-4">
                <div class="leaderboard__top--avatar">
                    @if ($leaderboard[1]->user->image)
                        <img src="{{ getFullUserImage($leaderboard[1]->user->image) }}" alt="student avatar" />
                    @else
                        <img src="{{ asset('images/no-avatar.png') }}" alt="student avatar">
                    @endif
                    <span class="line-clamp-2">{{ $leaderboard[1]->user->name }}</span>
                </div>
                <div class="leaderboard__top--point">
                    <img src="{{ asset('images/mypage/top-2.png') }}" alt="top 2">
                    <div class="leaderboard__top--point-detail">
                        <span class="point">{{ $leaderboard[1]->user->reward_point }}</span>
                        <span class="">Điểm</span>
                    </div>
                </div>
            </div>
            <div class="leaderboard__top">
                <div class="leaderboard__top--avatar">
                    @if ($leaderboard[0]->user->image)
                        <img src="{{ getFullUserImage($leaderboard[0]->user->image) }}" alt="student avatar" />
                    @else
                        <img src="{{ asset('images/no-avatar.png') }}" alt="student avatar">
                    @endif
                    <span class="line-clamp-2">{{ $leaderboard[0]->user->name }}</span>
                </div>
                <div class="leaderboard__top--point first-place">
                    <img src="{{ asset('images/mypage/top-1.png') }}" alt="top 2">
                    <div class="leaderboard__top--point-detail">
                        <span class="point">{{ $leaderboard[0]->user->reward_point }}</span>
                        <span class="">Điểm</span>
                    </div>
                </div>
            </div>
            <div class="leaderboard__top py-4">
                <div class="leaderboard__top--avatar">
                    @if ($leaderboard[2]->user->image)
                        <img src="{{ getFullUserImage($leaderboard[2]->user->image) }}" alt="student avatar" />
                    @else
                        <img src="{{ asset('images/no-avatar.png') }}" alt="student avatar">
                    @endif
                    <span>{{ $leaderboard[2]->user->name }}</span>
                </div>
                <div class="leaderboard__top--point">
                    <img src="{{ asset('images/mypage/top-3.png') }}" alt="top 2">
                    <div class="leaderboard__top--point-detail">
                        <span class="point">{{ $leaderboard[2]->user->reward_point }}</span>
                        <span class="">Điểm</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="leaderboard__list">
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
                                    <img src="{{ getFullUserImage($ranking->user->image) }}" alt="student avatar" class="avatar" />
                                @else
                                    <img src="{{ asset('images/no-avatar.png') }}" alt="student avatar" class="avatar">
                                @endif
                            </td>
                            <td>{{ $ranking->user->name }}</td>
                            <td class="score">{{ $ranking->reward_point }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3">
                            <div class="fs-5 my-2">Hạng hiện tại</div>
                        </td>
                    </tr>
                    <tr class="current-user">
                        <td class="rank text-primary">{{ $userRank->rank }}</td>
                        <td>
                        @if ($ranking->user->image)
                            <img src="{{ getFullUserImage($ranking->user->image) }}" alt="student avatar" class="avatar" />
                        @else
                            <img src="{{ asset('images/no-avatar.png') }}" alt="student avatar" class="avatar">
                        @endif
                        </td>
                        <td class="text-primary">Bạn</td>
                        <td class="score">{{ $userRank->reward_point }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection