@extends('admin.layouts.'.getRole().'.'.getRole().'layout')
@section('content')
<div id="page-wrapper">
  <div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
      <div class="col-lg-12">
        <ol class="breadcrumb">
          <li><a href="{{URL_ADMIN_DASHBOARD}}"><i class="mdi mdi-home"></i></a> </li>
          <li> <a href="{{URL_STUDENT_EXAM_SERIES_LIST}}">Bộ đề thi</a> </li>
          <li class="active"><?php change_furigana_text ($title); ?></li>
        </ol>
      </div>
    </div>
    <div class="panel panel-custom">
      <div class="panel-body">
       
        <div class="row">
          <?php 
          $image_path = IMAGE_PATH_UPLOAD_SERIES.'n'.$item->category_id.'.png';
          ?>
          <div class="col-md-3"> 
            <div><img src="{{$image_path}}" class="img-responsive center-block" alt=""></div>
            <div class="series-details">
            <h3><?php change_furigana_text ($item->title); ?> </h3>
            </div>
           </div>
          <div class="col-md-9">
            @include('admin.exams.examseries.series-items-show', array('series'=>$item, 'content'=>$content_record))
          </div>
        </div>
       
        <Br>
        
      </div>
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
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