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
					<li><a href="{{url('/')}}"><i class="mdi mdi-home"></i></a> </li>
					<li>{{ $title }}</li>
				</ol>
			</div>
		</div>

		<!-- /.row -->
		<div class="panel panel-custom">
			<div class="panel-heading">
				<!-- <div class="pull-right messages-buttons">
					<button type="button" class="btn btn-primary btn-rounded btn-fw" data-toggle="modal" data-target="#import-exams">
						<i class="mdi mdi-plus-circle"></i>	Import Exams
					</button>
				</div> -->
				<div class="pull-right messages-buttons">
					<button type="button" class="btn btn-primary btn-rounded btn-fw" data-toggle="modal" data-target="#import-mucluc">
						<i class="mdi mdi-plus-circle"></i>	Import Mục lục
					</button>
				</div>
				<!-- <div class="pull-right messages-buttons">
					<a href="{{$URL_LMS_CONTENT_ADD}}" class="btn  btn-primary button" >{{ getPhrase('create')}}</a>
				</div> -->
				<h1>{{ $title }}</h1>
			</div>
			<div class="panel-body packages">
				<div>
					<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>STT</th>
								<th>{{ getPhrase('tiêu đề')}}</th>
								<th>{{ getPhrase('loại')}}</th>
								<!-- <th>{{ getPhrase('image')}}</th>
								<th>{{ getPhrase('type')}}</th> -->
								<th>Trạng thái</th>
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

				<h5 class="modal-title" id="exampleModalLabel-2">Import exams excel</h5>

			</div>

			<form action="{{$URL_IMPORT_EXAMS}}" class="forms-sample" method="post" id="form-importExcel"  enctype="multipart/form-data">
				{{ csrf_field() }}

				<div class="modal-body">

					<div class="card-body">
						<input type="hidden" name="series_slug" value="{{$series_slug}}">
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

<div class="modal fade" id="import-mucluc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">

	<div class="modal-dialog" role="document">

		<div class="modal-content">

			<div class="modal-header">

				<h5 class="modal-title" id="exampleModalLabel-2">Import exams excel</h5>

			</div>

			<form action="{{$URL_IMPORT_MUCLUC}}" class="forms-sample" method="post" enctype="multipart/form-data">
				{{ csrf_field() }}

				<div class="modal-body">

					<div class="card-body">
						<input type="hidden" name="series_slug" value="{{$series_slug}}">
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

@include('admin.common.datatables', array('route'=>$datatbl_url, 'route_as_url' => true))
@include('admin.common.deletescript', array('route'=>URL_LMS_CONTENT_DELETE))

@stop
