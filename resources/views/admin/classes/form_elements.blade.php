 

					 <fieldset class="form-group">

						

						{{ Form::label('name', 'Tên Lớp') }}

						<span class="text-red">*</span>

						{{ Form::text('name', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'Vd: N32019K12',

							'ng-model'=>'name',

							'required'=> 'true', 

							'ng-pattern' => '',

							'ng-minlength' => '2',

							'ng-maxlength' => '20',

							'ng-class'=>'{"has-error": formClasses.name.$touched && formClasses.name.$invalid}',



						)) }}

						<div class="validation-error" ng-messages="formClasses.name.$error" >

	    					{!! getValidationMessage()!!}

	    					{!! getValidationMessage('minlength')!!}

	    					{!! getValidationMessage('maxlength')!!}

	    					{!! getValidationMessage('pattern')!!}

						</div>

					</fieldset>

					<?php 
						$teacher_id = array(''=>'--Chọn giáo viên--') + $teacher_id;
					 ?>

					<fieldset class="form-group ">
						{{ Form::label('teacher_id', 'Giáo viên') }} <span class="text-red">*</span>

						{{Form::select('teacher_id', $teacher_id, null, ['class'=>'form-control', "id"=>"teacher_id"])}}
					</fieldset>


					<div class="buttons text-center">

						<button class="btn btn-lg btn-success button" 

						ng-disabled='!formClasses.$valid'>{{ $button_name }}</button>

					</div>