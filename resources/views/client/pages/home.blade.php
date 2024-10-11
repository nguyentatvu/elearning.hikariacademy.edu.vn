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
        }

        .fea-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #F3F9FA;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .img-fluid {
            height: 100px;
            width: 100px;
        }
    </style>
@endsection

@section('content')
    <div id="carouselExampleCaptions" class="carousel slide">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('images/asset/slider/banner-slider-n3.jpg') }}" class="d-block w-100" alt="...">
                <div class="carousel-caption d-flex flex-column h-100 align-items-center justify-content-center bottom-0 ">
                    <h2 class="bg-dark bg-opacity-50 py-2 px-4">Second slide label</h2>
                    <p class="bg-dark bg-opacity-50 py-2 px-4">Some representative placeholder content for the second
                        slide.</p>
                    <a href="#" class="btn btn-outline-light px-4 py-2 rounded-0">Learn More</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/asset/slider/banner-slider-n3.jpg') }}" class="d-block w-100" alt="...">
                <div class="carousel-caption d-flex flex-column h-100 align-items-center justify-content-center bottom-0 ">
                    <h2 class="bg-dark bg-opacity-50 py-2 px-4">Second slide label</h2>
                    <p class="bg-dark bg-opacity-50 py-2 px-4">Some representative placeholder content for the second
                        slide.</p>
                    <a href="#" class="btn btn-outline-light px-4 py-2 rounded-0">Learn More</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/asset/slider/banner-slider-n4.jpg') }}" class="d-block w-100" alt="...">
                <div class="carousel-caption d-flex flex-column h-100 align-items-center justify-content-center bottom-0">
                    <h2 class="bg-dark bg-opacity-50 py-2 px-4">Third slide label</h2>
                    <p class="bg-dark bg-opacity-50 py-2 px-4">Some representative placeholder content for the third
                        slide.</p>
                    <a href="#" class="btn btn-outline-light px-4 py-2 rounded-0">Learn More</a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    {{-- Banner --}}

    <div class="row mb-2 mt-5">
        <div class="col-md-6">
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
                    <svg class="bd-placeholder-img" width="200" height="250" xmlns="http://www.w3.org/2000/svg"
                        role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice"
                        focusable="false">
                        <title>Placeholder</title>
                        <rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef"
                            dy=".3em">Thumbnail</text>
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                <div class="col p-4 d-flex flex-column position-static">
                    <strong class="d-inline-block mb-2 text-success">Design</strong>
                    <h3 class="mb-0">Post title</h3>
                    <div class="mb-1 text-muted">Nov 11</div>
                    <p class="mb-auto">This is a wider card with supporting text below as a natural lead-in to additional
                        content.</p>
                    <a href="#" class="stretched-link">Continue reading</a>
                </div>
                <div class="col-auto d-none d-lg-block">
                    <svg class="bd-placeholder-img" width="200" height="250" xmlns="http://www.w3.org/2000/svg"
                        role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice"
                        focusable="false">
                        <title>Placeholder</title>
                        <rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef"
                            dy=".3em">Thumbnail</text>
                    </svg>

                </div>
            </div>
        </div>
    </div>
    {{-- List course 1 --}}
    <div class="mt-5">
        <h1 class="mb-3">Khóa học</h1>
        <p class="text-muted">409.352+ người khác đã học</p>

        <div id="courseCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="course-card">
                                <img alt="Placeholder image for course" height="400"
                                    src="https://riki.edu.vn/_nuxt/img/1.abd2d70.png" width="600" />
                                <div class="course-card-body">
                                    <h5 class="course-card-title">Khóa học</h5>
                                    <p class="course-card-price">1.600.000đ</p>
                                    <p class="course-card-description">Khóa học giúp bạn rèn luyện kỹ năng đọc hiểu</p>
                                    <p class="text-muted">Cô Vi, Cô Hoa, Thầy Nakamura</p>
                                    <div class="d-flex align-items-center text-primary-color">
                                        <i class="bi bi-play-circle-fill"></i>
                                        <span class="ms-2">45</span>
                                        <i class="bi bi-clock ms-3"></i>
                                        <span class="ms-2 ml-auto">9 tháng</span>
                                        <button class="btn btn-outline-primary ms-auto">Xem thêm</button>
                                    </div>
                                    <button class="btn btn-primary w-100 mt-3">Mua ngay</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="course-card">
                                <img alt="Placeholder image for course" height="400"
                                    src="https://riki.edu.vn/_nuxt/img/1.abd2d70.png" width="600" />
                                <div class="course-card-body">
                                    <h5 class="course-card-title">Khóa học</h5>
                                    <p class="course-card-price">1.600.000đ</p>
                                    <p class="course-card-description">Khóa học giúp bạn rèn luyện kỹ năng đọc hiểu</p>
                                    <p class="text-muted">Cô Vi, Cô Hoa, Thầy Nakamura</p>
                                    <div class="d-flex align-items-center text-primary-color">
                                        <i class="bi bi-play-circle-fill"></i>
                                        <span class="ms-2">45</span>
                                        <i class="bi bi-clock ms-3"></i>
                                        <span class="ms-2 ml-auto">9 tháng</span>
                                        <button class="btn btn-outline-primary ms-auto">Xem thêm</button>
                                    </div>
                                    <button class="btn btn-primary w-100 mt-3">Mua ngay</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="course-card">
                                <img alt="Placeholder image for course" height="400"
                                    src="https://riki.edu.vn/_nuxt/img/1.abd2d70.png" width="600" />
                                <div class="course-card-body">
                                    <h5 class="course-card-title">Khóa học</h5>
                                    <p class="course-card-price">1.600.000đ</p>
                                    <p class="course-card-description">Khóa học giúp bạn rèn luyện kỹ năng đọc hiểu</p>
                                    <p class="text-muted">Cô Vi, Cô Hoa, Thầy Nakamura</p>
                                    <div class="d-flex align-items-center text-primary-color">
                                        <i class="bi bi-play-circle-fill"></i>
                                        <span class="ms-2">45</span>
                                        <i class="bi bi-clock ms-3"></i>
                                        <span class="ms-2 ml-auto">9 tháng</span>
                                        <button class="btn btn-outline-primary ms-auto">Xem thêm</button>
                                    </div>
                                    <button class="btn btn-primary w-100 mt-3">Mua ngay</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="course-card">
                                <img alt="Placeholder image for course" height="400"
                                    src="https://riki.edu.vn/_nuxt/img/1.abd2d70.png" width="600" />
                                <div class="course-card-body">
                                    <h5 class="course-card-title">Khóa học</h5>
                                    <p class="course-card-price">1.600.000đ</p>
                                    <p class="course-card-description">Khóa học giúp bạn rèn luyện kỹ năng đọc hiểu</p>
                                    <p class="text-muted">Cô Vi, Cô Hoa, Thầy Nakamura</p>
                                    <div class="d-flex align-items-center text-primary-color">
                                        <i class="bi bi-play-circle-fill"></i>
                                        <span class="ms-2">45</span>
                                        <i class="bi bi-clock ms-3"></i>
                                        <span class="ms-2 ml-auto">9 tháng</span>
                                        <button class="btn btn-outline-primary ms-auto">Xem thêm</button>
                                    </div>
                                    <button class="btn btn-primary w-100 mt-3">Mua ngay</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="course-card">
                                <img alt="Placeholder image for course" height="400"
                                    src="https://riki.edu.vn/_nuxt/img/1.abd2d70.png" width="600" />
                                <div class="course-card-body">
                                    <h5 class="course-card-title">Khóa học</h5>
                                    <p class="course-card-price">1.600.000đ</p>
                                    <p class="course-card-description">Khóa học giúp bạn rèn luyện kỹ năng đọc hiểu</p>
                                    <p class="text-muted">Cô Vi, Cô Hoa, Thầy Nakamura</p>
                                    <div class="d-flex align-items-center text-primary-color">
                                        <i class="bi bi-play-circle-fill"></i>
                                        <span class="ms-2">45</span>
                                        <i class="bi bi-clock ms-3"></i>
                                        <span class="ms-2 ml-auto">9 tháng</span>
                                        <button class="btn btn-outline-primary ms-auto">Xem thêm</button>
                                    </div>
                                    <button class="btn btn-primary w-100 mt-3">Mua ngay</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="course-card">
                                <img alt="Placeholder image for course" height="400"
                                    src="https://riki.edu.vn/_nuxt/img/1.abd2d70.png" width="600" />
                                <div class="course-card-body">
                                    <h5 class="course-card-title">Khóa học</h5>
                                    <p class="course-card-price">1.600.000đ</p>
                                    <p class="course-card-description">Khóa học giúp bạn rèn luyện kỹ năng đọc hiểu</p>
                                    <p class="text-muted">Cô Vi, Cô Hoa, Thầy Nakamura</p>
                                    <div class="d-flex align-items-center text-primary-color">
                                        <i class="bi bi-play-circle-fill"></i>
                                        <span class="ms-2">45</span>
                                        <i class="bi bi-clock ms-3"></i>
                                        <span class="ms-2 ml-auto">9 tháng</span>
                                        <button class="btn btn-outline-primary ms-auto">Xem thêm</button>
                                    </div>
                                    <button class="btn btn-primary w-100 mt-3">Mua ngay</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#courseCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#courseCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    {{-- List test --}}
    <div class="mt-5">
        <h1 class="mb-3">Khóa học</h1>
        <p class="text-muted">409.352+ người khác đã học</p>

        <div id="courseCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="course-card">
                                <img alt="Placeholder image for course" height="400"
                                    src="https://riki.edu.vn/_nuxt/img/1.abd2d70.png" width="600" />
                                <div class="course-card-body">
                                    <h5 class="course-card-title">Khóa học</h5>
                                    <p class="course-card-price">1.600.000đ</p>
                                    <p class="course-card-description">Khóa học giúp bạn rèn luyện kỹ năng đọc hiểu</p>
                                    <p class="text-muted">Cô Vi, Cô Hoa, Thầy Nakamura</p>
                                    <div class="d-flex align-items-center text-primary-color">
                                        <i class="bi bi-play-circle-fill"></i>
                                        <span class="ms-2">45</span>
                                        <i class="bi bi-clock ms-3"></i>
                                        <span class="ms-2 ml-auto">9 tháng</span>
                                        <button class="btn btn-outline-primary ms-auto">Xem thêm</button>
                                    </div>
                                    <a href="" class="btn btn-primary w-100 mt-3">Mua ngay</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="course-card">
                                <img alt="Placeholder image for course" height="400"
                                    src="https://riki.edu.vn/_nuxt/img/1.abd2d70.png" width="600" />
                                <div class="course-card-body">
                                    <h5 class="course-card-title">Khóa học</h5>
                                    <p class="course-card-price">1.600.000đ</p>
                                    <p class="course-card-description">Khóa học giúp bạn rèn luyện kỹ năng đọc hiểu</p>
                                    <p class="text-muted">Cô Vi, Cô Hoa, Thầy Nakamura</p>
                                    <div class="d-flex align-items-center text-primary-color">
                                        <i class="bi bi-play-circle-fill"></i>
                                        <span class="ms-2">45</span>
                                        <i class="bi bi-clock ms-3"></i>
                                        <span class="ms-2 ml-auto">9 tháng</span>
                                        <button class="btn btn-outline-primary ms-auto">Xem thêm</button>
                                    </div>
                                    <button class="btn btn-primary w-100 mt-3">Mua ngay</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="course-card">
                                <img alt="Placeholder image for course" height="400"
                                    src="https://riki.edu.vn/_nuxt/img/1.abd2d70.png" width="600" />
                                <div class="course-card-body">
                                    <h5 class="course-card-title">Khóa học</h5>
                                    <p class="course-card-price">1.600.000đ</p>
                                    <p class="course-card-description">Khóa học giúp bạn rèn luyện kỹ năng đọc hiểu</p>
                                    <p class="text-muted">Cô Vi, Cô Hoa, Thầy Nakamura</p>
                                    <div class="d-flex align-items-center text-primary-color">
                                        <i class="bi bi-play-circle-fill"></i>
                                        <span class="ms-2">45</span>
                                        <i class="bi bi-clock ms-3"></i>
                                        <span class="ms-2 ml-auto">9 tháng</span>
                                        <button class="btn btn-outline-primary ms-auto">Xem thêm</button>
                                    </div>
                                    <button class="btn btn-primary w-100 mt-3">Mua ngay</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="course-card">
                                <img alt="Placeholder image for course" height="400"
                                    src="https://riki.edu.vn/_nuxt/img/1.abd2d70.png" width="600" />
                                <div class="course-card-body">
                                    <h5 class="course-card-title">Khóa học</h5>
                                    <p class="course-card-price">1.600.000đ</p>
                                    <p class="course-card-description">Khóa học giúp bạn rèn luyện kỹ năng đọc hiểu</p>
                                    <p class="text-muted">Cô Vi, Cô Hoa, Thầy Nakamura</p>
                                    <div class="d-flex align-items-center text-primary-color">
                                        <i class="bi bi-play-circle-fill"></i>
                                        <span class="ms-2">45</span>
                                        <i class="bi bi-clock ms-3"></i>
                                        <span class="ms-2 ml-auto">9 tháng</span>
                                        <button class="btn btn-outline-primary ms-auto">Xem thêm</button>
                                    </div>
                                    <button class="btn btn-primary w-100 mt-3">Mua ngay</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="course-card">
                                <img alt="Placeholder image for course" height="400"
                                    src="https://riki.edu.vn/_nuxt/img/1.abd2d70.png" width="600" />
                                <div class="course-card-body">
                                    <h5 class="course-card-title">Khóa học</h5>
                                    <p class="course-card-price">1.600.000đ</p>
                                    <p class="course-card-description">Khóa học giúp bạn rèn luyện kỹ năng đọc hiểu</p>
                                    <p class="text-muted">Cô Vi, Cô Hoa, Thầy Nakamura</p>
                                    <div class="d-flex align-items-center text-primary-color">
                                        <i class="bi bi-play-circle-fill"></i>
                                        <span class="ms-2">45</span>
                                        <i class="bi bi-clock ms-3"></i>
                                        <span class="ms-2 ml-auto">9 tháng</span>
                                        <button class="btn btn-outline-primary ms-auto">Xem thêm</button>
                                    </div>
                                    <button class="btn btn-primary w-100 mt-3">Mua ngay</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="course-card">
                                <img alt="Placeholder image for course" height="400"
                                    src="https://riki.edu.vn/_nuxt/img/1.abd2d70.png" width="600" />
                                <div class="course-card-body">
                                    <h5 class="course-card-title">Khóa học</h5>
                                    <p class="course-card-price">1.600.000đ</p>
                                    <p class="course-card-description">Khóa học giúp bạn rèn luyện kỹ năng đọc hiểu</p>
                                    <p class="text-muted">Cô Vi, Cô Hoa, Thầy Nakamura</p>
                                    <div class="d-flex align-items-center text-primary-color">
                                        <i class="bi bi-play-circle-fill"></i>
                                        <span class="ms-2">45</span>
                                        <i class="bi bi-clock ms-3"></i>
                                        <span class="ms-2 ml-auto">9 tháng</span>
                                        <button class="btn btn-outline-primary ms-auto">Xem thêm</button>
                                    </div>
                                    <button class="btn btn-primary w-100 mt-3">Mua ngay</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#courseCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#courseCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    {{-- List course 2 --}}
    <div class="testimonial-slider mt-5">
        <div id="carouselExampleControls" class="carousel carousel-dark">
            <div class="container-fluid">
                <div class="row d-flex justify-content-center align-items-center">
                    <div class="col-md-8">
                        <div class="testimonial-title">
                            {{-- <i class="bi bi-quote display-1"></i> --}}
                            <h4 class="fw-bold"> <span class="text-primary-color fw-bold">OFFLINE</span> Học trực tiếp
                                tại các cơ sở ở TP. HCM cùng các giảng viên giàu
                                kinh nghiệm.</h4>
                            <div>Lớp học trực tiếp có giáo viên kèm cặp, cam kết đảm bảo đầu ra bằng văn bản.</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="circle-container">
                            <div class="circle">N1</div>
                            <div class="circle">N2</div>
                            <div class="circle">N3</div>
                            <div class="circle">N4</div>
                            <div class="circle">N5</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="cover-image about-widget sptb bg-background-color my-5 p-5"
        style="background: url('{{ asset('images/banner/background-logan-2.jpg') }}') no-repeat;
           background-size: cover;">
        <div class="content-text mb-0">
            <div class="container">
                <div class="text-center">
                    <h2 class="mb-2 fw-normal">HỌC TIẾNG NHẬT CÙNG HIKARI ACADEMY</h2>
                    <p>Hikari Academy không ngừng chú trọng phát triển nội dung nhằm đạt chất lượng cao, luôn
                        lắng nghe phản
                        hồi của khách hàng và hành động, ngày càng góp phần nâng cao lòng tin của khách hàng.
                    </p>
                    <div class="mt-5">
                        <a href="/register" class="btn btn-lg btn-primary">Đăng ký ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Student review --}}
    <div class="row mt-5">
        <div class="col-md-12" data-wow-delay="0.2s">
            <div id="quote-carousel" class="carousel slide" data-bs-ride="carousel">
                <!-- Bottom Carousel Indicators -->
                <ol class="carousel-indicators d-flex justtify-content-center align-items-center">
                    <li data-bs-target="#quote-carousel" data-bs-slide-to="0" class="active">
                        <img class="img-responsive"
                            src="https://static.vecteezy.com/system/resources/previews/019/896/008/original/male-user-avatar-icon-in-flat-design-style-person-signs-illustration-png.png"
                            alt="">
                    </li>
                    <li data-bs-target="#quote-carousel" data-bs-slide-to="1">
                        <img class="img-responsive"
                            src="https://cdn.icon-icons.com/icons2/2643/PNG/512/avatar_female_woman_person_people_white_tone_icon_159360.png"
                            alt="">
                    </li>
                    <li data-bs-target="#quote-carousel" data-bs-slide-to="2">
                        <img class="img-responsive"
                            src="https://cdn1.iconfinder.com/data/icons/avatars-1-5/136/60-512.png" alt="">
                    </li>
                    <li data-bs-target="#quote-carousel" data-bs-slide-to="3">
                        <img class="img-responsive"
                            src="https://cdn.icon-icons.com/icons2/1736/PNG/512/4043251-avatar-female-girl-woman_113291.png"
                            alt="">
                    </li>
                    <li data-bs-target="#quote-carousel" data-bs-slide-to="4">
                        <img class="img-responsive"
                            src="https://cdn.icon-icons.com/icons2/1736/PNG/512/4043250-avatar-child-girl-kid_113270.png"
                            alt="">
                    </li>
                </ol>

                <!-- Carousel Slides / Quotes -->
                <div class="quote-carousel-inner text-center ">
                    <!-- Quote 1 -->
                    <div class="carousel-item active">
                        <blockquote>
                            <div class="row">
                                <div class="col-sm-8 offset-sm-2">
                                    <h2>Học viên A</h2>
                                    <small>Khoá học tiếng Nhật ngắn này thực sự tuyệt vời, giúp tôi nắm vững những kiến thức
                                        cơ bản trong thời gian ngắn và cải thiện khả năng giao tiếp tiếng Nhật một cách hiệu
                                        quả.
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
                    <!-- Quote 2 -->
                    <div class="carousel-item">
                        <blockquote>
                            <div class="row">
                                <div class="col-sm-8 offset-sm-2">
                                    <h2>Học viên B</h2>
                                    <small>Khoá học tiếng Nhật ngắn này đã giúp tôi nắm bắt nhanh các kỹ năng cơ bản và tự
                                        tin hơn trong việc sử dụng tiếng Nhật hàng ngày.
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
                                    <h2>Học viên C</h2>
                                    <small>Khoá học tiếng Nhật ngắn gọn nhưng đầy đủ, cung cấp nền tảng vững chắc và giúp
                                        tôi tiến bộ rõ rệt trong thời gian ngắn.
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
                                    <h2>Học viên D</h2>
                                    <small class="w3-xxlarge w3-serif">Trung tâm tiếng Nhật HIKARI có đội ngũ giáo viên tận
                                        tâm và giàu kinh nghiệm, học viên nắm vững ngữ pháp, từ vựng và phát âm. Các bài
                                        học được thiết kế sinh động, tạo điều kiện cho việc giao tiếp và thực hành thực
                                        tế.
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
                                    <h2>Học viên E</h2>
                                    <small>HIKARI mang đến một không gian học tập thoải mái và khuyến khích sự giao lưu giữa
                                        h2 học viên. Các hoạt động ngoại khóa, như câu lạc bộ tiếng Nhật, giúp nâng cao kỹ
                                        năng và tạo cơ hội kết bạn cho những người cùng sở thích.
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
                                src="https://staging.hikariacademy.edu.vn/public/assets/images/clients/1.jpg" />
                        </div>
                        <div class="col">
                            <img alt="Logo of Global CyberSoft with text 'A Hitachi Consulting Company'"
                                class="d-block mx-auto" height="100"
                                src="https://staging.hikariacademy.edu.vn/public/assets/images/clients/7.jpg" />
                        </div>
                        <div class="col">
                            <img alt="Logo of Vina Acecook" class="d-block mx-auto" height="100"
                                src="https://staging.hikariacademy.edu.vn/public/assets/images/clients/7.jpg" />
                        </div>
                        <div class="col">
                            <img alt="Logo of NEC with text 'Orchestrating a brighter world'" class="d-block mx-auto"
                                height="100"
                                src="https://staging.hikariacademy.edu.vn/public/assets/images/clients/8.jpg" />
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="row">
                        <div class="col">
                            <img alt="Logo of KatchUp.vn with text 'Thẻ học tiếng Nhật - Học nhanh - Nhớ lâu'"
                                class="d-block mx-auto" height="100"
                                src="https://staging.hikariacademy.edu.vn/public/assets/images/clients/1.jpg" />
                        </div>
                        <div class="col">
                            <img alt="Logo of Global CyberSoft with text 'A Hitachi Consulting Company'"
                                class="d-block mx-auto" height="100"
                                src="https://staging.hikariacademy.edu.vn/public/assets/images/clients/7.jpg" />
                        </div>
                        <div class="col">
                            <img alt="Logo of Vina Acecook" class="d-block mx-auto" height="100"
                                src="https://staging.hikariacademy.edu.vn/public/assets/images/clients/7.jpg" />
                        </div>
                        <div class="col">
                            <img alt="Logo of NEC with text 'Orchestrating a brighter world'" class="d-block mx-auto"
                                height="100"
                                src="https://staging.hikariacademy.edu.vn/public/assets/images/clients/8.jpg" />
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
    <div class="cover-image sptb bg-background-color text-white p-5 w-100 mb-5"
        data-image-src="{{ asset('images/banner/background-logan-4.jpg') }}"
        style="background: url('{{ asset('images/banner/background-logan-4.jpg') }}')  no-repeat;
    background-size: cover;">
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
                            <div class="fea-icon bg-success mb-3 text-white">
                                <i class="bi bi-megaphone-fill"></i>
                            </div>
                            <h3 class="fw-semibold">KHÓA HỌC</h3>
                            <p>Đa dạng theo mọi trình độ từ N5 đến N1</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 sptb-item">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="fea-icon bg-danger mb-3 text-white">
                                <i class="bi bi-heart-fill"></i>
                            </div>
                            <h3 class="fw-semibold">LỚP HỌC</h3>
                            <p>Tính năng lớp học dành riêng cho doanh nghiệp, trường học</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 sptb-item">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="fea-icon bg-warning mb-3 text-white">
                                <i class="bi bi-bookmark-fill"></i>
                            </div>
                            <h3 class="fw-semibold">KỸ NĂNG TOÀN DIỆN</h3>
                            <p>Từ vựng, ngữ pháp, đọc hiểu, nghe, thoại hội, luyện tập</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 sptb-item">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="fea-icon bg-secondary mb-3 text-white">
                                <i class="bi bi-mic-fill"></i>
                            </div>
                            <h3 class="fw-semibold">LUYỆN PHÁT ÂM</h3>
                            <p>Ứng dụng công nghệ hàng đầu kiểm tra phát âm theo giọng bản xứ</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 sptb-item">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="fea-icon bg-primary mb-3 text-white">
                                <i class="bi bi-spellcheck"></i>
                            </div>
                            <h3 class="fw-semibold">CHỮ HÁN</h3>
                            <p>Tính năng thông minh hỗ trợ nhớ nhanh Hán Tự</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 sptb-item">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="fea-icon bg-info mb-3 text-white">
                                <i class="bi bi-card-checklist"></i>
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
    <script type="module">
        $(document).ready(function() {
            // $('#loginModal').modal('show');
        });
    </script>
@endsection
