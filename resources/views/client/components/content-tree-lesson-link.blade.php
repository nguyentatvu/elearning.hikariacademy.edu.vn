@if ($is_valid_payment)
    @foreach($contents as $content_index => $content)
    <li class="list-group-item px-5 d-flex align-items-center {{ $content->css_class }}">
        {{-- @php
            $contentStatus = $content->checkBlockedContent($testContentResult);
            $contentLink = $contentStatus['isContentBlocked'] ? 'javascript:void(0);' : $content->url;
            $clickEvent = $contentStatus['isContentBlocked'] ? `showBLockedContentAlert({$contentStatus['ineligibleTestTitle']})` : '';
        @endphp --}}
        {{-- @php
            $isContentBlocked = true;
            $checkTestContentExists = count($testContentResult) > 0;
            $ineligibleTestTitle = '';

            foreach ($testContentResult as $testContentId =>$testResult) {
                if ($testContentId >= $content->id && $testResult['is_passed']) {
                    $isContentBlocked = false;
                    break;
                } else if ($ineligibleTestTitle == '') {
                    $ineligibleTestTitle = $testResult['title'];
                }
            }
            $isContentBlocked = $isContentBlocked && $checkTestContentExists;
            $contentLink = $isContentBlocked ? 'javascript:void(0);' : $content->url;
            $clickEvent = $isContentBlocked ? `showBLockedContentAlert({$ineligibleTestTitle})` : '';
        @endphp --}}
        {{-- <a href="{{ $content->url }}" class="text-dark"> --}}
        <a href="{{ $content->contentLink }}" class="text-dark" onclick="{{ $content->clickEvent }}">
            @if ($is_valid_payment || $chapter_index !== 0)
                <img src="{{ asset("images/icons/{$content->checkbox_icon}") }}"alt="check box"
                style="bottom: 1px;" class="position-relative size-16 me-1" data-content-id="{{ $content->id }}">
            @else
                <img src="{{ asset("images/icons/empty-box.svg") }}"alt="check box" style="bottom: 1px;"
                    class="position-relative me-1">
            @endif
            <span>{{ $content->bai }}</span>
        </a>
        @if (!empty($content->download_doc))
            <a href="{{ asset($content->download_doc) }}" class="btn p-0 download-link" target="_blank"
                download>
                <i class="bi bi-file-earmark-arrow-down download-icon"></i>
            </a>
        @endif
    </li>
    @endforeach
@else
    @php $purchased_content_count = 0; @endphp
    @foreach($contents as $content_index => $content)
        @if ($content->el_try == App\LmsContent::TRIAL_TYPE)
            <li class="list-group-item px-5 d-flex align-items-center {{ $content->css_class }}">
                <a href="{{ $content->url }}" class="text-dark">
                    @if ($is_valid_payment || $chapter_index !== 0)
                        <img src="{{ asset("images/icons/{$content->checkbox_icon}") }}"alt="check box"
                            style="bottom: 1px;" class="position-relative size-16 me-1" data-content-id="{{ $content->id }}">
                    @else
                        <img src="{{ asset("images/icons/empty-box.svg") }}"alt="check box"
                            style="bottom: 1px;" class="position-relative me-1">
                    @endif
                    <span>{{ $content->bai }}</span>
                </a>
                @if (!empty($content->download_doc))
                    <a href="{{ asset($content->download_doc) }}" class="btn p-0 download-link" target="_blank"
                        download>
                        <i class="bi bi-file-earmark-arrow-down download-icon"></i>
                    </a>
                @endif
            </li>
        @else
            @php $purchased_content_count++; @endphp
        @endif
    @endforeach
    @if ($purchased_content_count > 0)
    <li class="list-group-item px-5">
        <strong role="button" onclick="{{ Auth::check() ? 'showBuyCourseModal()' : 'showAuthModal(true)' }}">
            Có {{ $purchased_content_count }} bài học bị ẩn
            <small><i class="bi bi-lock-fill text-primary"></i></small>
        </strong>
    </li>
    @endif
@endif