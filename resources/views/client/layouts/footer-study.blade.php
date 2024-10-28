<style>
    .custom-container {
        background-color: #f8f9fa;
        padding: 10px;
        position: relative;
    }

    .btn-custom {
        border-radius: 20px;
        border: 1px solid #00a2ff;
        color: #00a2ff;
        background-color: white;
    }

    .btn-custom:hover {
        background-color: #e6f7ff;
    }

    .btn-custom-primary {
        border-radius: 20px;
        background-color: var(--primary);
        color: white;
    }

    .btn-custom-primary:hover {
        background-color: #99c2ff;
    }

    .text-custom {
        font-weight: bold;
        font-size: 16px;
        position: absolute;
        right: 0;
    }

    @media (min-width: 768px) {
        .mobile-footer-study {
            display: none !important;
        }
    }

    @media (max-width: 767px) {
        .text-custom {
            position: relative;
        }

        .footer-study {
            display: none !important;
        }
    }
</style>

<div class="custom-container d-flex justify-content-center align-items-center footer-study">
    <span></span>
    <div>
        <button class="btn btn-custom" onclick="goToPreviousLesson()"><i class="bi bi-chevron-left"></i> bài trước</button>
        <button class="btn btn-custom-primary" onclick="goToNextLesson()">bài tiếp theo <i class="bi bi-chevron-right"></i></button>
    </div>
    <div class="text-custom me-4">
        {{ $detailContent->bai }}
    </div>
</div>

<div class="custom-container d-flex justify-content-between align-items-center mobile-footer-study">
    <button class="btn btn-custom" onclick="goToPreviousLesson()"><i class="bi bi-chevron-left"></i></button>
    <div class="text-custom">
        {{ $detailContent->bai }}
    </div>
    <button class="btn btn-custom-primary" onclick="goToNextLesson()"><i class="bi bi-chevron-right"></i></button>
</div>

<script>
    function goToNextLesson() {
        let seriesSlug = "{{ request()->route('slug') }}";
        let seriesComboSlug = "{{ request()->route('combo_slug') }}";
        let contentId = "{{ request()->route('stt') }}";

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            url: "{{ route('learning-management.next-lesson') }}",
            type: "get",
            data: {
                series_slug: seriesSlug,
                series_combo_slug: seriesComboSlug,
                content_id: contentId
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = response.url;
                }
            }
        });
    }

    function goToPreviousLesson() {
        let seriesSlug = "{{ request()->route('slug') }}";
        let seriesComboSlug = "{{ request()->route('combo_slug') }}";
        let contentId = "{{ request()->route('stt') }}";

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            url: "{{ route('learning-management.previous-lesson') }}",
            type: "get",
            data: {
                series_slug: seriesSlug,
                series_combo_slug: seriesComboSlug,
                content_id: contentId
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = response.url;
                }
            }
        });
    }
</script>