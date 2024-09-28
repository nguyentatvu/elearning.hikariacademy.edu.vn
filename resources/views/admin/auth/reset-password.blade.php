@extends('admin.layouts.sitelayout')
@section('content')

<section class="sptb">
    <div class="container customerpage">
        <div class="row">
            <div class="single-page">
                <div class="col-lg-5 col-xl-4 col-md-6 d-block mx-auto">
                    <div class="wrapper wrapper2">
					    @include('admin.errors.errors')
						 {!! Form::open(array('url' => URL_USERS_FORGOT_PASSWORD, 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'', 'class'=>"card-body", 'name'=>"passwordForm", 'onsubmit'=>"showLoadingSpinner();")) !!}
                            <h3>Đặt lại mật khẩu</h3>
                            <div class="mail">
							  
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
							  <label>Nhập địa chỉ email</label>
							</div>
							<div>
								<!-- Trường CAPTCHA -->
								{!! NoCaptcha::renderJs() !!}
								{!! NoCaptcha::display() !!}
							</div>
							<div class="submit">
                                <button type="submit" class="btn btn-primary btn-block" style="margin: 0 auto; display: block;" ng-disabled='!passwordForm.$valid'>Gửi</button>
                             </div>
                            <p class="text-dark mb-0">Bạn đã có tài khoản?<a href="{{URL_USERS_LOGIN}}" class="text-primary ml-1">Đăng nhập</a></p>
                            <p class="text-dark mb-0">Bạn chưa có tài khoản?<a href="{{URL_USERS_REGISTER}}" class="text-primary ml-1">Đăng ký</a></p>
                        </form>
 
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@stop
@section('footer_scripts')
@include('admin.common.validations')
@stop