<div class="mypage-content modal-container d-flex flex-column" style="gap: 12px;">
    @if ($view_series_history->count() > 0)
        @foreach ($view_series_history as $series)
        <div class="course-item d-flex align-items-center">
            <img class="series-image" alt="series image" src="{{ '/public/uploads/lms/series/'.$series->image }}" />
            <div class="course-info flex-grow-1">
                <div class="course-title">{{ $series->title }}</div>
                <div class="course-time mb-1">Học cách đây {{ compareTime($series->viewed_time) }}</div>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-secondary-custom" role="progressbar"
                        aria-valuenow="{{ $series->progressPercent }}" aria-valuemin="0" aria-valuemax="100"
                        style="width: {{ $series->progressPercent }}%">
                        <span class="{{ $series->progressPercent <= 10 ? 'd-none' : '' }}">
                            {{ $series->progressPercent }}%
                        </span>
                    </div>
                    <span class="ms-1 text-primary {{ $series->progressPercent <= 10 ? '' : 'd-none' }}">
                        {{ $series->progressPercent }}%
                    </span>
                </div>
                @if ($series->roadmapChosen)
                    <a href="{{ route('learning-management.lesson.show', ['combo_slug' => $series->combo_slug, 'slug' => $series->slug]) }}"
                        class="text-primary mt-1 fs-6 d-block">
                        Tiếp tục học
                    </a>
                @elseif (optional($series->seriesCombo)->checkMultipleCombo)
                    <a href="{{ route('series.introduction-detail-combo', ['combo_slug' => $series->combo_slug]) . '?series_action=scrollToList' }}"
                        class="text-primary mt-1 fs-6 d-block">
                        Chọn lộ trình và học ngay
                    </a>
                @else
                    <a href="{{ route('series.introduction-detail', ['combo_slug' => $series->combo_slug, 'slug' => $series->slug]) . '?series_action=openRoadmapModal' }}"
                        class="text-primary mt-1 fs-6 d-block">
                        Chọn lộ trình và học ngay
                    </a>
                @endif
            </div>
        </div>
        @endforeach
    @else
        <div>
            <span>Bạn chưa học bài học nào.</span>
            <a href="/home" class="fs-5">Hãy chọn ngay cho mình một khoá học!</a>
        </div>
    @endif
</div>