@extends($layout)
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
						
						<div class="pull-right messages-buttons">
							 
							<!-- <a href="{{URL_USERS_IMPORT}}" class="btn  btn-primary button" >{{ getPhrase('import_excel')}}</a> -->
							<a href="{{URL_USERS_ADD}}" class="btn  btn-primary button" >{{ getPhrase('add_user')}}</a>
							 
						</div>
						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">
						<div> 
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>{{ getPhrase('name')}}</th>
								 	<th>{{ getPhrase('email')}}</th>
								 	<th>Quốc gia</th>
								 	<th>Tỉnh/Tp</th>
								 	<th>Quận/Huyện</th>
								 	<th>Ngày đăng ký</th>
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
			'name', 'email', 'country', 'state', 'city', 'created_at', 'action'
		];
	@endphp

	@include('admin.common.datatables', array('route'=>'users.registerjpdataTable','excel'=>'0,1,2,3,4', 'table_columns' => $defaultColumns))
	@include('admin.common.deletescript', array('route'=>URL_USERS_DELETE))
@stop
