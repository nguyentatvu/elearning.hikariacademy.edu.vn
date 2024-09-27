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
							<a href="{{URL_LMS_SERIES_ADD}}" class="btn  btn-primary button" >{{ getPhrase('create')}}</a>
						</div>
						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">
						<div> 
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Tiêu đề</th>
									<!-- <th>Hình ảnh</th> -->
									<th>Loại</th>
									<th>Giá</th>
									<!-- <th>{{ getPhrase('validity')}}</th> -->
									<th>Số bài</th>
									<!-- <th>{{ getPhrase('show_in_home_page')}}</th> -->
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
 @include('admin.common.datatables', array('route'=>URL_LMS_SERIES_AJAXLIST, 'route_as_url' => TRUE))
 @include('admin.common.deletescript', array('route'=>URL_LMS_SERIES_DELETE))
@stop
