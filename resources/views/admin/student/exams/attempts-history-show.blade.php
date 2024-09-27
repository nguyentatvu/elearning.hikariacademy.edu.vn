@extends($layout)
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
<style>
	table thead tr th:before,table thead tr th:after{
		content: none!important;
	}
	.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate {
		display: none;
	}
	table.dataTable thead .sorting_desc, table.dataTable thead .sorting {
		background-image: none !important;
	}
</style>
@stop
@section('content')
<div id="page-wrapper">
	<div class="container-fluid rac">
		<!-- Page Heading -->
		{{--<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
					<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
					<li><a href="{{URL_STUDENT_EXAM_ATTEMPTS_FINISH.$user->slug}}">Lịch sử bài thi</a></li>
					<li>{{ $de_thi}}</li>
				</ol>
			</div>
		</div>--}}

		<nav aria-label="breadcrumb">

			<ol class="breadcrumb breadcrumb-custom bg-inverse-info">

				<li class="breadcrumb-item"><a href="{{PREFIX}}"><i class="mdi mdi-home menu-icon"></i></a></li>

				<li class="breadcrumb-item"><a href="{{URL_STUDENT_EXAM_ATTEMPTS_FINISH.$user->slug}}">Lịch sử bài thi</a></li>

				<li class="breadcrumb-item active" aria-current="page"><span>{{ $de_thi }}</span></li>

			</ol>

		</nav>
		<!-- /.row -->
		<div class="panel panel-custom">
			<div class="panel-heading">
				<h1> Lịch Sử Đề Thi: {{ $de_thi}}</h1>
			</div>
			<div class="panel-body packages">
				<div class="table-responsive"> 
					<table class="table table-striped table-bordered datatable hikari-history" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Đề thi </th>
								{{-- <th>Điểm </th>--}}
								<th>Chi tiết</th>
							</tr>
						</thead>
					</table>

			</div>
					<!-- <div class="row">
						<div class="col-md-6 col-md-offset-3">
							<canvas id="myChart1" width="100" height="110"></canvas>
						</div>
					</div> -->
				</div>
			</div>
			<?php if($category_id) {?>
			<div class="panel panel-custom" style="display: none;">
				<div class="panel-heading">
					<h1>CÁCH TÍNH ĐIỂM</h1>
				</div>
				<div class="panel-body packages">
					<div class="col-md-12">
						<img src="/public/uploads/exams/marks/mark_n<?php echo $category_id; ?>.png" style="padding-right: 10px">
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
		<!-- /.container-fluid -->
	</div>
	@endsection
	@section('footer_scripts')
	@if(!$exam_record)
	@include('admin.common.datatables', array('route'=>URL_STUDENT_EXAM_GETATTEMPTS.$user->slug.'/'.$quizresultfinish_id, 'route_as_url' => 'TRUE'))
	@else
	@include('admin.common.datatables', array('route'=>URL_STUDENT_EXAM_GETATTEMPTS.$user->slug.'/'.$exam_record->slug, 'route_as_url' => 'TRUE'))
	@endif
	@include('admin.common.chart', array($chart_data,'ids' => array('myChart1')));
	@stop