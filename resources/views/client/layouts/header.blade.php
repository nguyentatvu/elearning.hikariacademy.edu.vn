<nav class="navbar navbar-expand-lg navbar-light px-5">
    <div class="w-100 px-5 d-flex align-items-center justify-conten-center">
        <div class="me-auto">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('images/Logo-hikari.png') }}" alt="Logo" class="d-inline-block align-text-top">
            </a>
        </div>
        <div class="me-auto" id="navbarSupportedContent">
            <div class="row height d-flex justify-content-center align-items-center">
                <div class="form-search">
                    <i class="bi bi-search"></i>
                    <input type="text" spellcheck="false" class="form-input"
                        placeholder="Tìm kiếm khoá học, bài viết, video, ...">
                </div>
            </div>
        </div>
        @if (Auth::check())
            <div class="header-my-coin">
                <a href="{{ route('mypage.reward-point') }}">
                    {{ formatNumber(Auth::user()->reward_point + Auth::user()->recharge_point) }}
                </a>
                <img src="{{ asset('images/icons/coin.svg') }}" class="rounded-circle object-fit-cover">
            </div>

            <div class="btn-group mx-2">
                <div type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div>Khóa học của tôi</div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end w-450px p-3">
                    <li>
                        <b>Khoá học của tôi</b>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li class="mt-2">
                        <div class="d-flex">
                            <img src="{{ asset('images/logo-N1.png') }}" class="header-course-img" alt="" srcset="">
                            <div class="w-100">
                                <div>
                                    <b>Khoá học N1</b>
                                    <div class="mt-2">Học cách đây 2 phút trước</div>
                                    <div class="progress mt-2">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 35%">35%</div>
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
                            <img src="{{ asset('images/logo-N1.png') }}" class="header-course-img" alt="" srcset="">
                            <div class="w-100">
                                <div>
                                    <b>Khoá học N1</b>
                                    <div class="mt-2">Học cách đây 2 phút trước</div>
                                    <div class="progress mt-2">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">75%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <ul class="dropdown-menu dropdown-menu-end w-600px p-3">
                <li>
                    <b>Thông báo</b>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li class="mt-2">
                    <div class="d-flex">
                        <img src="{{ asset('images/logo-N1.png') }}" class="header-notification-img" alt=""
                            srcset="">
                        <div>
                            <div class="mb-2">
                                Chào [Tên học viên], chúng tôi nhận thấy bạn chưa hoàn thành bài học tuần này.
                                Đừng để tiến độ học của mình bị chậm lại, hãy cố gắng hoàn thành bài học trong thời gian
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
                        <img src="{{ asset('images/logo-N1.png') }}" class="header-notification-img" alt=""
                            srcset="">
                        <div>
                            <div class="mb-2">
                                Chúng tôi nhận thấy rằng tiến độ học tập của bạn đang chậm hơn so với lộ trình học đề
                                ra.
                                Để đạt được kết quả tốt nhất và hoàn thành khóa học đúng hạn, bạn cần nỗ lực hơn nữa
                                trong việc hoàn thành các bài học và bài tập được giao.
                            </div>
                            <b>26/8/2021 - 14:23</b>
                        </div>
                    </div>
                </li>
            </ul>

            <div class="btn-group mx-2">
                <div type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <a href="#"><i class="bi bi-bell"></i></a>
                </div>
                <ul class="dropdown-menu dropdown-menu-end w-600px p-3">
                    <li>
                        <b>Thông báo</b>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li class="mt-2">
                        <div class="d-flex">
                            <img src="{{ asset('images/logo-N1.png') }}" class="header-notification-img" alt="" srcset="">
                            <div>
                                <div class="mb-2">
                                    Chào [Tên học viên], chúng tôi nhận thấy bạn chưa hoàn thành bài học tuần này.
                                    Đừng để tiến độ học của mình bị chậm lại, hãy cố gắng hoàn thành bài học trong thời gian sớm nhất nhé!
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
                            <img src="{{ asset('images/logo-N1.png') }}" class="header-notification-img" alt="" srcset="">
                            <div>
                                <div class="mb-2">
                                    Chúng tôi nhận thấy rằng tiến độ học tập của bạn đang chậm hơn so với lộ trình học đề ra.
                                    Để đạt được kết quả tốt nhất và hoàn thành khóa học đúng hạn, bạn cần nỗ lực hơn nữa
                                    trong việc hoàn thành các bài học và bài tập được giao.
                                </div>
                                <b>26/8/2021 - 14:23</b>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="btn-group mx-2">
                <div type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    @if (Auth::user()->image)
                        <img src="{{ asset('uploads/users/thumbnail/' . Auth::user()->image) }}" class="rounded-circle object-fit-cover" width="60px" height="60px" alt="Avatar" />
                    @else
                        <img src="{{ asset('images/no-avatar.png') }}" class="rounded-circle object-fit-cover" height="40px" width="40px" alt="Avatar" />
                    @endif
                </div>
                <ul class="dropdown-menu dropdown-menu-end w-200px p-2">
                    <li>
                        <div class="d-flex justify-content-center align-items-center">
                            @if (Auth::user()->image)
                                <img src="{{ asset('uploads/users/thumbnail/' . Auth::user()->image) }}" class="rounded-circle object-fit-cover" width="60px" height="60px" alt="Avatar" />
                            @else
                                <img src="{{ asset('images/no-avatar.png') }}" class="rounded-circle object-fit-cover" height="60px" width="60px" alt="Avatar" />
                            @endif
                            <div>
                                <div>Học viên A</div>
                                <div>@hocvienA</div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="{{ route('mypage.personal') }}">Trang cá nhân</a></li>
                    <li><a class="dropdown-item" href="{{ route('mypage.leaderboard') }}">Bảng xếp hạng</a></li>
                    <li><a class="dropdown-item" href="{{ route('mypage.reward-point') }}">Điễm tích luỹ</a></li>
                    <li><a class="dropdown-item" href="{{ route('mypage.courses') }}">Khoá học</a></li>
                    <li><a class="dropdown-item" href="{{ route('mypage.exams') }}">Khoá luyện thi</a></li>
                    <li><a class="dropdown-item" href="#">Phòng thi</a></li>
                    <li><a class="dropdown-item" href="{{ route('mypage.my-result-exam') }}">Kết quả thi</a></li>
                    <li><a class="dropdown-item" data-toggle="modal" data-target="#loginModal">Đổi mật khẩu</a></li>
                    <li><a class="dropdown-item" href="{{ route('mypage.recharge-point') }}">Nạp</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="{{ route('logout') }}">Đăng xuất</a></li>
                </ul>
            </div>
        @else
            <div class="d-flex gap-3">
                <button type="button" id="btn_register" class="btn btn-transparent p-0 m-0 text-secondary" onclick="showAuthModal(false)">Đăng ký</button>
                <button type="button" id="btn_login" class="btn btn-primary text-white" onclick="showAuthModal(true)">Đăng nhập</button>
            </div>
        @endif
    </div>
</nav>
