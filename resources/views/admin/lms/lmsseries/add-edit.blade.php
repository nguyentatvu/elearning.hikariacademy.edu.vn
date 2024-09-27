@extends('admin.layouts.admin.adminlayout')
<link rel="stylesheet" type="text/css" href="{{CSS}}select2.css">
<link href="{{CSS}}bootstrap-datepicker.css" rel="stylesheet">
@section('content')
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li>
                        <a href="{{PREFIX}}">
                            <i class="mdi mdi-home">
                            </i>
                        </a>
                    </li>
                    <li>
                        <a href="{{$URL_LMS_SERIES}}">
                            Khóa học
                        </a>
                    </li>
                    <li>
                        {{ $title }}
                    </li>
                </ol>
            </div>
        </div>

		@include('errors.errors')

 	<div class="panel panel-custom col-lg-12">
 		<div class="panel-heading">
 		    <div class="pull-right messages-buttons">
 		        <a class="btn btn-primary button" href="{{$URL_LMS_SERIES}}">
 		            Danh sách khóa học
 		        </a>
 		    </div>
 		    <h1>
 		        {{ $title }}
 		    </h1>
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
					 @include('lms.lmsseries.form_element',
						 array('button_name'=> $button_name),
						 array('record'=>$record, 
						 'type_series' =>$type_series,
						 'user_gv' =>$user_gv,
                         'selectedTeacher' =>$selectedTeacher,
						 ))
					{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
@stop
@section('footer_scripts')
 @include('common.validations');
 @include('common.editor');
 @include('common.alertify')
  <script src="{{JS}}datepicker.min.js"></script>
    <script>
 	var file = document.getElementById('image_input');
file.onchange = function(e){
    var ext = this.value.match(/\.([^\.]+)$/)[1];
    //alert(12312312);
    switch(ext)
    {
        case 'jpg':
        case 'JPG':
        case 'jpeg':
        case 'JPEG':
        case 'PNG':
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
 </script>
 <script src="{{JS}}select2.js"></script>
    <script>
      $('.select2').select2({
       placeholder: "Thêm giáo viên",
    });
    </script>
@stop
