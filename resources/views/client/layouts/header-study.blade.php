<style>
    /* .navbar-custom {
        background-color: #000000;
        color: white;
    } */

    .navbar-custom .navbar-brand,
    .navbar-custom .nav-link {
        color: white;
    }


    .navbar-custom .navbar-brand img {
        height: 100%;
    }

    .navbar-custom .navbar-brand span {
        font-size: 1.25rem;
    }
    .progress {
        width: 100px;
    }

    .progress-text {
        color: white;
    }
</style>

<nav class="navbar navbar-custom bg-primary header-study">
    <div class="container-fluid">
        <a class="navbar-brand me-2 d-flex align-items-center justify-content-center" href="#">
            <i class="bi bi-chevron-left"></i>
            <span class="ms-2 d-inline-block">{{ $series->title }}</span>
        </a>
        <div class="d-flex align-items-center">
            @if (Auth::check())
                <div class="header-my-coin me-3">
                    <a href="{{ route('mypage.reward-point') }}" class="owned-point text-white">
                        {{ formatNumber(Auth::user()->reward_point + Auth::user()->recharge_point) }}
                    </a>
                    <img src="{{ asset('images/icons/coin.svg') }}" class="rounded-circle object-fit-cover">
                    <div class="hicoin-animation">
                        <span class="me-1 fs-5 text-white">+<span class="increased-point"></span></span>
                        <img width="20" alt="hi-coin" src="{{ asset('images/icons/coin.svg') }}">
                    </div>
                </div>
            @endif
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75"
                    aria-valuemin="0" aria-valuemax="100" style="width: 35%">35%</div>
            </div>
            <div class="progress-text mx-2">74/204 bài học</div>
        </div>
    </div>
</nav>
