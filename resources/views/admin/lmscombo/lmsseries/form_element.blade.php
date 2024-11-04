
<div class="row">
	<fieldset class="form-group col-md-12">
		{{ Form::label('title', getphrase('Tiêu đề')) }}

		<span class="text-red">*</span>

		{{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => getPhrase('series_title'),

		'ng-model'=>'title',

		'ng-pattern'=>'',

		'required'=> 'true',

		'ng-class'=>'{"has-error": formLms.title.$touched && formLms.title.$invalid}',

		'ng-minlength' => '2',

		'ng-maxlength' => '240',

		)) }}

		<div class="validation-error" ng-messages="formLms.title.$error" >

			{!! getValidationMessage()!!}

			{!! getValidationMessage('pattern')!!}

			{!! getValidationMessage('minlength')!!}

			{!! getValidationMessage('maxlength')!!}

		</div>

	</fieldset>

</div>


<div class="row">

	<?php $category_options = array(0 => 'Khóa học', 1 => 'Khóa luyện thi');?>

	<fieldset class="form-group col-md-4" >

			{{ Form::label('type', 'Loại khóa') }}

			<span class="text-red">*</span>

			{{Form::select('type', $category_options, $value, ['class'=>'form-control',

            'ng-model'=>'type',

            'required'=> 'true',

            'ng-pattern' => getRegexPattern("name"),

            'ng-minlength' => '2',

            'ng-maxlength' => '20',

            'ng-class'=>'{"has-error": formLms.type.$touched && formLms.type.$invalid}',



            ]) }}

			<div class="validation-error" ng-messages="formLms.type.$error" >

				{!! getValidationMessage()!!}

			</div>





		</fieldset>


	<fieldset class="form-group col-md-6" >

		{{ Form::label('image', getphrase('image')) }}

		<input type="file" class="form-control" name="image"
			   accept=".png,.jpg,.jpeg" id="image_input">



		<div class="validation-error" ng-messages="formCategories.image.$error" >

			{!! getValidationMessage('image')!!}



		</div>

	</fieldset>



	<fieldset class="form-group col-md-2" >

		@if($record)

			@if($record->image)

				<?php $examSettings = getExamSettings(); ?>

				<img src="{{ '/public/uploads/lms/combo/'.$record->image }}" height="auto" width="100" >



			@endif

		@endif

	</fieldset>

</div>


<div class="row" ng-if="type == 0">
	<fieldset class="form-group col-md-6">
		{{ Form::label('n1', 'Khóa học N1') }}
		<span class="text-red">*</span>
		{{Form::select('n1', $n1, $value, ['class'=>'form-control'])}}
	</fieldset>
	<fieldset class="form-group col-md-6">
		{{ Form::label('n2', 'Khóa học N2') }}
		<span class="text-red">*</span>
		{{Form::select('n2', $n2, $value, ['class'=>'form-control'])}}
	</fieldset>
	<fieldset class="form-group col-md-6">
		{{ Form::label('n3', 'Khóa học N3') }}
		<span class="text-red">*</span>
		{{Form::select('n3', $n3, $value, ['class'=>'form-control'])}}
	</fieldset>
	<fieldset class="form-group col-md-6">
		{{ Form::label('n4', 'Khóa học N4') }}
		<span class="text-red">*</span>
		{{Form::select('n4', $n4, $value, ['class'=>'form-control'])}}
	</fieldset>
	<fieldset class="form-group col-md-6">
		{{ Form::label('n5', 'Khóa học N5') }}
		<span class="text-red">*</span>
		{{Form::select('n5', $n5, $value, ['class'=>'form-control'])}}
	</fieldset>
    <fieldset class="form-group col-md-6">
        {{ Form::label('redeem_point', 'Giảm giá quy đổi (coin * 1000đ)') }}
		<i class="fa fa-info-circle redeem-info"
			data-toggle="tooltip"
			title="<strong>Lưu ý:</strong> Khi nhập số vào đây, khóa học sẽ không xuất hiện trên trang chủ mà sẽ được đăng ở mục “Quy đổi điểm”<br><br>Nhập số HICOIN x 1000 để quy định mức giảm giá khi quy đổi. <br><br><em>Ví dụ:</em> khuyến mãi này được quy đổi bằng 500 coin => cần nhập 500000"
			data-html="true"
			data-placement="top">
		</i>
        {{ Form::number('redeem_point', $value = null , $attributes = [
            'class'=>'form-control',
            'min'=>'0',
            'ng-model'=>'redeem_point',
            'ng-max'=>'cost',
            'string-to-number'=>'true',
            'ng-class'=>'{"has-error": formLms.redeem_point.$touched && formLms.redeem_point.$invalid}'
        ]) }}
		<div class="validation-error" ng-messages="formLms.redeem_point.$error" >
			{!! getValidationMessage()!!}
			{!! getValidationMessage('number')!!}
            <span ng-show="formLms.redeem_point.$viewValue > cost">Số coin không được lớn hơn giá khoá học</span>
		</div>
	</fieldset>
</div>

<div class="row" ng-if="type == 1">

	<fieldset class="form-group col-md-6">
		{{ Form::label('n1', 'Khóa luyện thi N1') }}
		<span class="text-red">*</span>
		{{Form::select('n1', $en1, $value, ['class'=>'form-control'])}}
	</fieldset>
	<fieldset class="form-group col-md-6">
		{{ Form::label('n2', 'Khóa luyện thi N2') }}
		<span class="text-red">*</span>
		{{Form::select('n2', $en2, $value, ['class'=>'form-control'])}}
	</fieldset>
	<fieldset class="form-group col-md-6">
		{{ Form::label('n3', 'Khóa luyện thi N3') }}
		<span class="text-red">*</span>
		{{Form::select('n3', $en3, $value, ['class'=>'form-control'])}}
	</fieldset>
	<fieldset class="form-group col-md-6">
		{{ Form::label('n4', 'Khóa luyện thi N4') }}
		<span class="text-red">*</span>
		{{Form::select('n4', $en4, $value, ['class'=>'form-control'])}}
	</fieldset>
	<fieldset class="form-group col-md-6">
		{{ Form::label('n5', 'Khóa luyện thi N5') }}
		<span class="text-red">*</span>
		{{Form::select('n5', $en5, $value, ['class'=>'form-control'])}}
	</fieldset>
	@php
		$redeem_point = (is_null($record) || !isset($record->redeem_point))
			? null
			: $record->redeem_point * config('constant.redeemed_coin.vnd_convert_rate');
	@endphp
	<fieldset class="form-group col-md-6">
        {{ Form::label('redeem_point', 'Giảm giá quy đổi (coin * 1000đ)') }}
		<i class="fa fa-info-circle redeem-info"
			data-toggle="tooltip"
			title="<strong>Lưu ý:</strong> Khi nhập số vào đây, khóa học sẽ không xuất hiện trên trang chủ mà sẽ được đăng ở mục “Quy đổi điểm”<br><br>Nhập số HICOIN x 1000 để quy định mức giảm giá khi quy đổi. <br><br><em>Ví dụ:</em> khuyến mãi này được quy đổi bằng 500 coin => cần nhập 500000"
			data-html="true"
			data-placement="top">
		</i>
        {{ Form::number('redeem_point', $value = $redeem_point , $attributes = [
            'class'=>'form-control',
            'min'=>'0',
            'ng-model'=>'redeem_point',
            'ng-max'=>'cost',
            'string-to-number'=>'true',
            'ng-class'=>'{"has-error": formLms.redeem_point.$touched && formLms.redeem_point.$invalid}'
        ]) }}
		<div class="validation-error" ng-messages="formLms.redeem_point.$error" >
			{!! getValidationMessage()!!}
			{!! getValidationMessage('number')!!}
            <span ng-show="formLms.redeem_point.$viewValue > cost">Số coin không được lớn hơn giá khoá học</span>
		</div>
	</fieldset>
</div>

<div class="row">

	<fieldset class="form-group col-md-2">



		{{ Form::label('cost', getphrase('cost')) }}

		<span class="text-red">*</span>

		{{ Form::number('cost', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '40',

        'min'=>'0',



        'ng-model'=>'cost',

        'required'=> 'true',
        'string-to-number'=>'true',
        'ng-class'=>'{"has-error": formLms.cost.$touched && formLms.cost.$invalid}',



        )) }}

		<div class="validation-error" ng-messages="formLms.cost.$error" >

			{!! getValidationMessage()!!}

			{!! getValidationMessage('number')!!}

		</div>

	</fieldset>
	<fieldset class="form-group col-md-2">



		{{ Form::label('selloff', getphrase('selloff')) }}

		<span class="text-red">*</span>

		{{ Form::number('selloff', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '40',

        'min'=>'0',



        'ng-model'=>'selloff',

        'required'=> 'true',
        'string-to-number'=>'true',
        'ng-class'=>'{"has-error": formLms.selloff.$touched && formLms.selloff.$invalid}',



        )) }}

		<div class="validation-error" ng-messages="formLms.selloff.$error" >

			{!! getValidationMessage()!!}

			{!! getValidationMessage('number')!!}

		</div>

	</fieldset>

	<fieldset class="form-group col-md-3" >

		{{ Form::label('timefrom', 'Thời gian áp dụng Selloff') }}

		<span class="text-red">*</span>

		{{ Form::text('timefrom', $value = null , $attributes = array('class'=>'form-control input-date-picker', 'placeholder' => 'Ngày bắt đầu',

		'ng-model'=>'timefrom',
		'name' => 'timefrom',
		'required'=> 'true',
		'ng-class'=>'{"has-error": formLms.timefrom.$touched && formLms.timefrom.$invalid}',


		)) }}

		<div class="validation-error" ng-messages="formLms.timefrom.$error" >

		{!! getValidationMessage()!!}

		</div>
	</fieldset>

	<fieldset class="form-group col-md-2">

		<div style="margin-top: 27px;"></div>
		{{ Form::text('timeto', $value = null , $attributes = array('class'=>'form-control input-date-picker', 'placeholder' => 'Ngày kết thúc',
		'name' => 'timeto',
		'ng-model'=>'timeto',
		'required'=> 'true',
		'ng-class'=>'{"has-error": formLms.timeto.$touched && formLms.timeto.$invalid}',


		)) }}

		<div class="validation-error" ng-messages="formLms.timeto.$error" >

		{!! getValidationMessage()!!}
	</fieldset>


	<?php $time_options = array(0 => '3 tháng', 1 => '6 tháng' , 2 => '12 tháng');?>

	<fieldset class="form-group col-md-2" >

		{{ Form::label('time', 'Thời gian') }}

		<span class="text-red">*</span>

		{{Form::select('time', $time_options, $value, ['placeholder' => getPhrase('select'),'class'=>'form-control',

        'ng-model'=>'time',

        'required'=> 'true',

        'ng-pattern' => getRegexPattern("name"),

        'ng-minlength' => '2',

        'ng-maxlength' => '20',

        'ng-class'=>'{"has-error": formLms.time.$touched && formLms.time.$invalid}',



        ]) }}

		<div class="validation-error" ng-messages="formLms.time.$error" >

			{!! getValidationMessage()!!}

		</div>





	</fieldset>




</div>
<div class="row">

</div>
<div class="row">

	<fieldset class="form-group  col-md-12">



		{{ Form::label('short_description', getphrase('short_description')) }}



		{{ Form::textarea('short_description', $value = null , $attributes = array('class'=>'form-control ckeditor', 'rows'=>'5', 'placeholder' => getPhrase('short_description'))) }}

	</fieldset>

    @php
        if ($record) {
            $description = json_decode($record->description);
        } else {
            $description = null;
        }
    @endphp

    <fieldset class="form-group col-md-12">
        <legend>Mô tả chi tiết</legend>
        {{ Form::label('content_description', 'Mô tả nội dung chính') }}
        {{ Form::textarea('content_description', $value = optional($description)->content_description , $attributes = array('id' => 'main_description', 'class'=>'form-control ckeditor', 'rows'=>'7', 'placeholder' => '')) }}
    </fieldset>
    <fieldset class="form-group col-md-6">
        {{ Form::label('time_description', 'Mô tả thời gian học') }}
        {{ Form::textarea('time_description', $value = optional($description)->time_description , $attributes = array('class'=>'form-control ckeditor', 'rows'=>'3', 'placeholder' => '')) }}
    </fieldset>
    <fieldset class="form-group col-md-6">
        {{ Form::label('curriculum_description', 'Mô tả giáo trình') }}
        {{ Form::textarea('curriculum_description', $value = optional($description)->curriculum_description , $attributes = array('class'=>'form-control ckeditor', 'rows'=>'3', 'placeholder' => '')) }}
    </fieldset>
    <fieldset class="form-group col-md-6">
        {{ Form::label('teacher_description', 'Mô tả giảng viên') }}
        {{ Form::textarea('teacher_description', $value = optional($description)->teacher_description , $attributes = array('class'=>'form-control ckeditor', 'rows'=>'3', 'placeholder' => '')) }}
    </fieldset>
</div>

<div class="buttons text-center">

	<button class="btn btn-lg btn-success button"

	ng-disabled='!formLms.$valid'>{{ $button_name }}</button>

</div>
