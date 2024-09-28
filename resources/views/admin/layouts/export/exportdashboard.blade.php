@extends('admin.layouts.export.exportlayout')
@section('content')
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
					<li><img src="public/images/Image-Icon/icon-68.png" alt="img" width="25px" height="25px">{{ $title }}</li>
				</ol>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="{{URL_QUIZ_QUESTIONBANK}}"><div class="state-icn bg-icon-orange"><i class="fa fa-question-circle"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\QuestionBank::get()->count() }}</h4>
						<a href="{{URL_QUIZ_QUESTIONBANK}}">Câu hỏi</a>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="{{URL_USERS}}"><div class="state-icn bg-icon-info"><i class="fa fa-users"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\User::get()->count()}}</h4>
						<a href="{{URL_USERS}}">Số học viên</a>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="{{URL_USERS}}"><div class="state-icn bg-icon-info"><i class="fa fa-star"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\User::get()->where('is_register','=',1)->count()}}</h4>
						<a href="{{URL_USERS}}">Đăng ký</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /#page-wrapper -->
	@stop
	@section('footer_scripts')
	@stop
