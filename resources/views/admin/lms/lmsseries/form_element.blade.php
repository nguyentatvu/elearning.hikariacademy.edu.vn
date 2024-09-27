{{ Form::hidden('type_series', $value = $type_series , $attributes = array()) }}
<div class="row">
    <fieldset class="form-group col-md-6">
        {{ Form::label('title', getphrase('Tiêu đề')) }}
        <span class="text-red">
            *
        </span>
        {{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '',
		'ng-model'=>'title',
		'ng-pattern'=>'',
		'required'=> 'true',
		'ng-class'=>'{"has-error": formLms.title.$touched && formLms.title.$invalid}',
		'ng-minlength' => '2',
		'ng-maxlength' => '240',
		)) }}
        <div class="validation-error" ng-messages="formLms.title.$error">
            {!! getValidationMessage()!!}
			{!! getValidationMessage('pattern')!!}
			{!! getValidationMessage('minlength')!!}
			{!! getValidationMessage('maxlength')!!}
        </div>
    </fieldset>
    <fieldset class="form-group col-md-6">
        <?php foreach($user_gv as $user) {
	                $teacherUser[$user->id] = $user->name.'-'.$user->username; 
	            }
	        ?>
            {{ Form::label('title', 'Giáo viên phụ trách') }}
        <span class="text-red">*</span>
        {{Form::select('teachers[]', $teacherUser, $selectedTeacher, ['class'=>'form-control select2', 'name'=>'teachers[]', 'multiple'=>'true'])}}
    </fieldset>
</div>
<div class="row">
    <?php $category_options = array(1 =>
    'N1', 2 => 'N2', 3 =>'N3', 4 => 'N4' , 5 => 'N5' );?>
    <fieldset class="form-group col-md-6">
        {{ Form::label('Trình độ') }}
        <span class="text-red">
            *
        </span>
        {{Form::select('lms_category_id', $category_options, null, ['placeholder' => '','class'=>'form-control',
		'ng-model'=>'lms_category_id',
		'required'=> 'true',
		'ng-pattern' => getRegexPattern("name"),
		'ng-minlength' => '2',
		'ng-maxlength' => '20',
		'ng-class'=>'{"has-error": formLms.lms_category_id.$touched && formLms.lms_category_id.$invalid}',
		]) }}
        <div class="validation-error" ng-messages="formLms.is_paid.$error">
            {!! getValidationMessage()!!}
        </div>
    </fieldset>

    <?php $type_series = array(0 =>
    'Khóa học', 1 => 'Khóa luyện thi');?>
    <fieldset class="form-group col-md-6">
        {{ Form::label('Loại') }}
        <span class="text-red">
            *
        </span>
        {{Form::select('type_series', $type_series, null, ['placeholder' => '','class'=>'form-control',
        'ng-model'=>'type_series',
        'ng-class'=>'{"has-error": formLms.lms_category_id.$touched && formLms.type_series.$invalid}',
        ]) }}
        <div class="validation-error" ng-messages="formLms.is_paid.$error">
            {!! getValidationMessage()!!}
        </div>
    </fieldset>
</div>
<div class="row">
    <fieldset class="form-group col-md-6">
        {{ Form::label('image', 'Hình ảnh') }}
        <input class="form-control" id="image_input" name="image" type="file">
        </input>
    </fieldset>
    <fieldset class="form-group col-md-2">
        @if($record)
		@if($record->image)
        <?php $examSettings = getExamSettings(); ?>
        <img height="100" src="{{ IMAGE_PATH_UPLOAD_LMS_SERIES.$record->image }}" width="100">
            @endif
		@endif
        </img>
    </fieldset>
</div>
<!--<div class="row input-daterange" id="dp">
	<?php
	$date_from = date('Y/m/d');
	$date_to = date('Y/m/d');
	if($record)
	{
		$date_from = $record->start_date;
		$date_to = $record->end_date;
	}
	?>
	<fieldset class="form-group col-md-6">
		{{ Form::label('start_date', getphrase('start_date')) }}
		{{ Form::text('start_date', $value = $date_from , $attributes = array('class'=>'input-sm form-control', 'placeholder' => '2015/7/17')) }}
	</fieldset>
	<fieldset class="form-group col-md-6">
		{{ Form::label('end_date', getphrase('end_date')) }}
		{{ Form::text('end_date', $value = $date_to , $attributes = array('class'=>'input-sm form-control', 'placeholder' => '2015/7/17')) }}
	</fieldset>
</div>-->
<!-- <div class="row">
	<?php $options = array('1'=>'Yes', '0'=>'No');?>
	<fieldset class="form-group col-md-12" >
		{{ Form::label('show_in_front', getphrase('show_in_home_page')) }}
		<span class="text-red">*</span>
		{{Form::select('show_in_front', $options, null, ['placeholder' => getPhrase('select'),'class'=>'form-control',
		'ng-model'=>'show_in_front',
		'required'=> 'true',
		'ng-class'=>'{"has-error": formLms.show_in_front.$touched && formLms.show_in_front.$invalid}',
		]) }}
		<div class="validation-error" ng-messages="formLms.show_in_front.$error" >
			{!! getValidationMessage()!!}
		</div>
	</fieldset>
</div> -->
<div class="row">
    <fieldset class="form-group col-md-12">
        {{ Form::label('short_description', 'Mô tả ngắn') }}
		{{ Form::textarea('short_description', $value = null , $attributes = array('class'=>'form-control ckeditor', 'rows'=>'3', 'placeholder' => '')) }}
    </fieldset>
    <fieldset class="form-group col-md-12">
        {{ Form::label('description', 'Mô tả') }}
		{{ Form::textarea('description', $value = null , $attributes = array('class'=>'form-control ckeditor', 'rows'=>'10', 'placeholder' => '')) }}
    </fieldset>
</div>
<div class="buttons text-center">
    <button class="btn btn-lg btn-success button" ng-disabled="!formLms.$valid">
        {{ $button_name }}
    </button>
</div>