@foreach($contents as $content_index => $content)
    <div class="accordion-item">
        <h2 class="accordion-header" id="flush-heading{{ $chapter_index }}_{{ $content_index }}">
            <button class="d-flex align-items-center accordion-button {{ $content->css_class === 'active-content' ? 'active-content' : 'collapsed' }}"
                data-bs-target="#flush-collapse{{ $chapter_index }}_{{ $content_index }}" aria-expanded="false"
                aria-controls="flush-collapse{{ $chapter_index }}_{{ $content_index }}"
                type="button" data-bs-toggle="collapse">
                @if ($isFreeSeries)
                    <img src="{{ (isset($content->image) && !empty($content->image)) ? asset($content->image) : asset('images/icons/sound.png') }}" class="ms-3 me-1 size-20 object-fit-cover">
                @elseif ($seriesType == App\LmsSeriesCombo::EXAM_TYPE)
                    <img src="{{ (isset($content->image) && !empty($content->image)) ? asset($content->image) : asset('images/icons/score.png') }}" class="ms-3 me-1 size-20 object-fit-cover">
                @else
                    <img src="{{ (isset($content->image) && !empty($content->image)) ? asset($content->image) : asset('images/icons/' . config('constant.series.topic_icons')[$content_index]) ?? 'lesson.png' }}"
                        class="ms-3 me-1 size-20 object-fit-cover">
                @endif
                <span>{{ $content->bai }}</span>
                @if (!empty($content->download_doc))
                    <a href="{{ asset($content->download_doc) }}" class="btn p-0 download-link" target="_blank"
                        download>
                        <i class="bi bi-file-earmark-arrow-down download-icon"></i>
                    </a>
                @endif
            </button>
        </h2>
        <div id="flush-collapse{{ $chapter_index }}_{{ $content_index }}" class="accordion-collapse collapse {{ $content->css_class == 'active-content' ? 'show' : '' }}"
            aria-labelledby="flush-heading{{ $chapter_index }}_{{ $content_index }}" data-bs-parent="#accordion_flush_container_{{ $chapter_index }}">
            <div>
                <ul class="list-group">
                    @component('client.components.content-tree-lesson-link',[
                        'contents' => $content->childContents,
                        'chapter_index' => $chapter_index,
                        'is_valid_payment' => $is_valid_payment,
                        'testContentResult' => $testContentResult
                    ])
                    @endcomponent
                </ul>
            </div>
        </div>
    </div>
@endforeach