<div class="mypage-content">
    @if ($view_series_history->count() > 0)
        @foreach ($view_series_history as $series)
        <div class="course-item d-flex align-items-center pb-3">
            <img class="series-image" alt="series image" src="{{ '/public/uploads/lms/combo/'.$series->image }}" />
            <div class="course-info flex-grow-1">
                <div class="course-title">{{ $series->title }}</div>
                <div class="course-time mb-1">Học cách đây {{ compareTime($series->viewed_time) }}</div>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
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
                <a href="{{ route('learning-management.lesson.show', ['combo_slug' => $series->combo_slug, 'slug' => $series->slug]) }}"
                    class="text-primary mt-1 fs-5 d-block">
                    Tiếp tục học
                </a>
            </div>
        </div>
        @endforeach
    @else
        <div>
            <span>Bạn chưa học bài học nào.</span>
            <a href="#" class="fs-5">Hãy chọn ngay cho mình một khoá học!</a>
        </div>
    @endif
</div>