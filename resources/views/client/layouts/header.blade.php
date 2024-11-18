<nav class="navbar navbar-expand-lg navbar-light px-lg-5 px-sm-4 px-3">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand me-auto" href="/">
            <img src="{{ asset('images/Logo-hikari.png') }}" alt="Logo" class="d-inline-block align-text-top logo-img">
        </a>

        @if (Auth::check())
            <div class="header-my-coin ms-3 d-lg-none d-flex me-2 my-icon-info"
                onclick="window.location.href = '{{ route('mypage.reward-point') }}'">
                <span class="owned-point">
                    {{ formatNumber(Auth::user()->reward_point + Auth::user()->recharge_point) }}
                </span>
                <img src="{{ asset('images/icons/coin.svg') }}" alt="Coin Icon" class="ms-2" width="20">
                <div class="hicoin-animation">
                    <span class="me-1 fs-5">+<span class="increased-point"></span></span>
                    <img width="20" alt="hi-coin" src="{{ asset('images/icons/coin.svg') }}">
                </div>
            </div>
        @endif

        <!-- Navbar Toggler for Mobile -->
        <div class="d-lg-none">
            <!-- User Dropdown -->
            @if (Auth::check())
                <button class="navbar-toggler avatar-icon-mobile" type="button" data-bs-toggle="dropdown"
                    id="userDropdownMobile" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end w-200px p-2 mt-3 me-3" aria-labelledby="userDropdownMobile">
                    <li class="d-flex align-items-center">
                        @if (Auth::user()->image)
                            <img src="{{ asset('uploads/users/thumbnail/' . Auth::user()->image) }}"
                                class="rounded-circle object-fit-cover me-2" width="40" height="40"
                                alt="Avatar">
                        @else
                            <img src="{{ asset('images/no-avatar.png') }}" class="rounded-circle object-fit-cover me-2"
                                width="40" height="40" alt="Avatar">
                        @endif
                        <div>
                            <div>{{ Auth::user()->name }}</div>
                            <div><span>@</span>{{ Auth::user()->username ?? '' }}</div>
                            <!-- Streak daily -->
                            <div class="header-my-coin ms-3 d-lg-flex owned-login-streak" onclick="window.location.href = '{{ route('mypage.reward-point') }}'"
                                id="owned_login_streak_mobile">
                                <a>
                                    {{ isset(Auth::user()->login_streak) && Auth::user()->login_streak ? Auth::user()->login_streak : 0 }}
                                </a>
                                <img src="{{ asset('images/icons/fire.svg') }}" alt="Coin Icon" class="ms-2"
                                    width="20">
                                <div class="hicoin-animation">
                                    <span class="me-1 fs-5">+<span class="increased-point"></span></span>
                                    <img width="20" alt="hi-coin" src="{{ asset('images/icons/coin.svg') }}">
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="{{ route('mypage.personal') }}">Trang cá nhân</a>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('mypage.reward-point') }}">Điểm tích
                            luỹ</a></li>
                    <li><a class="dropdown-item" href="{{ route('mypage.leaderboard') }}">Bảng xếp
                            hạng</a></li>
                    <li><a class="dropdown-item" href="{{ route('mypage.courses') }}">Khoá học</a></li>
                    <li><a class="dropdown-item" href="{{ route('mypage.exams') }}">Khoá luyện thi</a>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('mypage.my-comments') }}">
                            Câu hỏi của bạn</a>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('mypage.mock-exam.list') }}">
                            Phòng thi của bạn</a>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('mypage.my-exam-result') }}">Kết quả
                            thi</a></li>
                    <li><a class="dropdown-item" href="{{ route('mypage.payment-management') }}">
                            Quản lý thanh toán</a></li>
                    <li><a class="dropdown-item" href="{{ route('mypage.recharge-point') }}">Nạp điểm</a></li>
                    <li><a class="dropdown-item" href="{{ route('mypage.personal') . '?action=change-password' }}">Đổi mật khẩu</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="{{ route('logout') }}">Đăng xuất</a></li>
                </ul>
            @else
                <!-- Register and Login Buttons -->
                <div class="d-flex gap-3">
                    <button type="button" id="btn_register"
                        class="btn btn-link text-decoration-none text-secondary d-none d-sm-block"
                        onclick="showAuthModal(false)">Đăng ký</button>
                    <button type="button" id="btn_login" class="btn btn-primary text-white"
                        onclick="showAuthModal(true)">Đăng nhập</button>
                </div>
            @endif
        </div>

        <!-- Main Navbar Content -->
        <div class="collapse navbar-collapse navbar-support-mobile" id="navbarSupportedContentMobile">
            <div class="d-flex w-100 justify-content-end align-items-center">
                <!-- Right Side: Coins, Courses, Notifications, and User Profile -->
                <div class="d-flex align-items-center">
                    @if (Auth::check())
                        <!-- Coin Balance -->
                        <div class="header-my-coin ms-3 d-lg-flex my-icon-info" onclick="window.location.href = '{{ route('mypage.reward-point') }}'">
                            <a href="{{ route('mypage.reward-point') }}" class="owned-point">
                                {{ formatNumber(Auth::user()->reward_point + Auth::user()->recharge_point) }}
                            </a>
                            <img src="{{ asset('images/icons/coin.svg') }}" alt="Coin Icon" class="ms-2"
                                width="20">
                            <div class="hicoin-animation">
                                <span class="me-1 fs-5">+<span class="increased-point"></span></span>
                                <img width="20" alt="hi-coin" src="{{ asset('images/icons/coin.svg') }}">
                            </div>
                        </div>
                        <!-- Streak daily -->
                        <div class="header-my-coin ms-3 d-lg-flex owned-login-streak" id="owned_login_streak_mobile" onclick="window.location.href = '{{ route('mypage.reward-point') }}'">
                            <a>
                                {{ isset(Auth::user()->login_streak) && Auth::user()->login_streak ? Auth::user()->login_streak : 0 }}
                            </a>
                            <img src="{{ asset('images/icons/fire.svg') }}" alt="Coin Icon" class="ms-2"
                                width="20">
                            <div class="hicoin-animation">
                                <span class="me-1 fs-5">+<span class="increased-point"></span></span>
                                <img width="20" alt="hi-coin" src="{{ asset('images/icons/coin.svg') }}">
                            </div>
                        </div>

                        <!-- My Courses Dropdown -->
                        <div class="btn-group mx-2">
                            <div type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                onclick="showMyCoursesDropdown()">
                                <h5 class="mb-0 fw-semibold gradient-title-sm my-course-info" style="color: #166AC9">
                                    Khóa học của tôi
                                </h5>
                            </div>
                            <ul
                                class="dropdown-menu dropdown-center dropdown-menu-end p-3 dropdown-my-course mt-3 no-content">
                                <div class="d-flex align-items-center justify-content-center p-3">
                                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <span class="text-muted">Đang tải khóa học...</span>
                                </div>
                            </ul>
                        </div>

                        <!-- Notifications Dropdown -->
                        <div class="btn-group mx-2 d-none">
                            <div type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <a href="#"><i class="bi bi-bell"></i></a>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end w-600px p-3 dropdown-my-notification">
                                <li>
                                    <b>Thông báo</b>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li class="mt-2">
                                    <div class="d-flex">
                                        <img src="{{ asset('images/logo-N1.png') }}" class="header-notification-img"
                                            alt="" srcset="">
                                        <div>
                                            <div class="mb-2">
                                                Chào [Tên học viên], chúng tôi nhận thấy bạn chưa hoàn thành bài học
                                                tuần
                                                này.
                                                Đừng để tiến độ học của mình bị chậm lại, hãy cố gắng hoàn thành bài học
                                                trong thời gian
                                                sớm nhất nhé!
                                            </div>
                                            <b>26/8/2021 - 14:23</b>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li class="mt-2">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('images/logo-N1.png') }}" class="header-notification-img"
                                            alt="" srcset="">
                                        <div>
                                            <div class="mb-2">
                                                Chúng tôi nhận thấy rằng tiến độ học tập của bạn đang chậm hơn so với lộ
                                                trình học đề
                                                ra.
                                                Để đạt được kết quả tốt nhất và hoàn thành khóa học đúng hạn, bạn cần nỗ
                                                lực hơn nữa
                                                trong việc hoàn thành các bài học và bài tập được giao.
                                            </div>
                                            <b>26/8/2021 - 14:23</b>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- User Profile Dropdown -->
                        <div class="dropdown mx-2 user-avatar" role="button">
                            <div id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                @if (Auth::user()->image)
                                    <img src="{{ asset('uploads/users/thumbnail/' . Auth::user()->image) }}"
                                        class="rounded-circle object-fit-cover avatar" width="40" height="40"
                                        alt="Avatar">
                                @else
                                    <img src="{{ asset('images/no-avatar.png') }}"
                                        class="rounded-circle object-fit-cover avatar" width="40" height="40"
                                        alt="Avatar">
                                @endif
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end w-200px p-2 mt-3"
                                aria-labelledby="userDropdown">
                                <li class="d-flex align-items-center justify-content-between p-2">
                                    @if (Auth::user()->image)
                                        <img src="{{ asset('uploads/users/thumbnail/' . Auth::user()->image) }}"
                                            class="rounded-circle object-fit-cover me-2" width="40"
                                            height="40" alt="Avatar">
                                    @else
                                        <img src="{{ asset('images/no-avatar.png') }}"
                                            class="rounded-circle object-fit-cover me-2" width="40"
                                            height="40" alt="Avatar">
                                    @endif
                                    <div>
                                        <div class="text-end mb-1 fw-bold" style="line-height: 1.1;">
                                            {{ Auth::user()->name }}</div>
                                        <div class="float-end"><span>@</span>{{ Auth::user()->username ?? '' }}</div>
                                    </div>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="{{ route('mypage.personal') }}">Trang cá nhân</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('mypage.reward-point') }}">Điểm tích
                                        luỹ</a></li>
                                <li><a class="dropdown-item" href="{{ route('mypage.leaderboard') }}">Bảng xếp
                                        hạng</a></li>
                                <li><a class="dropdown-item" href="{{ route('mypage.courses') }}">Khoá học</a></li>
                                <li><a class="dropdown-item" href="{{ route('mypage.exams') }}">Khoá luyện thi</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('mypage.my-comments') }}">
                                        Câu hỏi của bạn</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('mypage.mock-exam.list') }}">
                                        Phòng thi của bạn</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('mypage.my-exam-result') }}">Kết quả
                                        thi</a></li>
                                <li><a class="dropdown-item" href="{{ route('mypage.payment-management') }}">
                                        Quản lý thanh toán</a></li>
                                <li><a class="dropdown-item" href="{{ route('mypage.recharge-point') }}">Nạp điểm</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('mypage.personal') . '?action=change-password' }}">Đổi mật khẩu</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}">Đăng xuất</a></li>
                            </ul>
                        </div>
                    @else
                        <!-- Register and Login Buttons -->
                        <div class="d-flex gap-3">
                            <button type="button" id="btn_register"
                                class="btn btn-link text-decoration-none text-secondary"
                                onclick="showAuthModal(false)">Đăng ký</button>
                            <button type="button" id="btn_login" class="btn btn-primary text-white"
                                onclick="showAuthModal(true)">Đăng nhập</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</nav>
