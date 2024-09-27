@extends('admin.layouts.sitelayout')
@section('content')

<section class="sptb">

  <div class="container customerpage">

    <div class="row">

      <div class="single-page">

        <div class="col-lg-5 col-xl-4 col-md-6 d-block mx-auto">

          <div class="wrapper wrapper2">

            @include('errors.errors')
	
			{!! Form::open(array('url' => URL_USERS_REGISTER, 'method' => 'POST', 'name'=>'formLanguage ', 'novalidate'=>'', 'class'=>"card-body", 'name'=>"registrationForm", 'onsubmit'=>"showLoadingSpinner();")) !!}

              <h3>Đăng ký</h3>

              <div class="name">

                {{ Form::text('name', $value = null , $attributes = array('class'=>'form-control', 'autocomplete'=>'off',

                  'placeholder' => '',

                  'ng-model'=>'name',

                  'ng-pattern' => '',

                  'required'=> 'true', 

                  'ng-class'=>'{"has-error": registrationForm.name.$touched && registrationForm.name.$invalid}',

                  'ng-minlength' => '4',

                )) }}

                <div class="validation-error" ng-messages="registrationForm.name.$error" >

                {!! getValidationMessage()!!}

                {!! getValidationMessage('minlength')!!}

              </div>

                <label>Họ tên</label>

              </div>

              {{-- <div class="mail">

                {{ Form::text('username', $value = null , $attributes = array('class'=>'form-control', 'autocomplete'=>'off',

                    'placeholder' => '',

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

                <label>Tên đăng nhập</label>

              </div> --}}



              <div class="mail">

                {{ Form::email('email', $value = null , $attributes = array('class'=>'form-control', 'autocomplete'=>'off',

                            'placeholder' => '',

                            'ng-model'=>'email',

                            'required'=> 'true', 

                            'ng-class'=>'{"has-error": registrationForm.email.$touched && registrationForm.email.$invalid}',

                            )) }}

                <div class="validation-error" ng-messages="registrationForm.email.$error" >

                {!! getValidationMessage()!!}

                {!! getValidationMessage('email')!!}

            </div>

                <label>Email</label>

              </div>



              <div class="passwd">

                {{ Form::number('phone', $value = null , $attributes = array('class'=>'form-control', 'autocomplete'=>'off',

                    'placeholder' => '',

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

                    {!! getValidationMessage('maxlength')!!}

                </div>

                <label>Số điện thoại</label>

              </div>
			  <div>
			      <!-- Trường CAPTCHA -->
				{!! NoCaptcha::renderJs() !!}
				{!! NoCaptcha::display() !!}
			  </div>

              <div class="submit">

                <button type="submit" class="btn btn-primary btn-block font-weight-medium auth-form-btn" ng-disabled='!registrationForm.$valid'>Đăng ký ngay</button>



                {{-- <a  class="btn btn-primary btn-block" href="index.html">Register</a> --}}

              </div>

              <p class="text-dark mb-0">Bạn đã có tài khoản?<a href="{{URL_USERS_LOGIN}}" class="text-primary ml-1">Đăng nhập</a></p>

            </form>

            {{-- <hr class="divider"> --}}

            {{-- <div class="card-body">

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

@include('common.validations')

@stop