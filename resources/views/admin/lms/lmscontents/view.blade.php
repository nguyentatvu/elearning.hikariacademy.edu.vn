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
					<li><a href="{{$back_content}}">{{ $title }}</a></li>
					<li>Detail</li>
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
								<th>Dạng</th>
								<th>Câu</th>
								<th>Mô tả</th>
								<th>Lựa chọn 1</th>
								<th>Lựa chọn 2</th>
								<th>Lựa chọn 3</th>
								<th>Lựa chọn 4</th>
								<th>Đáp án</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach($tr as $r){
								echo $r;
							}
							?>
						</tbody>

					</table>
				</div>

			</div>
		</div>
	</div>
	<!-- /.container-fluid -->
</div>

@endsection

@section('footer_scripts')

@stop
