@extends('admin.layouts.sitelayout')
@section('content')

<section class="sptb">
    <div class="container customerpage">
        <div class="row">
            <div class="single-page">
                <div class="col-lg-5 col-xl-4 col-md-6 d-block mx-auto">
                    <div class="wrapper wrapper2">
					    @include('admin.errors.errors')
                        {!! Form::open(array('url' => URL_USERS_LOGIN, 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'', 'class'=>"card-body", 'name'=>"loginForm", 'onsubmit'=>"showLoadingSpinner();")) !!}
                            <h3>Đăng nhập</h3>
                            <div class="mail">
                                {{ Form::text('email', $value = null , $attributes = array('class'=>'form-control', 'autocomplete'=>'off',
                                  'ng-model'=>'email',
                                  'required'=> 'true',
                                  'id'=> 'email',
                                  'placeholder' => '',
                                  'ng-class'=>'{"has-error": loginForm.email.$touched && loginForm.email.$invalid}',
                                  )) }}
                                  <div class="validation-error" ng-messages="loginForm.email.$error" >
                                  {!! getValidationMessage()!!}
                                  {!! getValidationMessage('email')!!}
                                </div>
                                <label>Email hoặc tên đăng nhập</label>
                            </div>
                            <div class="passwd">
                                {{ Form::password('password', $attributes = array('class'=>'form-control instruction-call', 'autocomplete'=>'off',
                                  'placeholder' => '',
                                  'ng-model'=>'registration.password',
                                  'required'=> 'true', 
                                  'id'=> 'password', 
                                  'ng-class'=>'{"has-error": loginForm.password.$touched && loginForm.password.$invalid}',
                                  'ng-minlength' => 6
                                  )) }}
                                  <div class="validation-error" ng-messages="loginForm.password.$error" >
                                    {!! getValidationMessage()!!}
                                    {!! getValidationMessage('password')!!}
                                  </div>
                                <label>Mật khẩu</label>
                            </div>
							<div>
								<!-- Trường CAPTCHA -->
								{!! NoCaptcha::renderJs() !!}
								{!! NoCaptcha::display() !!}
							</div>

							<div class="submit">
                                <button type="submit" class="btn btn-primary btn-block" style="margin: 0 auto; display: block;" ng-disabled='!loginForm.$valid'>Đăng nhập</button>
                             </div>
                            <p class="mb-2"><a href="{{URL_USERS_FORGOT_PASSWORD}}" class="text-primary ml-1">Quên mật khẩu</a></p>
                            <p class="text-dark mb-0">Bạn chưa có tài khoản?<a href="{{URL_USERS_REGISTER}}" class="text-primary ml-1">Đăng ký</a></p>
                        </form>
                        {{-- <hr class="divider">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="btn-group">
                                    <a href="https://www.facebook.com/" class="btn btn-icon mr-2 brround">
                                        <span class="fa fa-facebook"></span>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a href="https://www.google.com/gmail/" class="btn  mr-2 btn-icon brround">
                                        <span class="fa fa-google"></span>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a href="https://twitter.com/" class="btn  btn-icon brround">
                                        <span class="fa fa-twitter"></span>
                                    </a>
                                </div>
                            </div>
                        </div> --}}
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