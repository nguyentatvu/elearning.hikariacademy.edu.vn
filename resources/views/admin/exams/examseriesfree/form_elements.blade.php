<div class="row">
	<fieldset class="form-group col-md-6">
		{{ Form::label('name', 'Tên đợt thi') }}
		<span class="text-red">*</span>
		{{ Form::text('name', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '',
		'ng-model'=>'title', 
		'ng-pattern'=>'', 
		'required'=> '', 
		'ng-class'=>'{"has-error": formExamfree.title.$touched && formExamfree.name.$invalid}',
		'ng-minlength' => '2',
		'ng-maxlength' => '40',
		)) }}
		<div class="validation-error" ng-messages="formExamfree.title.$error" >
			{!! getValidationMessage()!!}
			{!! getValidationMessage('pattern')!!}
			{!! getValidationMessage('minlength')!!}
			{!! getValidationMessage('maxlength')!!}
		</div>
	</fieldset>

</div>


<div class="row">
	
	<fieldset class="form-group col-md-6">
		{{ Form::label('n1', 'Bộ đề N1') }}
		<span class="text-red">*</span>
		{{Form::select('exam1_1', $n1, null, ['class'=>'form-control'])}}
	</fieldset>
</div>

<div class="row">
	
	<fieldset class="form-group col-md-6">
		{{ Form::label('n2', 'Bộ đề N2') }}
		<span class="text-red">*</span>
		{{Form::select('exam2_1', $n2, null, ['class'=>'form-control'])}}
	</fieldset>
</div>

<div class="row">
	
	<fieldset class="form-group col-md-6">
		{{ Form::label('n3', 'Bộ đề N3') }}
		<span class="text-red">*</span>
		{{Form::select('exam3_1', $n3, null, ['class'=>'form-control'])}}
	</fieldset>
</div>
<div class="row">
	
	<fieldset class="form-group col-md-6">
		{{ Form::label('n4', 'Bộ đề N4') }}
		<span class="text-red">*</span>
		{{Form::select('exam4_1', $n4, null, ['class'=>'form-control'])}}
	</fieldset>
</div>
<div class="row">
	
	<fieldset class="form-group col-md-6">
		{{ Form::label('n5', 'Bộ đề N5') }}
		<span class="text-red">*</span>
		{{Form::select('exam5_1', $n5, null, ['class'=>'form-control'])}}
	</fieldset>
</div>

<div class="row">
						
<?php 
$date_from = '2019-04-03 09:20';
$date_to = '2019-05-03 09:20';
?>
<fieldset class="form-group col-md-6">
	{{ Form::label('start_date', 'Ngày bắt đầu') }}
	<div class='input-group date' id='datetimepicker6'>
		{{ Form::text('start_date', $start_date , $attributes = array('class'=>'input-sm form-control', 'placeholder' => '', 'required'=> '', 'data-date-format'=>'YYYY-MM-DD HH:mm')) }}
		<span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
	</div>
</fieldset>

<fieldset class="form-group col-md-6">

	{{ Form::label('end_date', 'Ngày kết thúc') }}
	<div class='input-group date' id='datetimepicker7'>
		{{ Form::text('end_date', $end_date , $attributes = array('class'=>'input-sm form-control', 'placeholder' => '', 'required'=> '', 'data-date-format'=>'YYYY-MM-DD HH:mm')) }}
		<span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
	</div>
</fieldset>

</div>
<div class="buttons text-center">
	<button class="btn btn-lg btn-success button"
	ng-disabled='!formExamfree.$valid'>{{ $button_name }}</button>
</div>


