@foreach($contents as $content_index => $content)
    @php $checkNotRoadmapChosen = !$is_roadmap_chosen && $isValidPayment @endphp
    @if ($content->childContents->isNotEmpty())
        <div class="accordion-item {{ $checkNotRoadmapChosen ? 'disabled' : ''  }}">
            <h2 class="accordion-header" id="heading{{ $content_index }}">
                <div class="accordion-button d-block {{ $content->css_class === 'active-content' ? 'active-content' : 'collapsed' }}"
                    data-bs-toggle="collapse" data-bs-target="#collapse{{ $content_index }}"
                    aria-expanded="{{ $content_index === 1 ? 'true' : 'false' }}"
                    @if ($checkNotRoadmapChosen) onclick="openRoadmapSelectionModal({{ $series->id }})" @endif
                    aria-controls="collapse{{ $content_index }}" type="button">
                    <div class="fw-bold">
                        <img src="{{ (isset($content->image) && !empty($content->image)) ? asset($content->image) : asset('images/icons/lesson.png') }}" alt="image" class="chapter-image">
                        <span>{{ $content->bai }}</span>
                    </div>
                </div>
            </h2>
            <div id="collapse{{ $content_index }}" class="accordion-collapse {{ $content->css_class == 'active-content' ? 'show' : 'collapse' }}"
                aria-labelledby="heading{{ $content_index }}" data-bs-parent="#accordion_container{{ $content_index }}">
                <div>
                    <div class="accordio" id="accordion_flush_container_{{ $content_index }}">
                        <div class="accordion accordion-flush" id="accordion_flush_container_{{ $content_index }}">
                            @component('client.components.series-introduction-dropdown-topic',
                                ['contents' => $content->childContents,
                                'chapter_index' => $content_index,
                                'is_valid_payment' => $isValidPayment,
                                'seriesType' => $seriesType])
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="accordion-item {{ $content->css_class }} {{ (!$is_roadmap_chosen && $isValidPayment) ? 'disabled' : ''  }}"
            @if ($checkNotRoadmapChosen) onclick="openRoadmapSelectionModal({{ $series->id }})" @endif>
            @if ($content->type == App\LmsContent::SUMMARY_AND_INTRODUCTION && $content->el_try != App\LmsContent::TRIAL_TYPE && !$isValidPayment)
                <a href="javascript:void(0)" class="topic-content-link" onclick={{ Auth::check() ? 'showBuyCourseModal()' : 'showAuthModal(true)' }}>
                    <i class="bi bi-lock-fill text-primary"></i>
                    <span>Nội dung bị ẩn</span>
                </a>
            @else
                <a href="{{ ($isValidPayment || $content->type === App\LmsContent::SUMMARY_AND_INTRODUCTION) ? $content->url : 'javascript:void(0)' }}" class="topic-content-link">
                    <img src="{{ (isset($content->image) && !empty($content->image)) ? asset($content->image) : asset("images/icons/" . config('constant.series.chapter_icons')[$content->type]) }}" alt="image" class="chapter-image">
                    <span>{{ $content->bai }}</span>
                </a>
            @endif
        </div>
    @endif
@endforeach