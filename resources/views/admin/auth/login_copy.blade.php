@extends('admin.layouts.sitelayout')
@section('content')


<div class="content-wrapper d-flex align-items-center auth px-0">
  <div class="row w-100 mx-0">
    <div class="col-lg-5 mx-auto">
      <div class="auth-form-light text-left py-5 px-4 px-sm-5">
        <div class="brand-logo" style="text-align: center;">
          <img src="/public/uploads/settings/logo-elearning.png" alt="logo">
        </div>
        <h4 style="text-align: center; color: #448afd">Xin chào! Bắt đầu ngay nào</h4>
        <!-- <h6 class="font-weight-light"></h6> -->
        
        {!! Form::open(array('url' => URL_USERS_LOGIN, 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'', 'class'=>"pt-3", 'name'=>"loginForm")) !!}
          <div class="form-group">
            {{ Form::text('email', $value = null , $attributes = array('class'=>'form-control', 'autocomplete'=>'off',
                      'ng-model'=>'email',
                      'required'=> 'true',
                      'id'=> 'email',
                      'placeholder' => 'Tên đăng nhập hoặc email',
                      'ng-class'=>'{"has-error": loginForm.email.$touched && loginForm.email.$invalid}',
                      )) }}
            <div class="validation-error" ng-messages="loginForm.email.$error" >
              {!! getValidationMessage()!!}
              {!! getValidationMessage('email')!!}
            </div>
          </div>
          <div class="form-group">
            {{ Form::password('password', $attributes = array('class'=>'form-control instruction-call', 'autocomplete'=>'off',
                      'placeholder' => 'Mật khẩu',
                      'ng-model'=>'registration.password',
                      'required'=> 'true', 
                      'id'=> 'password', 
                      'ng-class'=>'{"has-error": loginForm.password.$touched && loginForm.password.$invalid}',
                      'ng-minlength' => 5
                      )) }}
                      <div class="validation-error" ng-messages="loginForm.password.$error" >
                        {!! getValidationMessage()!!}
                        {!! getValidationMessage('password')!!}
                      </div>
          </div>
          <div class="mt-3">
            <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" ng-disabled='!loginForm.$valid' style="margin: 0 auto; display: block;">Đăng nhập</button>
            <!-- <a class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" href="../../index.html">SIGN IN</a> -->
          </div>
          <div class="my-2 d-flex justify-content-between align-items-center">
            <div class="form-check">
              <a href="{{URL_USERS_REGISTER}}" class="auth-link text-black">Tạo tài khoản</a>
            </div>
            <a href="javascript:void(0);" data-toggle="modal" data-target="#myModal" >Quên mật khẩu?</a>
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