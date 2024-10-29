{{-- resources/views/home.blade.php --}}
@extends('client.app')

@section('styles')
    <style>
        .form-container h1 span {
            color: var(--primary);
        }

        .form-container p span {
            color: var(--primary);
        }

        .form-container .form-label {
            color: var(--primary);
        }

        .form-container .form-control {
            border-radius: 8px;
            height: 48px;
            font-size: 16px;
        }

        .form-container .btn-primary {
            background-color: var(--primary);
            border: none;
            border-radius: 8px;
            height: 48px;
            font-size: 16px;
            font-weight: 500;
        }

        .form-container .btn-primary:hover {
            background-color: #1558b0;
        }

        .form-container .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
        }

        .form-container .divider::before,
        .form-container .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #dadce0;
        }

        .form-container .social-login {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .form-container .social-login a {
            font-size: 24px;
            color: #5f6368;
        }

        .form-container .social-login a:hover {
            color: var(--primary);
        }

        .form-container .login-link {
            text-align: center;
            margin-top: 20px;
        }

        .form-container .login-link a {
            color: var(--primary);
            text-decoration: none;
        }

        .form-container .login-link a:hover {
            text-decoration: underline;
        }

        .image-container img {
            width: 100%;
            border-radius: 16px;
        }

        .social-icon {
            background-color: #F3F9FA;
            padding: 10px;
            height: 40px;
            width: 40px;
            display: flex;
            border-radius: 12px;
        }

        .sptb .sptb-item {
            margin: 10px 0;
        }

        .sptb .sptb-item .card-body {
            min-height: 150px;
            height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: rgba(14, 63, 126, 0.04) 0px 0px 0px 1px, rgba(42, 51, 69, 0.04) 0px 1px 1px -0.5px, rgba(42, 51, 70, 0.04) 0px 3px 3px -1.5px, rgba(42, 51, 70, 0.04) 0px 6px 6px -3px, rgba(14, 63, 126, 0.04) 0px 12px 12px -6px, rgba(14, 63, 126, 0.04) 0px 24px 24px -12px;
        }

        .fea-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #F3F9FA;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            display: table;
        }

        .feature-icon-content {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            overflow: hidden;
            mix-blend-mode: hard-light;
            text-shadow: rgba(90, 90, 90, 1) 1px 1px, rgba(90, 90, 90, 1) 2px 2px, rgba(90, 90, 90, 1) 3px 3px, rgba(90, 90, 90, 1) 3px 3px, rgba(90, 90, 90, 1) 4px 4px, rgba(90, 90, 90, 1) 5px 5px, rgba(90, 90, 90, 1) 6px 6px, rgba(90, 90, 90, 1) 7px 7px, rgba(90, 90, 90, 1) 8px 8px, rgba(90, 90, 90, 1) 9px 9px, rgba(90, 90, 90, 1) 10px 10px, rgba(90, 90, 90, 1) 11px 11px, rgba(90, 90, 90, 1) 12px 12px, rgba(90, 90, 90, 1) 13px 13px, rgba(90, 90, 90, 1) 14px 14px, rgba(90, 90, 90, 1) 15px 15px, rgba(90, 90, 90, 1) 16px 16px, rgba(90, 90, 90, 1) 17px 17px, rgba(90, 90, 90, 1) 18px 18px, rgba(90, 90, 90, 1) 19px 19px, rgba(90, 90, 90, 1) 20px 20px, rgba(90, 90, 90, 1) 21px 21px, rgba(90, 90, 90, 1) 22px 22px, rgba(90, 90, 90, 1) 23px 23px, rgba(90, 90, 90, 1) 24px 24px, rgba(90, 90, 90, 1) 25px 25px, rgba(90, 90, 90, 1) 26px 26px, rgba(90, 90, 90, 1) 27px 27px, rgba(90, 90, 90, 1) 28px 28px, rgba(90, 90, 90, 1) 29px 29px, rgba(90, 90, 90, 1) 30px 30px, rgba(90, 90, 90, 1) 31px 31px, rgba(90, 90, 90, 1) 32px 32px, rgba(90, 90, 90, 1) 33px 33px, rgba(90, 90, 90, 1) 34px 34px, rgba(90, 90, 90, 1) 35px 35px, rgba(90, 90, 90, 1) 36px 36px, rgba(90, 90, 90, 1) 37px 37px, rgba(90, 90, 90, 1) 38px 38px, rgba(90, 90, 90, 1) 39px 39px, rgba(90, 90, 90, 1) 40px 40px, rgba(90, 90, 90, 1) 41px 41px, rgba(90, 90, 90, 1) 42px 42px, rgba(90, 90, 90, 1) 43px 43px, rgba(90, 90, 90, 1) 44px 44px, rgba(90, 90, 90, 1) 45px 45px, rgba(90, 90, 90, 1) 46px 46px, rgba(90, 90, 90, 1) 47px 47px, rgba(90, 90, 90, 1) 48px 48px, rgba(90, 90, 90, 1) 49px 49px, rgba(90, 90, 90, 1) 50px 50px, rgba(90, 90, 90, 1) 51px 51px, rgba(90, 90, 90, 1) 52px 52px, rgba(90, 90, 90, 1) 53px 53px, rgba(90, 90, 90, 1) 54px 54px, rgba(90, 90, 90, 1) 55px 55px, rgba(90, 90, 90, 1) 56px 56px, rgba(90, 90, 90, 1) 57px 57px, rgba(90, 90, 90, 1) 58px 58px, rgba(90, 90, 90, 1) 59px 59px, rgba(90, 90, 90, 1) 60px 60px, rgba(90, 90, 90, 1) 61px 61px, rgba(90, 90, 90, 1) 62px 62px, rgba(90, 90, 90, 1) 63px 63px, rgba(90, 90, 90, 1) 64px 64px, rgba(90, 90, 90, 1) 65px 65px, rgba(90, 90, 90, 1) 66px 66px, rgba(90, 90, 90, 1) 67px 67px, rgba(90, 90, 90, 1) 68px 68px, rgba(90, 90, 90, 1) 69px 69px, rgba(90, 90, 90, 1) 70px 70px, rgba(90, 90, 90, 1) 71px 71px, rgba(90, 90, 90, 1) 72px 72px, rgba(90, 90, 90, 1) 73px 73px, rgba(90, 90, 90, 1) 74px 74px, rgba(90, 90, 90, 1) 75px 75px, rgba(90, 90, 90, 1) 76px 76px, rgba(90, 90, 90, 1) 77px 77px, rgba(90, 90, 90, 1) 78px 78px, rgba(90, 90, 90, 1) 79px 79px, rgba(90, 90, 90, 1) 80px 80px, rgba(90, 90, 90, 1) 81px 81px, rgba(90, 90, 90, 1) 82px 82px, rgba(90, 90, 90, 1) 83px 83px, rgba(90, 90, 90, 1) 84px 84px, rgba(90, 90, 90, 1) 85px 85px, rgba(90, 90, 90, 1) 86px 86px, rgba(90, 90, 90, 1) 87px 87px, rgba(90, 90, 90, 1) 88px 88px, rgba(90, 90, 90, 1) 89px 89px, rgba(90, 90, 90, 1) 90px 90px, rgba(90, 90, 90, 1) 91px 91px, rgba(90, 90, 90, 1) 92px 92px, rgba(90, 90, 90, 1) 93px 93px, rgba(90, 90, 90, 1) 94px 94px, rgba(90, 90, 90, 1) 95px 95px, rgba(90, 90, 90, 1) 96px 96px, rgba(90, 90, 90, 1) 97px 97px, rgba(90, 90, 90, 1) 98px 98px, rgba(90, 90, 90, 1) 99px 99px, rgba(90, 90, 90, 1) 100px 100px;
        }

        .img-fluid {
            height: 100px;
            width: 100px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/plugins/swiperjs/swiper-bundle.min.css') }}">
@endsection

@section('content')
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        @if (isset($banners['home_slider_banner']) &&
                $banners['home_slider_banner']->is_active == App\Enums\BannerStatus::ACTIVE)
            <div class="carousel-indicators">
                @if (isset($banners['home_slider_banner']->image))
                    @foreach ($banners['home_slider_banner']->image as $index => $banner)
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="{{ $index }}"
                            class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                            aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                @endif
            </div>
            <div class="carousel-inner">
                @if (isset($banners['home_slider_banner']->image))
                    @foreach ($banners['home_slider_banner']->image as $index => $banner)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <img src="{{ asset($banner) }}"
                                alt="{{ $banners['home_slider_banner']->title ?? 'Slide Image' }}" class="d-block w-100">
                        </div>
                    @endforeach
                @else
                    <div class="carousel-item active">
                        <img src="default-image.jpg" alt="Default Slide" class="d-block w-100">
                        <div class="carousel-caption d-flex flex-column h-100 align-items-center justify-content-center">
                            <h2 class="bg-dark bg-opacity-50 py-2 px-4">Default Title</h2>
                            <p class="bg-dark bg-opacity-50 py-2 px-4">Placeholder content for default slide.</p>
                            <a href="#" class="btn btn-outline-light px-4 py-2 rounded-0">Learn More</a>
                        </div>
                    </div>
                @endif
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        @endif
    </div>

    {{-- Banner --}}

    <div class="row mb-2 mt-5 d-none">
        <div class="col-md-6">
            @if (isset($banners['home_banner_mini_1']) &&
                    $banners['home_banner_mini_1']->is_active == App\Enums\BannerStatus::ACTIVE)
                <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                    <div class="col p-4 d-flex flex-column position-static">
                        <strong class="d-inline-block mb-2 text-primary">World</strong>
                        <h3 class="mb-0">Featured post</h3>
                        <div class="mb-1 text-muted">Nov 12</div>
                        <p class="card-text mb-auto">This is a wider card with supporting text below as a natural lead-in to
                            additional content.</p>
                        <a href="#" class="stretched-link">Continue reading</a>
                    </div>
                    <div class="col-auto d-none d-lg-block">
                        <img src="{{ asset($banners['home_banner_mini_1']->image) }}" alt="Top Banner">
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-6">
            @if (isset($banners['home_banner_mini_2']) &&
                    $banners['home_banner_mini_2']->is_active == \App\Enums\BannerStatus::ACTIVE)
                <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                    <div class="col p-4 d-flex flex-column position-static">
                        <strong class="d-inline-block mb-2 text-success">Design</strong>
                        <h3 class="mb-0">Post title</h3>
                        <div class="mb-1 text-muted">Nov 11</div>
                        <p class="mb-auto">This is a wider card with supporting text below as a natural lead-in to
                            additional
                            content.</p>
                        <a href="#" class="stretched-link">Continue reading</a>
                    </div>
                    <div class="col-auto d-none d-lg-block">
                        <img src="{{ asset($banners['home_banner_mini_2']->image) }}" alt="Top Banner">
                    </div>
                </div>
            @endif
        </div>
    </div>
    {{-- List course 1 --}}
    <div class="mt-5 learning-series-list">
        <h1 class="mb-3">Khóa học</h1>
        <div class="swiper-learning-series swiper swiper-container">
            <div class="swiper-wrapper">
                @foreach ($learning_series_list as $learning_series)
                    @if (isset($learning_series->seriesList) && count($learning_series->seriesList) > 0)
                        <div class="swiper-slide">
                            <div class="course-card">
                                <img alt="course image" height="400" width="600"
                                    src="{{ asset('/public/' . config('constant.series_combo.upload_path') . $learning_series->image) }}" />
                                <div class="course-card-body">
                                    <h5 class="course-card-title">{{ $learning_series->title }}</h5>
                                    <p class="course-card-price">{{ formatCurrencyVND($learning_series->cost) }}</p>
                                    <div class="course-card-description line-clamp-3">{!! $learning_series->short_description !!}</div>
                                    <div class="course-card-teacher text-muted w-100 mb-1">{!! $learning_series->description['teacher_description'] ?? '' !!}</div>
                                    <div class="d-flex align-items-center text-primary-color mt-3">
                                        <i class="bi bi-play-circle-fill"></i>
                                        <span class="ms-2">{{ $learning_series->content_count }}</span>
                                        <i class="bi bi-book ms-3"></i>
                                        <span
                                            class="ms-2">{{ empty($learning_series->chapter_count) ? 1 : $learning_series->chapter_count }}
                                            chương</span>
                                        @if ($learning_series->checkMultipleCombo)
                                            <button class="btn btn-outline-primary ms-auto"
                                                onclick="location.href='{{ route('series.introduction-detail-combo', ['combo_slug' => $learning_series->slug]) }}'">
                                                Xem thêm
                                            </button>
                                        @else
                                            <button class="btn btn-outline-primary ms-auto"
                                                onclick="location.href='{{ route('series.introduction-detail', ['combo_slug' => $learning_series->slug, 'slug' => $learning_series->seriesList[0]->slug]) }}'">
                                                Xem thêm
                                            </button>
                                        @endif
                                    </div>
                                    @if (Auth::check() && $learning_series->valid_payment && count($learning_series->seriesList) > 1)
                                        <button class="btn btn-primary w-100 mt-3"
                                            onclick="location.href='{{ route('mypage.courses') }}'">
                                            Học ngay
                                        </button>
                                    @elseif (Auth::check() && $learning_series->valid_payment && count($learning_series->seriesList) == 1)
                                        <button class="btn btn-primary w-100 mt-3"
                                            onclick="location.href='{{ route('learning-management.lesson.show', ['combo_slug' => $learning_series->slug, 'slug' => $learning_series->seriesList[0]->slug]) }}'">
                                            Học ngay
                                        </button>
                                    @elseif (Auth::check())
                                        <button class="btn btn-primary w-100 mt-3"
                                            onclick="location.href='{{ route('payments.lms', $learning_series->slug) }}'">
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
        <div class="swiper-button-next-1 swiper-button-next"></div>
        <div class="swiper-button-prev-1 swiper-button-prev"></div>
    </div>

    {{-- List course 2 --}}
    <div class="mt-5 exam-series-list">
        <h1 class="mb-3">Khóa luyện thi</h1>
        <div class="swiper-exam-series swiper-container swiper">
            <div class="swiper-wrapper">
                @foreach ($exam_series_list as $exam_series)
                    @if (isset($exam_series->seriesList) && count($exam_series->seriesList) > 0)
                        <div class="swiper-slide">
                            <div class="course-card">
                                <img alt="course image" height="400" width="600"
                                    src="{{ asset('/public/' . config('constant.series_combo.upload_path') . $exam_series->image) }}" />
                                <div class="course-card-body">
                                    <h5 class="course-card-title">{{ $exam_series->title }}</h5>
                                    <p class="course-card-price">{{ formatCurrencyVND($exam_series->cost) }}</p>
                                    <div class="course-card-description line-clamp-3">{!! $exam_series->short_description !!}</div>
                                    <div class="course-card-teacher text-muted w-100 mb-1">{!! $exam_series->description['teacher_description'] ?? '' !!}</div>
                                    <div class="d-flex align-items-center text-primary-color mt-3">
                                        <i class="bi bi-play-circle-fill"></i>
                                        <span class="ms-2">{{ $exam_series->content_count }}</span>
                                        <i class="bi bi-book ms-3"></i>
                                        <span
                                            class="ms-2">{{ empty($exam_series->chapter_count) ? 1 : $exam_series->chapter_count }}
                                            chương</span>
                                        @if ($exam_series->checkMultipleCombo)
                                            <button class="btn btn-outline-primary ms-auto"
                                                onclick="location.href='{{ route('series.introduction-detail-combo', ['combo_slug' => $exam_series->slug]) }}'">
                                                Xem thêm
                                            </button>
                                        @else
                                            <button class="btn btn-outline-primary ms-auto"
                                                onclick="location.href='{{ route('series.introduction-detail', ['combo_slug' => $exam_series->slug, 'slug' => $exam_series->seriesList[0]->slug]) }}'">
                                                Xem thêm
                                            </button>
                                        @endif
                                    </div>
                                    @if (Auth::check() && $exam_series->valid_payment && count($exam_series->seriesList) > 1)
                                        <button class="btn btn-primary w-100 mt-3"
                                            onclick="location.href='{{ route('mypage.courses') }}'">
                                            Học ngay
                                        </button>
                                    @elseif (Auth::check() && $exam_series->valid_payment && count($exam_series->seriesList) == 1)
                                        <button class="btn btn-primary w-100 mt-3"
                                            onclick="location.href='{{ route('learning-management.lesson.show', ['combo_slug' => $exam_series->slug, 'slug' => $exam_series->seriesList[0]->slug]) }}'">
                                            Học ngay
                                        </button>
                                    @elseif (Auth::check())
                                        <button class="btn btn-primary w-100 mt-3"
                                            onclick="location.href='{{ route('payments.lms', $exam_series->slug) }}'">
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
        <div class="swiper-button-next-2 swiper-button-next"></div>
        <div class="swiper-button-prev-2 swiper-button-prev"></div>
    </div>

    @if (isset($banners['home_banner_1']) && $banners['home_banner_1']->is_active === App\Enums\BannerStatus::ACTIVE)
        <div class="cover-image about-widget sptb bg-background-color my-5 p-5"
            style="background: url('{{ asset($banners['home_banner_1']->image) }}') no-repeat;
        background-size: cover;">
            <div class="content-text mb-0">
                <div class="container">
                    <div class="row text-center justify-content-center">
                        <div class="col-12">
                            <h1 class="display-5 gradient-title mb-4">HỌC TIẾNG NHẬT CÙNG HIKARI ACADEMY</h1>
                            <p class="lead mb-4">
                                Hikari Academy không ngừng chú trọng phát triển nội dung nhằm đạt chất lượng cao, luôn lắng
                                nghe
                                phản hồi của khách hàng và hành động, ngày càng góp phần nâng cao lòng tin của khách hàng.
                            </p>
                            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                                <button type="button" class="btn btn-primary btn-lg px-4 gap-3"
                                    onclick="showAuthModal(false)">
                                    Đăng ký ngay
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- Student review --}}
    <div class="row mt-5">
        <div class="col-md-12" data-wow-delay="0.2s">
            <div id="quote-carousel" class="carousel slide" data-bs-ride="carousel">
                <!-- Bottom Carousel Indicators -->
                <ol class="carousel-indicators d-flex justtify-content-center align-items-center">
                    <li data-bs-target="#quote-carousel" data-bs-slide-to="0" class="active">
                        <img class="img-responsive" src="{{ asset('images/hoc-vien-1.png') }}" alt="">
                    </li>
                    <li data-bs-target="#quote-carousel" data-bs-slide-to="1">
                        <img class="img-responsive" src="{{ asset('images/hoc-vien-2.png') }}" alt="image" />
                    </li>
                    <li data-bs-target="#quote-carousel" data-bs-slide-to="2">
                        <img class="img-responsive" src="{{ asset('images/hoc-vien-3.png') }}" alt="image" />
                    </li>
                </ol>

                <!-- Carousel Slides / Quotes -->
                <div class="quote-carousel-inner text-center ">
                    <!-- Quote 1 -->
                    <div class="carousel-item active">
                        <blockquote>
                            <div class="row">
                                <div class="col-sm-8 offset-sm-2">
                                    <h2>Mr. Long</h2>
                                    <small>Học tại Hikari Academy là trải nghiệm tuyệt vời. Giáo viên nhiệt tình, phương
                                        pháp giảng dạy hiệu quả, môi trường thân thiện. Em tiến bộ nhanh chóng và hiểu
                                        sâu
                                        hơn về văn hóa Nhật Bản. Rất hài lòng với sự lựa chọn của mình.</small>
                                    <div>
                                        <i class="bi bi-star-fill text-warning pe-1"></i>
                                        <i class="bi bi-star-fill text-warning pe-1"></i>
                                        <i class="bi bi-star-fill text-warning pe-1"></i>
                                        <i class="bi bi-star-fill text-warning pe-1"></i>
                                        <i class="bi bi-star-fill text-warning pe-1"></i>
                                    </div>
                                </div>
                            </div>
                        </blockquote>
                    </div>
                    <!-- Quote 2 -->
                    <div class="carousel-item">
                        <blockquote>
                            <div class="row">
                                <div class="col-sm-8 offset-sm-2">
                                    <h2>Ms. My</h2>
                                    <small>
                                        Em rất hài lòng khi học tại Hikari Academy. Giáo viên giàu kinh nghiệm, nhiệt
                                        tình
                                        hỗ trợ học viên. Phương pháp giảng dạy sáng tạo, dễ hiểu, giúp em tiếp thu nhanh
                                        chóng. Trung tâm còn tổ chức nhiều hoạt động bổ ích, Tự tin hơn trong kỳ thi sắp
                                        tới
                                        ạ.
                                    </small>
                                    <div>
                                        <i class="bi bi-star-fill text-warning pe-1"></i>
                                        <i class="bi bi-star-fill text-warning pe-1"></i>
                                        <i class="bi bi-star-fill text-warning pe-1"></i>
                                        <i class="bi bi-star-fill text-warning pe-1"></i>
                                        <i class="bi bi-star-fill text-warning pe-1"></i>
                                    </div>
                                </div>
                            </div>
                        </blockquote>
                    </div>
                    <!-- Quote 3 -->
                    <div class="carousel-item">
                        <blockquote>
                            <div class="row">
                                <div class="col-sm-8 offset-sm-2">
                                    <h2>Ms. Linh</h2>
                                    <small>
                                        Học tại Hikari Academy, em thấy hài lòng và hứng thú. Giáo viên tận tâm, phương
                                        pháp
                                        giảng dạy hiện đại. Môi trường thân thiện, trang thiết bị đầy đủ giúp tôi tiến
                                        bộ
                                        nhanh chóng. Em tự tin hơn trong việc sử dụng tiếng Nhật hàng ngày.
                                    </small>
                                    <div>
                                        <i class="bi bi-star-fill text-warning pe-1"></i>
                                        <i class="bi bi-star-fill text-warning pe-1"></i>
                                        <i class="bi bi-star-fill text-warning pe-1"></i>
                                        <i class="bi bi-star-fill text-warning pe-1"></i>
                                        <i class="bi bi-star-fill text-warning pe-1"></i>
                                    </div>
                                </div>
                            </div>
                        </blockquote>
                    </div>
                </div>

                <!-- Carousel Buttons Next/Prev -->
                <button class="carousel-control-prev" type="button" data-bs-target="#quote-carousel"
                    data-bs-slide="prev">
                    <i class="bi bi-chevron-right"></i>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#quote-carousel"
                    data-bs-slide="next">
                    <i class="bi bi-chevron-right"></i>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Partner carousel --}}
    <div class="partners-section">
        <h2>ĐỐI TÁC CỦA HIKARI ACADEMY</h2>
        <div class="divider"></div>
        <div class="carousel slide" data-bs-ride="carousel" id="partnersCarousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="row">
                        <div class="col">
                            <img alt="Logo of KatchUp.vn with text 'Thẻ học tiếng Nhật - Học nhanh - Nhớ lâu'"
                                class="d-block mx-auto" height="100"
                                src="{{ asset('images/clients/client-3.png') }}" />
                        </div>
                        <div class="col">
                            <img alt="Logo of Global CyberSoft with text 'A Hitachi Consulting Company'"
                                class="d-block mx-auto" height="100"
                                src="{{ asset('images/clients/client-2.png') }}" />
                        </div>
                        <div class="col">
                            <img alt="Logo of Vina Acecook" class="d-block mx-auto" height="100"
                                src="{{ asset('images/clients/client-4.png') }}" />
                        </div>
                        <div class="col">
                            <img alt="Logo of NEC with text 'Orchestrating a brighter world'" class="d-block mx-auto"
                                height="100" src="{{ asset('images/clients/client-1.png') }}" />
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row">
                        <div class="col">
                            <img alt="Logo of Global CyberSoft with text 'A Hitachi Consulting Company'"
                                class="d-block mx-auto" height="100"
                                src="{{ asset('images/clients/client-2.png') }}" />
                        </div>
                        <div class="col">
                            <img alt="Logo of NEC with text 'Orchestrating a brighter world'" class="d-block mx-auto"
                                height="100" src="{{ asset('images/clients/client-1.png') }}" />
                        </div>
                        <div class="col">
                            <img alt="Logo of KatchUp.vn with text 'Thẻ học tiếng Nhật - Học nhanh - Nhớ lâu'"
                                class="d-block mx-auto" height="100"
                                src="{{ asset('images/clients/client-3.png') }}" />
                        </div>
                        <div class="col">
                            <img alt="Logo of Vina Acecook" class="d-block mx-auto" height="100"
                                src="{{ asset('images/clients/client-4.png') }}" />
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#partnersCarousel"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#partnersCarousel"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    @if (isset($banners['home_banner_2']) && $banners['home_banner_2']->is_active == App\Enums\BannerStatus::ACTIVE)
        <div class="cover-image sptb bg-background-color text-white p-5 w-100 mb-5"
            style="background: url('{{ asset($banners['home_banner_2']->image) }}') no-repeat; background-size: cover;">
            <div class="content-text mb-0">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="widgets-cards mb-5 d-flex">
                                        <div class="widgets-cards-icons me-5">
                                            <i class="fe fe-wifi"></i>
                                        </div>
                                        <div class="widgets-cards-data">
                                            <h4>
                                                <a class="text-white fs-25" href="#">
                                                    Đào tạo khoa học
                                                </a>
                                            </h4>
                                            <p class="text-white mt-2 mb-0">
                                                Cùng lộ trình giảng dạy bài bản, chuyên sâu xây dựng bởi đội ngũ
                                                giảng viên tiếng Nhật giàu kinh nghiệm
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="widgets-cards mb-5 d-flex">
                                        <div class="widgets-cards-icons me-5">
                                            <i class="fe fe-wifi-off"></i>
                                        </div>
                                        <div class="widgets-cards-data">
                                            <h4>
                                                <a class="text-white fs-25" href="#">
                                                    Mô phỏng thực tế
                                                </a>
                                            </h4>
                                            <p class="text-white mt-2 mb-0">
                                                Thời gian thực làm quen cùng kỳ thi JLPT cùng kho tàng đề thi đa
                                                dạng - đầy đủ nhất Việt Nam
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="widgets-cards d-flex">
                                        <div class="widgets-cards-icons me-5">
                                            <i class="fe fe-book-open"></i>
                                        </div>
                                        <div class="widgets-cards-data">
                                            <h4>
                                                <a class="text-white fs-25" href="#">
                                                    Cơ hội việc làm
                                                </a>
                                            </h4>
                                            <p class="text-white mt-2 mb-0">
                                                Cung cấp cơ hội việc làm và du học tại Nhật Bản với tính năng tìm
                                                việc cùng thông tin du học đa dạng.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="clients-img">
                                <img alt="img" class="bg-white br-3 p-1 img-fluid"
                                    src="{{ asset('images/banner/about-hikari01.jpg') }}">
                                <img alt="img" class="bg-white br-3 p-1 img-fluid"
                                    src="{{ asset('images/banner/cong-ty-quang-viet.png') }}">
                                <img alt="img" class="bg-white br-3 p-1 img-fluid"
                                    src="{{ asset('images/banner/khoa-hoc-offline.jpg') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <section class="sptb my-5">
        <div class="container">
            <div class="section-title text-center">
                <h2>TIẾNG NHẬT KHÓ ĐÃ CÓ HIKARI ACADEMY</h2>
                <p>Hikari Academy đem đến cho bạn một khóa học với các bài giảng xuyên suốt các chủ đề rõ ràng và quen
                    thuộc
                    với hầu hết những kĩ năng cần thiết.</p>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-4 sptb-item">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="fea-icon fs-3 bg-success mb-3 text-white">
                                <div class="feature-icon-content">
                                    <i class="bi bi-megaphone-fill"></i>
                                </div>
                            </div>
                            <h3 class="fw-semibold">KHÓA HỌC</h3>
                            <p>Đa dạng theo mọi trình độ từ N5 đến N1</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 sptb-item">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="fea-icon fs-3 bg-danger mb-3 text-white">
                                <div class="feature-icon-content">
                                    <i class="bi bi-heart-fill"></i>
                                </div>
                            </div>
                            <h3 class="fw-semibold">LỚP HỌC</h3>
                            <p>Tính năng lớp học dành riêng cho doanh nghiệp, trường học</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 sptb-item">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="fea-icon fs-3 bg-warning mb-3 text-white">
                                <div class="feature-icon-content">
                                    <i class="bi bi-bookmark-fill"></i>
                                </div>
                            </div>
                            <h3 class="fw-semibold">KỸ NĂNG TOÀN DIỆN</h3>
                            <p>Từ vựng, ngữ pháp, đọc hiểu, nghe, thoại hội, luyện tập</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 sptb-item">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="fea-icon fs-3 bg-secondary mb-3 text-white">
                                <div class="feature-icon-content">
                                    <i class="bi bi-mic-fill"></i>
                                </div>
                            </div>
                            <h3 class="fw-semibold">LUYỆN PHÁT ÂM</h3>
                            <p>Ứng dụng công nghệ hàng đầu kiểm tra phát âm theo giọng bản xứ</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 sptb-item">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="fea-icon fs-3 bg-primary mb-3 text-white">
                                <div class="feature-icon-content">
                                    <i class="bi bi-spellcheck"></i>
                                </div>
                            </div>
                            <h3 class="fw-semibold">CHỮ HÁN</h3>
                            <p>Tính năng thông minh hỗ trợ nhớ nhanh Hán Tự</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 sptb-item">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="fea-icon fs-3 bg-info mb-3 text-white">
                                <div class="feature-icon-content">
                                    <i class="bi bi-card-checklist"></i>
                                </div>
                            </div>
                            <h3 class="fw-semibold">ĐỀ THI</h3>
                            <p>Phong phú, đa dạng mọi trình độ JLPT N5 - N1</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('js/plugins/swiperjs/swiper-bundle.min.js') }}"></script>
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            setupLearningSeriesSwiper();
            setEqualSeriesCardHeight('.swiper-learning-series');
            setCourseBoxRightDisplay('.swiper-learning-series');

            setupExamSeriesSwiper();
            setEqualSeriesCardHeight('.swiper-exam-series');
            setCourseBoxRightDisplay('.swiper-exam-series');
        });

        const setupExamSeriesSwiper = () => {
            new Swiper('.swiper-exam-series', {
                slidesPerView: 1,
                spaceBetween: 5,
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: '.swiper-button-next-2',
                    prevEl: '.swiper-button-prev-2',
                },
                slidesPerGroup: 1,
                autoHeight: true,
                breakpoints: {
                    1200: {
                        slidesPerView: 3,
                    },
                    768: {
                        slidesPerView: 2,
                    }
                },
            });
        }

        const setupLearningSeriesSwiper = () => {
            new Swiper('.swiper-learning-series', {
                slidesPerView: 1,
                spaceBetween: 5,
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: '.swiper-button-next-1',
                    prevEl: '.swiper-button-prev-1',
                },
                slidesPerGroup: 1,
                autoHeight: true,
                breakpoints: {
                    1200: {
                        slidesPerView: 3,
                    },
                    768: {
                        slidesPerView: 2,
                    }
                },
            });
        }

        const setEqualSeriesCardHeight = (swiperContainerClass) => {
            let maxTeacherDescripitonHeight = 0;
            let maxShortDescriptionHeight = 0;

            $(swiperContainerClass).find('.course-card-teacher').each(function() {
                let currentHeight = $(this).outerHeight();
                if (currentHeight > maxTeacherDescripitonHeight) {
                    maxTeacherDescripitonHeight = currentHeight;
                }
            });

            $(swiperContainerClass).find('.course-card-description').each(function() {
                let currentHeight = $(this).outerHeight();
                if (currentHeight > maxShortDescriptionHeight) {
                    maxShortDescriptionHeight = currentHeight;
                }
            });

            $(swiperContainerClass).find('.course-card-teacher').css('min-height', maxTeacherDescripitonHeight + 'px');
            $(swiperContainerClass).find('.course-card-description').css('min-height', maxShortDescriptionHeight +
                'px');
        }

        const setCourseBoxRightDisplay = (swiperContainerClass) => {
            const contentWidth = $(swiperContainerClass).find('.course-box .course-header').width();
            const titleWidth = $(swiperContainerClass).find('.course-box .course-header .course-title').width();

            if (titleWidth / contentWidth > 0.5) {
                const courseHeader = $(swiperContainerClass).find('.course-box .course-header');
                courseHeader.css('flex-direction', 'column');
                courseHeader.css('gap', '8px');
            }
        }
    </script>
@endsection
