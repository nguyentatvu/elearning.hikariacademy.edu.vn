<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<!-- layout gv -->
	<meta charset="utf-8">
	<meta name="google" content="notranslate"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="{{getSetting('meta_description', 'seo_settings')}}">
	<meta name="keywords" content="{{getSetting('meta_keywords', 'seo_settings')}}">
	<link rel="icon" href="/public/uploads/settings/favicon.png" type="image/x-icon" />
	<title>@yield('title') {{ isset($title) ? $title : getSetting('site_title','site_settings') }}</title>
	<!-- Bootstrap Core CSS -->
	<link href="{{admin_asset('css/bootstrap.min.css')}}" rel="stylesheet">
	<link href="{{admin_asset('css/sweetalert.css')}}" rel="stylesheet">
	<link href="{{admin_asset('css/sb-admin.css')}}" rel="stylesheet">
	<link href="{{admin_asset('css/custom-fonts.css')}}" rel="stylesheet">
	<link href="{{admin_asset('css/materialdesignicons.css')}}" rel="stylesheet">
	<link href="{{admin_asset('font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
	<link href="{{admin_asset('css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
	<!-- Morris Charts CSS -->
	<link href="{{admin_asset('css/plugins/morris.css')}}" rel="stylesheet">
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
	<link href="{{admin_asset('css/theme-colors/white-theme.css')}}" rel="stylesheet">]
	@endif 
	@yield('header_scripts')
</head>
<?php 
$class = '';
if(!isset($right_bar))
	$class = 'no-right-sidebar';
$block_class = '';
if(isset($block_navigation))
	$block_class = 'non-clickable';
?>
<body ng-app="academia" >
	<div id="wrapper" class="{{$class}}">
		<!-- Navigation -->
		<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
				<a class="navbar-brand" href="{{PREFIX}}"><img src="/public/uploads/settings/logo-elearning.png" style="width: 190px;"></a>
			</div>
			<!-- Top Menu Items -->
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
							<a href="{{URL_USERS_EDIT.Auth::user()->slug}}">
								<sapn>My Hikari</sapn>
							</a>
						</li>
						<li>
							<a href="{{URL_USERS_CHANGE_PASSWORD.Auth::user()->slug}}">
								<sapn>Đổi mật khẩu</sapn>
							</a>
						</li>
						 <!-- <li>
 							<a href="{{URL_USERS_SETTINGS.Auth::user()->slug}}">
 								<sapn>{{ getPhrase('settings') }}</sapn>
 								</a>
 						</li>
 						<li>
 							<a href="{{URL_FEEDBACK_SEND}}">
 								<sapn>{{ getPhrase('feedback') }}</sapn>
 							</a>
 						</li> -->
 						<li>
 							<a href="{{URL_USERS_LOGOUT}}">
 								<sapn>Thoát</sapn>
 							</a>
 						</li>
 					</ul>
 				</li>
 			</ul>
 			<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
 			<!-- /.navbar-collapse -->
 		</nav>

 		<aside class="left-sidebar">
 			<div class="collapse navbar-collapse navbar-ex1-collapse">
 				<ul class="nav navbar-nav side-nav">
 					<li {{ isActive($active_class, 'dashboard') }}> 
 						<a href="{{PREFIX}}">
 							<i class="fa fa-fw fa-window-maximize"></i> {{ getPhrase('dashboard') }} 
 						</a> 
 					</li>
 					<li {{ isActive($active_class, 'children') }} > 
 						<a data-toggle="collapse" data-target="#children"><i class="fa fa-fw fa-user-circle"></i>
 						Danh sách lớp </a> 
 						<ul id="children" class="collapse sidemenu-dropdown">
 							<li><a href="/parent/class"> <i class="fa fa-th"></i>Danh sách lớp</a></li>
 							<!-- <li><a href="{{URL_USERS_ADD}}"> <i class="fa fa-plus"></i>Thêm học viên</a></li> -->
 							<!-- <li><a href="{{URL_PARENT_CHILDREN}}"> <i class="fa fa-th"></i>Danh sách học viên</a></li> -->
 						</ul>
 					</li>
 					<li {{ isActive($active_class, 'messages') }} >
					<a  href="{{PREFIX."parent/comments"}}"> <i class="fa fa-fw fa-comments" aria-hidden="true"> </i>
						Trả lời câu hỏi </a>
					</li>
					<!-- <li {{ isActive($active_class, 'analysis') }} > 
					<a href="{{URL_PARENT_ANALYSIS_FOR_STUDENTS}}"> 
					<i class="fa fa-fw fa-bar-chart" aria-hidden="true"></i>
					Phân tích </a> 
					</li>
					<li {{ isActive($active_class, 'exams') }} > 
					<a data-toggle="collapse" data-target="#exams"><i class="fa fa-fw fa-desktop" ></i> 
					Luyện thi </a> 
					<ul id="exams" class="collapse sidemenu-dropdown">
						<li><a href="{{URL_STUDENT_EXAM_CATEGORIES}}"> <i class="fa fa-random"></i>{{ getPhrase('categories') }}</a></li>
						<li><a href="{{URL_STUDENT_EXAM_SERIES_LIST}}"> <i class="fa fa-list-ol"></i>{{ getPhrase('exam_series') }}</a></li>
					</ul>
					</li>
					<li {{ isActive($active_class, 'lms') }} > 
					<a data-toggle="collapse" data-target="#lms"><i class="fa fa-fw fa-tv" ></i> 
					Khóa học </a> 
					<ul id="lms" class="collapse sidemenu-dropdown">
							<li><a href="{{ URL_STUDENT_LMS_CATEGORIES }}"> <i class="fa fa-random"></i>{{ getPhrase('categories') }}</a></li>
							<li><a href="{{ URL_STUDENT_LMS_SERIES }}"> <i class="fa fa-list-ol"></i>{{ getPhrase('series') }}</a></li>
					</ul>
					</li>
					<li {{ isActive($active_class, 'subscriptions') }} > 
					<a  href="{{URL_PAYMENTS_LIST.Auth::user()->slug}}"><i class="fa fa-fw fa-ticket" ></i> 
					{{ getPhrase('subscriptions') }} </a> 
					</li>
					<li {{ isActive($active_class, 'notifications') }} > 
						<a href="{{URL_NOTIFICATIONS}}" ><i class="fa fa-fw fa-bell" aria-hidden="true"></i> 
					Thông báo </a> 
				</li> -->
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
<!-- /#wrapper -->
<!-- jQuery -->
<script src="{{admin_asset('js/jquery-1.12.1.min.js')}}"></script>
<script src="{{admin_asset('js/bootstrap.min.js')}}"></script>
<script src="{{admin_asset('js/main.js')}}"></script>
<script src="{{admin_asset('js/sweetalert-dev.js')}}"></script>
@yield('footer_scripts')
@include('admin.errors.formMessages')
</body>
</html>