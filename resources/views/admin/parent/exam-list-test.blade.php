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
		<div class="panel-body"> 
			{{ Form::model('', array('url' => [''], 'method'=>'post')) }}
	        <h3>Bài thi</h3>
	        <?php 
	        $user_record = '';
	        ?>
	        <div class="sem-parent-container">
	        <div class="row" >
	        <div class="col-md-6">
	            <fieldset class="form-group" ng-show="showSearch">
	            {{ Form::label('search', 'Bài thi cần tìm') }}
	            <span class="text-red" >*</span>
	                {{ Form::text('search', $value = null , $attributes = array(
	                    'class'         => 'form-control', 
	                    'placeholder'   => 'VD: Bài thi N3',
	                    'ng-model'      => 'search',
	                    'ng-change'     => 'getParentRecords(search)',
	                    )) }}
	            </fieldset>
	        </div>
	        </div>
	        </div>
	        <div class="buttons text-center">
	            <button type="submit" class="btn btn-lg btn-success button">Cập nhật</button>
	        </div>
	        {!! Form::close() !!}
		
		<!-- /.row -->
		<div class="panel panel-custom">
			<div class="panel-heading">
				<div class="pull-right messages-buttons">
					<a href="/parent/class" class="btn  btn-primary button" >Danh sách lớp</a>
				</div>
				<h1>{{ $title }}</h1>
			</div>
			<div class="panel-body packages">
				<div > 
				<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
					<thead>
						<tr>
						 	<th>Để thi</th>
							<th>Ngày tạo</th>
							<th>{{ getPhrase('action')}}</th>
						</tr>
					</thead>
				</table>
				</div>
			</div>
		</div>

		</div>
		
	</div>
			<!-- /.container-fluid -->
</div>
@endsection
 <?php $url = URL_PARENT_EXAM_GETLIST.$slug;  ?>
@section('footer_scripts')
@include('admin.common.validations')
@include('admin.common.alertify')
@include('admin.parent.scripts.js-scripts')
@include('admin.common.datatables', array('route'=>$url, 'route_as_url' => TRUE))
@stop