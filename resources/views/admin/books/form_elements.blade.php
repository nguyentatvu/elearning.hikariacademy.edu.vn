 <fieldset class="form-group">
	{{ Form::label('name', 'Mã sách') }}
	<span class="text-red">*</span>
	{{ Form::text('code', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '',
		'ng-model'=>'code',
		'required'=> 'true', 
		'ng-pattern' => '',
		'ng-minlength' => '2',
		'ng-maxlength' => '60',
		'ng-class'=>'{"has-error": formBooks.code.$touched && formBooks.code.$invalid}',
	)) }}
	<div class="validation-error" ng-messages="formBooks.code.$error" >
		{!! getValidationMessage()!!}
		{!! getValidationMessage('minlength')!!}
		{!! getValidationMessage('maxlength')!!}
		{!! getValidationMessage('pattern')!!}
	</div>
</fieldset>

 <fieldset class="form-group">
	{{ Form::label('name', 'Tên sách') }}
	<span class="text-red">*</span>
	{{ Form::text('name', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '',
		'ng-model'=>'name',
		'ng-class'=>'{"has-error": formBooks.name.$touched && formBooks.name.$invalid}',
	)) }}
	<div class="validation-error" ng-messages="formBooks.name.$error" >
		{!! getValidationMessage()!!}
		{!! getValidationMessage('minlength')!!}
		{!! getValidationMessage('maxlength')!!}
		{!! getValidationMessage('pattern')!!}
	</div>
</fieldset>

<div class="buttons text-center">
	<button class="btn btn-lg btn-success button" 
	ng-disabled='!formBooks.$valid'>{{ $button_name }}</button>
</div>