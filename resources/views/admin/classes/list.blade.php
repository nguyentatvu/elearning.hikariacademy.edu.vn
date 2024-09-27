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

							 

							<a href="<?php echo URL_CLASSES_ADD; ?>" class="btn  btn-primary button" >Thêm lớp</a>

							 

						</div>

						<h1>{{ $title }}</h1>

					</div>

					<div class="panel-body packages">

						<div> 

						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">

							<thead>

								<tr>

								 

									<th>Tên lớp</th>

									<th>Giáo viên</th>

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
			'name', 'giaovien', 'action'
		];
	@endphp

	@include('admin.common.datatables', array('route'=> '/classes/getList', 'route_as_url' => 'TRUE', 'table_columns' => $defaultColumns))
	@include('admin.common.deletescript', array('route'=> '/classes/delete'))
@stop

