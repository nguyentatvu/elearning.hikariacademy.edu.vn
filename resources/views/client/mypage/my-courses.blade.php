    @extends('client.shared.mypage')

    <?php
    $dr_trinhdo = [
        1 => 'N1',
        2 => 'N2',
        3 => 'N3',
        4 => 'N4',
        5 => 'N5',
        6 => 'Chữ cái',
    ];
    ?>

    @section('styles')
        <link href="{{ asset('css/pages/mypage.css') }}" rel="stylesheet">
        <style>
            .card-aside-img img {
                height: 100%;
                width: 100%;
            }

            .modal-dialog {
                max-width: 800px;
                width: 800px;
            }

            .qa-course-detail {
                color: #333;
                line-height: 1.25;
                margin-left: 20px;
            }

            .section-title {
                margin-bottom: 20px;
                font-size: 24px;
                font-weight: 700;
                padding-bottom: 0px;
            }

            .view-all-qa {
                font-size: 16px;
                border: 0;
                border-radius: 3px;
                padding: 4px;
                padding-right: 8px;
                padding-left: 8px;
            }

            .qa-lesson {
                margin-bottom: 30px;
            }

            .qa-lesson button {
                width: 100%;
                font-size: 24px;
                font-weight: 600;
                margin-bottom: 20px;
                border-width: 0;
                display: flex;
                justify-content: space-between;
                background-color: transparent;
                align-items: center;
            }

            .qa-lesson-container {
                display: flex;
                flex-direction: column;
                gap: 20px;
            }

            .qa-lesson .qa-content {
                background-color: #f9f9f9;
                border-radius: 5px;
                padding: 20px;
                display: flex;
                flex-direction: column;
                gap: 14px;
            }

            .qa-lesson .qa-content h2 {
                font-size: 20px;
                font-weight: 600;
                margin-top: 0;
                color: #1a0267;
            }

            .qa-lesson .qa-content p {
                margin: 0;
                color: #666;
                font-size: 1rem;
            }

            .qa-lesson .char-before {
                font-size: 18px;
                font-weight: 600;
            }

            .targeted-unit-lesson {
                display: flex;
                gap: 10px;
                font-size: 18px;
                display: inline-block;
            }

            .targeted-unit-lesson a {
                color: #ec296b;
                font-size: 1rem;
            }

            .targeted-unit-lesson a:hover {
                text-decoration: underline;
            }
        </style>
    @endsection

    @section('mypage-content')
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" style="max-width: 100px;" scope="col">STT</th>
                        <th class="text-center" style="max-width: 400px;" scope="col">KHÓA HỌC</th>
                        <th class="text-center" style="max-width: 200px;" scope="col">LỘ TRÌNH</th>
                        <th class="text-center" style="max-width: 200px;" scope="col">TRÌNH ĐỘ</th>
                        <th class="text-center" style="max-width: 100px;" scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @if ($series->isNotEmpty())
                        @foreach ($series as $index => $item)
                            <tr>
                                <td class="text-center align-middle w-50px">{{ $index + 1 }}</td>
                                <td class="mw-450px w-450px">
                                    <div class="media mt-0 mb-0">
                                        <div class="card-aside-img row">
                                            <div class="col-4 mw-150px">
                                                <img alt="{{ $item->title }}"
                                                    src="{{ asset('uploads/lms/combo/' . $item->combo_image) }}"
                                                    style="height: auto;">
                                                <br>
                                                <button type="button" class="btn btn-primary mt-3 w-100"
                                                    onclick="loadSeriesModal('{{ $item->slug }}', '{{ $item->combo_slug }}')"
                                                    data-toggle="modal"
                                                    data-target="#series_detail_modal_{{ $item->slug }}">
                                                    <i class="bi bi-info-circle"></i> Tổng quan
                                                </button>
                                            </div>
                                            <div
                                                class="media-bod col-8 d-flex align-items-center">
                                                <div class="card-item-desc ml-4 p-0 mt-2">
                                                    <?php $dr_time = ['3 tháng', '6 tháng', '12 tháng']; ?>
                                                    <a class="text-dark" href="#">
                                                        <h4 class="font-weight-semibold">{{ $item->title }}
                                                            ({{ $dr_time[$item->time] }})
                                                        </h4>
                                                    </a>
                                                    <span>Ngày mua:
                                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</span>
                                                    <?php
                                                    $dr_time = [90, 180, 365];
                                                    $dr_day = [0, 30, 90, 180, 365];
                                                    ?>
                                                    <br>
                                                    <span>Ngày hết hạn:
                                                        {{ \Carbon\Carbon::parse($item->created_at)->addDays($dr_time[$item->time] + $dr_day[$item->month_extend])->format('d-m-Y') }}</span>
                                                    <br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center align-middle mw-150px w-200px">
                                    @if ($item->user_roadmap === null || $item->user_roadmap->duration_months === null)
                                        Chưa chọn lộ trình
                                    @elseif ($item->user_roadmap->duration_months === 0)
                                        Lộ trình tự do
                                    @else
                                        @php
                                            $durationMonths = $item->user_roadmap->duration_months;

                                            if (isset($item->user_roadmap->contents)) {
                                                $contents = json_decode($item->user_roadmap->contents, true);
                                            }
                                        @endphp
                                        <div class="d-flex flex-column align-items-center">
                                            @if (isset($contents))
                                                {{ $durationMonths }} tháng - {{ count($contents) }} buổi học
                                            @endif
                                            <a class="btn btn-primary mt-2 w-100px" type="button" href="/roadmap/{{ $item->combo_slug }}/{{ $item->slug }}?month={{ $durationMonths }}">
                                                Xem chi tiết
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center align-middle mw-150px w-200px">
                                    <div class="media mt-0 mb-0">
                                        <div class="media-body">
                                            <div class="card-item-desc">
                                                <p class="mb-2">
                                                    <span class="fs-14 ml-2">
                                                        <i class="fa fa-star text-yellow mr-2"></i>
                                                        Hoàn thành:
                                                        {{ (int) isset($count_series) ?? 0 }}/{{ $item->total_course }} bài
                                                        học
                                                    </span>
                                                </p>
                                                <?php
                                                    $count = (int) isset($count_series) ?? 0;
                                                    $percent = $count > 0 ? (int) (($count / $item->total_course) * 100) : 0;
                                                    $percent = ($count / $item->total_course) * 100 > 0 && ($count / $item->total_course) * 100 < 1 ? 1 : $percent;
                                                ?>
                                                <div class="progress position-relative">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-secondary-custom"
                                                        role="progressbar" style="width: {{ $percent }}%"
                                                        aria-valuenow="{{ $percent }}" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                    <small
                                                        class="justify-content-center d-flex position-absolute w-100">{{ $percent }}%</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center align-middle mw-100px w-100px">
                                    <?php $dayEnd = \Carbon\Carbon::parse($item->created_at)
                                        ->addDays($dr_time[$item->time] + $dr_day[$item->month_extend])
                                        ->format('d-m-Y'); ?>
                                    @if ($item->status == 3)
                                        <p>Vô hiệu</p>
                                    @elseif (strtotime($dayEnd) < strtotime(now()))
                                        <p>Hết hạn</p>
                                    @else
                                        @if ($item->user_roadmap !== null &&
                                            $item->user_roadmap->duration_months !== 0 &&
                                            $item->user_roadmap->duration_months !== null)
                                            <a href="{{ route('learning-management.lesson.show', ['combo_slug' => $item->combo_slug, 'slug' => $item->slug]) }}"
                                                class="btn btn-primary mb-3 mb-xl-0">
                                                Học ngay
                                            </a>
                                        @elseif (optional($item->seriesCombo)->checkMultipleCombo)
                                            <a href="{{ route('series.introduction-detail-combo', ['combo_slug' => $item->combo_slug]) . '?series_action=scrollToList' }}"
                                                class="btn btn-primary mb-3 mb-xl-0">
                                                Học ngay
                                            </a>
                                        @else
                                            <a href="{{ route('series.introduction-detail', ['combo_slug' => $item->combo_slug, 'slug' => $item->slug]) . '?series_action=openRoadmapModal' }}"
                                                class="btn btn-primary mb-3 mb-xl-0">
                                                Học ngay
                                            </a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">
                                <h5 style="color: #ee2833!important" class="mb-0">Bạn chưa có {{ $title }}</h5>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    @endsection
    @section('mypage-scripts')
        <script>
            function toggleQA(event) {
                const btnToggle = $(event.target);
                btnToggle.closest('.qa-lesson').find('.qa-lesson-container').slideToggle();
            }

            function showAllQA() {
                $('.qa-lesson-container').show();
            }

            function loadSeriesModal(seriesSlug, comboSeriesSlug) {
                const $lmsSeriesModal = $('#series_detail_modal_' + seriesSlug);
                if ($lmsSeriesModal.length > 0) {
                    $lmsSeriesModal.modal('show');
                } else {
                    $.ajax({
                        url: '{{ route('lms.series.overview-content') }}?lms_combo_series_slug=' + comboSeriesSlug +
                            '&lms_series_slug=' + seriesSlug,
                        type: 'GET',
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        },
                        beforeSend: function() {
                            toggleLoadingOverlay();
                        },
                        success: function(response) {
                            $('.mypage-content').after(response);
                            $('#series_detail_modal_' + seriesSlug).modal('show');
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Thông báo',
                                text: 'Tạm thời không thể xem tổng quan khóa học, vui lòng thử lại sau.',
                                type: 'warning',
                                showConfirmButton: false,
                                showCancelButton: false,
                                timer: 1500,
                            });
                        },
                        complete: function() {
                            toggleLoadingOverlay();
                        }
                    });
                }
            }
        </script>
    @endsection
