@extends('admin.layouts.sitelayout')
@section('content')
<section class="sptb">
    <div class="container customerpage">
        <div class="row">
            <div class="single-page">
                <div class="col-lg-5 col-xl-4 col-md-6 d-block mx-auto">
                    <div class="wrapper wrapper2">
                        {{ Form::model($record, 
						array('url' => ['users/change-password', $record->slug], 
						'method'=>'patch', 'novalidate'=>'', 'class'=>"card-body", 'name'=>"changePassword")) }}
                        <h3>
                            Đổi mật khẩu
                        </h3>
                        
                        <div class="mail">
                            {{ Form::password('old_password', $attributes = array('class'=>'form-control', 'placeholder' => '',
                                	'ng-model'=>'old_password',
                                	'required'=> 'true', 
                                	'ng-class'=>'{"has-error": changePassword.old_password.$touched && changePassword.old_password.$invalid}',
                                	'ng-minlength' => 5
                                )) }}
                            <div class="validation-error" ng-messages="changePassword.old_password.$error">
                                {!! getValidationMessage()!!}
                                	{!! getValidationMessage('password')!!}
                            </div>
                            {{ Form::label('old_password', 'Mật khẩu cũ') }}
                        </div>
                        <div class="mail">
                            {{ Form::password('password', $attributes = array('class'=>'form-control', 'placeholder' => '',
						'ng-model'=>'password',
							'required'=> 'true', 
							'ng-class'=>'{"has-error": changePassword.password.$touched && changePassword.password.$invalid}',
							'ng-minlength' => 5
						)) }}
                            <div class="validation-error" ng-messages="changePassword.password.$error">
                                {!! getValidationMessage()!!}
									{!! getValidationMessage('password')!!}
                            </div>
                            {{ Form::label('password', 'Mật khẩu mới') }}
                        </div>
                        <div class="passwd">
                            {{ Form::password('password_confirmation', $attributes = array('class'=>'form-control', 'placeholder' => '',
								'ng-model'=>'password_confirmation',
								'required'=> 'true', 
								'ng-class'=>'{"has-error": changePassword.password_confirmation.$touched && changePassword.password_confirmation.$invalid}',
								'compare-to' =>"password",
								'ng-minlength' => 5
						)) }}
                            <div class="validation-error" ng-messages="changePassword.password_confirmation.$error">
                                {!! getValidationMessage()!!}
								{!! getValidationMessage('password')!!}
								{!! getValidationMessage('confirmPassword')!!}
                            </div>
                            {{ Form::label('password_confirmation', 'Nhập lại mật khẩu') }}
                        </div>
                        <div class="submit">
                            <button class="btn btn-primary btn-block" ng-disabled="!changePassword.$valid" style="margin: 0 auto; display: block;" type="submit">
                                Cập nhật
                            </button>
                        </div>
                        {!! Form::close() !!}
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{--
<hr class="divider">
    <div class="card-body">
        <div class="text-center">
            <div class="btn-group">
                <a class="btn btn-icon mr-2 brround" href="https://www.facebook.com/">
                    <span class="fa fa-facebook">
                    </span>
                </a>
            </div>
            <div class="btn-group">
                <a class="btn mr-2 btn-icon brround" href="https://www.google.com/gmail/">
                    <span class="fa fa-google">
                    </span>
                </a>
            </div>
            <div class="btn-group">
                <a class="btn btn-icon brround" href="https://twitter.com/">
                    <span class="fa fa-twitter">
                    </span>
                </a>
            </div>
        </div>
    </div>
    --}}
</hr>
@stop
@section('footer_scripts')
  @include('admin.common.validations')
@stop
