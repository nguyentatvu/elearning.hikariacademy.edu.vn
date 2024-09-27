<div class="row">
    
    <div class="col-sm-6 col-md-6">
        <div class="form-group">
            <?php
                 $username_value = null;
                 if($record){
                    $readonly = 'readonly="true"';
                    $username_value = $record->username;
                 }
            ?>
            {{ Form::label('username', 'Tên đăng nhập') }}
            {{ Form::text('username', $value = $username_value , $attributes = array('class'=>'form-control', 'placeholder' => '',
            'ng-model'=>'username',
            'required'=> 'true', 
            'readonly'=>true,
            'ng-minlength' => '2',
            'ng-maxlength' => '20',
            'ng-class'=>'{"has-error": formUsers.username.$touched && formUsers.username.$invalid}',
            )) }}
        </div>
    </div>
    <div class="col-sm-6 col-md-6">
        <div class="form-group">
            <?php 
            $readonly = '';
            if(!checkRole(getUserGrade(4)))
                $readonly = 'readonly="true"';
            if($record)
            {
                $readonly = 'readonly="true"';
            }
            ?>
            {{ Form::label('email', 'Email') }}
            {{ Form::email('email', $value = null, $attributes = array('class'=>'form-control', 'placeholder' => '',
            'ng-model'=>'email',
            'required'=> 'true', 
            'ng-class'=>'{"has-error": formUsers.email.$touched && formUsers.email.$invalid}',
            $readonly)) }}
            <div class="validation-error" ng-messages="formUsers.email.$error" >
                {!! getValidationMessage()!!}
                {!! getValidationMessage('email')!!}
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-6">
        <div class="form-group">
            {{ Form::label('name', 'Họ tên') }}
            <span class="text-red">*</span>
            {{ Form::text('name', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '',
            'ng-model'=>'name',
            'required'=> 'true', 
            'ng-pattern' => '',
            'ng-minlength' => '2',
            'ng-maxlength' => '200',
            'ng-class'=>'{"has-error": formUsers.name.$touched && formUsers.name.$invalid}',
            )) }}
            <div class="validation-error" ng-messages="formUsers.name.$error" >
                {!! getValidationMessage()!!}
                {!! getValidationMessage('minlength')!!}
                {!! getValidationMessage('maxlength')!!}
                {!! getValidationMessage('pattern')!!}
            </div>
            {{-- <input class="form-control" placeholder="First Name" type="text"> --}}
            </input>
        </div>
    </div>
    <div class="col-sm-6 col-md-6">
        <div class="form-group">
            {{ Form::label('phone', getphrase('phone')) }}
            {{ Form::text('phone', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '',
            'ng-model'=>'phone',
            'ng-pattern' => getRegexPattern("phone"),
            'ng-class'=>'{"has-error": formUsers.phone.$touched && formUsers.phone.$invalid}',
            )) }}
            <div class="validation-error" ng-messages="formUsers.phone.$error" >
                {!! getValidationMessage()!!}
                {!! getValidationMessage('phone')!!}
                {!! getValidationMessage('maxlength')!!}
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('address', 'Địa chỉ') }}
            {{ Form::textarea('address', $value = null , $attributes = array('class'=>'form-control','rows'=>3, 'cols'=>'15', 'placeholder' => '',
            'ng-model'=>'address',
            )) }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-0">
            {{ Form::label('image', 'Hình đại diện') }}
            <div class="custom-file">
                {!! Form::file('image', array('id'=>'image_input', 'accept'=>'')) !!}
            </div>
        </div>
    </div>
    <?php if(isset($record) && $record) { 
        if($record->image!='') {
            ?>
            <div class="col-md-6">
                <img src="{{ getProfilePath($record->image) }}" />
            </div>
    <?php } } ?>
</div>