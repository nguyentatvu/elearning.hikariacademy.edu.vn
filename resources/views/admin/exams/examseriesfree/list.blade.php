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
								
				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						
						<div class="pull-right messages-buttons">
							<a href="{{URL_EXAM_SERIES_FREE_ADD}}" class="btn  btn-primary button" >{{ getPhrase('create')}}</a>
						</div>
						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">
						<div> 
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Đợt thi</th>
									<th>N1</th>
									<th>N2</th>
									<th>N3</th>
									<th>N4</th>
									<th>N5</th>
									<th>Bắt đầu</th>
									<th>Kết thúc</th>
									<th>{{ getPhrase('action')}}</th>
								  
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
	@php
	// MAKE DEFAULT VALUE COLUMN
	$defaultColumns = [
		'name', 'exam1_1','exam2_1','exam3_1', 'exam4_1', 'exam5_1', 'start_date', 'end_date', 'action'
	];
	@endphp
	@include('admin.common.datatables', array('route'=>URL_EXAM_SERIES_FREE_AJAXLIST, 'route_as_url' => TRUE, 'table_columns' => $defaultColumns))
	@include('admin.common.deletescript', array('route'=>URL_EXAM_SERIES_FREE_DELETE))

@stop
