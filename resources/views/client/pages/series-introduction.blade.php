@extends('client.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/series-introduction.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/swiperjs/swiper-bundle.min.css') }}">
@endSection

@section('content')
    <div class="series-introduction">
        <div class="banner-section">
            <img src="{{ asset('images/banner/series-banner-placeholder.png') }}" alt="Banner" class="banner-img">
            <div class="course-box">
                <div class="course-content">
                    <div class="course-header">
                        <span class="fs-2 fw-bold course-title" style="line-height: 1.1;">{{ $seriesCombo->title }}</span>
                        <span class="fs-5">Thời gian: {{ $seriesCombo->month_duration }} tháng</span>
                    </div>
                    <div class="price-roadmap-learn-container">
                        <div class="price-tag-container">
                            <div class="price-tag">
                                <div class="price-label">GIÁ ƯU ĐÃI</div>
                                <div class="text-center mb-2 d-flex">
                                    <span class="currency">VNĐ</span>
                                    <span class="amount">{{ formatNumber($seriesCombo->cost) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="button-container">
                            @if (!$seriesCombo->checkMultipleCombo && $seriesCombo->cost != 0)
                                <button class="roadmap-btn"
                                    onclick="location.href='{{ route('home.roadmap', ['comboSlug' => $seriesCombo->slug, 'slug' => request()->route('slug')]) }}'">
                                    <div>
                                        <i class="bi bi-rocket-takeoff-fill"></i>
                                        Lộ trình
                                    </div>
                                </button>
                            @endif
                            @if ($seriesCombo->cost != 0 && Auth::check() && $isValidPayment && $is_multiple_combo)
                                {{-- Student has purchased the series combo and it include multiple serises --}}
                                <button class="purchase-btn" id="first_purchase_button"
                                    onclick="scrollToPurchasedSeriesList()">
                                    <div><i class="bi bi-book me-1"></i>Học ngay</div>
                                </button>
                            @elseif ($seriesCombo->cost != 0 && Auth::check() && $isValidPayment && !$is_multiple_combo && !$roadmap_chosen_list[$series->id])
                                {{-- Student has purchased the series combo and it's a single series and student hasn't chosen roadmap --}}
                                <button class="purchase-btn" id="first_purchase_button"
                                    onclick="openRoadmapSelectionModal({{ $series->id }})">
                                    <div><i class="bi bi-book me-1"></i>Học ngay</div>
                                </button>
                            @elseif ($seriesCombo->cost == 0 || (Auth::check() && $isValidPayment))
                                {{-- Student has purchased the series combo and it's a single series and student has chosen roadmap --}}
                                {{-- Or The series combo is free --}}
                                <button class="purchase-btn" id="first_purchase_button"
                                    onclick="location.href='{{ route('learning-management.lesson.show', ['combo_slug' => $seriesCombo->slug, 'slug' => request()->route('slug')]) }}'">
                                    <div><i class="bi bi-book me-1"></i>Học ngay</div>
                                </button>
                            @elseif (Auth::check() && !$isValidPayment)
                                {{-- Student has signed in but hasn't purchased the series combo --}}
                                <button class="purchase-btn" id="first_purchase_button"
                                    onclick="location.href='{{ route('payments.lms', $seriesCombo->slug) }}'">
                                    <div><i class="bi bi-cart-fill"></i>Mua ngay</div>
                                </button>
                            @else
                                {{-- Student hasn't signed in --}}
                                <button class="purchase-btn" id="first_purchase_button" onclick="showAuthModal()">
                                    <div><i class="bi bi-cart-fill"></i>Mua ngay</div>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="description-section">
            <div class="description-container">
                <div class="series-content">
                    <p class="fs-5 fw-bold"><i class="bi bi-file-earmark-text"></i> Nội dung: </p>
                    @if (isset($series_description['content_description']))
                        {!! $series_description['content_description'] !!}
                    @endif
                </div>
                <div class="series-info">
                    @if (isset($series_description['time_description']))
                        <div class="mb-3">
                            <span class="fs-5 fw-bold"><i class="bi bi-clock"></i> Thời gian: </span>
                            <span>{!! $series_description['time_description'] !!}</span>
                        </div>
                    @endif
                    @if (isset($series_description['curriculum_description']))
                        <div class="mb-3">
                            <span class="fs-5 fw-bold"><i class="bi bi-clipboard-data"></i> Giáo trình: </span>
                            <span>{!! $series_description['curriculum_description'] !!}</span>
                        </div>
                    @endif
                    @if (isset($series_description['teacher_description']))
                        <div class="mb-3">
                            <span class="fs-5 fw-bold"><i class="bi bi-person"></i> Giảng viên: </span>
                            <span>{!! $series_description['teacher_description'] !!}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @if (!$is_multiple_combo)
            <div class="content-section">
                <div class="d-flex content-section-wrapper">
                    <div class="lesson-list-dropdown">
                        <div class="d-flex align-items-baseline justify-content-between my-2">
                            <h4 class="my-2" style="width: fit-content">Nội dung khoá học</h4>
                        </div>
                        <div class="accordion" id="accordion_container">
                            @include('client.components.series-introduction-dropdown', [
                                'contents' => $contents,
                                'is_roadmap_chosen' => $roadmap_chosen_list[$series->id]
                            ])
                        </div>
                    </div>
                    <div class="overview-series">
                        <img src="{{ asset('/public/' . config('constant.series_combo.upload_path') . $seriesCombo->image) }}"
                            alt="series image">
                        @if ($seriesCombo->cost != 0 && Auth::check() && $isValidPayment && !$roadmap_chosen_list[$series->id])
                            {{-- Student has purchased the series combo and student hasn't chosen roadmap --}}
                            <button class="btn btn-primary"
                                onclick="openRoadmapSelectionModal({{ $series->id }})">
                                Học ngay
                            </button>
                        @elseif ($seriesCombo->cost == 0 || (Auth::check() && $isValidPayment))
                            {{-- Student has purchased the series combo student has chosen roadmap --}}
                            {{-- Or The series combo is free --}}
                            <button class="btn btn-primary"
                                onclick="location.href='{{ route('learning-management.lesson.show', ['combo_slug' => $seriesCombo->slug, 'slug' => request()->route('slug')]) }}'">
                                Học ngay
                            </button>
                        @elseif (Auth::check() && !$isValidPayment)
                            {{-- Student has signed in but hasn't purchased the series combo --}}
                            <button class="btn btn-primary"
                                onclick="location.href='{{ route('payments.lms', $seriesCombo->slug) }}'">
                                Mua ngay
                            </button>
                        @else
                            {{-- Student hasn't signed in --}}
                            <button class="btn btn-primary" onclick="showAuthModal()">
                                Mua ngay
                            </button>
                        @endif
                        <div class="overview-stats">
                            <div class="stat-item">
                                <img src="{{ asset('images/icons/open-book.png') }}" alt="icon" class="icon-img">
                                <span>Số chương:</span>
                                <span class="text-dark">{{ $seriesCombo->chapter_count }}</span>
                            </div>
                            <div class="stat-item">
                                <img src="{{ asset('images/icons/film.png') }}" alt="icon" class="icon-img">
                                <span>Tổng số:</span>
                                <span class="text-dark">{{ $seriesCombo->content_count }} bài học</span>
                            </div>
                            <div class="stat-item">
                                <img src="{{ asset('images/icons/battery.png') }}" alt="icon" class="icon-img">
                                <span>Học mọi lúc mọi nơi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <h4 class="mt-4 series-combo-title">Chi tiết gói Combo</h4>
            <div class="series-combo-section row">
                <div class="col-lg-8 series-item-section">
                    @foreach ($seriesCombo->seriesList as $itemSeries)
                        <div class="series-card card align-items-center">
                            <img src="{{ asset('/public/' . config('constant.series.upload_path') . $itemSeries->image) }}"
                                alt="series image" class="series-image">
                            <div class="series-card-body">
                                <div class="d-flex justify-content-between">
                                    <span class="fs-4 fw-bold">{{ $itemSeries->title }}</span>
                                    <a href="{{ route('series.introduction-detail', ['combo_slug' => $itemSeries->comboSeries->slug, 'slug' => $itemSeries->slug]) }}"
                                        class="btn more-info-btn">Xem thêm</a>
                                </div>
                                <div class="d-flex gap-2 series-stats">
                                    <span>Thời gian: <strong>{{ $itemSeries->month_duration }}</strong> tháng</span>
                                    <span>Bài học: <strong>{{ $itemSeries->content_count }}</strong></span>
                                </div>
                                <span class="fw-light short-description">{!! $itemSeries->short_description !!}</span>
                                <div class="text-center mt-auto">
                                    @if ($roadmap_chosen_list[$itemSeries->id] && $isValidPayment)
                                        <button class="btn bg-secondary text-white btn-lg px-2 py-1 rounded-pill shadow-sm" style="min-width: 260px;"
                                            onclick="window.location.href='{{ route('learning-management.lesson.show', ['combo_slug' => $seriesCombo->slug, 'slug' => $itemSeries->slug]) }}'">
                                            <i class="bi bi-play-circle-fill me-2"></i>
                                            Học ngay
                                        </button>
                                    @elseif ($isValidPayment)
                                        <button class="btn bg-secondary text-white btn-lg px-2 py-1 rounded-pill shadow-sm"
                                            onclick="openRoadmapSelectionModal('{{ $itemSeries->id }}')">
                                            <i class="bi bi-play-circle-fill me-2"></i>
                                            Chọn lộ trình & bắt đầu học
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-lg-4 col-md-6 payment-card-container">
                    <div class="payment-card card">
                        <div class="ribbon-top-left">
                            <span>Khuyến mãi</span>
                        </div>
                        <img src="{{ asset('/public/' . config('constant.series_combo.upload_path') . $seriesCombo->image) }}"
                            alt="combo series image" class="series-combo-img">
                        <div class="d-flex gap-2 justify-content-end align-items-baseline mt-2">
                            <span class="fs-5 text-decoration-underline">Giá: </span>
                            <strong class="fs-4 combo-series-price">{{ formatCurrencyVND($seriesCombo->cost) }}</strong>
                        </div>
                        @if (Auth::check() && $isValidPayment)
                            <div class="btn btn-primary mt-2 fs-5 purchase-btn opacity-50">
                                Đã mua
                            </div>
                        @elseif (Auth::check() && !$isValidPayment)
                            <button class="btn btn-primary mt-2 fs-5 purchase-btn"
                                onclick="location.href='{{ route('payments.lms', $seriesCombo->slug) }}'">
                                Mua ngay
                            </button>
                        @else
                            <button class="btn btn-primary mt-2 fs-5 purchase-btn" onclick="showAuthModal()">
                                Mua ngay
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        <div class="other-series-section">
            <h4 class="align-self-baseline mt-3">Gợi ý các khoá học</h4>
            <div class="swiper swiper-container">
                <div class="swiper-wrapper">
                    @foreach ($other_combo_series as $recommended_series)
                        @if (isset($recommended_series->seriesList) && count($recommended_series->seriesList) > 0)
                            <div class="swiper-slide">
                                <div class="course-card"
                                    @if ($recommended_series->checkMultipleCombo)
                                        onclick="location.href='{{ route('series.introduction-detail-combo', ['combo_slug' => $recommended_series->slug]) }}'"
                                    @else
                                        onclick="location.href='{{ route('series.introduction-detail', ['combo_slug' => $recommended_series->slug, 'slug' => $recommended_series->seriesList[0]->slug]) }}'"
                                    @endif
                                >
                                    <img alt="course image" height="400" width="600"
                                        src="{{ asset('/public/' . config('constant.series_combo.upload_path') . $recommended_series->image) }}" />
                                    <div class="course-card-body">
                                        <div class="course-card-title line-clamp-2">
                                            <h5 class="course-card-text">{{ $recommended_series->title }}</h5>
                                        </div>
                                        <div class="d-flex justify-content-between gap-2 align-items-center card-price-container mb-1">
                                            <div class="d-flex flex-column align-items-baseline">
                                                <p class="course-card-price mb-0">{{ $recommended_series->actualCost == 0 ? 'Miễn phí' : formatCurrencyVND($recommended_series->actualCost) }}</p>
                                                @if ($recommended_series->checkPromotion)
                                                    <span class="orginal-price">{{ formatCurrencyVND($recommended_series->cost) }}</span>
                                                @endif
                                            </div>
                                            @if ($recommended_series->seriesList[0]->hasTrialContent && !$recommended_series->checkMultipleCombo && !$recommended_series->valid_payment)
                                                <button class="trial-btn btn py-1"
                                                    onclick="event.stopPropagation(); location.href='{{ route('learning-management.lesson.show', ['combo_slug' => $recommended_series->slug, 'slug' => $recommended_series->seriesList[0]->slug]) }}'">
                                                    Học thử
                                                </button>
                                            @endif
                                        </div>

                                        <div class="course-card-description line-clamp-3">{!! $recommended_series->short_description !!}</div>
                                        <div class="course-card-teacher text-muted w-100 mb-1">{!! $recommended_series->description['teacher_description'] ?? '' !!}
                                        </div>
                                        <div class="d-flex align-items-center text-primary-color mt-3">
                                            <i class="bi bi-play-circle-fill"></i>
                                            <span class="ms-2">{{ $recommended_series->content_count }}</span>
                                            <i class="bi bi-book ms-3"></i>
                                            <span
                                                class="ms-2">{{ empty($recommended_series->chapter_count) ? 1 : $recommended_series->chapter_count }}
                                                chương</span>
                                            {{-- @if ($recommended_series->checkMultipleCombo)
                                                <button class="btn btn-outline-primary ms-auto"
                                                    onclick="event.stopPropagation(); location.href='{{ route('series.introduction-detail-combo', ['combo_slug' => $recommended_series->slug]) }}'">
                                                    Xem thêm
                                                </button>
                                            @else
                                                <button class="btn btn-outline-primary ms-auto"
                                                    onclick="event.stopPropagation(); location.href='{{ route('series.introduction-detail', ['combo_slug' => $recommended_series->slug, 'slug' => $recommended_series->seriesList[0]->slug]) }}'">
                                                    Xem thêm
                                                </button>
                                            @endif --}}
                                        </div>
                                        @if (Auth::check() && $recommended_series->valid_payment && count($recommended_series->seriesList) > 1)
                                            <button class="btn btn-primary w-100 mt-3"
                                                onclick="event.stopPropagation(); location.href='{{ route('series.introduction-detail-combo', ['combo_slug' => $recommended_series->slug]) . '?series_action=scrollToList' }}'">
                                                Học ngay
                                            </button>
                                        @elseif ($recommended_series->cost == 0 || (Auth::check() && $recommended_series->valid_payment && count($recommended_series->seriesList) == 1))
                                            @if (!$recommended_series->checkAllSeriesRoadmapOfSeriesComboChosen($roadmap_chosen_list) && $recommended_series->cost !== 0)
                                                <button class="btn btn-primary w-100 mt-3"
                                                    onclick="event.stopPropagation(); location.href='{{ route('series.introduction-detail', ['combo_slug' => $recommended_series->slug, 'slug' => $recommended_series->seriesList[0]->slug]) . '?series_action=openRoadmapModal' }}'">
                                                    Học ngay
                                                </button>
                                            @else
                                                <button class="btn btn-primary w-100 mt-3"
                                                    onclick="event.stopPropagation(); location.href='{{ route('learning-management.lesson.show', ['combo_slug' => $recommended_series->slug, 'slug' => $recommended_series->seriesList[0]->slug]) }}'">
                                                    Học ngay
                                                </button>
                                            @endif
                                        @elseif (Auth::check())
                                            <button class="btn btn-primary w-100 mt-3"
                                                onclick="event.stopPropagation(); location.href='{{ route('payments.lms', $recommended_series->slug) }}'">
                                                Mua ngay
                                            </button>
                                        @else
                                            <button class="btn btn-primary w-100 mt-3" onclick="showAuthModalWithStopPropagation(event, true)">
                                                Mua ngay
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
        <div class="modal fade select-roadmap-modal" id="selectRoadmapModal" tabindex="-1" aria-labelledby="selectRoadmapModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title w-100 text-center" id="hikariModalLabel">
                            <img src="{{ asset('images/Logo-hikari.png') }}" alt="Hikari logo" class="modal-logo">
                            <span class="ms-2">Chào mừng bạn đến với Hikari!</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="welcome-container">
                            <p class="lead mb-4 p-3 border border-primary border-2 rounded-2 bg-light position-relative text-center">
                                <i class="bi bi-stars text-warning me-2"></i>
                                <span class="fw-bold text-primary">Cùng bắt đầu hành trình học tập nhé!</span>
                                <img src="{{ asset('images/icons/coin.svg') }}" alt="Coin Icon" class="ms-2 mb-1" width="20">
                                <svg class="position-absolute start-50 translate-middle next-icon" width="20" height="10">
                                <polygon points="0,0 10,10 20,0" fill="#0d6efd"/>
                                </svg>
                            </p>

                            <!-- Selection prompt with decorative card -->
                            <div class="card border-2 border-info shadow-sm">
                                <div class="card-body text-center position-relative">
                                    <i class="bi bi-clock-history text-info fs-4 mb-2"></i>
                                    <p class="card-text mb-0 fw-semibold fs-5 d-flex">
                                        <i class="bi bi-arrow-right-circle-fill text-info"></i>
                                        <span>Chọn thời gian phù hợp cho lộ trình dành riêng cho bạn!</span>
                                        <i class="bi bi-arrow-left-circle-fill text-info"></i>
                                    </p>
                                    <!-- Decorative shapes -->
                                    <div class="position-absolute top-0 start-0 translate-middle p-2">
                                        <i class="bi bi-map-fill text-warning fs-3"></i>
                                    </div>
                                    <div class="position-absolute top-0 end-0 translate-middle p-2">
                                        <i class="bi bi-geo-alt-fill text-danger fs-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="custom-checkbox-container">
                            {{-- Roadmap selection --}}
                        </div>
                        <button class="confirm-btn" onclick="">XÁC NHẬN</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/plugins/swiperjs/swiper-bundle.min.js') }}"></script>
    <script>
        const roadmapSelectionList = @json($roadmap_selection_list);

        document.addEventListener('DOMContentLoaded', function() {
            setupSeriesSwiper();
            setEqualSeriesCardHeight();
            setCourseBoxRightDisplay();
            preventAccordionToggleForDisabledItems();
            doSeriesAction();
        });

        const setupSeriesSwiper = () => {
            new Swiper('.swiper', {
                slidesPerView: 1,
                spaceBetween: 1,
                loop: true,
                allowTouchMove: false,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                slidesPerGroup: 1,
                autoHeight: true,
                breakpoints: {
                    1300: {
                        slidesPerView: 4,
                    },
                    1000: {
                        slidesPerView: 3,
                    },
                    768: {
                        slidesPerView: 2,
                    }
                },
            });
        }

        const setEqualSeriesCardHeight = () => {
            let maxTeacherDescripitonHeight = 0;
            let maxShortDescriptionHeight = 0;
            let maxPriceHeight = 0;

            $('.course-card-teacher').each(function() {
                let currentHeight = $(this).outerHeight();
                if (currentHeight > maxTeacherDescripitonHeight) {
                    maxTeacherDescripitonHeight = currentHeight;
                }
            });

            $('.course-card-description').each(function() {
                let currentHeight = $(this).outerHeight();
                if (currentHeight > maxShortDescriptionHeight) {
                    maxShortDescriptionHeight = currentHeight;
                }
            });

            $('.card-price-container').each(function() {
                let currentHeight = $(this).outerHeight();
                if (currentHeight > maxPriceHeight) {
                    maxPriceHeight = currentHeight;
                }
            });

            $('.course-card-teacher').css('min-height', maxTeacherDescripitonHeight + 'px');
            $('.course-card-description').css('min-height', maxShortDescriptionHeight + 'px');
            $('.card-price-container').css('min-height', maxPriceHeight + 'px');
        }

        const setCourseBoxRightDisplay = () => {
            const contentWidth = $('.course-box .course-header').width();
            const titleWidth = $('.course-box .course-header .course-title').width();

            if (titleWidth / contentWidth > 0.5) {
                const courseHeader = $('.course-box .course-header');
                courseHeader.css('flex-direction', 'column');
                courseHeader.css('gap', '8px');
            }
        }

        const openRoadmapSelectionModal = (seriesId) => {
            const selectRoadmapModal = $('#selectRoadmapModal');
            selectRoadmapModal.find('.custom-checkbox-container').empty();
            if (roadmapSelectionList[seriesId]) {
                roadmapSelectionList[seriesId].forEach(roadmap => {
                    selectRoadmapModal.find('.custom-checkbox-container').append(`
                        <div class="custom-checkbox">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="studyPath" value="${roadmap.duration_months}" id="path${roadmap.duration_months}">
                                <label class="form-check-label fs-5" for="path${roadmap.duration_months}">
                                    ${roadmap.duration_months} tháng
                                </label>
                                <div class="text-muted">Hoàn thành trong ${roadmap.duration_months * 30} ngày với ${roadmap.contents.length} buổi học</div>
                            </div>
                        </div>
                    `);
                });
            }
            selectRoadmapModal.find('.custom-checkbox-container').append(`
                <div class="custom-checkbox">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="studyPath" value="0" id="path4" checked>
                        <label class="form-check-label fs-5" for="path4">
                            Lộ trình tự do
                        </label>
                        <div class="text-muted">Tự học theo thời gian linh hoạt</div>
                    </div>
                </div>
            `);

            selectRoadmapModal.find('button.confirm-btn').off('click').on('click', () => {
                saveRoadmap(seriesId);
            })

            selectRoadmapModal.modal('show');
        }

        const scrollToPurchasedSeriesList = () => {
            const seriesComboTitle = document.querySelector('.series-combo-title');
            seriesComboTitle.scrollIntoView({
                behavior: 'smooth'
            });
        }

        const saveRoadmap = (seriesId) => {
            const selectRoadmapModal = $('#selectRoadmapModal');
            const selectedRoadmapMonth = selectRoadmapModal.find('input[type="radio"]:checked').val();

            $.ajax({
                headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                url: '{{ route('series.save-user-roadmap') }}',
                type: 'post',
                data: {
                    series_id : seriesId,
                    duration_months : selectedRoadmapMonth,
                    combo_slug: '{{ $seriesCombo->slug }}'
                },
                success: function(response){
                    window.location.href = response.redirect_url;
                }
            });
        }

        const preventAccordionToggleForDisabledItems = () => {
            $('.accordion-button').on('click', function (e) {
                if ($(this).closest('.accordion-item').hasClass('disabled')) {
                    e.stopPropagation();
                    e.preventDefault();
                }
            });

            $('.accordion-item.disabled > a').on('click', function (e) {
                e.preventDefault();
            });

            $('#accordion_container').on('show.bs.collapse', function (e) {
                if ($(e.target).closest('.accordion-item').hasClass('disabled')) {
                    e.preventDefault();
                }
            });
        }

        const doSeriesAction = () => {
            const urlParams = new URLSearchParams(window.location.search);
            const seriesAction = urlParams.get('series_action');

            if (!seriesAction) return;

            setTimeout(() => {
                if (seriesAction == 'scrollToList') {
                    const seriesComboTitle = document.querySelector('.series-combo-title');
                    seriesComboTitle.scrollIntoView({
                        behavior: 'smooth'
                    });
                } else if (seriesAction == 'openRoadmapModal') {
                    $('#first_purchase_button').trigger('click');
                }
            }, 200);
        }

        function showAuthModalWithStopPropagation(event, isLogin = true) {
            event.stopPropagation();
            showAuthModal(isLogin);
        }
    </script>
@endSection
