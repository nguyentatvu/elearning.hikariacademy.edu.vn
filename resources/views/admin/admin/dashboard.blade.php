@extends('admin.layouts.admin.adminlayout')
@section('content')
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
					<li class="vertical-line-bottom">
						<a href="{{URL_ADMIN_DASHBOARD}}">
							<i class="mdi mdi-home"></i>
						</a>
					</li>
					<li>{{ $title }}</li>
				</ol>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4 col-sm-6">
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
			<div class="col-md-4 col-sm-6">
		 		<div class="media state-media box-ws">
		 			<div class="media-left">
		 				<a href="{{URL_TOPICS}}"><div class="state-icn bg-icon-purple"><i class="fa fa-list"></i></div></a>
		 			</div>
		 			<div class="media-body">
		 				<h4 class="card-title">{{ App\Topic::get()->count() }}</h4>
		 				<a href="{{URL_TOPICS}}">Câu hỏi Mondai</a>
		 			</div>
		 		</div>
		 	</div>
		 	<div class="col-md-4 col-sm-6">
		 		<div class="media state-media box-ws">
		 			<div class="media-left">
		 				<a href="{{URL_SUBJECTS}}"><div class="state-icn bg-icon-success"><i class="fa fa-book"></i></div></a>
		 			</div>
		 			<div class="media-body">
		 				<h4 class="card-title">{{ App\Subject::get()->count()}}</h4>
		 				<a href="{{URL_SUBJECTS}}">Mondai</a>
		 			</div>
		 		</div>
		 	</div>
			<div class="col-md-4 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="{{URL_USERS}}"><div class="state-icn bg-icon-info"><i class="fa fa-users"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\User::where('role_id','=',5)->where('is_register','=',0)->get()->count()}}</h4>
						<a href="{{URL_USERS}}">Số HV Hikari</a>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="{{URL_USERS}}"><div class="state-icn bg-icon-success"><i class="fa fa-star"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\User::get()->where('is_register','=',1)->count()}}</h4>
						<a href="{{URL_USERS}}/register">HV đăng ký</a>
					</div>
				</div>
			</div>
			
			<div class="col-md-4 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="{{URL_QUIZ_QUESTIONBANK}}"><div class="state-icn bg-icon-info"><i class="fa fa-list-alt"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\QuizResultfinish::where('exam_free_id', '>' , 0)->get()->count() }}</h4>
						<a href="#">Số lượt thi Online</a>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="#"><div class="state-icn bg-icon-info"><i class="fa fa-list"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\QuizResultfinish::where('exam_free_id', '>' , 0)->where('finish','=',3)->get()->count() }}</h4>
						<a href="#">HVĐK hoàn thành thi</a>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="#"><div class="state-icn bg-icon-orange"><i class="fa fa-desktop"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\QuizResultfinish::where('exam_free_id', '>' , 0)->where('finish','<>',3)->get()->count() }}</h4>
						<a href="#">HVĐK chưa hoàn thành thi</a>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="#"><div class="state-icn bg-icon-pink"><i class="fa fa-book"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\QuizResultfinish::where('exam_free_id', '>' , 0)->where('finish','=',3)->where('status','=',1)->get()->count() }}</h4>
						<a href="#">HVĐK thi đạt</a>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="#"><div class="state-icn bg-icon-orange"><i class="fa fa-circle"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\QuizResultfinish::where('exam_free_id', '>' , 0)->where('finish','=',3)->where('status','=',0)->get()->count() }}</h4>
						<a href="#">HVĐK thi chưa đạt</a>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="/users/registerjp"><div class="state-icn bg-icon-purple"><i class="fa fa-certificate"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\User::get()->where('is_register','=',1)->where('country_code', '=' , 'JP')->count()}}</h4>
						<a href="/users/registerjp">HVĐK tại Nhật</a>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="{{URL_QUIZ_QUESTIONBANK}}"><div class="state-icn bg-icon-success"><i class="fa fa-circle"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\QuizResultfinish::where('country_code', '=' , 'JP')->get()->count() }}</h4>
						<a href="#">Số lượt thi tại Nhật</a>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="{{URL_QUIZ_QUESTIONBANK}}"><div class="state-icn bg-icon-pink"><i class="fa fa-mobile"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\QuizResultfinish::where('is_device', '=' , 1)->get()->count() }}</h4>
						<a href="#">Số lượt thi trên di động</a>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-6">
				<div class="media state-media box-ws">
					<div class="media-left">
						<a href="{{URL_QUIZ_QUESTIONBANK}}"><div class="state-icn bg-icon-pink"><i class="fa fa-laptop"></i></div></a>
					</div>
					<div class="media-body">
						<h4 class="card-title">{{ App\QuizResultfinish::where('is_device', '=' , 0)->get()->count() }}</h4>
						<a href="#">Số lượt thi trên PC</a>
					</div>
				</div>
			</div>
		</div>

			
		<div class="row">
			
				<div class="col-md-6 col-lg-4">
					<div class="panel panel-primary dsPanel">
						<div class="panel-heading"><i class="fa fa-bar-chart-o"></i>HVĐK theo quốc gia</div>
						<div class="panel-body" >
							<?php $ids=[];?>
							@for($i=0; $i<count($country_register_data); $i++)
							<div class="panel-body">
								<div class="row">
									<div class="col-md-12">
										<canvas id="contry_register" width="100" height="110"></canvas>
									</div>
								</div>
							</div>
							@endfor
						</div>
						
					</div>
				</div>

				<div class="col-md-6 col-lg-4">
						<div class="panel panel-primary dsPanel">
							<div class="panel-heading"><i class="fa fa-bar-chart-o"></i>HVĐK theo Tỉnh/TP</div>
							<div class="panel-body" >
								<?php $ids=[];?>
								@for($i=0; $i<count($chart_usercity_data); $i++)
								<?php 
								$newid = 'myChart'.$i;
								$ids[] = $newid; ?>
								<div class="panel-body">
									<div class="row">
										<div class="col-md-12">
											<canvas id="chart_usercity_chart" width="100" height="110"></canvas>
										</div>
									</div>
								</div>
								@endfor
							</div>
						</div>
				</div>

				<div class="col-md-6 col-lg-4">
						<div class="panel panel-primary dsPanel">
							<div class="panel-heading"><i class="fa fa-bar-chart-o"></i>HVĐK thi theo Tỉnh/TP</div>
							<div class="panel-body" >
								<?php $ids=[];?>
								@for($i=0; $i<count($chart_usercityexam_data); $i++)
								<?php 
								$newid = 'myChart'.$i;
								$ids[] = $newid; ?>
								<div class="panel-body">
									<div class="row">
										<div class="col-md-12">
											<canvas id="chart_usercityexam_chart" width="100" height="110"></canvas>
										</div>
									</div>
								</div>
								@endfor
							</div>
						</div>
				</div>

		</div><!-- row -->

		<div class="row">


			<div class="col-md-6 col-lg-4">
				<div class="panel panel-primary dsPanel">
					<div class="panel-heading"><i class="fa fa-bar-chart-o"></i>HVĐK thi trên thiết bị</div>
					<div class="panel-body" >
						<?php $ids=[];?>
						@for($i=0; $i<count($chart_device_data); $i++)
							<div class="panel-body">
								<div class="row">
									<div class="col-md-12">
										<canvas id="chart_device_chart" width="100" height="110"></canvas>
									</div>
								</div>
							</div>
						@endfor
					</div>
				</div>
			</div>
			
			<div class="col-md-6 col-lg-4">
				<div class="panel panel-primary dsPanel">
					<div class="panel-heading"><i class="fa fa-bar-chart-o"></i>HVĐK theo tháng</div>
					<div class="panel-body" >
						<?php $ids=[];?>
						@for($i=0; $i<count($chart_usermonth_data); $i++)
						<?php 
						$newid = 'myChart'.$i;
						$ids[] = $newid; ?>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<canvas id="chart_usermonth_chart" width="100" height="110"></canvas>
								</div>
							</div>
						</div>
						@endfor
					</div>
				</div>	
			</div>

			
			<div class="col-md-6 col-lg-4">
				<div class="panel panel-primary dsPanel">
					<div class="panel-heading"><i class="fa fa-bar-chart-o"></i>HV thi theo đợt</div>
					<div class="panel-body" >
						<?php $ids=[];?>
						@for($i=0; $i<count($chart_exam_free_data); $i++)
							<?php 
							$newid = 'myChart'.$i;
							$ids[] = $newid; ?>
							<div class="panel-body">
								<div class="row">
									<div class="col-md-12">
										<canvas id="chart_exam_free_chart" width="100" height="110"></canvas>
									</div>
								</div>
							</div>
						@endfor
					</div>
				</div>
			</div>


			

		</div><!-- row -->

 		</div>
 		</div>
 		<!-- /#page-wrapper -->
 		@stop
 		@section('footer_scripts')
 		
 		@include('admin.common.chart', array('chart_data'=>$country_register_data,'ids'=>array('contry_register'), 'scale'=>TRUE))
		@include('admin.common.chart', array('chart_data'=>$chart_exam_free_data,'ids'=>array('chart_exam_free_chart'), 'scale'=>TRUE))
		@include('admin.common.chart', array('chart_data'=>$chart_usermonth_data,'ids'=>array('chart_usermonth_chart'), 'scale'=>TRUE))
		@include('admin.common.chart', array('chart_data'=>$chart_usercity_data,'ids'=>array('chart_usercity_chart'), 'scale'=>TRUE))
		@include('admin.common.chart', array('chart_data'=>$chart_usercityexam_data,'ids'=>array('chart_usercityexam_chart'), 'scale'=>TRUE))
		@include('admin.common.chart', array('chart_data'=>$chart_device_data,'ids'=>array('chart_device_chart'), 'scale'=>TRUE))


 		@stop
