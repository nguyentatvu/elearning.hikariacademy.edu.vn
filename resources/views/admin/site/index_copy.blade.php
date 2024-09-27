@extends('admin.layouts.sitelayout')

@section('content')

<div class="owl-carousel owl-theme full-width">

  <div class="item">

    <img src="/public/images/ok-10.jpg" alt="image"/>

  </div>

  <div class="item">

    <img src="/public/images/ok-5.jpg" alt="image"/>

  </div>

  <div class="item">

    <img src="/public/images/ok-9.jpg" alt="image"/>

  </div>

</div>



<div class="container">


  <div class="row pricing-table">

    <div class="ed_heading_top col-sm-12">

      <h3 class="tilte-h3 wow fadeInDown text-danger">KHÓA HỌC</h3>

    </div>

      <div id="menuitem3" class="owl-carousel menuitem2">
          @if(count($series) > 0)

              @foreach($series as $r)
                  <div class="item col-sm-4">

                      <div class="ih-item square effect4">

                          <a href="{{PREFIX.'learning-management/lesson/show/'.$r->slug}}">

                              <div class="img"><img src="{{ IMAGE_PATH_UPLOAD_LMS_SERIES.$r->image}}" alt="{{$r->title}}"></div>

                              <div class="mask1"></div>

                              <div class="mask2"></div>

                              <div class="info">

                                  <h3>{{ $r->title }}</h3>

                                  <p>Xem chi tiết</p>

                              </div></a>

                      </div>
                  </div>
              @endforeach
          @else
              <div class="item col-sm-4">

                  <div class="ih-item square effect4">

                      <a href="/login">

                          <div class="img"><img src="/public/uploads/exams/series/site/n3.png" alt="img"></div>

                          <div class="mask1"></div>

                          <div class="mask2"></div>

                          <div class="info">

                              <h3>Bộ đề thi N3</h3>

                              <p>Đề thi online Hikari Elearning</p>

                          </div></a>

                  </div>
              </div>

              <div class="item col-sm-4">

                  <div class="ih-item square effect4">

                      <a href="/login">

                          <div class="img"><img src="/public/uploads/exams/series/site/n4.png" alt="img"></div>

                          <div class="mask1"></div>

                          <div class="mask2"></div>

                          <div class="info">

                              <h3>Bộ đề thi N4</h3>

                              <p>Đề thi online Hikari Elearning</p>

                          </div></a>

                  </div>
              </div>

              <div class="item col-sm-4">

                  <div class="ih-item square effect4">

                      <a href="/login">

                          <div class="img"><img src="/public/uploads/exams/series/site/n5.png" alt="img"></div>

                          <div class="mask1"></div>

                          <div class="mask2"></div>

                          <div class="info">

                              <h3>Bộ đề thi N5</h3>

                              <p>Đề thi online Hikari Elearning</p>

                          </div></a>

                  </div>
              </div>

          @endif




      </div>



    @foreach($lms_series as $key => $value)
      <div class="col-md-6 col-xl-4 grid-margin stretch-card pricing-card">



        <div class="card border-primary border pricing-card-body">

          <img src="{{ IMAGE_PATH_UPLOAD_LMS_SERIES.$value->image}}" style="width: 100%; padding-bottom: 20px">

          <div class="text-center pricing-card-head">

            <h3 class="text-success">{{ $value->title }}</h3>

            <!--h1 class="font-weight-normal mb-4 text-danger">GIÁ: <?php echo $value->cost; ?> <img src="/Themes/themeone/assets/images/icon-bank.png" style="width: 30px;"></h1-->

          </div>

          <?php echo $value->short_description; ?>



              <!--div class="wrapper">

                @if(Auth::check())
                  <a href="{{URL_STUDENT_LMS_SERIES_VIEW.$value->slug}}" class="btn btn-success btn-block">MUA NGAY</a>
                @else
                  <a href="/login" class="btn btn-success btn-block">MUA NGAY</a>
                @endif
              </div-->

              <!-- <p class="mt-3 mb-0 plan-cost text-gray">Free</p> -->

            </div>

          </div>



      @endforeach

        

      </div>



      <div class="row">

       <div class="ed_heading_top col-sm-12">

         <h3 class="tilte-h3 wow fadeInDown text-danger">TÌM HIỂU VỀ HIKARI E-LEARNING</h3>

       </div>

       <div class="col-lg-6 col-md-6 col-sm-6">

         <div class="wow bounceInLeft">

           <img src="/public/images/hikari.jpg" style="width: 100%">

         </div>

       </div>

       <div class="col-lg-6 col-md-6 col-sm-6 content_video wow bounceInRight">

         <div class="ed_toppadder30 about-hikari">

           <h4 class="ed_bottompadder20" style="color:#167ac6">HIKARI E-LEARNING</h4>

           <p class="ed_bottompadder20">Trải qua hơn 10 năm phát triển, Hikari Academy hiện đã có hơn 50.000 học viên học tại trung tâm và học viên đi Nhật theo dạng du học.</p>

           <p class="ed_bottompadder20"> Không khoảng cách, không giới hạn thời gian, tính ứng dụng cao, không những vậy khoá học trực tuyến còn giúp bạn tăng tính độc lập trong việc học. Hikari Elearning cung cấp cho bạn một khóa học với các trình độ và kĩ năng, đáp ứng như cầu của mọi người.</p>

           <p class="ed_bottompadder20"> Trung tâm Nhật Ngữ Hikari Academy được thành lập nhằm hỗ trợ nhu cầu học cấp tốc tiếng Nhật qua Nhật Bản du học và làm việc. Bên cạnh sử học online, các khoá học trực tiếp tại trung tâm sẽ hỗ trợ học viên đạt trình độ N3 trong 6 tháng và cải thiện khả năng giao tiếp bản xứ cùng đội ngũ giảng viên kinh nghiệm của trung tâm.</p>

           <!-- <span><a href="#" class="btn ed_btn ed_orange ">XEM THÊM</a></span> -->

         </div>

       </div>

     </div>



   <!-- <div class="row">

     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 home-hotro-title">

       <div class="ed_heading_top">

         <h3 class="tilte-h3 wow fadeInDown text-danger">TẠI SAO HIKARI E-LEARNING CÓ THỂ GIÚP BẠN?</h3>

       </div>

     </div>



     <div class="col-sm-3">

       <div class="ih-item circle effect1 wow pulse"><a href="#">

         <div class="spinner"></div>

         <div class="img"><img src="public/images/B1.jpg" alt="img"></div>

         <div class="info">

           <div class="info-back">

             <h3>ĐÀO TẠO KHOA HỌC</h3>

             <p>Lộ trình giảng dạy bài bản, chuyên sâu</p>

           </div>

         </div></a>

       </div>

     </div>

     <div class="col-sm-3">

       <div class="ih-item circle effect1 wow pulse"><a href="#">

         <div class="spinner"></div>

         <div class="img"><img src="public/images/B2.jpg" alt="img"></div>

         <div class="info">

           <div class="info-back">

             <h3>Tư vấn - hỗ trợ</h3>

             <p>Miễn phí - Nhanh chóng</p>

           </div>

         </div></a>

       </div>

     </div>

     <div class="col-sm-3">

       <div class="ih-item circle effect1 wow pulse"><a href="#">

         <div class="spinner"></div>

         <div class="img"><img src="public/images/B3.jpg" alt="img"></div>

         <div class="info">

           <div class="info-back">

             <h3>Mô phỏng thực tế</h3>

             <p>Kiến thức thực tế tại Nhật</p>

           </div>

         </div></a>

       </div>

     </div>

     <div class="col-sm-3">

       <div class="ih-item circle effect1 wow pulse"><a href="#">

         <div class="spinner"></div>

         <div class="img"><img src="public/images/B4.jpg" alt="img"></div>

         <div class="info">

           <div class="info-back">

             <h3>Phương pháp</h3>

             <p>Hợp lý nhất cho bạn</p>

           </div>

         </div></a>

       </div>

     </div>

   </div> -->





   <div class="row " style="padding-bottom: 30px;">

     <div class="ed_heading_top col-sm-12">

       <h3 class="tilte-h3 wow fadeInDown text-danger">ĐỀ THI ONLINE CÁC CẤP ĐỘ</h3>

     </div>
     <div id="menuitem2" class="owl-carousel menuitem2">
        <div class="item col-sm-4">

         <div class="ih-item square effect4">

          <a href="/login">

            <div class="img"><img src="/public/uploads/exams/series/site/n1.png" alt="img"></div>

            <div class="mask1"></div>

            <div class="mask2"></div>

            <div class="info">

             <h3>Bộ đề thi N1</h3>

             <p>Đề thi online Hikari Elearning</p>

           </div></a>

         </div>
       </div>

       <div class="item col-sm-4">

         <div class="ih-item square effect4">

          <a href="/login">

            <div class="img"><img src="/public/uploads/exams/series/site/n2.png" alt="img"></div>

            <div class="mask1"></div>

            <div class="mask2"></div>

            <div class="info">

             <h3>Bộ đề thi N2</h3>

             <p>Đề thi online Hikari Elearning</p>

           </div></a>

         </div>
       </div>

       <div class="item col-sm-4">

         <div class="ih-item square effect4">

          <a href="/login">

            <div class="img"><img src="/public/uploads/exams/series/site/n3.png" alt="img"></div>

            <div class="mask1"></div>

            <div class="mask2"></div>

            <div class="info">

             <h3>Bộ đề thi N3</h3>

             <p>Đề thi online Hikari Elearning</p>

           </div></a>

         </div>
       </div>

       <div class="item col-sm-4">

         <div class="ih-item square effect4">

          <a href="/login">

            <div class="img"><img src="/public/uploads/exams/series/site/n4.png" alt="img"></div>

            <div class="mask1"></div>

            <div class="mask2"></div>

            <div class="info">

             <h3>Bộ đề thi N4</h3>

             <p>Đề thi online Hikari Elearning</p>

           </div></a>

         </div>
       </div>

       <div class="item col-sm-4">

         <div class="ih-item square effect4">

          <a href="/login">

            <div class="img"><img src="/public/uploads/exams/series/site/n5.png" alt="img"></div>

            <div class="mask1"></div>

            <div class="mask2"></div>

            <div class="info">

             <h3>Bộ đề thi N5</h3>

             <p>Đề thi online Hikari Elearning</p>

           </div></a>

         </div>
       </div>

       
     </div>



   </div>

{{--   <div class="row " style="padding-bottom: 30px;">

     <div class="ed_heading_top col-sm-12">

       <h3 class="tilte-h3 wow fadeInDown text-danger">KHÓA HỌC ONLINE CÁC CẤP ĐỘ</h3>

     </div>
     <div id="menuitem3" class="owl-carousel menuitem2">
         @if(count($series) > 0)

             @foreach($series as $r)
                 <div class="item col-sm-4">

                 <div class="ih-item square effect4">

                     <a href="{{PREFIX.'learning-management/lesson/show/'.$r->slug}}">

                         <div class="img"><img src="{{ IMAGE_PATH_UPLOAD_LMS_SERIES.$r->image}}" alt="{{$r->title}}"></div>

                         <div class="mask1"></div>

                         <div class="mask2"></div>

                         <div class="info">

                             <h3>{{ $r->title }}</h3>

                             <p>Xem chi tiết</p>

                         </div></a>

                 </div>
             </div>
             @endforeach
         @else
             <div class="item col-sm-4">

                 <div class="ih-item square effect4">

                     <a href="/login">

                         <div class="img"><img src="/public/uploads/exams/series/site/n3.png" alt="img"></div>

                         <div class="mask1"></div>

                         <div class="mask2"></div>

                         <div class="info">

                             <h3>Bộ đề thi N3</h3>

                             <p>Đề thi online Hikari Elearning</p>

                         </div></a>

                 </div>
             </div>

             <div class="item col-sm-4">

                 <div class="ih-item square effect4">

                     <a href="/login">

                         <div class="img"><img src="/public/uploads/exams/series/site/n4.png" alt="img"></div>

                         <div class="mask1"></div>

                         <div class="mask2"></div>

                         <div class="info">

                             <h3>Bộ đề thi N4</h3>

                             <p>Đề thi online Hikari Elearning</p>

                         </div></a>

                 </div>
             </div>

             <div class="item col-sm-4">

                 <div class="ih-item square effect4">

                     <a href="/login">

                         <div class="img"><img src="/public/uploads/exams/series/site/n5.png" alt="img"></div>

                         <div class="mask1"></div>

                         <div class="mask2"></div>

                         <div class="info">

                             <h3>Bộ đề thi N5</h3>

                             <p>Đề thi online Hikari Elearning</p>

                         </div></a>

                 </div>
             </div>

         @endif



       
     </div>



   </div>--}}





 </div>



 @stop

 @section('footer_scripts')

 <script type="text/javascript">
  $('#menuitem2').owlCarousel({
    loop:true,
    margin:10,
    autoplay: true,
    autoplayTimeout: 3000,
    responsive:{
      0:{
        items:1
      },
      600:{
        items:3
      },
      1000:{
        items:3
      }
    }
  });

  $('#menuitem3').owlCarousel({
    // loop:true,
    margin:10,
    // autoplay: true,
    autoplayTimeout: 3000,
    responsive:{
      0:{
        items:1
      },
      600:{
        items:3
      },
      1000:{
        items:3
      }
    }
  })
</script>


@stop