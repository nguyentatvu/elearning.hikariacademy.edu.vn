@extends($layout)
@section('content')

<nav aria-label="breadcrumb">
  <ol class="breadcrumb breadcrumb-custom bg-inverse-info">
    <li class="breadcrumb-item"><a href="/home"><i class="mdi mdi-home menu-icon"></i></a></li>
    <li class="breadcrumb-item"><a href="/learning-management/series">Khóa luyện thi</a></li>
    <li class="breadcrumb-item active" aria-current="page"><span>{{ $title }}</span></li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">

            <div id="page-wrapper">
                @if(!$content_record)
                <div class="row">
                <?php 
                     $image = IMAGE_PATH_UPLOAD_LMS_DEFAULT;
                     if($item->image)
                     $image = IMAGE_PATH_UPLOAD_LMS_SERIES.$item->image;

                 ?>
                    <div class="col-md-4"> <img src="{{$image}}" class="img-responsive center-block" alt="" width="100%"> </div>
                    <div class="col-md-7 col-md-offset-1">
                        <div class="series-details" style="padding-left: 20px">
                            <h1 style="text-transform: uppercase;">{{$item->title}} </h1>
                                {!! $item->description!!}
            
                            @if($item->is_paid && !isItemPurchased($item->id, 'lms'))
                            <div class="buttons text-left">
                                 <span style="font-size: 28px; color: red;">Giá: {!! $item->cost!!} <img src="/Themes/themeone/assets/images/icon-bank.png" style="width: 30px;"></span>
                                 
                                <a href="javascript:void(0);" class="btn btn-success text-uppercase" onclick="ajax_btn_buy_lms({{ $item->id }});">Mua ngay</a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @elseif($content_record->content_type == 'url' || $content_record->content_type == 'iframe' || $content_record->content_type == 'video_url')
                    @include('student.lms.series-video-player', array('series'=>$item, 'content' => $content_record))
                @elseif($content_record->content_type == 'audio' || $content_record->content_type == 'audio_url')
                    @include('student.lms.series-audio-player', array('series'=>$item, 'content' => $content_record))
                @endif
                <hr>
               @include('student.lms.series-items', array('series'=>$item, 'content'=>$content_record))
                        
              </div>

          </div>
      </div>
  </div>
</div>
        <!-- /#page-wrapper -->
                        <script type="text/javascript">

                            function ajax_btn_buy_lms(item) {

                                alertify.set({ labels: {
                                  ok     : "Có",
                                  cancel : "Không"
                                } });
                                alertify.confirm('Bạn có chắc chắn muốn mua khóa học này?',
                                  function(e){ 
                                    if(e){
                                      $.ajax({
                                          headers: {
                                              'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                                          },
                                          url : "<?php echo URL_AJAX_PAYMENTS_CHECKOUT ?>",
                                          type : "post",
                                          data: {item: item},
                                          success : function (result){
                                              check = JSON.parse(result);
                                              if (check.error == 0) {
                                                alertify.set({ labels: {
                                                  ok     : "Bắt đầu ngay",
                                                  cancel : "Không"
                                                } });
                                                alertify
                                                  .alert("Bạn đã mua khóa học thành công", function(e){
                                                    if(e){
                                                        location.reload();
                                                    } else {
                                                        location.reload();
                                                    }
                                                  });
                                                } else {
                                                  alertify
                                                    .alert("Bạn không đủ số Hi Koi để mua, vui lòng nạp thêm", function(){
                                                      alertify.message('OK');
                                                    });
                                                }
                                               
                                               //alert(123);
                                               //
                                          }
                                          });
                                    }
                                    else{

                                    }
                                  });

                                
                    }
                </script>
@stop
@section('footer_scripts')
@if($content_record)
    @if($content_record->content_type == 'video' || $content_record->content_type == 'video_url')
        @include('common.video-scripts')
    @endif
@endif
@include('common.custom-message-alert')
@stop