@php
    function getUrl($lms_type, $lms_series_slug, $lms_combo_series_slug, $lmsseries_id, $comment_id)
    {
        $lesson_types = [1, 2, 6, 9];
        $audit_types = [5];
        $exercise_types = [3, 4];
        $flashcard_types = [10];

        $path_maps = [
            'lesson_types' => 'show',
            'audit_types' => 'audit',
            'exercise_types' => 'exercise',
            'flashcard_types' => 'flashcard',
        ];

        foreach ($path_maps as $content_constant => $path) {
            $content_types = $$content_constant;
            if (in_array($lms_type, $content_types)) {
                return PREFIX .
                    "learning-management/lesson/$path/$lms_combo_series_slug/$lms_series_slug/$lmsseries_id?comment_id=$comment_id";
            }
        }

        return PREFIX .
            "learning-management/lesson/show/$lms_combo_series_slug/$lms_series_slug/$lmsseries_id?comment_id=$comment_id";
    }
@endphp

<div class="modal fade" id="series_detail_modal_{{ $lms_series_slug }}" tabindex="-1"
    aria-labelledby="series_detail_modal_{{ $lms_series_slug }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="section-title">
                    <i class="fa fa-comments"></i>
                    <span>Danh sách Q&A: {{ $lms_series_title }}</span>
                    <button onclick="showAllQA()" class="btn btn-primary view-all-qa">
                        <i class="fa fa-arrows-v me-1"></i>
                        Xem tất cả
                    </button>
                </div>
                <div class="qa-course-detail">
                    @foreach ($lms_series_comments as $lms_content_comments)
                        @php
                            if (empty($lms_content_comments)) {
                                continue;
                            }
                        @endphp
                        <div class="qa-lesson">
                            <button type="button" class="btn btn-link" onclick="toggleQA(event)">
                                <span>{{ $lms_content_comments['content_title'] }}</span>
                                <i class="fa fa-angle-down" style="font-size: 34px;"></i>
                            </button>
                            <div class="qa-lesson-container" style="display: none;">
                                @foreach ($lms_content_comments as $key => $lms_content_comment)
                                    @php
                                        if (!is_numeric($key)) {
                                            continue;
                                        }
                                    @endphp
                                    <div class="qa-content">
                                        <div class="d-flex">
                                            <span class="char-before me-2">Q:</span>
                                            <h2>{{ $lms_content_comment['body'] ?? '' }}</h2>
                                        </div>
                                        <div class="d-flex">
                                            <span class="char-before me-2">A:</span>
                                            @if (isset($lms_content_comment['child_comments']) && !empty($lms_content_comment['child_comments']))
                                                <p>{{ $lms_content_comment['child_comments'][0]['body'] ?? '' }}</p>
                                            @else
                                                <p>Tạm thời giáo viên chưa thể trả lời câu hỏi này!</p>
                                            @endif
                                        </div>
                                        <div class="targeted-unit-lesson">
                                            <span class="fw-semibold">Bài:</span>
                                            <a target="_blank"
                                                href="{{ getUrl($lms_content_comment['type'] ?? 1, $lms_series_slug, $lms_combo_series_slug, $lms_content_comment['lmscontent_id'], $lms_content_comment['id']) }}">
                                                {{ $lms_content_comment['breadcrumb'] ?? '' }}</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>