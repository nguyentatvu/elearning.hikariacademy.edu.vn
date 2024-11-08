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
					<li><a href="{{PREFIX}}">Khóa luyện thi</a> </li>
				</ol>
			</div>
		</div>
		<!-- /.row -->
		<div class="panel panel-custom">
			<div class="panel-heading">
				<div class="pull-right messages-buttons">
					<a href="{{$create_url}}" class="btn btn-primary button" >{{ getPhrase('create')}}</a>
				</div>
			</div>
			<div class="panel-body packages">
				<div>
					<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>khóa luyện thi</th>
								<!-- <th>Loại</th>
								<th>Giá</th>
								<th>Số bài</th> -->
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

<div class="modal fade" id="import-exams" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">

	<div class="modal-dialog" role="document">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title" id="exampleModalLabel-2">Import excel</h5>

			</div>

			<form action="{{$URL_IMPORT_CONTENT}}" class="forms-sample" method="post" id="form-importExcel"  enctype="multipart/form-data">
				{{ csrf_field() }}

				<div class="modal-body">

					<div class="card-body">
						<label>File (.xlsx)</label>
						<input type="file" name="file" class="form-control">
					</div>

				</div>

				<div class="modal-footer">

					<button type="button" class="btn btn-danger" data-dismiss="modal">Hủy bỏ</button>
					<button type="submit" class="btn btn-success">Tải lên</button>

				</div>

			</form>

		</div>

	</div>

</div>
@endsection
@section('footer_scripts')
	@php
		// MAKE DEFAULT VALUE COLUMN
		$defaultColumns = [
			'title', 'action'
		];
	@endphp
	@include('admin.common.datatables', array('route'=>$datatbl_url, 'route_as_url' => true, 'table_columns' => $defaultColumns))
	@include('admin.common.deletescript', array('route'=>URL_LMS_SERIES_DELETE))
@stop
