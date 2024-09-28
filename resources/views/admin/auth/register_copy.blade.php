@extends('admin.layouts.sitelayout')
@section('content')


<div class="content-wrapper d-flex align-items-center auth px-0">
  <div class="row w-100 mx-0">
    <div class="col-lg-5 mx-auto">
      <div class="auth-form-light text-left py-5 px-4 px-sm-5">
        <div class="brand-logo" style="text-align: center;">
          <img src="/public/uploads/settings/logo-elearning.png" alt="logo">
        </div>
        <h4 style="text-align: center; color: #448afd">Xin chào! Đăng ký ngay nào</h4>
        
        @include('admin.errors.errors')
        {!! Form::open(array('url' => URL_USERS_REGISTER, 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'', 'class'=>"pt-3", 'name'=>"registrationForm")) !!}
        
          <div class="form-group">
            {{ Form::text('name', $value = null , $attributes = array('class'=>'form-control', 'autocomplete'=>'off',
                                        'placeholder' => 'Họ tên',
                                        'ng-model'=>'name',
                                        'ng-pattern' => '',
                                        'required'=> 'true', 
                                        'ng-class'=>'{"has-error": registrationForm.name.$touched && registrationForm.name.$invalid}',
                                        'ng-minlength' => '4',
                                        )) }}
            <div class="validation-error" ng-messages="registrationForm.name.$error" >
                {!! getValidationMessage()!!}
                {!! getValidationMessage('minlength')!!}
                {!! getValidationMessage('pattern')!!}
            </div>
          </div>
          <div class="form-group">
            {{ Form::text('username', $value = null , $attributes = array('class'=>'form-control', 'autocomplete'=>'off',
                                        'placeholder' => 'Tên đăng nhập',
                                        'ng-model'=>'username',
                                        'required'=> 'true', 
                                        'ng-class'=>'{"has-error": registrationForm.username.$touched && registrationForm.username.$invalid}',
                                        'ng-minlength' => '4',
                                        )) }}
            <div class="validation-error" ng-messages="registrationForm.username.$error" >
                {!! getValidationMessage()!!}
                {!! getValidationMessage('minlength')!!}
                {!! getValidationMessage('pattern')!!}
            </div>
          </div>
        
          <div class="form-group">
            {{ Form::email('email', $value = null , $attributes = array('class'=>'form-control', 'autocomplete'=>'off',
                            'placeholder' => 'Email',
                            'ng-model'=>'email',
                            'required'=> 'true', 
                            'ng-class'=>'{"has-error": registrationForm.email.$touched && registrationForm.email.$invalid}',
                            )) }}
            <div class="validation-error" ng-messages="registrationForm.email.$error" >
                {!! getValidationMessage()!!}
                {!! getValidationMessage('email')!!}
            </div>
          </div>

          <div class="form-group">
            {{ Form::number('phone', $value = null , $attributes = array('class'=>'form-control', 'autocomplete'=>'off',
                                        'placeholder' => 'Số điện thoại',
                                        'ng-model'=>'phone',
                                        'required'=> 'true', 
                                        'ng-class'=>'{"has-error": registrationForm.phone.$touched && registrationForm.phone.$invalid}',
                                        'ng-pattern' => '',
                                        'ng-minlength' => '10',
                                        'ng-maxlength' => '11',
                                        )) }}
            <div class="validation-error" ng-messages="registrationForm.phone.$error" >
                {!! getValidationMessage()!!}
                {!! getValidationMessage('phone')!!}
                {!! getValidationMessage('minlength')!!}
            </div>
          </div>

          <div class="mt-3">
            <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" ng-disabled='!registrationForm.$valid'>Đăng ký ngay</button>
          </div>
          <div class="my-2 d-flex justify-content-between align-items-center">
            <div class="form-check">
              <a href="{{URL_USERS_LOGIN}}" class="auth-link text-black">Bạn đã có tài khoản?</a>
            </div>
            
          </div>
          
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    {!! Form::open(array('url' => URL_USERS_FORGOT_PASSWORD, 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'', 'class'=>"pt-3", 'name'=>"passwordForm")) !!}
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Quên mật khẩu</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="recipient-name" class="col-form-label">Nhập địa chỉ email</label>
          {{ Form::email('email', $value = null , $attributes = array('class'=>'form-control',
          'ng-model'=>'email',
          'required'=> 'true',
          'placeholder' => '',
          'ng-class'=>'{"has-error": passwordForm.email.$touched && passwordForm.email.$invalid}',
          )) }}
          <div class="validation-error" ng-messages="passwordForm.email.$error" >
            {!! getValidationMessage()!!}
            {!! getValidationMessage('email')!!}
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="pull-right">
          <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-primary" ng-disabled='!passwordForm.$valid'>Gửi</button>
        </div>
      </div>
    </div>
    {!! Form::close() !!}
  </div>
</div>

@stop
@section('footer_scripts')
@include('admin.common.validations')

@stop