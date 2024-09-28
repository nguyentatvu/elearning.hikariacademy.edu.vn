@extends('admin.layouts.admin.adminlayout')
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')


<div id="page-wrapper">
	<div class="container-fluid">
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
					<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
					<li>{{ $title }}</li>
				</ol>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="{{URL_QUIZ_QUESTIONBANK}}"><div class="state-icn bg-icon-purple"><i class="fa fa-registered"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\User::where('is_register' , '=', 1)->whereraw('created_at BETWEEN "2020-11-07 10:00:00" AND "2020-11-08 23:59:33"')->get()->count() }}</h4>
						<a href="{{URL_QUIZ_QUESTIONBANK}}">HV đã đăng ký mới</a>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="#"><div class="state-icn bg-icon-info"><i class="fa fa-asterisk"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\QuizResultfinish::where('exam_free_id' , '=', $slug)->get()->count() }}</h4>
						<a href="#">Số lần thi</a>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="#"><div class="state-icn bg-icon-pink"><i class="fa fa-fighter-jet"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\QuizResultfinish::where('exam_free_id' , '=', $slug)->where('status', '=' , 1)->get()->count() }}</h4>
						<a href="#">Số HV Đạt</a>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="{{URL_QUIZ_QUESTIONBANK}}"><div class="state-icn bg-icon-orange"><i class="fa fa-quote-right"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\QuizResultfinish::where('exam_free_id' , '=', $slug)->where('finish', '=' , 3)->where('status', '=' , 0)->get()->count() }}</h4>
						<a href="{{URL_QUIZ_QUESTIONBANK}}">Số HV chưa đạt</a>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="{{URL_QUIZ_QUESTIONBANK}}"><div class="state-icn bg-icon-success"><i class="fa fa-first-order"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\QuizResultfinish::where('exam_free_id' , '=', $slug)->where('finish', '=' , 3)->get()->count() }}</h4>
						<a href="{{URL_QUIZ_QUESTIONBANK}}">Số HV hoàn thành</a>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="{{URL_QUIZ_QUESTIONBANK}}"><div class="state-icn bg-icon-orange"><i class="fa fa-flag-o"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\QuizResultfinish::where('exam_free_id' , '=', $slug)->where('finish', '<>' , 3)->get()->count() }}</h4>
						<a href="{{URL_QUIZ_QUESTIONBANK}}">Chưa hoàn thành</a>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="{{URL_QUIZ_QUESTIONBANK}}"><div class="state-icn bg-icon-success"><i class="fa fa-heart"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\QuizResultfinish::where('exam_free_id' , '=', $slug)->where('country_code', '=' , 'JP')->get()->count() }}</h4>
						<a href="{{URL_QUIZ_QUESTIONBANK}}">Số HV thi tại Nhật</a>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="{{URL_QUIZ_QUESTIONBANK}}"><div class="state-icn bg-icon-success"><i class="fa fa-mobile"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\QuizResultfinish::where('exam_free_id' , '=', $slug)->where('is_device', '=' , '1')->get()->count() }}</h4>
						<a href="{{URL_QUIZ_QUESTIONBANK}}">Số HV thi di động</a>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="{{URL_QUIZ_QUESTIONBANK}}"><div class="state-icn bg-icon-success"><i class="fa fa-laptop"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\QuizResultfinish::where('exam_free_id' , '=', $slug)->where('is_device', '=' , '0')->get()->count() }}</h4>
						<a href="{{URL_QUIZ_QUESTIONBANK}}">Số HV thi máy tính</a>
					</div>
				</div>
			</div>

		</div>					
		
	</div>
	<!-- /.container-fluid -->
</div>
@endsection
 

@section('footer_scripts')
  
 @include('admin.common.datatables', array('route'=>URL_EXAM_SERIES_FREE_AJAXLIST, 'route_as_url' => TRUE))
 @include('admin.common.deletescript', array('route'=>URL_EXAM_SERIES_FREE_DELETE))

@stop