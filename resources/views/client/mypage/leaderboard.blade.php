@extends('client.shared.mypage')

@section('mypage-styles')
    <link href="{{ asset('css/pages/mypage.css') }}" rel="stylesheet">
@endsection

@section('mypage-content')
    <div class="leaderboard">
        @if ($leaderboard->isEmpty())
            <span class="text-center highlight-message w-full">
                Hãy là người đầu tiên tích lũy điểm và dẫn đầu bảng xếp hạng!
            </span>
        @endif
        <div class="leaderboard-wrapper">
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
                                    <img src="{{ getFullUserImage(Auth::user()->image) }}"
                                        alt="student avatar" class="avatar" />
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
