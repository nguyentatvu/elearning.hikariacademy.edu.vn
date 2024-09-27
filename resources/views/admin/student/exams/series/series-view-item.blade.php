@extends('admin.layouts.student.studentsettinglayout')
@section('content')
<?php $image_path = IMAGE_PATH_UPLOAD_SERIES.'n'.$item->category_id.'.png'; ?>
<div class="card mb-10">
  <div class="card-header">
    <h3 class="card-title"><?php change_furigana_text ($item->title); ?></h3>
  </div>
  <div class="card-body">
      @if(!$content_record)
      <div class="row">
        <?php 
        $image_path = IMAGE_PATH_UPLOAD_SERIES.'n'.$item->category_id.'.png';
        ?>
        <div class="col-md-4"> 
          <img src="{{$image_path}}" class="img-responsive center-block" alt="Bộ đề thi" width="100%"> 
          </div>
        <div class="col-md-8 ">
          @include('student.exams.series.series-items-show', array('series'=>$item, 'content'=>$content_record))
        </div>
      </div>
      @endif
  </div>
</div>  
@stop
@section('footer_scripts')
<script>
  function showInstructions(url) {
    var popup = window.open(url, "_blank", "type=fullWindow,fullscreen,minimizable=no,scrollbars=no,titlebar=no,location=no,dialog=yes,resizable=no");
  //window.open(url, "_blank", ',type=fullWindow,fullscreen,scrollbars=yes');
  if (popup.outerWidth < screen.availWidth || popup.outerHeight < screen.availHeight)
  {
    popup.moveTo(0,0);
    popup.resizeTo(screen.availWidth, screen.availHeight);
  }
  localStorage.clear();
  runner();
}
function runner()
{
  url = localStorage.getItem('redirect_url');
  if(url) {
    localStorage.clear();
    window.location = url;
  }
  setTimeout(function() {
    runner();
  }, 500);
}
</script>
@stop