@extends('admin.layouts.admin.adminlayout')
@section('header_scripts')
{{-- <link href="{{CSS}}ajax-datatables.css" rel="stylesheet"> --}}
 <link href="{{admin_asset('css/ajax-datatables.css')}}" rel="stylesheet">
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
						
						
						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">
						<div> 
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
									 
									<th>{{ getPhrase('theme_name')}}</th>
									<th>{{ getPhrase('description')}}</th>
									<th>{{ getPhrase('make_as_default_theme')}}</th>
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
		'title', 'description', 'is_active', 'action'
	];
	@endphp

	@include('admin.common.datatables', array('route'=>URL_THEMES_GET_DATA, 'route_as_url'=>TRUE, 'table_columns'=>$defaultColumns))
@stop
