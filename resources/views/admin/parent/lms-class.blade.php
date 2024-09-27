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
					<a href="/parent/class" class="btn  btn-primary button" >Danh sách lớp</a>
				</div>
				<h1>{{ $title }}</h1>
			</div>
			<div class="panel-body packages">
				<!-- Form thêm bài thi -->
				{{ Form::model('', array('url' => Request::url(), 'method'=>'post')) }}
				<h3></h3>
				<?php 
				$user_record = '';
				?>
				<div class="sem-parent-container">
					<div class="row">
						<?php 
							$subjects = array(''=>'--Chọn trình độ--',
								1=>'N1',2=>'N2',3=>'N3',4=>'N4', 5=>'N5');
							$categories = array(''=>'--Chọn trình độ--') + $categories;

						?>
						<fieldset class="form-group col-sm-4">
							{{ Form::label('subject_id', 'Chọn trình độ') }}
							<span class="text-red">*</span>
							{{Form::select('subject_id', $categories, null, ['class'=>'form-control','onChange'=>'getSubjectParents()', 'id'=>'subject',
								'ng-model'=>'subject_id',
								'required'=> 'true', 
								'ng-class'=>''
							])}}
						</fieldset>

						

						<?php 
						$option_exam = array(''=>'--Chọn bộ đề thi--') + $option_exam_chidinh;
						?>
						<fieldset class="form-group col-sm-4">
							{{ Form::label('teacher_id', 'Nhập bộ đề thi chỉ định') }} <span class="text-red">*</span>
							{{Form::select('exam_id', $option_exam, null, ['class'=>'form-control', "id"=>"parent_n", 'required'=> 'true'])}}
						</fieldset>
						
					</div>
					<div class="row">
						
						<?php 
						$date_from = '2019-04-03 09:20';
						$date_to = '2019-05-03 09:20';
						?>
						<fieldset class="form-group col-md-4">
							{{ Form::label('start_date', 'Ngày bắt đầu') }}
							<div class='input-group date' id='datetimepicker6'>
								{{ Form::text('start_date', '' , $attributes = array('class'=>'input-sm form-control', 'placeholder' => '', 'required'=> 'true', 'data-date-format'=>'YYYY-MM-DD HH:mm')) }}
								<span class="input-group-addon">
				                    <span class="glyphicon glyphicon-calendar"></span>
				                </span>
							</div>
						</fieldset>

						<fieldset class="form-group col-md-4">

							{{ Form::label('end_date', 'Ngày kết thúc') }}
							<div class='input-group date' id='datetimepicker7'>
								{{ Form::text('end_date', '' , $attributes = array('class'=>'input-sm form-control', 'placeholder' => '', 'required'=> 'true', 'data-date-format'=>'YYYY-MM-DD HH:mm')) }}
								<span class="input-group-addon">
				                    <span class="glyphicon glyphicon-calendar"></span>
				                </span>
							</div>
						</fieldset>
						
					</div>
					<!-- <div class="row">
						<div class="container">
						    <div class='col-md-5'>
						        <div class="form-group">
						            <div class='input-group date' id='datetimepicker6'>
						                <input type='text' class="form-control" />
						                <span class="input-group-addon">
						                    <span class="glyphicon glyphicon-calendar"></span>
						                </span>
						            </div>
						        </div>
						    </div>
						    <div class='col-md-5'>
						        <div class="form-group">
						            <div class='input-group date' id='datetimepicker7'>
						                <input type='text' class="form-control" />
						                <span class="input-group-addon">
						                    <span class="glyphicon glyphicon-calendar"></span>
						                </span>
						            </div>
						        </div>
						    </div>
						</div>
						
					</div> -->
					<div class="row">
						<fieldset class="form-group col-sm-4">
							<div class="buttons">
								<button type="submit" class="btn btn-lg btn-success button">Thêm bài thi</button>
							</div>
						</fieldset>
					</div>
					{!! Form::close() !!}
				</div>
				<div > 
					<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Để thi</th>
								<th>Bắt đầu</th>
								<th>Kết thúc</th>
								<th>{{ getPhrase('action')}}</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
			<!--### Form thêm bài thi -->
		</div>
	</div>
</div>
<!-- /.container-fluid -->
</div>
@endsection
<?php $url = URL_PARENT_EXAM_GETLIST.$slug;  ?>
@section('footer_scripts')
	@php
		// MAKE DEFAULT VALUE COLUMN
		$defaultColumns = [
			'title', 'start_date', 'end_date', 'action'
		];
	@endphp
	@include('admin.common.validations')
	@include('admin.common.alertify')
	@include('admin.parent.scripts.js-scripts')
	@include('admin.common.datatables', array('route'=>$url, 'route_as_url' => TRUE, 'table_columns' => $defaultColumns))
	@include('admin.common.deletescript', array('route'=>URL_PARENT_CLASS_DELETE))
	<script src="{{JS}}datepicker.min.js"></script>
	<script src="{{JS}}moment.min.js"></script>
	<script src="{{JS}}bootstrap-datetimepicker.js"></script>
	<script>
	// $('.input-daterange').datepicker({
	//        autoclose: true,
	//        startDate: "0d",
	//         format: '{{getDateFormat()}}',
	//    });
	</script>
	<script type="text/javascript">
		$(function () {
			$('#datetimepicker6').datetimepicker({
				// format: "d-m-y H:i",
				"defaultDate":new Date(),
			});
			$('#datetimepicker7').datetimepicker({
				useCurrent: false, //Important! See issue #1075
				// format: "d-m-y H:i"
				// autoclose: true,
				// startDate: "0d",
				// format: '{{getDateFormat()}}',
				"defaultDate":new Date(),
			});
			$("#datetimepicker6").on("dp.change", function (e) {
				$('#datetimepicker7').data("DateTimePicker").minDate(e.date);
			});
			$("#datetimepicker7").on("dp.change", function (e) {
				$('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
			});
		});
	</script>
@stop


