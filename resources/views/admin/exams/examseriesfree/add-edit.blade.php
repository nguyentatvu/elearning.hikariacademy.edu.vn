@extends('admin.layouts.admin.adminlayout')
<link href="{{CSS}}bootstrap-datepicker.css" rel="stylesheet">	
@section('content')
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="/"><i class="mdi mdi-home"></i></a> </li>
							<li><a href="{{URL_EXAM_SERIES_FREE}}">Đợt thi</a></li>
							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
					@include('admin.errors.errors')
				<!-- /.row -->
				
				<div class="panel panel-custom col-lg-8  col-lg-offset-2" >
					<div class="panel-heading">
						<div class="pull-right messages-buttons">
							<a href="{{URL_EXAM_SERIES_FREE}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>
						</div>
					<h1>{{ $title }}  </h1>
					</div>
					<div class="panel-body" >
					<?php $button_name = getPhrase('create'); ?>
					@if ($record)
					<?php $button_name = getPhrase('update'); ?>
						{{ Form::model($record, 
						array('url' => URL_EXAM_SERIES_FREE_EDIT.$record->id, 
						'method'=>'patch', 'files' => true, 'name'=>'formExamfree ', 'novalidate'=>'')) }}
					@else
						{!! Form::open(array('url' => URL_EXAM_SERIES_FREE_ADD, 'method' => 'POST', 'files' => true, 'name'=>'formExamfree ', 'novalidate'=>'')) !!}
					@endif
					

					 @include('admin.exams.examseriesfree.form_elements', 
					 array('button_name'=> $button_name),
					 array('record'=>$record,'n1'=>$n1, 'n2'=>$n2, 'n3' => $n3,'n4' => $n4, 'n5' => $n5, 'start_date' => $start_date, 'end_date' => $end_date))
					 		
					{!! Form::close() !!}
					</div>

				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->
@stop

@section('footer_scripts')
 @include('admin.common.validations');
 @include('admin.common.editor');
 @include('admin.common.alertify')

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
 
 