<div class="row">
    <div class="col-md-6">
        <fieldset class="form-group">
            {{ Form::label('title', 'Tên bài luyện viết') }}
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
                    'ng-class' => '{"has-error": formHandwriting.title.$touched && formHandwriting.title.$invalid}',
                ]
            ) }}
            <div class="validation-error" ng-messages="formHandwriting.title.$error">
                {!! getValidationMessage() !!}
            </div>
        </fieldset>
    </div>
    <div class="col-md-6">
        <fieldset class="form-group">
            {{ Form::label('file_import', 'Nhập file excel bài luyện viết') }}
            {{ Form::file('file_import', $value = null, ['class' => 'form-control', 'placeholder' => '', 'accept' => '.xls,.xlsx']) }}
        </fieldset>
    </div>
</div>
@if (!$record)
    <div class="row">
        <div class="col-md-6">
            <?php $type = array(1 => 'Hiragana', 2 => 'Kanji');?>
            <fieldset class="form-group">
                {{ Form::label('type', 'Loại bài luyện viết') }}
                <span class="text-red">*</span>
                {{ Form::select('type', $type, $value = null, [
                        'class' => 'form-control',
                        'placeholder' => '',
                        'required' => 'true',
                        'ng-model'=>'type',
                        'ng-pattern' => '',
                        'ng-class'=>'{"has-error": formHandwriting.type.$touched && formHandwriting.type.$invalid}',
                    ])
                }}
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
