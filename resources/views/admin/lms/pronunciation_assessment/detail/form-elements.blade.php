<input name="pronunciation_id" type="hidden" value="{{ $pronunciation_assessment->id }}">

<div class="row">
    <div class="col-md-6">
        <fieldset class="form-group">
            {{ Form::label('text', 'Câu luyện phát âm') }}
            <span class="text-red">
                *
            </span>
            {{ Form::text(
                'text',
                $value = null,
                $attributes = [
                    'class' => 'form-control',
                    'placeholder' => '',
                    'ng-model' => 'text',
                    'required' => 'true',
                    'ng-pattern' => '',
                    'ng-class' => '{"has-error": formPronunciationAssessment.text.$touched && formPronunciationAssessment.text.$invalid}',
                ]
            ) }}
            <div class="validation-error" ng-messages="formPronunciationAssessment.text.$error">
                {!! getValidationMessage() !!}
            </div>
        </fieldset>
    </div>
    <div class="col-md-6">
        <fieldset class="form-group">
            {{ Form::label('audio', 'Audio') }}
            {{ Form::file(
                'audio',
                $attributes = [
                    'class' => 'form-control',
                    'accept' => '.mp3, .wav',
                    'ng-class' => '{"has-error": formPronunciationAssessment.audio.$touched && formPronunciationAssessment.audio.$invalid}',
                ]
            ) }}
            <div class="validation-error" ng-messages="formPronunciationAssessment.audio.$error">
                {!! getValidationMessage() !!}
            </div>
        </fieldset>
    </div>
</div>

@if (!$record)
    <div class="buttons text-center">
        <button id="pronunciation_submit_btn" class="btn btn-lg btn-success button" ng-disabled="!formPronunciationAssessment.$valid">
            Tạo mới
        </button>
    </div>
@else
    <div class="buttons text-center">
        <button id="pronunciation_submit_btn" class="btn btn-lg btn-success button" ng-disabled="!formPronunciationAssessment.$valid">
            Cập nhật
        </button>
    </div>
@endif
</input>
