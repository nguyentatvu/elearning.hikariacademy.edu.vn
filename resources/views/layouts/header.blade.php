<nav class="navbar navbar-expand-lg navbar-light">
    <div class="w-100 px-5 d-flex align-items-center justify-conten-center">
        <div class="me-auto">
            <a class="navbar-brand" href="#">
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
        <div class="header-my-coin mx-2">
            <a href="#">1.000</a>
            <img src="{{ asset('images/coin.jpg') }}" class="rounded-circle">
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
                        <img src="{{ asset('images/logo-N1.png') }}" class="header-course-img" alt=""
                            srcset="">
                        <div class="w-100">
                            <div>
                                <b>Khoá học N1</b>
                                <div class="mt-2">Học cách đây 2 phút trước</div>
                                <div class="progress mt-2">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                        role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                                        style="width: 35%">35%</div>
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
                        <img src="{{ asset('images/logo-N1.png') }}" class="header-course-img" alt=""
                            srcset="">
                        <div class="w-100">
                            <div>
                                <b>Khoá học N1</b>
                                <div class="mt-2">Học cách đây 2 phút trước</div>
                                <div class="progress mt-2">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                        role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                                        style="width: 75%">75%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

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
                        <img src="{{ asset('images/logo-N1.png') }}" class="header-notification-img" alt=""
                            srcset="">
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
        </div>

        <div class="btn-group mx-2">
            <div type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp" class="rounded-circle" height="30px"
                    alt="Avatar" />
            </div>
            <ul class="dropdown-menu dropdown-menu-end w-200px p-2">
                <li>
                    <div class="d-flex justify-content-center align-items-center">
                        <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp" class="rounded-circle mx-2"
                            height="60px" alt="Avatar" />
                        <div>
                            <div>Học viên A</div>
                            <div>@hocvienA</div>
                        </div>
                    </div>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="#">Trang cá nhân</a></li>
                <li><a class="dropdown-item" href="#">Điểm tích luỹ</a></li>
                <li><a class="dropdown-item" href="#">Khoá học</a></li>
                <li><a class="dropdown-item" href="#">Khoá luyện thi</a></li>
                <li><a class="dropdown-item" href="#">Phòng thi</a></li>
                <li><a class="dropdown-item" href="#">Kết quả thi</a></li>
                <li><a class="dropdown-item" href="#">Đổi mật khẩu</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="#">Đăng xuất</a></li>
            </ul>
        </div>
        {{-- <div class="header-personal mx-2">
            <button class="btn btn-link" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp" class="rounded-circle" height="30px" alt="Avatar" />
            </button>
            <ul class="dropdown-menu dropdown-menu-lg-end">
                <li><a class="dropdown-item" href="#">Trang cá nhân</a></li>
                <li><a class="dropdown-item" href="#">Điểm tích luỹ</a></li>
                <li><a class="dropdown-item" href="#">Khoá học</a></li>
                <li><a class="dropdown-item" href="#">Khoá luyện thi</a></li>
                <li><a class="dropdown-item" href="#">Phòng thi</a></li>
                <li><a class="dropdown-item" href="#">Kết quả thi</a></li>
                <li><a class="dropdown-item" href="#">Đổi mật khẩu</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Đăng xuất</a></li>
              </ul>
        </div> --}}
    </div>
</nav>
