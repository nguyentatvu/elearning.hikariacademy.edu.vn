@extends($layout)
@section('header_scripts')
<link href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css" rel="stylesheet">
@stop
@section('content')

<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{url('/')}}"><i class="mdi mdi-home"></i></a> </li>
							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>
					
				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						
						<div class="pull-right messages-buttons">
							<!-- <a href="#" class="btn  btn-primary button" >Thêm bài kiểm tra</a> -->
						</div>
						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">
						<div > 
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
								 	<th>Lớp học</th>
									<!-- <th>Số học viên</th> -->
									<th>Ngày bắt đầu</th>
									<th>Hành động</th>
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
 
 <?php $url = URL_PARENT_CLASS_GETLIST.$user->slug; ?>
@section('footer_scripts')
	@php
		// MAKE DEFAULT VALUE COLUMN
		$defaultColumns = [
			'name', 'created_at', 'action'
		];
	@endphp

	@include('admin.common.validations')
	@include('admin.common.datatables', array('route'=>$url, 'route_as_url' => TRUE, 'table_columns' => $defaultColumns))
@stop
