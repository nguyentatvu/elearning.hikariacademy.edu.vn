<style>
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
        box-shadow: rgba(9, 30, 66, 0.25) 0px 4px 8px -2px, rgba(9, 30, 66, 0.08) 0px 0px 0px 1px;
    }

    .progress-text {
        color: white;
    }
</style>

<nav class="navbar navbar-custom bg-primary header-study">
    <div class="container-fluid">
        <a class="navbar-brand me-2 d-flex align-items-center justify-content-center" href="{{ $prevUrl }}">
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
            @php
                $contentProgression = (int) (($contentViewCount / $seriesContentCount) * 100);
                $contentProgression = (($contentViewCount / $seriesContentCount) * 100) < 1 && (($contentViewCount / $seriesContentCount) * 100) > 0 ? 1 : $contentProgression;
            @endphp
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-secondary-custom" role="progressbar"
                    aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: {{ $contentProgression }}%">
                    <span class="{{ $contentProgression <= 30 ? 'd-none' : '' }}">{{ $contentProgression }}%</span>
                </div>
                <span class="ms-1 text-primary {{ $contentProgression <= 30 ? '' : 'd-none' }}">
                    {{ $contentProgression }}%
                </span>
            </div>
            <div class="progress-text mx-2">{{ $contentViewCount }}/{{ $seriesContentCount }} bài học</div>
        </div>
    </div>
</nav>
