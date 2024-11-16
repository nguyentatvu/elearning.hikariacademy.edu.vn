<input name="handwriting_id" type="hidden" value="{{ $handwriting->id }}">
<input name="type" type="hidden" value="{{ $handwriting->type }}">

@if ($handwriting->type == \App\JapaneseWritingPractice::HIRAGANA)
    <div class="row">
        <div class="col-md-6">
            <fieldset class="form-group">
                {{ Form::label('hiragana', 'Hiragana/Katakana/Kanji') }}
                <span class="text-red">
                    *
                </span>
                {{ Form::text(
                    'character',
                    $value = null,
                    $attributes = [
                        'class' => 'form-control',
                        'placeholder' => '',
                        'ng-model' => 'character',
                        'required' => 'true',
                        'ng-pattern' => '',
                        'ng-class' => '{"has-error": formHandwriting.character.$touched && formHandwriting.character.$invalid}',
                    ]
                ) }}
                <div class="validation-error" ng-messages="formHandwriting.character.$error">
                    {!! getValidationMessage() !!}
                </div>
            </fieldset>
        </div>
    </div>
@elseif ($handwriting->type == \App\JapaneseWritingPractice::KANJI)
    <div class="row">
        <div class="col-md-6">
            <fieldset class="form-group">
                {{ Form::label('full_word', 'Từ') }}
                <span class="text-red">
                    *
                </span>
                {{ Form::text(
                    'full_word',
                    $value = null,
                    $attributes = [
                        'class' => 'form-control',
                        'placeholder' => '',
                        'ng-model' => 'full_word',
                        'required' => 'true',
                        'ng-pattern' => '',
                        'ng-class' => '{"has-error": formHandwriting.full_word.$touched && formHandwriting.full_word.$invalid}',
                    ]
                ) }}
                <div class="validation-error" ng-messages="formHandwriting.full_word.$error">
                    {!! getValidationMessage() !!}
                </div>
            </fieldset>
        </div>
        <div class="col-md-6">
            <fieldset class="form-group">
                {{ Form::label('underlined_word', 'Phần cần viết Hán tự') }}
                <span class="text-red">
                    *
                </span>
                {{ Form::text(
                    'underlined_word',
                    $value = null,
                    $attributes = [
                        'class' => 'form-control',
                        'required' => 'true',
                        'placeholder' => ''
                    ])
                }}
                <div class="validation-error" ng-messages="formHandwriting.underlined_word.$error">
                    {!! getValidationMessage() !!}
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <fieldset class="form-group">
                {{ Form::label('kanji', 'Hán tự') }}
                <span class="text-red">
                    *
                </span>
                {{ Form::text(
                    'kanji',
                    $value = null,
                    $attributes = [
                        'class' => 'form-control',
                        'required' => 'true',
                        'placeholder' => ''
                    ])
                }}
                <div class="validation-error" ng-messages="formHandwriting.kanji.$error">
                    {!! getValidationMessage() !!}
                </div>
            </fieldset>
        </div>
    </div>
@endif

@if (!$record)
    <div class="buttons text-center">
        <button class="btn btn-lg btn-success button" ng-disabled="!formHandwriting.$valid">
            Tạo mới
        </button>
    </div>
@else
    <div class="buttons text-center">
        <button class="btn btn-lg btn-success button" ng-disabled="!formHandwriting.$valid">
            Cập nhật
        </button>
    </div>
@endif
</input>
