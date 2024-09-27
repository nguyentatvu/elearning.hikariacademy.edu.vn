<input name="flashcard_id" type="hidden" value="{{ $flashcard->id }}">
	<div class="row">
        <div class="col-md-6">
            <fieldset class="form-group">
                {{ Form::label('m1tuvung', 'Từ vựng (Mặt 1)') }}
                <span class="text-red">
                    *
                </span>
				{{ Form::text('m1tuvung', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '',
							'ng-model'=>'m1tuvung',
							'required'=> 'true', 
							'ng-pattern' => '',
							'ng-class'=>'{"has-error": formFlashcard.m1tuvung.$touched && formFlashcard.m1tuvung.$invalid}',
						)) }}
                <div class="validation-error" ng-messages="formFlashcard.m1tuvung.$error">
                    {!! getValidationMessage()!!}
                </div>
            </fieldset>
        </div>
        <div class="col-md-6">
            <fieldset class="form-group">
                {{ Form::label('m1vidu', 'Ví dụ (Mặt 1)') }} 
				{{ Form::text('m1vidu', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '')) }}
                <div class="validation-error" ng-messages="formFlashcard.m1vidu.$error">
                    {!! getValidationMessage()!!}
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <fieldset class="form-group">
                {{ Form::label('m2cachdoc', 'Cách đọc') }} 
				{{ Form::text('m2cachdoc', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '')) }}
                <div class="validation-error" ng-messages="formFlashcard.m2cachdoc.$error">
                    {!! getValidationMessage()!!}
                </div>
            </fieldset>
        </div>
        <div class="col-md-6">
            <fieldset class="form-group">
                {{ Form::label('m2amhanviet', 'Âm Hán Việt') }} 
				{{ Form::text('m2amhanviet', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '')) }}
                <div class="validation-error" ng-messages="formFlashcard.m2amhanviet.$error">
                    {!! getValidationMessage()!!}
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <fieldset class="form-group">
                {{ Form::label('m2ynghia', 'Ý nghĩa') }}
                 <span class="text-red">
                    *
                </span> 
				{{ Form::text('m2ynghia', $value = null , 
						$attributes = array('class'=>'form-control', 
							'placeholder' => '',
							'ng-model'=>'m2ynghia', 
							'required'=> 'true',
							'ng-class'=>'{"has-error": formFlashcard.m2ynghia.$touched && formFlashcard.m2ynghia.$invalid}',
							)) }}
                <div class="validation-error" ng-messages="formFlashcard.m2ynghia.$error">
                    {!! getValidationMessage()!!}
                </div>
            </fieldset>
        </div>
        <div class="col-md-6">
            <fieldset class="form-group">
                {{ Form::label('m2vidu', 'Ví dụ') }} 
				{{ Form::text('m2vidu', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '')) }}
                <div class="validation-error" ng-messages="formFlashcard.m2vidu.$error">
                    {!! getValidationMessage()!!}
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <fieldset class="form-group">
                {{ Form::label('stt', 'STT') }} 
                {{ Form::text('stt', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '')) }}
                <div class="validation-error" ng-messages="formFlashcard.stt.$error">
                    {!! getValidationMessage()!!}
                </div>
            </fieldset>
        </div>
        <div class="col-md-6">
            <fieldset class="form-group col-md-6">
                {{ Form::label('mp3', 'File âm thanh .mp3') }}
                <input accept=".mp3" class="form-control" id="mp3" name="mp3" type="file">
                    <div class="validation-error" ng-messages="formFlashcard.mp3.$error">
                        {!! getValidationMessage('mp3')!!}
                    </div>
                </input>
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