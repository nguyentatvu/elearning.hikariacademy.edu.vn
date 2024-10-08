<?php $settings = getSettings('lms');?>
<input type="hidden" name="series_slug" value="{{$series_slug}}">
<?php
  $dr_loai = ['0'=>'Menu', '8'=>'Sub menu', '1'=>'Từ vựng','2'=>'Bài học','3'=>'Bài tập','4'=>'Bài tập toàn bài','5'=>'Bài test','6'=>'Hán tự','7'=>'Bài ôn tập', '9'=>'Giới thiệu', 10=>'Flashcard', 11 => 'Luyện viết'];
  $loai_selected = (isset($record->type)) ? $record->type : null;
?>
<div class="row">
  <fieldset class="form-group col-md-6">
    {{ Form::label('bai', getphrase('Bài')) }}
    <span class="text-red">*</span>
    {{ Form::text('bai', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '',
    'ng-model'=>'bai',
    'ng-class'=>'{"has-error": formLms.bai.$touched && formLms.bai.$invalid}',
    )) }}
    <div class="validation-error" ng-messages="formLms.bai.$error" >
      {!! getValidationMessage()!!}
    </div>
  </fieldset>
  <fieldset class="form-group col-md-6">
    {{ Form::label('loai', getphrase('Loại')) }}
    <span class="text-red">*</span>
    {{ Form::select('loai', $dr_loai, $value =$loai_selected , $attributes = array('class'=>'form-control',
    'ng-model'=>'loai',
    'ng-class'=>'{"has-error": formLms.loai.$touched && formLms.loai.$invalid}',
    )) }}
    <div class="validation-error" ng-messages="formLms.loai.$error" >
      {!! getValidationMessage()!!}
    </div>
  </fieldset>
</div>
<div class='row'>
  <fieldset class="form-group col-md-12" ng-if="loai=='2' || loai=='6'">
    {{ Form::label('title', getphrase('Tên bài')) }}
    <span class="text-red"></span>
    {{ Form::text('title', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '',
    'ng-model'=>'title',
    'ng-pattern' => '',
    'ng-minlength' => '2',
    'ng-maxlength' => '260',
    'ng-class'=>'{"has-error": formLms.title.$touched && formLms.title.$invalid}',
    )) }}
    <div class="validation-error" ng-messages="formLms.title.$error" >
      {!! getValidationMessage()!!}
      {!! getValidationMessage('minlength')!!}
      {!! getValidationMessage('maxlength')!!}
      {!! getValidationMessage('pattern')!!}
    </div>
  </fieldset>
</div>
<div class="row">
  {{-- <fieldset class="form-group col-md-6" >
    {{ Form::label('stt', getphrase('Số thứ tự')) }}
    <span class="text-red">*</span>
    {{ Form::number('stt', $value = null , $attributes = array('class'=>'form-control',
    'ng-model'=>'stt',
    'required'=> 'true',
    'ng-class'=>'{"has-error": formLms.stt.$touched && formLms.stt.$invalid}',
    )) }}
    <div class="validation-error" ng-messages="formLms.stt.$error" >
      {!! getValidationMessage()!!}
    </div>
  </fieldset> --}}
  <fieldset class="form-group col-md-6" ng-if="loai=='3' || loai=='5' || loai=='7'">
    {{ Form::label('maucau', getphrase('Mẫu câu số')) }}
    <span class="text-red"></span>
    {{ Form::number('maucau', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '',
    'ng-model'=>'maucau',
    'ng-class'=>'{"has-error": formLms.maucau.$touched && formLms.maucau.$invalid}',
    )) }}
    <div class="validation-error" ng-messages="formLms.maucau.$error" >
      {!! getValidationMessage()!!}
    </div>
  </fieldset>
</div>
<div  class="row">
  <fieldset class="form-group  col-md-6"   >
    {{ Form::label('image', getphrase('Hình ảnh')) }}
    <input type="file" class="form-control" name="image"
    accept=".png,.jpg,.jpeg" id="image_input">
  </fieldset>
  <fieldset class="form-group col-md-6" ng-if="loai=='1' || loai=='2' || loai=='6' || loai=='9'">
    {{ Form::label('lms_file', getphrase('File video (.mp4)')) }}
    <span class="text-red">*</span>
    <input type="file"
    class="form-control"
    name="lms_file" accept=".mp4" >
  </fieldset>
  {{-- <fieldset class="form-group col-md-6" ng-if="loai=='1' || loai=='2' || loai=='6' || loai=='9'">
    {{ Form::label('video_duration', getphrase('Thời gian')) }}
    <span class="text-red">*</span>
    {{ Form::text('video_duration', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '',
    'ng-model'=>'video_duration',
    'ng-class'=>'{"has-error": formLms.video_duration.$touched && formLms.video_duration.$invalid}',
    )) }}
  </fieldset> --}}
  <fieldset class="form-group col-md-6" ng-if="loai=='8' || loai == '3'">
    {{ Form::label('lms_excel', getphrase('File Import bài tập ( excel )')) }}
    <span class="text-red">*</span>
    <input type="file"
    class="form-control"
    name="lms_excel" accept=".xls,.xlsx" >
  </fieldset>
  <fieldset class="form-group col-md-6">
    {{ Form::label('lms_pdf', getphrase('File Import tài liệu ( pdf )')) }}
    <input type="file"
    class="form-control"
    name="lms_pdf" accept=".pdf" id="pdf_input">
  </fieldset>
  <fieldset class="form-group col-md-6" ng-if="loai=='5'">
    {{ Form::label('lms_test', getphrase('File Import bài test ( excel )')) }}
    <span class="text-red">*</span>
    <input type="file"
    class="form-control"
    name="lms_test" accept=".xls,.xlsx" >
  </fieldset>
  <fieldset class="form-group col-md-6" ng-if="loai=='4'">
    {{ Form::label('lms_test', getphrase('File Import bài tập ( excel )')) }}
    <span class="text-red">*</span>
    <input type="file"
    class="form-control"
    name="lms_type_4" accept=".xls,.xlsx" >
  </fieldset>
  <fieldset class="form-group col-md-6" ng-if="loai=='11'">
    {{ Form::label('handwriting', 'Chọn bài luyện viết') }}
    {{ Form::select('handwriting', $handwriting, $value = $record->japanese_writing_practice_id , $attributes = array('class'=>'form-control',
      'ng-model'=>'handwriting',
      'ng-class'=>'{"has-error": formLms.handwriting.$touched}',
      )) }}
    <div class="validation-error" ng-messages="formLms.handwriting.$error">
        {!! getValidationMessage()!!}
    </div>
  </fieldset>
  <fieldset class="form-group col-md-6" ng-if="loai=='11'">
    <?php $type = array(1 => 'Hiragana', 2 => 'Kanji');?>
    {{ Form::label('type', 'Loại bài luyện viết') }}
    <span class="text-red">*</span>
    {{ Form::select('type', $type, $value = $handwriting_type, [
        'class' => 'form-control',
        'placeholder' => '',
        'required' => 'true',
        'ng-model'=>'type',
        'ng-pattern' => '',
        'ng-class'=>'{"has-error": formHandwriting.type.$touched && formHandwriting.type.$invalid}',
      ])
    }}
  </fieldset>
  @if($record)
  @if($record->image!='')
  <fieldset class="form-group col-md-3">
    {{link_to_asset(IMAGE_PATH_UPLOAD_LMS_CONTENTS.$record->image, getPhrase('download image'))}}
  </fieldset>
  @endif
  @if($record->download_doc!='')
  <fieldset class="form-group col-md-6">
    {{link_to_asset($record->download_doc, getPhrase('download '.  basename($record->download_doc)), $attributes = array('target' => '_blank'))}}
  </fieldset>
  @endif
  @endif
</div>
<fieldset class="form-group">
  {{ Form::label('Mô tả') }}
  {{ Form::textarea('description', $value = null , $attributes = array('class'=>'form-control ckeditor', 'rows'=>'5', 'placeholder' => '')) }}
</fieldset>
<div class="buttons text-center">
  <button onclick="modal();" id="submitForm" class="btn btn-lg btn-success button"
  ng-disabled='!formLms.$valid'>{{ $button_name }}</button>
</div>
