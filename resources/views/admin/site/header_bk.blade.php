<!-- partial:partials/_horizontal-navbar.html -->
<div class="horizontal-menu">
    <nav class="navbar top-navbar col-lg-12 col-12 p-0">
        <div class="container">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo" href="/home"><img src="/public/uploads/settings/logo-elearning.png" style="width: 100px;" alt="logo"></a>
                <a class="navbar-brand brand-logo-mini" href="/home"><img src="/public/uploads/settings/logo-elearning.png" style="width: 100px;" alt="logo"></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center">
                <ul class="navbar-nav navbar-nav-right">
                    @if(Auth::check())
                    <li class="nav-item dropdown mr-4">
                        <span style="padding: 8px; font-size: 16px; font-weight: 700; color: #FFEB3B;">{{ number_format(Auth::user()->point, 0, 0, '.')}} Hi Koi</span>
                        <img src="/Themes/themeone/assets/images/icon-bank.png" style="width: 30px;">
                    </li>
                    {{-- <li class="nav-item dropdown mr-4">
                        <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center" id="notificationDropdown" href="#" data-toggle="dropdown">
                            <i class="mdi mdi-bell mx-0">
                                <span class="count-reply">0</span>
                            </i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                            <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                            <a href="https://test.hikariacademy.edu.vn/learning-management/comment" class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-success">
                                        <i class="mdi mdi-information mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">
                                      <span class="count-reply">0</span>
                                  </h6>
                                  <p class="font-weight-light small-text mb-0 text-muted">
                                    Series comment   
                                </p>
                            </div>
                        </a>
                        </div>
                    </li> --}}
                <li class="nav-item nav-profile dropdown mr-0 mr-sm-2">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                        {{-- <img src="{{ getProfilePath(Auth::user()->image, 'thumb') }}" alt="profile" style="width: 30px; height: 30px"> --}}
                        <span class="nav-profile-name">{{Auth::user()->name}}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                        <a class="dropdown-item" href="{{URL_USERS_EDIT.Auth::user()->slug}}">
                            <i class="mdi mdi-settings text-primary"></i> My Hikari
                        </a>
                        <a class="dropdown-item" href="{{PREFIX.'lms/exam-categories/list'}}">
                                <i class="mdi mdi-settings text-primary"></i>  Khóa học của tôi
                            </a>
                        <a class="dropdown-item" href="{{PREFIX.'exams/student-exam-series/list'}}">
                            <i class="mdi mdi-settings text-primary"></i> Phòng thi của tôi
                        </a>
                        <a class="dropdown-item" href="{{URL_USERS_CHANGE_PASSWORD.Auth::user()->slug}}">
                            <i class="mdi mdi-security text-primary"></i> Đổi mật khẩu
                        </a>
                        <a class="dropdown-item" href="{{URL_USERS_LOGOUT}}">
                            <i class="mdi mdi-logout text-primary"></i> Đăng xuất
                        </a>
                    </div>
                </li>
                @else
                <li class="nav-item dropdown mr-4">
                    <a class="nav-link" href="{{URL_USERS_LOGIN}}">
                        <span class="nav-profile-name">Đăng nhập</span>
                    </a>
                </li>
                <li class="nav-item dropdown mr-4">
                    <a class="nav-link" href="{{URL_USERS_REGISTER}}">
                        <span class="nav-profile-name">Đăng ký</span>
                    </a>
                </li>
                @endif
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="horizontal-menu-toggle">
                <span class="mdi mdi-menu"></span>
            </button>
        </div>
    </div>
</nav>
@if(Auth::check())
<nav class="bottom-navbar">
    <div class="container">
        <ul class="nav page-navigation">
            <li class="nav-item">
                <a href="/home" class="nav-link">
                    <i class="mdi mdi-home menu-icon"></i>
                    <span class="menu-title">Trang Chủ</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/site/courses" class="nav-link">
                    <i class="mdi mdi-playlist-check menu-icon"></i>
                    <span class="menu-title">Khóa Luyện Thi</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="submenu">
                    <ul class="submenu-item">
                        <li class="nav-item"><a class="nav-link" href="{{URL_STUDENT_EXAM_SERIES_LIST}}">Khóa luyện thi N5</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{URL_STUDENT_EXAM_SERIES_LIST}}">Khóa luyện thi N4</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{URL_STUDENT_EXAM_SERIES_LIST}}">Khóa luyện thi N3</a></li>
                        {{-- <li class="nav-item"><a class="nav-link" href="{{URL_STUDENT_EXAM_SERIES_LIST}}">Khóa luyện thi N2</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{URL_STUDENT_EXAM_SERIES_LIST}}">Khóa luyện thi N1</a></li> --}}
                        
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{URL_STUDENT_EXAM_SERIES_LIST}}">
                    <i class="mdi mdi-airplay menu-icon"></i>
                    <span class="menu-title">Phòng Thi</span>
                    <i class="menu-arrow"></i>
                </a>
                {{-- <div class="submenu">
                    <ul class="submenu-item">
                        <li class="nav-item"><a class="nav-link" href="{{URL_STUDENT_EXAM_SERIES_LIST}}">Phòng thi</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{URL_STUDENT_EXAM_ATTEMPTS_FINISH.Auth::user()->slug }}">Lịch sử thi</a></li>
                    </ul>
                </div> --}}
            </li>
            <li class="nav-item">
                <a href="/site/shop" class="nav-link">
                    <i class="mdi mdi-codepen menu-icon"></i>
                    <span class="menu-title">Cửa hàng</span>
                    {{-- <i class="menu-arrow"></i> --}}</a>
                    {{-- <div class="submenu">
                        <ul class="submenu-item">
                            <li class="nav-item"><a class="nav-link" href="/site/shop">Cửa hàng</a></li>
                            <li class="nav-item"><a class="nav-link" href="/payments/history">Lịch sử mua</a></li>
                        </ul>
                    </div> --}}
                </li>
                <li class="nav-item">
                    <a href="/site/contact" class="nav-link">
                        <i class="mdi mdi-file-document-box-outline menu-icon"></i>
                        <span class="menu-title">Liên Hệ</span></a>
                    </li>
                </ul>
            </div>
        </nav>
        @else
        <nav class="bottom-navbar">
            <div class="container">
                <ul class="nav page-navigation">
                    <li class="nav-item">
                        <a href="/home" class="nav-link">
                            <i class="mdi mdi-home menu-icon"></i>
                            <span class="menu-title">Trang chủ</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/site/courses" class="nav-link">
                            <i class="mdi mdi-playlist-check menu-icon"></i>
                            <span class="menu-title">Khóa luyện thi online</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{URL_STUDENT_EXAM_SERIES_LIST}}">
                            <i class="mdi mdi-airplay menu-icon"></i>
                            <span class="menu-title">Phòng Thi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/site/shop" class="nav-link">
                            <i class="mdi mdi-codepen menu-icon"></i>
                            <span class="menu-title">Cửa hàng</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/site/contact" class="nav-link">
                            <i class="mdi mdi-file-document-box-outline menu-icon"></i>
                            <span class="menu-title">Liên hệ</span></a>
                        </li>
                    </ul>
                </div>
            </nav>
            @endif
        </div>
        {{-- <script>
            setInterval(function(){
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    type : 'post',
                    url : "https://test.hikariacademy.edu.vn/log/count_lms_notification_comment",     
                    data : {},
                    success:function(data){
                        var obj = JSON.parse(data);
                        $(".count-reply").html(obj.count);
                    }
                });
            }, 5000);
        </script> --}}     