@extends('client.shared.mypage')

@section('mypage-styles')
    <link href="{{ asset('css/pages/mypage.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/plugins/swiperjs/swiper-bundle.min.css') }}">
    <style>
        .swiper-button-next {
            right: -50px;
        }

        .swiper-button-prev {
            left: -50px;
        }

        .position-relative {
            position: relative;
        }

        .courses-container {
            display: flex;
            overflow-x: auto;
            gap: 20px;
            padding: 10px 5px;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE and Edge */
        }

        .courses-container::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari and Opera */
        }

        .course-item {
            min-width: 300px;
            flex: 0 0 auto;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 8px;
            background: white;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .course-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .series-image {
            width: 100px;
            height: 70px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 15px;
        }

        .course-info {
            min-width: 0;
        }

        .course-title {
            font-weight: 500;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .course-time {
            font-size: 0.875rem;
            color: #666;
        }

        /* Navigation Buttons Styles */
        .nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: white;
            border: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            opacity: 0;
        }

        .nav-btn.show {
            opacity: 1;
        }

        .nav-btn:hover {
            background-color: #f8f9fa;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .nav-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background-color: #f1f1f1;
        }

        .prev-btn {
            left: 0;
        }

        .next-btn {
            right: 0;
        }

        /* Hide buttons on mobile */
        @media (max-width: 768px) {
            .nav-btn {
                display: none;
            }
        }
    </style>
@endsection

@section('mypage-content')
    <div class="px-5 py-3 personal-wrapper card-section">
        {{-- <div>
            <div class="banner">
                <div class="profile-pic">
                    @if (Auth::user()->image)
                        <img src="{{ asset('uploads/users/thumbnail/' . Auth::user()->image) }}"
                            class="rounded-circle object-fit-cover size-full" alt="Avatar" />
                    @else
                        <img src="{{ asset('images/no-avatar.png') }}" class="rounded-circle object-fit-cover size-full"
                            alt="Avatar" />
                    @endif
                </div>
                <h3 class="profile-name">{{ Auth::user()->name }}</h3>
            </div>
        </div> --}}
        <div class="row">
            <div class="col-12 col-sm-12 col-lg-12">
                <h4 class="align-self-baseline mt-3 text-center text-sm-start">Các khoá học đã tham gia</h4>
                <div class="position-relative">
                    <div class="courses-container" id="coursesContainer">
                        @if ($view_series_history->count() > 0)
                            @foreach ($view_series_history as $index => $series)
                                <div class="course-item">
                                    <div class="d-flex">
                                        <img class="series-image" alt="series image"
                                            src="{{ '/public/uploads/lms/combo/' . $series->seriesCombo->image }}" />
                                        <div class="course-info">
                                            <div class="course-title">{{ $series->title }}</div>
                                            <div class="course-time mb-1">Học cách đây
                                                {{ compareTime($series->viewed_time) }}</div>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-secondary-custom"
                                                    role="progressbar" aria-valuenow="{{ $series->progressPercent }}"
                                                    aria-valuemin="0" aria-valuemax="100"
                                                    style="width: {{ $series->progressPercent }}%">
                                                    <span class="{{ $series->progressPercent <= 10 ? 'd-none' : '' }}">
                                                        {{ $series->progressPercent }}%
                                                    </span>
                                                </div>
                                                <span
                                                    class="ms-1 text-primary {{ $series->progressPercent <= 10 ? '' : 'd-none' }}">
                                                    {{ $series->progressPercent }}%
                                                </span>
                                            </div>
                                            @if ($series->roadmapChosen)
                                                <a href="{{ route('learning-management.lesson.show', ['combo_slug' => $series->combo_slug, 'slug' => $series->slug]) }}"
                                                    class="text-primary mt-1 fs-6 d-block">
                                                    Tiếp tục học
                                                </a>
                                            @elseif (optional($series->seriesCombo)->checkMultipleCombo)
                                                <a href="{{ route('series.introduction-detail-combo', ['combo_slug' => $series->combo_slug]) . '?series_action=scrollToList' }}"
                                                    class="text-primary mt-1 fs-6 d-block">
                                                    Chọn lộ trình và học ngay
                                                </a>
                                            @else
                                                <a href="{{ route('series.introduction-detail', ['combo_slug' => $series->combo_slug, 'slug' => $series->slug]) . '?series_action=openRoadmapModal' }}"
                                                    class="text-primary mt-1 fs-6 d-block">
                                                    Chọn lộ trình và học ngay
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div>
                                <span>Bạn chưa học bài học nào.</span>
                                <a href="/home" class="fs-5">Hãy chọn ngay cho mình một khoá học!</a>
                            </div>
                        @endif
                    </div>

                    <!-- Navigation Buttons -->
                    <button class="nav-btn prev-btn" id="prevBtn" aria-label="Previous">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="nav-btn next-btn" id="nextBtn" aria-label="Next">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
            <h4 class="align-self-baseline mt-3 text-center text-sm-start">Gợi ý các khoá học</h4>
            <div class="recommended-series-section position-relative">
                <div class="swiper swiper-container p-2">
                    <div class="swiper-wrapper">
                        @foreach ($other_combo_series as $recommended_series)
                            @if (isset($recommended_series->seriesList) && count($recommended_series->seriesList) > 0)
                                <div class="swiper-slide">
                                    <div class="course-card recommended"
                                        @if ($recommended_series->checkMultipleCombo) onclick="location.href='{{ route('series.introduction-detail-combo', ['combo_slug' => $recommended_series->slug]) }}'"
                                        @else
                                            onclick="location.href='{{ route('series.introduction-detail', ['combo_slug' => $recommended_series->slug, 'slug' => $recommended_series->seriesList[0]->slug]) }}'" @endif>
                                        <img alt="course image" height="200" width="300"
                                            src="{{ asset('/public/' . config('constant.series_combo.upload_path') . $recommended_series->image) }}" />
                                        <div class="course-card-body">
                                            <div class="course-card-title line-clamp-2">
                                                <h5 class="course-card-text">{{ $recommended_series->title }}</h5>
                                            </div>
                                            <div
                                                class="d-flex justify-content-between gap-2 align-items-center card-price-container mb-1">
                                                <div class="d-flex flex-column align-items-baseline">
                                                    <p class="course-card-price mb-0">
                                                        {{ $recommended_series->actualCost == 0 ? 'Miễn phí' : formatCurrencyVND($recommended_series->actualCost) }}
                                                    </p>
                                                    @if ($recommended_series->checkPromotion)
                                                        <span
                                                            class="orginal-price">{{ formatCurrencyVND($recommended_series->cost) }}</span>
                                                    @endif
                                                </div>
                                                @if (
                                                    $recommended_series->seriesList[0]->hasTrialContent &&
                                                        !$recommended_series->checkMultipleCombo &&
                                                        !$recommended_series->valid_payment)
                                                    <button class="trial-btn btn py-1"
                                                        onclick="event.stopPropagation(); location.href='{{ route('learning-management.lesson.show', ['combo_slug' => $recommended_series->slug, 'slug' => $recommended_series->seriesList[0]->slug]) }}'">
                                                        Học thử
                                                    </button>
                                                @endif
                                            </div>
                                            <div>
                                                <i class="bi bi-calendar-event-fill"></i>
                                                <span class="ms-2 date-duration">
                                                    Thời hạn: {{ config('constant.series_combo.month_duration_map')[$recommended_series->time] }} tháng
                                                </span>
                                                <div class="d-flex align-items-center mt-2 info-course-card">
                                                    <i class="bi bi-play-circle-fill"></i>
                                                    <span class="ms-2">{{ $recommended_series->content_count }}</span>
                                                    <i class="bi bi-book ms-3"></i>
                                                    <span
                                                        class="ms-2">{{ empty($recommended_series->chapter_count) ? 1 : $recommended_series->chapter_count }}
                                                        chương</span>
                                                </div>
                                            </div>
                                            @if (Auth::check() && $recommended_series->valid_payment && count($recommended_series->seriesList) > 1)
                                                <button class="btn btn-primary w-100 mt-3 button-custom button-info"
                                                    onclick="event.stopPropagation(); location.href='{{ route('series.introduction-detail-combo', ['combo_slug' => $recommended_series->slug]) . '?series_action=scrollToList' }}'">
                                                    Học ngay
                                                </button>
                                            @elseif (
                                                $recommended_series->cost == 0 ||
                                                    (Auth::check() && $recommended_series->valid_payment && count($recommended_series->seriesList) == 1))
                                                @if (
                                                    !$recommended_series->checkAllSeriesRoadmapOfSeriesComboChosen($roadmap_chosen_list) &&
                                                        $recommended_series->cost !== 0)
                                                    <button class="btn btn-primary w-100 mt-3 button-custom button-info"
                                                        onclick="event.stopPropagation(); location.href='{{ route('series.introduction-detail', ['combo_slug' => $recommended_series->slug, 'slug' => $recommended_series->seriesList[0]->slug]) . '?series_action=openRoadmapModal' }}'">
                                                        Học ngay
                                                    </button>
                                                @else
                                                    <button class="btn btn-primary w-100 mt-3 button-custom button-info"
                                                        onclick="event.stopPropagation(); location.href='{{ route('learning-management.lesson.show', ['combo_slug' => $recommended_series->slug, 'slug' => $recommended_series->seriesList[0]->slug]) }}'">
                                                        Học ngay
                                                    </button>
                                                @endif
                                            @elseif (Auth::check())
                                                <button class="btn btn-primary w-100 mt-3 button-custom button-info"
                                                    onclick="event.stopPropagation(); location.href='{{ route('payments.lms', $recommended_series->slug) }}'">
                                                    Mua ngay
                                                </button>
                                            @else
                                                <button class="btn btn-primary w-100 mt-3 button-custom button-info"
                                                    onclick="showAuthModalWithStopPropagation(event, true)">
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
            <div class="col-12 col-sm-12 col-lg-8 mb-2">
                <div class="personal-information">
                    <h4 class="mb-4">Thông tin tài khoản</h4>
                    <form id="update_info_form" action="{{ route('mypage.update-info') }}" method="post"
                        enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row mb-3 gap-3 gap-md-0">
                            <div class="col-md-6">
                                <label class="text-personal-infomation">Username</label>
                                <input type="text" class="form-control input-personal-infomation"
                                    value="{{ Auth::user()->username }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="text-personal-infomation">Email</label>
                                <input type="email" class="form-control input-personal-infomation"
                                    value="{{ Auth::user()->email }}" disabled>
                            </div>
                        </div>
                        <div class="row mb-3 gap-3 gap-md-0" id="change_name_phone_section">
                            <div class="col-md-6">
                                <label for="name" class="text-personal-infomation">Họ và tên</label>
                                <input type="text" name="name"
                                    class="form-control input-personal-infomation {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                    value="{{ old('name', Auth::user()->name) }}" placeholder="Họ và tên">
                                <span class="text-danger invalid-feedback">{{ $errors->first('name') }}</span>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="text-personal-infomation">Số điện thoại</label>
                                <input type="text" name="phone"
                                    class="form-control input-personal-infomation {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                    value="{{ old('phone', Auth::user()->phone) }}" placeholder="Số điện thoại">
                                <span class="text-danger invalid-feedback">{{ $errors->first('phone') }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="old_password" class="text-personal-infomation">Mật khẩu cũ</label>
                            <input type="password"
                                class="form-control input-personal-infomation {{ $errors->has('old_password') ? 'is-invalid' : '' }}"
                                name="old_password" placeholder="">
                            <span class="text-danger invalid-feedback">{{ $errors->first('old_password') }}</span>
                        </div>
                        <div class="row mb-3 gap-3 gap-md-0">
                            <div class="col-md-6">
                                <label for="password" class="text-personal-infomation">Mật khẩu mới</label>
                                <input type="password"
                                    class="form-control input-personal-infomation {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                    name="password" placeholder="">
                                <span class="text-danger invalid-feedback">{{ $errors->first('password') }}</span>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="text-personal-infomation">Xác nhận mật khẩu
                                    mới</label>
                                <input type="password" class="form-control input-personal-infomation"
                                    name="password_confirmation" placeholder="">
                            </div>
                        </div>
                        <div class="row mb-3 dropzone-file-section">
                            <label for="dropzone_file" class="text-personal-infomation">Cập nhật ảnh đại diện</label>
                            <div class="col-xl-6 d-flex justify-content-center">
                                <div id="file-list" class="mt-3">
                                    <div
                                        class="file-list-information position-relative {{ $errors->has('avatar') ? 'is-invalid' : '' }}">
                                        @if (Auth::user()->image)
                                            <img src="{{ asset('uploads/users/thumbnail/' . Auth::user()->image) }}"
                                                class="rounded-circle object-fit-cover size-full file-image-list"
                                                alt="Avatar" />
                                        @else
                                            <img src="{{ asset('images/no-avatar.png') }}"
                                                class="rounded-circle object-fit-cover size-full file-image-list"
                                                alt="Avatar" />
                                        @endif
                                        <div class="change-file" data-index="${index}">Chỉnh sửa</div>
                                    </div>
                                    <span class="text-danger invalid-feedback">{{ $errors->first('avatar') }}</span>
                                </div>
                                <div id="file-error mt-1"></div>
                            </div>
                            <div class="col-xl-6">
                                <div class="dropzone" id="dropzone_file">
                                    <label for="files" class="dropzone-container" role="button">
                                        <div class="file-icon">
                                            <i class="bi bi-file-earmark-plus-fill"></i>
                                        </div>
                                        <div class="text-center px-5">
                                            <p class="text-dark fw-bold dropzone-text">
                                                Kéo tài liệu, ảnh của bạn vào đây để bắt đầu tải lên.
                                            </p>
                                        </div>
                                    </label>
                                    <input id="files" name="avatar" type="file" class="file-input"
                                        accept=".png, .jpeg, .jpg" />
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary w-200px">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('mypage-scripts')
    <script src="{{ asset('js/plugins/swiperjs/swiper-bundle.min.js') }}"></script>
    <script type="module">
        $(document).ready(function() {
            $('#files').on('change', function() {
                $('#file-list').empty();
                $('#file-list').show();
                $('#file-error').text('');
                $('#dropzone').hide();
                $.each(this.files, function(index, file) {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const imgHtml = `
                            <div>
                                <div class="file-list-information position-relative">
                                    <img src="${e.target.result}" alt="${file.name}" class="file-image-list object-fit-cover">
                                    <div class="change-file" data-index="${index}">Chỉnh sửa</div>
                                </div>
                            </div>`;
                            $('#file-list').append(imgHtml);
                            $('#dropzone_file').hide();
                        };
                        reader.readAsDataURL(file);
                    } else {
                        $('#file-error').text('Chỉ được chọn file ảnh.');
                    }
                });
            });

            $(document).on('click', '.change-file', function() {
                $('#dropzone_file').show();
            });

            setupRecommnededSeriesSwiper();
            //setEqualSeriesCardHeight();
            const $container = $('#coursesContainer');
            const $prevBtn = $('#prevBtn');
            const $nextBtn = $('#nextBtn');
            const scrollAmount = 300;

            // Update buttons state
            function updateButtonStates() {
                const scrollLeft = $container.scrollLeft();
                const maxScroll = $container[0].scrollWidth - $container[0].clientWidth;

                // Show/hide previous button
                if (scrollLeft <= 0) {
                    $prevBtn.removeClass('show');
                } else {
                    $prevBtn.addClass('show');
                }

                // Show/hide next button
                if (scrollLeft >= maxScroll) {
                    $nextBtn.removeClass('show');
                } else {
                    $nextBtn.addClass('show');
                }
            }

            // Handle button clicks
            $prevBtn.on('click', function() {
                $container.animate({
                    scrollLeft: '-=' + scrollAmount
                }, 300, updateButtonStates);
            });

            $nextBtn.on('click', function() {
                $container.animate({
                    scrollLeft: '+=' + scrollAmount
                }, 300, updateButtonStates);
            });

            // Update on scroll
            let scrollTimer;
            $container.on('scroll', function() {
                clearTimeout(scrollTimer);
                scrollTimer = setTimeout(updateButtonStates, 100);
            });

            // Update on window resize
            let resizeTimer;
            $(window).on('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(updateButtonStates, 100);
            });

            // Initial check
            updateButtonStates();

            // Check if scrollable on load
            $(window).on('load', function() {
                if ($container[0].scrollWidth > $container[0].clientWidth) {
                    $nextBtn.addClass('show');
                }
            });
        });

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

        const setupRecommnededSeriesSwiper = () => {
            new Swiper('.swiper-container', {
                slidesPerView: 1,
                spaceBetween: 1,
                loop: true,
                allowTouchMove: true,
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
                    3300: {
                        slidesPerView: 13,
                    },
                    3000: {
                        slidesPerView: 10,
                    },
                    2600: {
                        slidesPerView: 9,
                    },
                    2300: {
                        slidesPerView: 8,
                    },
                    2000: {
                        slidesPerView: 7,
                    },
                    1700: {
                        slidesPerView: 6,
                    },
                    1500: {
                        slidesPerView: 5,
                    },
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

            let maxHeight = 0;
            $('.course-card').each(function() {
                var cardHeight = $(this).outerHeight();
                if (cardHeight > maxHeight) {
                    maxHeight = cardHeight;
                }
            });

            $('.course-card').css('height', maxHeight);

            function showAuthModalWithStopPropagation(event, isLogin = true) {
                event.stopPropagation();
                showAuthModal(isLogin);
            }
        }

        // Scroll to change password section
        $(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const action = urlParams.get('action');

            if (action === 'change-password') {
                document
                    .getElementById('change_name_phone_section')
                    .scrollIntoView({
                        behavior: 'smooth'
                    });
            }
        });
    </script>
@endsection
