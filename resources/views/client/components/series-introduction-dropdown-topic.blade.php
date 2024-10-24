@foreach($contents as $content_index => $content)
    <div class="accordion-item">
        <h2 class="accordion-header" id="flush-heading{{ $chapter_index }}_{{ $content_index }}">
            <button class="accordion-button {{ $content->css_class === 'active-content' ? 'active-content' : 'collapsed' }}"
                data-bs-target="#flush-collapse{{ $chapter_index }}_{{ $content_index }}" aria-expanded="false"
                aria-controls="flush-collapse{{ $chapter_index }}_{{ $content_index }}"
                type="button" data-bs-toggle="collapse">
                @if ($seriesType == App\LmsSeriesCombo::EXAM_TYPE)
                    <img src="{{ asset('images/icons/score.png') }}" class="ms-3 me-1 size-20">
                @else
                    <img src="{{ asset('images/icons/' . config('constant.series.topic_icons')[$content_index]) ?? 'lesson.png' }}"
                        class="ms-3 me-1 size-20">
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
@endforeach