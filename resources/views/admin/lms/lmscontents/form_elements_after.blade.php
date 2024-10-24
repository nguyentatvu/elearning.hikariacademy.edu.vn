<input name="series_slug" type="hidden" value="{{$series_slug}}">
<input name="lms_content_after_id" type="hidden" value="{{$lms_content_after->id}}">
    <?php
  $dr_loai = ['0'=>'Menu','8'=>'Menu con', 
                '1'=>'Từ vựng','2'=>'Bài học','3'=>'Bài tập',
                '4'=>'Bài tập toàn bài','5'=>'Bài test',
                '6'=>'Hán tự','7'=>'Bài ôn tập','9'=>'Giới thiệu','10' => 'Flashcard', '11' => 'Luyện viết', '12' => 'Luyện phát âm'];
  $loai_selected = (isset($record->type)) ? $record->type : null;
?>
    <div class="row">
        <fieldset class="form-group col-md-6">
            Thêm vào sau bài học: {{$lms_content_after->bai}}
        </fieldset>
    </div>
    <div class="row">
        <fieldset class="form-group col-md-6">
            {{ Form::label('bai', getphrase('Tên bài')) }}
            <span class="text-red">
                *
            </span>
            {{ Form::text('bai', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '',
            'ng-model'=>'bai',
            'required'=> 'true',
            'ng-class'=>'{"has-error": formLms.bai.$touched && formLms.bai.$invalid}',
            )) }}
            <div class="validation-error" ng-messages="formLms.bai.$error">
                {!! getValidationMessage()!!}
            </div>
        </fieldset>
        <fieldset class="form-group col-md-6">
            {{ Form::label('loai', getphrase('Loại')) }}
            <span class="text-red">
                *
            </span>
            {{ Form::select('loai', $dr_loai, $value = $loai_selected , $attributes = array('class'=>'form-control',
              'ng-model'=>'loai',
              'ng-class'=>'{"has-error": formLms.loai.$touched && formLms.loai.$invalid}',
              )) }}
            <div class="validation-error" ng-messages="formLms.loai.$error">
                {!! getValidationMessage()!!}
            </div>
        </fieldset>
    </div>
    {{-- <div class="row">
        <fieldset class="form-group col-md-12" ng-if="loai=='2' || loai=='6'">
            {{ Form::label('title', 'Mô tả') }}
            <span class="text-red">
            </span>
            {{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '',
              'ng-model'=>'title',
              'ng-pattern' => '',
              'ng-minlength' => '2',
              'ng-maxlength' => '260',
              'ng-class'=>'{"has-error": formLms.title.$touched && formLms.title.$invalid}',
              )) }}
            <div class="validation-error" ng-messages="formLms.title.$error">
                {!! getValidationMessage()!!}
                {!! getValidationMessage('minlength')!!}
                {!! getValidationMessage('maxlength')!!}
                {!! getValidationMessage('pattern')!!}
            </div>
        </fieldset>
    </div> --}}
    <div class="row">
        {{-- <fieldset class="form-group col-md-6">
            {{ Form::label('stt', getphrase('Số thứ tự')) }}
            <span class="text-red">
                *
            </span>
            {{ Form::number('stt', $value = null , $attributes = array('class'=>'form-control',
              'ng-model'=>'stt',
              'required'=> 'true',
              'ng-class'=>'{"has-error": formLms.stt.$touched && formLms.stt.$invalid}',
              )) }}
            <div class="validation-error" ng-messages="formLms.stt.$error">
                {!! getValidationMessage()!!}
            </div>
        </fieldset> --}}

        <fieldset class="form-group col-md-6" ng-if="loai=='3'">
            {{ Form::label('maucau', getphrase('Mẫu câu số')) }}
            <span class="text-red">
            </span>
            {{ Form::number('maucau', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '',
                    'ng-model'=>'maucau',
                    'ng-class'=>'{"has-error": formLms.maucau.$touched && formLms.maucau.$invalid}',
                    )) }}
            <div class="validation-error" ng-messages="formLms.maucau.$error">
                {!! getValidationMessage()!!}
            </div>
        </fieldset>
        <fieldset class="form-group col-md-6" ng-if="loai=='10'">
            {{ Form::label('flashcard', 'Chọn flashcard') }}
            {{ Form::select('flashcard', $flashcard, $value = '' , $attributes = array('class'=>'form-control',
              'ng-model'=>'flashcard',
              'ng-class'=>'{"has-error": formLms.flashcard.$touched && formLms.loai.$invalid}',
              )) }}
            <div class="validation-error" ng-messages="formLms.flashcard.$error">
                {!! getValidationMessage()!!}
            </div>
        </fieldset>
        <fieldset class="form-group col-md-6" ng-if="loai=='11'">
            {{ Form::label('handwriting', 'Chọn bài luyện viết') }}
            {{ Form::select('handwriting', $handwriting, $value = '' , $attributes = array('class'=>'form-control',
              'ng-model'=>'handwriting',
              'ng-class'=>'{"has-error": formLms.handwriting.$touched && formLms.loai.$invalid}',
              )) }}
            <div class="validation-error" ng-messages="formLms.handwriting.$error">
                {!! getValidationMessage()!!}
            </div>
        </fieldset>
        <fieldset class="form-group col-md-6" ng-if="loai=='12'">
            {{ Form::label('pronunciation', 'Chọn bài luyện phát âm') }}
            {{ Form::select('pronunciation', $pronunciation, $value = '' , $attributes = array('class'=>'form-control',
              'ng-model'=>'pronunciation',
              'ng-class'=>'{"has-error": formLms.pronunciation.$touched && formLms.loai.$invalid}',
              )) }}
            <div class="validation-error" ng-messages="formLms.pronunciation.$error">
                {!! getValidationMessage()!!}
            </div>
        </fieldset>
    </div>
    <div class="row">
        {{-- <fieldset class="form-group col-md-6">
            {{ Form::label('image', getphrase('Hình ảnh')) }}
            <input accept=".png,.jpg,.jpeg" class="form-control" id="image_input" name="image" type="file">
            </input>
        </fieldset> --}}
        <fieldset class="form-group col-md-6" ng-if="loai=='1' || loai=='2' || loai=='6' || loai=='9'">
            {{ Form::label('lms_file', getphrase('File video (.mp4)')) }}
            <span class="text-red">
                *
            </span>
            <input accept=".mp4" class="form-control" name="lms_file" type="file">
            </input>
        </fieldset>
        <fieldset class="form-group col-md-6" ng-if="loai=='1' || loai=='2' || loai=='6' || loai=='9'">
            {{ Form::label('video_duration', getphrase('Thời gian')) }}
            <span class="text-red">
                *
            </span>
            {{ Form::text('video_duration', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '',
                'ng-model'=>'video_duration',
                'ng-class'=>'{"has-error": formLms.video_duration.$touched && formLms.video_duration.$invalid}',
                )) }}
        </fieldset>
        {{-- <fieldset class="form-group col-md-6" ng-if="loai=='8'">
            {{ Form::label('lms_excel', getphrase('File Import bài tập ( excel )')) }}
            <span class="text-red">
                *
            </span>
            <input accept=".xls,.xlsx" class="form-control" name="lms_excel" type="file">
            </input>
        </fieldset> --}}
        <fieldset class="form-group col-md-6" ng-if="loai=='5'">
            {{ Form::label('lms_test', getphrase('File Import bài test ( excel )')) }}
            <span class="text-red">
                *
            </span>
            <input accept=".xls,.xlsx" class="form-control" name="lms_test" type="file">
            </input>
        </fieldset>
        <fieldset class="form-group col-md-6" ng-if="loai=='4'">
            {{ Form::label('lms_test', getphrase('File Import bài tập ( excel )')) }}
            <span class="text-red">
                *
            </span>
            <input accept=".xls,.xlsx" class="form-control" name="lms_type_4" type="file">
            </input>
        </fieldset>
        @if($record)
            @if($record->image!='')
                <fieldset class="form-group col-md-6">
                    <label>
                    </label>
                    {{link_to_asset(IMAGE_PATH_UPLOAD_LMS_CONTENTS.$record->image, getPhrase('download'))}}
                </fieldset>
            @endif
        @endif
    </div>
    <fieldset class="form-group">
        {{ Form::label('Mô tả') }}
            {{ Form::textarea('description', $value = null , $attributes = array('class'=>'form-control ckeditor', 'rows'=>'5', 'placeholder' => '')) }}
    </fieldset>
    <div class="buttons text-center">
        <button class="btn btn-lg btn-success button" id="submitForm" ng-disabled="!formLms.$valid" onclick="modal();">
            {{ $button_name }}
        </button>
    </div>
</input>