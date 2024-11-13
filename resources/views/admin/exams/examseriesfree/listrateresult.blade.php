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
							<li><a href="{{URL_ADMIN_DASHBOARD}}"><i class="mdi mdi-home"></i></a> </li>
							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>
								
				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						
						<!-- <div class="pull-right messages-buttons">
							<a href="{{URL_EXAM_SERIES_FREE_ADD}}" class="btn  btn-primary button" >{{ getPhrase('create')}}</a>
						</div> -->
						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">
						<div> 
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th width="20%">Học viên</th>
									<th width="15%">Đề thi</th>
									<th width="40%">Điểm thi</th>
									<th width="10%">Tổng điểm</th>
									<th width="10%">Kết quả</th>
									
									<th width="5%">{{ getPhrase('action')}}</th>
								  
								</tr>
							</thead>
							 
						</table>
						</div>

					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
@endsection
 

@section('footer_scripts')
 
 	<?php $url = URL_EXAM_SERIES_RATE_RESULT_AJAXLIST . $slug; ?> 
 	@php
	// MAKE DEFAULT VALUE COLUMN
	$defaultColumns = [
		'user_id', 'title', 'quiz_1_total', 'total_marks', 'finish', 'action'
	];
	@endphp
	@include('admin.common.datatables', array('route'=>$url, 'route_as_url' => TRUE, 'pdf'=>'0,1,2,3,4', 'table_columns' => $defaultColumns))
	@include('admin.common.deletescript', array('route'=>URL_EXAM_SERIES_FREE_DELETE))

@stop
