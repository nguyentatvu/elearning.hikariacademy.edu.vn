<div class="row">
    <div class="col-md-6">
        <fieldset class="form-group">
            {{ Form::label('title', 'Tên bài luyện phát âm') }}
            <span class="text-red">
                *
            </span>
            {{ Form::text(
                'title',
                $value = null,
                $attributes = [
                    'class' => 'form-control',
                    'placeholder' => '',
                    'ng-model' => 'title',
                    'required' => 'true',
                    'ng-pattern' => '',
                    'ng-class' => '{"has-error": formPronunciationAssessment.title.$touched && formPronunciationAssessment.title.$invalid}',
                ]
            ) }}
            <div class="validation-error" ng-messages="formPronunciationAssessment.title.$error">
                {!! getValidationMessage() !!}
            </div>
        </fieldset>
    </div>
    <div class="col-md-6">
        <fieldset class="form-group">
            {{ Form::label('file_import', 'Nhập file excel các bài tập của bài luyện phát âm') }}
            {{ Form::file('file_import', ['class' => 'form-control', 'accept' => '.xls,.xlsx']) }}
        </fieldset>
    </div>
</div>

@if (!$record)
    <div class="buttons text-center">
        <button class="btn btn-lg btn-success button" ng-disabled="!formPronunciationAssessment.$valid">
            Tạo mới
        </button>
    </div>
@else
    <div class="buttons text-center">
        <button class="btn btn-lg btn-success button" ng-disabled="!formPronunciationAssessment.$valid">
            Cập nhật
        </button>
    </div>
@endif
</input>
