<nav class="navbar navbar-expand-lg navbar-light px-5">
    <div class="container-fluid px-5">
        <!-- Logo -->
        <a class="navbar-brand me-auto" href="/">
            <img src="{{ asset('images/Logo-hikari.png') }}" alt="Logo" class="d-inline-block align-text-top">
        </a>

        <!-- Navbar Toggler for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContentMobile" aria-controls="navbarSupportedContentMobile"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Main Navbar Content -->
        <div class="collapse navbar-collapse navbar-support-mobile" id="navbarSupportedContentMobile">
            <div class="d-flex w-100 justify-content-between align-items-center">
                <!-- Search Bar (Hidden on Mobile) -->
                <div class="me-auto" id="navbarSupportedContent">
                    <div class="row height d-flex justify-content-center align-items-center">
                        <div class="form-search">
                            <i class="bi bi-search"></i>
                            <input type="text" spellcheck="false" class="form-input"
                                placeholder="Tìm kiếm khoá học, bài viết, video, ...">
                        </div>
                    </div>
                </div>

                <!-- Right Side: Coins, Courses, Notifications, and User Profile -->
                <div class="d-flex align-items-center">
                    @if (Auth::check())
                        <!-- Coin Balance -->
                        <div class="header-my-coin ms-3 d-none d-lg-flex">
                            <a href="{{ route('mypage.reward-point') }}" class="owned-point">
                                {{ formatNumber(Auth::user()->reward_point + Auth::user()->recharge_point) }}
                            </a>
                            <img src="{{ asset('images/icons/coin.svg') }}" alt="Coin Icon" class="ms-2" width="20">
                            <div class="hicoin-animation">
                                <span class="me-1 fs-5">+<span class="increased-point"></span></span>
                                <img width="20" alt="hi-coin" src="{{ asset('images/icons/coin.svg') }}">
                            </div>
                        </div>

                        <!-- My Courses Dropdown -->
                        <div class="btn-group mx-2">
                            <div type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div>Khóa học của tôi</div>
                            </div>
                            <ul class="dropdown-menu dropdown-center dropdown-menu-end p-3 dropdown-my-course">
                                <li>
                                    <b>Khoá học của tôi</b>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li class="mt-2">
                                    <div class="d-flex">
                                        <img src="{{ asset('images/logo-N1.png') }}" class="header-course-img"
                                            alt="" srcset="">
                                        <div class="w-100">
                                            <div>
                                                <b>Khoá học N1</b>
                                                <div class="mt-2">Học cách đây 2 phút trước</div>
                                                <div class="progress mt-2">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                        role="progressbar" aria-valuenow="75" aria-valuemin="0"
                                                        aria-valuemax="100" style="width: 35%">35%</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li class="mt-2">
                                    <div class="d-flex">
                                        <img src="{{ asset('images/logo-N1.png') }}" class="header-course-img"
                                            alt="" srcset="">
                                        <div class="w-100">
                                            <div>
                                                <b>Khoá học N1</b>
                                                <div class="mt-2">Học cách đây 2 phút trước</div>
                                                <div class="progress mt-2">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                        role="progressbar" aria-valuenow="75" aria-valuemin="0"
                                                        aria-valuemax="100" style="width: 75%">75%</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- Notifications Dropdown -->
                        <div class="btn-group mx-2">
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
                                                Chào [Tên học viên], chúng tôi nhận thấy bạn chưa hoàn thành bài học tuần
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
                        <div class="dropdown mx-2">
                            <div id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                @if (Auth::user()->image)
                                    <img src="{{ asset('uploads/users/thumbnail/' . Auth::user()->image) }}"
                                        class="rounded-circle object-fit-cover" width="40" height="40"
                                        alt="Avatar">
                                @else
                                    <img src="{{ asset('images/no-avatar.png') }}"
                                        class="rounded-circle object-fit-cover" width="40" height="40"
                                        alt="Avatar">
                                @endif
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end w-200px p-2" aria-labelledby="userDropdown">
                                <li class="d-flex align-items-center">
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
                                        <div>{{ Auth::user()->name }}</div>
                                        <div><span>@</span>{{ Auth::user()->username ?? '' }}</div>
                                    </div>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="{{ route('mypage.personal') }}">Trang cá nhân</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('mypage.leaderboard') }}">Bảng xếp
                                        hạng</a></li>
                                <li><a class="dropdown-item" href="{{ route('mypage.reward-point') }}">Điểm tích
                                        luỹ</a></li>
                                <li><a class="dropdown-item" href="{{ route('mypage.courses') }}">Khoá học</a></li>
                                <li><a class="dropdown-item" href="{{ route('mypage.exams') }}">Khoá luyện thi</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('mypage.my-result-exam') }}">Kết quả
                                        thi</a></li>
                                <li><a class="dropdown-item" data-bs-toggle="modal"
                                        data-bs-target="#changePasswordModal">Đổi mật khẩu</a></li>
                                <li><a class="dropdown-item" href="{{ route('mypage.recharge-point') }}">Nạp</a></li>
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