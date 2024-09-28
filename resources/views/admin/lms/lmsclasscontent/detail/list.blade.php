@extends('admin.layouts.admin.adminlayout')
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')
<style type="text/css">
	tr, th{
		text-align: center;
	}
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
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
				<h1>{{ $title }}</h1>
			</div>

			<div class="panel-body packages">
				<div> 
					<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>STT</th>
								<th>Bài</th>
								<th>Trạng thái</th>
								<th>Hành động</th>
							</tr>
						</thead>

					</table>
				</div>

			</div>
		</div>
	</div>
	<!-- /.container-fluid -->
	<!-- Button trigger modal -->

<!-- 	<div class="modal fade" id="confirm-change" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="margin-top: 30%;">
		<div class="modal-dialog" style="max-width: 300px !important">
			<div class="modal-content">
				<div class="modal-body">
					Thay đổi trạng thái của <span>tenbai</span> từ <span>dahienthi</span> sang <span>chuahienthi</span>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
					<a class="btn btn-success btn-ok">Save change</a>
				</div>
			</div>
		</div>
	</div> -->

	@endsection


	@section('footer_scripts')

	<script type="text/javascript">
		function update_status(id){
			$('.btn-update-status').attr('disabled','disabled');
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
				url: "{{$ajax}}",
				method: 'post',
				data: {
					'id': id,
				},
				success: function(response){
					console.log(response);
					$('.datatable').DataTable().ajax.reload(null,false);
					$('.btn-update-status').removeAttr('disabled');
				},
				error: function(response){
					console.log(response);
				}
			});
		}
	</script>

	@php
		// MAKE DEFAULT VALUE COLUMN
		$defaultColumns = [
			'content_id', 'bai', 'show_status','status'
		];
	@endphp

	@include('admin.common.datatables', array('route'=>$url_datatable,'route_as_url' => TRUE, 'table_columns' => $defaultColumns))
	<!-- @include('admin.common.deletescript', array('route'=>URL_LMS_CATEGORIES_DELETE)) -->

	@stop
