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
                            @if (!$seriesCombo->getCheckMultipleComboAttribute())
                                <button class="roadmap-btn"
                                    onclick="location.href='{{ route('home.roadmap', ['comboSlug' => $seriesCombo->slug, 'slug' => request()->route('slug')]) }}'">
                                    <div>
                                        <i class="bi bi-rocket-takeoff-fill"></i>
                                        Lộ trình
                                    </div>
                                </button>
                            @endif
                            @if (Auth::check() && $isValidPayment)
                                <button class="purchase-btn"
                                    onclick="location.href='{{ route('learning-management.lesson.show', ['combo_slug' => $seriesCombo->slug, 'slug' => request()->route('slug')]) }}'">
                                    <div><i class="bi bi-book me-1"></i>Học ngay</div>
                                </button>
                            @elseif (Auth::check() && !$isValidPayment)
                                <button class="purchase-btn"
                                    onclick="location.href='{{ route('payments.lms', $seriesCombo->slug) }}'">
                                    <div><i class="bi bi-cart-fill"></i>Mua ngay</div>
                                </button>
                            @else
                                <button class="purchase-btn" onclick="showAuthModal()">
                                    <div><i class="bi bi-cart-fill"></i>Mua ngay</div>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="description-section">
            <h4 class="my-2">Lộ trình {{ $seriesCombo->title }}</h4>
            <div class="description-container">
                <div class="series-content">
                    <p class="fs-5 fw-bold"><i class="bi bi-file-earmark-text"></i> Nội dung: </p>
                    @if (isset($series_learning_description['content_description']))
                        {!! $series_learning_description['content_description'] !!}
                    @endif
                </div>
                <div class="series-info">
                    @if (isset($series_learning_description['time_description']))
                        <div class="mb-3">
                            <span class="fs-5 fw-bold"><i class="bi bi-clock"></i> Thời gian: </span>
                            <span>{!! $series_learning_description['time_description'] !!}</span>
                        </div>
                    @endif
                    @if (isset($series_learning_description['curriculum_description']))
                        <div class="mb-3">
                            <span class="fs-5 fw-bold"><i class="bi bi-clipboard-data"></i> Giáo trình: </span>
                            <span>{!! $series_learning_description['curriculum_description'] !!}</span>
                        </div>
                    @endif
                    @if (isset($series_learning_description['teacher_description']))
                        <div class="mb-3">
                            <span class="fs-5 fw-bold"><i class="bi bi-person"></i> Giảng viên: </span>
                            <span>{!! $series_learning_description['teacher_description'] !!}</span>
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
                            ])
                        </div>
                    </div>
                    <div class="overview-series">
                        <img src="{{ asset('/public/' . config('constant.series_combo.upload_path') . $seriesCombo->image) }}"
                            alt="series image">
                        @if (Auth::check() && $isValidPayment)
                            <button class="btn btn-primary"
                                onclick="location.href='{{ route('learning-management.lesson.show', ['combo_slug' => $seriesCombo->slug, 'slug' => request()->route('slug')]) }}'">
                                Học ngay
                            </button>
                        @elseif (Auth::check() && !$isValidPayment)
                            <button class="btn btn-primary"
                                onclick="location.href='{{ route('payments.lms', $seriesCombo->slug) }}'">
                                Mua ngay
                            </button>
                        @else
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
            <h4 class="mt-4">Chi tiết gói Combo</h4>
            <div class="series-combo-section row">
                <div class="col-lg-8 series-item-section">
                    @foreach ($seriesCombo->seriesList as $itemSeries)
                        <div class="series-card card">
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
                                <span class="fs-5 fw-light">{!! $itemSeries->short_description !!}</span>
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
                            <button class="btn btn-primary mt-2 fs-5 purchase-btn"
                                onclick="location.href='{{ route('mypage.courses') }}'">
                                Học ngay
                            </button>
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
                                <div class="course-card">
                                    <img alt="course image" height="400" width="600"
                                        src="{{ asset('/public/' . config('constant.series_combo.upload_path') . $recommended_series->image) }}" />
                                    <div class="course-card-body">
                                        <h5 class="course-card-title">{{ $recommended_series->title }}</h5>
                                        <p class="course-card-price">{{ formatCurrencyVND($recommended_series->cost) }}
                                        </p>
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
                                            @if ($recommended_series->checkMultipleCombo)
                                                <button class="btn btn-outline-primary ms-auto"
                                                    onclick="location.href='{{ route('series.introduction-detail-combo', ['combo_slug' => $recommended_series->slug]) }}'">
                                                    Xem thêm
                                                </button>
                                            @else
                                                <button class="btn btn-outline-primary ms-auto"
                                                    onclick="location.href='{{ route('series.introduction-detail', ['combo_slug' => $recommended_series->slug, 'slug' => $recommended_series->seriesList[0]->slug]) }}'">
                                                    Xem thêm
                                                </button>
                                            @endif
                                        </div>
                                        @if (Auth::check() && $recommended_series->valid_payment && count($recommended_series->seriesList) > 1)
                                            <button class="btn btn-primary w-100 mt-3"
                                                onclick="location.href='{{ route('mypage.courses') }}'">
                                                Học ngay
                                            </button>
                                        @elseif (Auth::check() && $recommended_series->valid_payment && count($recommended_series->seriesList) == 1)
                                            <button class="btn btn-primary w-100 mt-3"
                                                onclick="location.href='{{ route('learning-management.lesson.show', ['combo_slug' => $recommended_series->slug, 'slug' => $recommended_series->seriesList[0]->slug]) }}'">
                                                Học ngay
                                            </button>
                                        @elseif (Auth::check())
                                            <button class="btn btn-primary w-100 mt-3"
                                                onclick="location.href='{{ route('payments.lms', $recommended_series->slug) }}'">
                                                Mua ngay
                                            </button>
                                        @else
                                            <button class="btn btn-primary w-100 mt-3" onclick="showAuthModal()">
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
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/plugins/swiperjs/swiper-bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setupSeriesSwiper();
            setEqualSeriesCardHeight();
            setCourseBoxRightDisplay();
        });

        const setupSeriesSwiper = () => {
            new Swiper('.swiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
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
                    1400: {
                        slidesPerView: 3,
                    },
                    992: {
                        slidesPerView: 2,
                    }
                },
            });
        }

        const setEqualSeriesCardHeight = () => {
            let maxTeacherDescripitonHeight = 0;
            let maxShortDescriptionHeight = 0;

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

            $('.course-card-teacher').css('min-height', maxTeacherDescripitonHeight + 'px');
            $('.course-card-description').css('min-height', maxShortDescriptionHeight + 'px');
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
    </script>
@endSection
