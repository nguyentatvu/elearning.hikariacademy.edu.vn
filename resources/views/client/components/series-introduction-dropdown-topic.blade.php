@foreach($contents as $content_index => $content)
@if(in_array($content->type, [App\LmsContent::TEST_TRAFFIC_RULE, App\LmsContent::STRUCTURE]) || in_array($content->type, App\LmsContent::TEST_TOKUTEI_LIST))
    @if($is_valid_payment || $content->el_try == App\LmsContent::TRIAL_TYPE)
        <a href="{{ (isset($content->url) && $content->url) ? $content->url : 'javascript:void(0)' }}"
            class="topic-content-link {{ in_array($content->type, [App\LmsContent::STRUCTURE, App\LmsContent::TEST_TRAFFIC_RULE]) || in_array($content->type, App\LmsContent::TEST_TOKUTEI_LIST) ? 'pl-32' : ''}} {{ $content->css_class }}">
            <img src="{{ (isset($content->image) && !empty($content->image)) ? asset($content->image) : asset('images/icons/' . config('constant.series.chapter_icons')[$content->type]) }}"
                alt="image" class="chapter-image">
            <span>{{ $content->bai }}</span>
        </a>
    @else
        <a href="javascript:void(0);" class="text-dark topic-content-link">
            <i class="bi bi-lock-fill"></i>
            <span>Nội dung bị ẩn</span>
        </a>
    @endif
@else
    <div class="accordion-item">
        <h2 class="accordion-header" id="flush-heading{{ $chapter_index }}_{{ $content_index }}">
            <button class="accordion-button {{ $content->css_class === 'active-content' ? 'active-content' : 'collapsed' }}"
                data-bs-target="#flush-collapse{{ $chapter_index }}_{{ $content_index }}" aria-expanded="false"
                aria-controls="flush-collapse{{ $chapter_index }}_{{ $content_index }}"
                type="button" data-bs-toggle="collapse">
                @if ($seriesType == App\LmsSeriesCombo::EXAM_TYPE)
                    <img src="{{ (isset($content->image) && !empty($content->image)) ? asset($content->image) : asset('images/icons/score.png') }}" class="ms-3 me-1 size-20 object-fit-cover">
                @else
                    <img src="{{ (isset($content->image) && !empty($content->image)) ? asset($content->image) : asset('images/icons/' . config('constant.series.topic_icons')[min($content_index, 10)] ?? 'lesson.png') }}"
                        class="ms-3 me-1 size-20 object-fit-cover">
                @endif
                <span>{{ $content->bai }}</span>
            </button>
        </h2>
        <div id="flush-collapse{{ $chapter_index }}_{{ $content_index }}" class="accordion-collapse collapse {{ $content->css_class == 'active-content' ? 'show' : '' }}"
            aria-labelledby="flush-heading{{ $chapter_index }}_{{ $content_index }}" data-bs-parent="#accordion_flush_container_{{ $chapter_index }}_{{ $content_index }}">
            <div>
                <ul class="list-group">
                    @component('client.components.series-introduction-dropdown-lesson-link',
                        ['contents' => $content->childContents,
                        'chapter_index' => $chapter_index,
                        'is_valid_payment' => $is_valid_payment])
                    @endcomponent
                </ul>
            </div>
        </div>
    </div>
@endif
@endforeach