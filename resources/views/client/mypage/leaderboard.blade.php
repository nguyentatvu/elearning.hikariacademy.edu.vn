@extends('client.shared.mypage')

@section('mypage-content')
    <div class="leaderboard">
        <div class="leaderboard__top-3">
            <div class="leaderboard__top py-4">
                <div class="leaderboard__top--avatar">
                    <img src="https://avatar.iran.liara.run/public" alt="student avatar" />
                    <span>Rose</span>
                </div>
                <div class="leaderboard__top--point">
                    <img src="{{ asset('images/mypage/top-2.png') }}" alt="top 2">
                    <div class="leaderboard__top--point-detail">
                        <span class="point">22</span>
                        <span>Điểm</span>
                    </div>
                </div>
            </div>
            <div class="leaderboard__top">
                <div class="leaderboard__top--avatar">
                    <img src="https://avatar.iran.liara.run/public/boy" alt="student avatar" />
                    <span>David</span>
                </div>
                <div class="leaderboard__top--point first-place">
                    <img src="{{ asset('images/mypage/top-1.png') }}" alt="top 2">
                    <div class="leaderboard__top--point-detail">
                        <span class="point">24</span>
                        <span>Điểm</span>
                    </div>
                </div>
            </div>
            <div class="leaderboard__top py-4">
                <div class="leaderboard__top--avatar">
                    <img src="https://avatar.iran.liara.run/public" alt="student avatar" />
                    <span>Jessica</span>
                </div>
                <div class="leaderboard__top--point">
                    <img src="{{ asset('images/mypage/top-3.png') }}" alt="top 2">
                    <div class="leaderboard__top--point-detail">
                        <span class="point">21</span>
                        <span>Điểm</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="leaderboard__list">
            <table>
                <thead>
                    <tr class="fw-bold">
                        <th>Hạng</th>
                        <th>Ảnh đại diện</th>
                        <th>Học viên</th>
                        <th class="text-end">Tổng điểm</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="rank">4</td>
                        <td><img src="https://avatar.iran.liara.run/public" alt="Roland" class="avatar"></td>
                        <td>Roland</td>
                        <td class="score">20</td>
                    </tr>
                    <tr>
                        <td class="rank">5</td>
                        <td><img src="https://avatar.iran.liara.run/public" alt="Larissa Santos" class="avatar"></td>
                        <td>Larissa Santos</td>
                        <td class="score">19</td>
                    </tr>
                    <tr>
                        <td class="rank">6</td>
                        <td><img src="https://avatar.iran.liara.run/public" alt="Gabrielly Tavares" class="avatar"></td>
                        <td>Gabrielly Tavares</td>
                        <td class="score">16</td>
                    </tr>
                    <tr>
                        <td class="rank">7</td>
                        <td><img src="https://avatar.iran.liara.run/public" alt="Renan Matos" class="avatar"></td>
                        <td>Renan Matos</td>
                        <td class="score">12</td>
                    </tr>
                    <tr>
                        <td class="rank">8</td>
                        <td><img src="https://avatar.iran.liara.run/public" alt="Hugo Souza" class="avatar"></td>
                        <td>Hugo Souza</td>
                        <td class="score">8</td>
                    </tr>
                    <tr>
                        <td class="rank">9</td>
                        <td><img src="https://avatar.iran.liara.run/public" alt="Jessica Silva" class="avatar"></td>
                        <td>Jessica Silva</td>
                        <td class="score">5</td>
                    </tr>
                    <tr>
                        <td class="rank text-primary">10</td>
                        <td><img src="https://avatar.iran.liara.run/public" alt="You" class="avatar"></td>
                        <td class="text-primary">You</td>
                        <td class="score">3</td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div class="fs-5 my-2">Hạng hiện tại</div>
                        </td>
                    </tr>
                    <tr class="current-user">
                        <td class="rank text-primary">10</td>
                        <td><img src="https://avatar.iran.liara.run/public" alt="You" class="avatar"></td>
                        <td class="text-primary">You</td>
                        <td class="score">3</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection