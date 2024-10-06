<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="google" content="notranslate"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="{{getSetting('meta_description', 'seo_settings')}}">
	<meta name="keywords" content="{{getSetting('meta_keywords', 'seo_settings')}}">
	<meta name="csrf_token" content="{{ csrf_token() }}">
	<link rel="icon" href="/public/uploads/settings/favicon.png" type="image/x-icon" />
	<title>@yield('title') {{ isset($title) ? $title : getSetting('site_title','site_settings') }}</title>
	<!-- Bootstrap Core CSS -->
	@yield('header_scripts')
	<link href="{{admin_asset('css/bootstrap.min.css')}}" rel="stylesheet">
	<link href="{{admin_asset('css/sweetalert.css')}}" rel="stylesheet">
	<link href="{{admin_asset('css/metisMenu.min.css')}}" rel="stylesheet">
	<link href="{{admin_asset('css/custom-fonts.css')}}" rel="stylesheet">
	<link href="{{admin_asset('css/materialdesignicons.css')}}" rel="stylesheet">
	<link href="{{admin_asset('font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
	<link href="{{admin_asset('css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
	<!-- Morris Charts CSS -->
	<link href="{{admin_asset('css/plugins/morris.css')}}" rel="stylesheet">
	<link href="{{admin_asset('css/sb-admin.css')}}" rel="stylesheet">
    <!-- Custom Table CSS -->
    <link href="{{admin_asset('css/custom-table.css')}}" rel="stylesheet">
    <!-- Custom Form CSS -->
    <link href="{{admin_asset('css/custom-form.css')}}" rel="stylesheet">
	{{-- OwnerLayout common Style CSS --}}
	<link href="{{admin_asset('css/ownerlayout-common.css')}}" rel="stylesheet">
	<?php
	$theme_color  = getThemeColor();
	?>
	@if($theme_color == 'blueheader')
        <link href="{{admin_asset('css/theme-colors/header-blue.css')}}" rel="stylesheet">
	@elseif($theme_color == 'bluenavbar')
        <link href="{{admin_asset('css/theme-colors/blue-sidebar.css')}}" rel="stylesheet">
	@elseif($theme_color == 'darkheader')
        <link href="{{admin_asset('css/theme-colors/dark-header.css')}}" rel="stylesheet">
	@elseif($theme_color == 'darktheme')
        <link href="{{admin_asset('css/theme-colors/dark-theme.css')}}" rel="stylesheet">
	@elseif($theme_color == 'whitecolor')
        <link href="{{admin_asset('css/theme-colors/white-theme.css')}}" rel="stylesheet">
	@endif
</head>
<body ng-app="academia">
	@yield('custom_div')
	<?php
	$class = '';
	if(!isset($right_bar))
        $class = 'no-right-sidebar';
	?>
	<div id="wrapper" class="{{$class}}">
		<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
				<a class="navbar-brand" href="{{ URL_HOME }}" target="_blank"><img src="/public/uploads/settings/logo-elearning.png" style="width: 150px;"></a>
			</div>
			<?php $newUsers = (new App\User())->getLatestUsers(); ?>
			<ul class="nav navbar-right top-nav">
				<li class="dropdown profile-menu">
					<div class="dropdown-toggle top-profile-menu" data-toggle="dropdown">
						@if(Auth::check())
						<div class="username">
							<h2>{{Auth::user()->name}}</h2>
						</div>
						@endif
						<div class="profile-img"> <img src="{{ getProfilePath(Auth::user()->image, 'thumb') }}" alt=""> </div>
						<div class="mdi mdi-menu-down"></div>
					</div>
					<ul class="dropdown-menu">
						<li>
							<a href="{{URL_USERS_EDIT}}{{Auth::user()->slug}}">
								<sapn>My Hikari</sapn>
							</a>
						</li>
						<li>
							<a href="{{URL_USERS_CHANGE_PASSWORD}}{{Auth::user()->slug}}">
								<sapn>Đổi mật khẩu</sapn>
							</a>
						</li>
						<li>
							<a href="{{URL_USERS_LOGOUT}}">
								<sapn>Đăng xuất</sapn>
							</a>
						</li>
					</ul>
				</li>
			</ul>
		</nav>
  		<aside class="left-sidebar">
  			<div class="collapse navbar-collapse navbar-ex1-collapse">
  				<ul class="nav navbar-nav side-nav">
  					<li {{ isActive($active_class, 'dashboard') }}>
			  			<a href="{{PREFIX}}dashboard">
			  				<i class="fa fa-fw fa-window-maximize"></i> {{ getPhrase('dashboard') }}
			  			</a>
			  		</li>
					<li {{ isActive($active_class, 'exams') }} >
						<a data-toggle="collapse" data-target="#exams"><i class="fa fa-fw fa-desktop" ></i>Đề thi </a>
						<ul id="exams" class="collapse sidemenu-dropdown">
							<li><a href="{{URL_EXAM_SERIES}}"> <i class="fa fa-fw fa-certificate"></i> Bộ đề thi</a></li>
							<li><a href="{{URL_QUIZ_QUESTIONBANK}}"> <i class="fa fa-fw fa-fw fa-question"></i>Ngân hàng câu hỏi</a></li>
							<li><a href="{{URL_QUIZZES}}"> <i class="fa fa-fw fa-list-ol"></i> Đề thi</a></li>
							<li><a href="{{URL_MASTERSETTINGS_SUBJECTS}}"> <i class="icon-books"></i> Mondai</a></li>
							<li><a href="{{URL_MASTERSETTINGS_TOPICS}}"> <i class="fa fa-fw fa-database"></i> Câu hỏi mondai</a></li>
							<li><a href="{{URL_BOOKS}}"> <i class="fa fa-fw fa-book"></i> Sách</a></li>
							<li><a href="{{URL_QUIZ_CATEGORIES}}"> <i class="fa fa-fw fa-fw fa-random"></i>Danh mục đề</a></li>
							<li><a href="{{URL_INSTRUCTIONS}}"> <i class="fa fa-fw fa-hand-o-right"></i>Hướng dẫn</a></li>
						</ul>
					</li>
                    <li {{ isActive($active_class, 'articles') }} >
                        <a data-toggle="collapse" data-target="#article"><i class="fa fa-fw fa-newspaper-o" ></i> Bài viết</a>
                        <ul id="lms" class="collapse sidemenu-dropdown">
                            <li><a href="{{ route('articles.articles.index') }}"><i class="fa fa-fw fa-list"></i>Danh sách sách bài viết</a></li>
                            <li><a href="{{ route('articles.categories.index') }}"> <i class="fa fa-fw fa-sliders"></i>Quản lý chuyên mục</a></li>
                        </ul>
                    </li>
                    <li {{ isActive($active_class, 'dotthi') }} >
                        <a data-toggle="collapse" data-target="#dotthi"><i class="fa fa-fw fa-book" ></i> Đợt thi thử</a>
                        <ul id="lms" class="collapse sidemenu-dropdown">
                            <li><a href="{{URL_EXAM_SERIES_FREE}}"> <i class="fa fa-fw fa-list"></i>Danh sách đợt thi</a></li>
                            <li><a href="{{URL_EXAM_SERIES_FREE_ADD}}"> <i class="fa fa-fw fa-plus"></i>Thêm mới</a></li>
                        </ul>
                    </li>
                    <li {{ isActive($active_class, 'khoahoc') }} >
                        <a data-toggle="collapse" data-target="#khoahoc"><i class="fa fa-fw fa-tv" ></i> Khóa học </a>
                        <ul id="lms" class="collapse sidemenu-dropdown">
                            <li><a href="{{ URL_LMS_SERIES }}"> <i class="fa fa-fw fa-list"></i>Danh sách khóa học</a></li>
                            <li><a href="{{ URL_LMS_SERIES }}/add"> <i class="fa fa-fw fa-plus"></i>Thêm mới</a></li>
                        </ul>
                    </li>
                    <li {{ isActive($active_class, 'khoaluyenthi') }} >
                        <a data-toggle="collapse" data-target="#khoaluyenthi"><i class="fa fa-fw fa-certificate" ></i>
                            Khóa luyện thi </a>
                        <ul id="lms" class="collapse sidemenu-dropdown">
                            <li><a href="{{ URL_LMS_SERIES_EXAM }}"> <i class="fa fa-fw fa-list"></i>Danh sách KLT</a></li>
                            <li><a href="/lms/seriessexam/add"> <i class="fa fa-fw fa-plus"></i>Thêm mới</a></li>
                        </ul>
                    </li>
                    <li {{ isActive($active_class, 'chiphikhoahoc') }} >
                        <a data-toggle="collapse" data-target="#chiphikhoahoc"><i class="fa fa-fw fa-shopping-cart" ></i>Đăng khóa học </a>
                        <ul id="lms" class="collapse sidemenu-dropdown">
                            <li><a href="{{PREFIX.'lms/seriescombo'}}"> <i class="fa fa-fw fa-list"></i>Danh sách đăng</a></li>
                            <li><a href="{{PREFIX.'lms/seriescombo/add'}}"> <i class="fa fa-fw fa-plus"></i>Thêm mới</a></li>
                        </ul>
                    </li>
					<li {{ isActive($active_class, 'handwriting') }} >
						<a data-toggle="collapse" data-target="#handwriting"><i class="fa fa-fw fa-pencil"></i>Luyện viết</a>
						<ul id="handwriting" class="collapse sidemenu-dropdown">
							<li><a href="/lms/handwriting"> <i class="fa fa-fw fa-list"></i>Danh sách Luyện viết</a></li>
							<li><a href="/lms/handwriting/add"> <i class="fa fa-fw fa-plus"></i>Thêm mới</a></li>
						</ul>
					</li>
                    <li {{ isActive($active_class, 'coupons') }} >
                        <a data-toggle="collapse" data-target="#coupons"><i class="fa fa-fw fa-tags"></i>Flashcard</a>
                        <ul id="coupons" class="collapse sidemenu-dropdown">
                            <li><a href="/lms/flashcard"> <i class="fa fa-fw fa-list"></i>Danh sách Flashcard</a></li>
                            <li><a href="/lms/flashcard/add"> <i class="fa fa-fw fa-plus"></i>Thêm mới</a></li>
                        </ul>
                    </li>
                    <li {{ isActive($active_class, 'reports' ) }}>
                        <a data-toggle="collapse" data-target="#reports"><i class="fa fa-fw fa-credit-card"></i> Báo cáo thanh toán </a>
                        <ul id="reports" class="collapse sidemenu-dropdown">
                            <li><a href="{{PREFIX.'payments-order'}}"> <i class="fa fa-fw fa-shopping-cart"></i>Đơn hàng mua khoá học</a></li>
                            <li><a href="{{ route('payments.order.coin') }}"> <i class="fa fa-star"></i>Đơn hàng nạp Hi coin</a></li>
                            <li><a href="/payments-report/online/success"> <i class="fa fa-fw fa-link"></i>Thanh toán Online</a></li>
                            <li><a href="{{ route('payments-order.coin-recharge-packages.index') }}"> <i class="mdi mdi-coin"></i>Quản lý các gói nạp HiCoin</a></li>
                        </ul>
                    </li>
                    <li {{ isActive($active_class, 'notifications') }} >
                        <a href="/learning-process" ><i class="fa fa-fw fa-bell" aria-hidden="true"></i>
                        Tiến trình học </a>
                    </li>
                    <li {{ isActive($active_class, 'messages') }} >
                        <a  href="{{PREFIX."comments/index"}}"> <i class="fa fa-fw fa-comments" aria-hidden="true"> </i>
                            Comment <small class="msg">{{$count = Auth::user()->newThreadsCount()}} </small></a>
                    </li>
                    <li {{ isActive($active_class, 'khoahoc') }} >
                        <a data-toggle="collapse" data-target="#khoahoc"><i class="fa fa-user fa-user" ></i> Lớp học chỉ định </a>
                        <ul id="lms" class="collapse sidemenu-dropdown">
                            <li><a href="{{PREFIX.'lms/class-content/'}}"> <i class="fa fa-fw fa-list"></i>Danh sách lớp học</a></li>
                            <li><a href="/lms/class-content/add"> <i class="icon-books"></i>Thêm khóa học</a></li>
                        </ul>
                    </li>
                    <li {{ isActive($active_class, 'class') }}> <a href="{{URL_CLASS}}"><i class="fa fa-fw fa-users"></i> Lớp học </a> </li>
                    <li {{ isActive($active_class, 'users') }}> <a href="{{URL_USERS}}"><i class="fa fa-fw fa-user-circle"></i> {{ getPhrase('users') }} </a> </li>
					<li {{ isActive($active_class, 'master_settings') }} >
						<a data-toggle="collapse" data-target="#master_settings" href="#"><i class="fa fa-fw fa-cog" ></i>
                            Cài đặt </a>
                        <ul id="master_settings" class="collapse sidemenu-dropdown">
                            @if(checkRole(getUserGrade(1)))
                                <li><a href="/email/templates"> <i class="icon-settings"></i> Mail template</a></li>
							@endif
						</ul>
					</li>
                </ul>
			</ul>
		</div>
	</aside>
	@if(isset($right_bar))
	<aside class="right-sidebar" id="rightSidebar">
		<button class="sidebat-toggle" id="sidebarToggle" href='javascript:'><i class="mdi mdi-menu"></i></button>
		<div class="panel panel-right-sidebar">
			<?php $data = '';
			if(isset($right_bar_data))
				$data = $right_bar_data;
			?>
			@include($right_bar_path, array('data' => $data))
		</div>
	</aside>
	@endif
	@yield('content')
</div>
    <!-- Bootstrap Core JavaScript -->
    <script src="{{admin_asset('js/jquery-1.12.1.min.js')}}"></script>
    <script src="{{admin_asset('js/bootstrap.min.js')}}"></script>
    <script src="{{admin_asset('js/main.js')}}"></script>
    <script src="{{admin_asset('js/metisMenu.min.js')}}"></script>
    <script src="{{admin_asset('js/sweetalert-dev.js')}}"></script>
    <!-- Basic SweetAlert JavaScript -->
    <script src="{{admin_asset('js/basic-swal.js')}}"></script>
    <script >
        /*Sidebar Menu*/
        $("#ag-menu").metisMenu();
    </script>
    @yield('footer_scripts')
    @include('admin.errors.formMessages')
    @yield('custom_div_end')
    <div class="ajax-loader" style="display:none;" id="ajax_loader"><img src="{{AJAXLOADER}}"> {{getPhrase('please_wait')}}...</div>
</body>
</html>
