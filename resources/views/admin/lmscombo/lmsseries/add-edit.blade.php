
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
							<li><a href="{{$URL_LMS_SERIES}}">LMS {{ getPhrase('series')}}</a></li>
							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
					@include('admin.errors.errors')
				<!-- /.row -->

 <div class="panel panel-custom col-lg-12">
 <div class="panel-heading">
 	<div class="pull-right messages-buttons">
 		<a href="{{$URL_LMS_SERIES}}" class="btn btn-primary button">{{ getPhrase('list')}}</a>
 	</div>
 	
 </div>
 <div class="panel-body">
					<?php $button_name = getPhrase('create'); ?>
					@if ($record)
					 <?php $button_name = getPhrase('update'); ?>
						{{ Form::model($record,
						array('url' => $URL_LMS_SERIES_EDIT.$record->slug,
						'method'=>'patch', 'files' => true, 'name'=>'formLms ', 'novalidate'=>'')) }}
					@else
						{!! Form::open(array('url' => $URL_LMS_SERIES_ADD, 'method' => 'POST', 'files' => true, 'name'=>'formLms ', 'novalidate'=>'')) !!}
					@endif


					 @include('admin.lmscombo.lmsseries.form_element',
					 array('button_name'=> $button_name),
				 array('record'=>$record,'n1'=>$n1, 'n2'=>$n2, 'n3' => $n3,'n4' => $n4, 'n5' => $n5,/*'n6' => $n6,*/
				                    'en1'=>$en1, 'en2'=>$en2, 'en3' => $en3,'en4' => $en4, 'en5' => $en5))

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
    <script>
 	var file = document.getElementById('image_input');

file.onchange = function(e){
    var ext = this.value.match(/\.([^\.]+)$/)[1];
    switch(ext)
    {
        case 'jpg':
        case 'jpeg':
        case 'png':


            break;
        default:
               alertify.error("{{getPhrase('file_type_not_allowed')}}");
            this.value='';
    }
};
$('.input-daterange').datepicker({
        autoclose: true,
        startDate: "0d",
         format: '{{getDateFormat()}}',
    });

$('.input-date-picker').datepicker({
	// autoclose: true,
	// startDate: "0d",
	format: '{{getDateFormat()}}',
	// minDate:  new Date($(`input[name='timefrom'`).val())
});

$(`input[name='timefrom'`).on('change', function () {
	// Validation FROM - TO
	const timeFrom = $(`input[name='timefrom'`).val();
	const timeTo = $(`input[name='timeto'`).val();
	if(timeTo && timeTo)
	{
		const isValid = moment(timeFrom).isBefore(timeTo); 
		if(!isValid && timeFrom != timeTo) {
			$(`input[name='timefrom'`).val('');
			swal("Lỗi", "Vui lòng chọn ngày ngày bắt đầu <= ngày kết thúc", "error");
		}
	}
})

$(`input[name='timeto'`).on('change', function () {
	// Validation FROM - TO
	const timeFrom = $(`input[name='timefrom'`).val();
	const timeTo = $(`input[name='timeto'`).val();
	if(timeFrom && timeTo)
	{
		const isValid = moment(timeFrom).isBefore(timeTo); 
		if(!isValid && timeFrom != timeTo) {
			$(`input[name='timeto'`).val('');
			swal("Lỗi", "Vui lòng chọn ngày kết thúc >= ngày bắt đầu", "error");
		}
	}
})



 </script>
@stop
