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
    </style>
@endsection

@section('mypage-content')
    <div class="px-5 pb-5 personal-wrapper">
        <div>
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
        </div>
        <div class="row mt-5">
            <div class="col-lg-6">
                <section class="section">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="section-title">
                            Khoá học đã tham gia
                        </h5>
                    </div>
                    @if ($view_series_history->count() > 0)
                        @foreach ($view_series_history as $index => $series)
                            <div class="course-item d-flex align-items-center pb-3">
                                <img class="series-image" alt="series image"
                                    src="{{ '/public/uploads/lms/series/' . $series->image }}" />
                                <div class="course-info flex-grow-1">
                                    <div class="course-title">{{ $series->title }}</div>
                                    <div class="course-time mb-1">Học cách đây {{ compareTime($series->viewed_time) }}</div>
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
                        @endforeach
                    @else
                        <div>
                            <span>Bạn chưa học bài học nào.</span>
                            <a href="/home" class="fs-5">Hãy chọn ngay cho mình một khoá học!</a>
                        </div>
                    @endif
                </section>
            </div>

            <div class="col-lg-6">
                <div class="personal-information">
                    <h4 class="mb-4">Thông tin tài khoản</h4>
                    <form id="update_info_form" action="{{ route('mypage.update-info') }}" method="post"
                        enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row mb-3">
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
                        <div class="row mb-3">
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
                        <div class="row mb-3">
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
        <div class="recommended-series-section position-relative">
            <h4 class="align-self-baseline mt-3">Gợi ý các khoá học</h4>
            <div class="swiper swiper-container">
                <div class="swiper-wrapper">
                    @foreach ($other_combo_series as $recommended_series)
                        @if (isset($recommended_series->seriesList) && count($recommended_series->seriesList) > 0)
                            <div class="swiper-slide">
                                <div class="course-card recommended"
                                    @if ($recommended_series->checkMultipleCombo)
                                        onclick="location.href='{{ route('series.introduction-detail-combo', ['combo_slug' => $recommended_series->slug]) }}'"
                                    @else
                                        onclick="location.href='{{ route('series.introduction-detail', ['combo_slug' => $recommended_series->slug, 'slug' => $recommended_series->seriesList[0]->slug]) }}'"
                                    @endif
                                >
                                    <img alt="course image" height="200" width="300"
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
                                        <div class="course-card-description line-clamp-2">{!! $recommended_series->short_description !!}</div>
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
                                                    onclick="location.href='{{ route('series.introduction-detail-combo', ['combo_slug' => $recommended_series->slug]) }}'">
                                                    Xem thêm
                                                </button>
                                            @else
                                                <button class="btn btn-outline-primary ms-auto"
                                                    onclick="location.href='{{ route('series.introduction-detail', ['combo_slug' => $recommended_series->slug, 'slug' => $recommended_series->seriesList[0]->slug]) }}'">
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
                allowTouchMove: false,
                    // autoplay: {
                    //     delay: 5000,
                    //     disableOnInteraction: false,
                    // },
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
    </script>
@endsection
