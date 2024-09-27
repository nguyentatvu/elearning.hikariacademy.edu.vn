<div class="row">
    <div class="col-md-6">
        <fieldset class="form-group">
            {{ Form::label('name', 'Tên Flashcard') }}
            <span class="text-red">
                *
            </span>
            {{ Form::text('name', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '',
                        'ng-model'=>'name',
                        'required'=> 'true', 
                        'ng-pattern' => '',
                        'ng-class'=>'{"has-error": formFlashcard.name.$touched && formFlashcard.name.$invalid}',
                    )) }}
            <div class="validation-error" ng-messages="formFlashcard.name.$error">
                {!! getValidationMessage()!!}
            </div>
        </fieldset>
    </div>
    <div class="col-md-6">
        <fieldset class="form-group">
            {{ Form::label('file_import', 'Nhập file excel Flashcard') }}
            {{ Form::file('file_import', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '',
                    )) }}
        </fieldset>
    </div>
</div>
@if(!$record)
<div class="buttons text-center">
    <button class="btn btn-lg btn-success button" ng-disabled="!formFlashcard.$valid">
        Tạo mới
    </button>
</div>
@else
<div class="buttons text-center">
    <button class="btn btn-lg btn-success button" ng-disabled="!formFlashcard.$valid">
        Cập nhật
    </button>
</div>
@endif
</input>