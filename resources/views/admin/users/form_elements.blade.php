
@if(!checkRole(['parent']))
<fieldset class="form-group">
    {{ Form::label('role', 'Quyền') }}
    <span class="text-red">
        *
    </span>
    <?php $disabled = (checkRole(getUserGrade(2))) ? '' :'disabled'; 
						$selected = getRoleData('student');
						if($record)
							$selected = $record->role_id;
						?>
						{{Form::select('role_id', $roles, $selected, ['placeholder' => getPhrase('select_role'),'class'=>'form-control', $disabled,
							'ng-model'=>'role_id',
							'required'=> 'true', 
							'ng-class'=>'{"has-error": formUsers.role_id.$touched && formUsers.role_id.$invalid}'
						 ])}}
    <div class="validation-error" ng-messages="formUsers.role_id.$error">
        {!! getValidationMessage()!!}
    </div>
</fieldset>
@endif
<fieldset class="form-group">
    {{ Form::label('name', 'Họ tên') }}
    <span class="text-red">
        *
    </span>
    {{ Form::text('name', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '',
							'ng-model'=>'name',
							'required'=> 'true', 
							'ng-pattern' => '',
							'ng-minlength' => '2',
							'ng-maxlength' => '200',
							'ng-class'=>'{"has-error": formUsers.name.$touched && formUsers.name.$invalid}',
						)) }}
    <div class="validation-error" ng-messages="formUsers.name.$error">
        {!! getValidationMessage()!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
	    					{!! getValidationMessage('pattern')!!}
    </div>
</fieldset>
@php 
	$readonly = '';
	$username_value = null;
	if($record){
		$readonly = 'readonly="true"';
	}
@endphp
<fieldset class="form-group" ng-if="role_id !=5">
    {{ Form::label('username', 'Tên đăng nhập') }}
    <span class="text-red">
        *
    </span>
    {{ Form::text('username', $value = $username_value , $attributes = array('class'=>'form-control', 'placeholder' => '',
							'ng-model'=>'username',
							'required'=> 'true', 
							 $readonly,
							'ng-minlength' => '2',
							'ng-maxlength' => '20',
							'ng-class'=>'{"has-error": formUsers.username.$touched && formUsers.username.$invalid}',
						)) }}
    <div class="validation-error" ng-messages="formUsers.username.$error">
        {!! getValidationMessage()!!}
	    					{!! getValidationMessage('minlength')!!}
	    					{!! getValidationMessage('maxlength')!!}
	    					{!! getValidationMessage('pattern')!!}
    </div>
</fieldset>
@if($record)
<fieldset class="form-group">
    <?php $password_required = true; 
	if($record)
		$password_required = false;
	?>
    {{ Form::label('password', 'Mật khẩu') }}
	@if(!$record)
	    <span class="text-red">
	        *
	    </span>
	@endif
	{{ Form::password('password', $attributes = array('class'=>'form-control', 'placeholder' => '',
		'ng-model'=>'password',
		'required'=> $password_required, 
		'ng-minlength' => '6',
		'ng-maxlength' => '20',
		'ng-class'=>'{"has-error": formUsers.password.$touched && formUsers.password.$invalid}',
	)) }}
    <div class="validation-error" ng-messages="formUsers.password.$error">
        {!! getValidationMessage()!!}
		{!! getValidationMessage('minlength')!!}
		{!! getValidationMessage('maxlength')!!}
    </div>
</fieldset>
@endif
<fieldset class="form-group">
    @php 
		$readonly = '';
			if(!checkRole(getUserGrade(4)))
			$readonly = 'readonly="true"';
		if($record)
		{
			$readonly = 'readonly="true"';
		}
	@endphp
    {{ Form::label('email', getphrase('email')) }}
    <span class="text-red">
        *
    </span>
    {{ Form::email('email', $value = null, $attributes = array('class'=>'form-control', 'placeholder' => '',
		'ng-model'=>'email',
		'required'=> 'true', 
		'ng-class'=>'{"has-error": formUsers.email.$touched && formUsers.email.$invalid}',
	 $readonly)) }}
    <div class="validation-error" ng-messages="formUsers.email.$error">
        {!! getValidationMessage()!!}
	    {!! getValidationMessage('email')!!}
    </div>
</fieldset>
@if(!$record)
<fieldset class="form-group">
    {{ Form::label('password', 'Mật khẩu') }}
    <span class="text-red">
        *
    </span>
    {{ Form::password('password', $attributes = array('class'=>'form-control instruction-call',
								'placeholder' => '',
								'ng-model'=>'password',
								'required'=> 'true', 
								'ng-class'=>'{"has-error": formUsers.password.$touched && formUsers.password.$invalid}',
								'ng-minlength' => 5
							)) }}
    <div class="validation-error" ng-messages="formUsers.password.$error">
        {!! getValidationMessage()!!}
		{!! getValidationMessage('password')!!}
    </div>
</fieldset>
<fieldset class="form-group">
    {{ Form::label('confirm_password', 'Nhập lại mật khẩu') }}
    <span class="text-red">
        *
    </span>
    {{ Form::password('password_confirmation', $attributes = array('class'=>'form-control instruction-call',
		'placeholder' => '',
		'ng-model'=>'password_confirmation',
		'required'=> 'true', 
		'ng-class'=>'{"has-error": formUsers.password_confirmation.$touched && formUsers.password.$invalid}',
		'ng-minlength' => 5
	)) }}
    <div class="validation-error" ng-messages="formUsers.password_confirmation.$error">
        {!! getValidationMessage()!!}
		{!! getValidationMessage('password')!!}
    </div>
</fieldset>
@endif


<fieldset class="form-group">
    {{ Form::label('phone', 'Số điện thoại') }}
    <span class="text-red">
        *
    </span>
    {{ Form::text('phone', $value = null , $attributes = array('class'=>'form-control', 'placeholder' =>'',
		'ng-model'=>'phone',
		'required'=> 'true', 
		'ng-pattern' => getRegexPattern("phone"),
		'ng-class'=>'{"has-error": formUsers.phone.$touched && formUsers.phone.$invalid}',
	)) }}
    <div class="validation-error" ng-messages="formUsers.phone.$error">
        {!! getValidationMessage()!!}
		{!! getValidationMessage('phone')!!}
		{!! getValidationMessage('maxlength')!!}
    </div>
</fieldset>

<div class="buttons text-center">
    <button class="btn btn-lg btn-success button" ng-disabled="!formUsers.$valid">
        {{ $button_name }}
    </button>
</div>