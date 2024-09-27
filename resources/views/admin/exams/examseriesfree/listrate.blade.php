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
									<th width="12%">Học viên</th>
									<th width="12%">Đề thi</th>
									<th width="8%">Độ khó</th>
									<th width="8%">Giao diện</th>
									<th width="8%">Thao tác</th>
									<th width="8%">Âm thanh</th>
									<th width="8%">Tốc độ</th>
									<th>Góp ý</th>
									<th width="3%">{{ getPhrase('action')}}</th>
								  
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
 
 <?php $url = URL_EXAM_SERIES_RATE_AJAXLIST . $slug;?> 
 	@php
	// MAKE DEFAULT VALUE COLUMN
	$defaultColumns = [
		'name', 'title', 'dethi', 'giaodien', 'thaotac','amthanh', 'tocdo', 'gopy', 'action'
	];
	@endphp
	@include('admin.common.datatables', array('route'=>$url, 'route_as_url' => TRUE, 'pdf'=>'0,1,2,3,4,5,6,7', 'pdf_orientation'=>'1', 'table_columns' => $defaultColumns))
	@include('admin.common.deletescript', array('route'=>URL_EXAM_SERIES_FREE_DELETE))

@stop
