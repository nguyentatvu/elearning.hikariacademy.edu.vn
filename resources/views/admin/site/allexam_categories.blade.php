@extends('admin.layouts.sitelayout')
@section('content')
<div class="cs-gray-bg" style="margin-top: 101px;">
  <div class="container">
    <div class="row cs-row">
      <!-- Side Bar -->
      <div class="col-md-3">
        <style type="text/css">
        ul.cs-icon-list li ul li {
          padding: 10px;
          border-bottom: 1px solid #ccc;
          padding-left: 36px;
        }
        .cs-icon-list li.active ul li a {
          background: none;
          color: #ff9800;
        }
      </style>
      <!-- Icon List  -->
      <ul class="cs-icon-list">
        @if(count($categories))
        @foreach($categories as $category)
        <li id={{$category->slug}}><a href="{{URL_VIEW_ALL_EXAM_CATEGORIES.'/'.$category->slug}}">{{$category->category}}</a>
         <!--  <ul>
            <li><a href="#">Đề thi N3 TT201807001</a></li>
            <li><a href="#">Đề thi N3 TT201808001</a></li>
            <li><a href="#">Đề thi N3 TT201809001</a></li>
            <li><a href="#">Đề thi N3 TT201809027</a></li>
          </ul> -->
        </li>
        @endforeach
        @else
        <h4>No Exams Are Available</h4> 
        @endif 
      </ul>
      <!-- /Icon List  -->
    </div>
    <!-- Main Section -->
    @if(count($quizzes))
    <div class="col-md-9">
      <!-- Product Filter Bar -->
      <div class="row">
        <div class="col-sm-12">
          <ul class="cs-filter-bar clearfix">
            <li class="active"><a href="#">{{$title}}の受験</a></li>
            <li></li>
          </ul>
        </div>
      </div>
      <!-- Products Grid -->
                    <!-- <div class="row">
                    @foreach($quizzes as $quiz)    
                        <div class="col-md-4 col-sm-6">
                        Product Single Item
                       <div class="cs-product cs-animate">
                            <a href="{{URL_FRONTEND_START_EXAM.$quiz->slug}}">
                                <div class="cs-product-img">
                                    @if($quiz->image)
                                    <img src="{{IMAGE_PATH_EXAMS.$quiz->image}}" alt="exam" class="img-responsive">
                                    @else
                                    <img src="{{IMAGE_PATH_EXAMS_DEFAULT}}" alt="exam" class="img-responsive">
                                    @endif
                                </div>
                            </a>
                            <div class="cs-product-content">
                             <a href="{{URL_FRONTEND_START_EXAM.$quiz->slug}}" class="cs-product-title text-center">{{$quiz->title}}</a>
                              <ul class="cs-card-actions mt-0">
                                    <li>
                                        <a href="#">Marks : {{(int)$quiz->total_marks}}</a>
                                    </li>
                                    <li>  </li>
                                    <li class="cs-right">
                                        <a href="#">{{$quiz->dueration}} mins</a>
                                    </li>
                                </ul>
                                <div class="text-center mt-2">
                                     <a href="{{URL_FRONTEND_START_EXAM.$quiz->slug}}" class="btn btn-blue btn-sm btn-radius">Start Exam</a>
                                </div>
                            {{--   <a href="{{URL_FRONTEND_START_EXAM.$quiz->slug}}" class="cs-product-title pull-right">{{getPhrase('take_exam')}}</a> --}}
                            </div>
                        </div>
                    </div>
                     @endforeach 
                   </div> -->
                   <style type="text/css">
                   .ih-item.circle.effect1 .info h3 {
                    font-size: 18px;
                  }
                  .ih-item.circle.effect1 .info p {
                    font-size: 14px;
                    color: #fff;
                  }
                  .hikari-exam-title {
                    padding-top: 14px;
                    padding-right: 30px;
                    text-align: center;
                  }
                </style>
                <style type="text/css">
                #popup_box { 
                  display:none;
                  position:fixed; 
                  height:170px;  
                  width:330px;  
                  background: #fff;  
                  left: 50%;
                  top: 50%;
                  margin-left: -150px;
                  margin-top: -150px;
                  z-index:100;     
                  padding:15px;  
                  font-size:15px;  
                  -moz-box-shadow: 0 0 5px;
                  -webkit-box-shadow: 0 0 5px;
                  box-shadow: 0 0 5px;
                }
                #popupBoxClose { 
                  background: #fba82e;
                  color: #fff;
                  padding: 10px;
                  margin: -15px -15px 10px -15px;
                  display: block;
                  position: relative;
                  text-align: right;
                  cursor: pointer;
                }
                #popupBoxClose #countDown {
                  position: absolute;
                  top: 10px;
                  left: 10px;
                  width: 20px;
                  height: 20px;
                  background: #fff;
                  color: #369;
                  text-align: center;
                  -webkit-border-radius: 50%;
                  border-radius: 50%;
                  font-size: 14px;
                  font-weight: bold;
                }
                .hikari_div_disable {
                  pointer-events: none;
                }
              </style>
              <script src="http://elearning.hikariacademy.edu.vn/Themes/themeone/assets/site/js/jquery-3.1.1.min.js"></script>
              <script type="text/javascript">
                jQuery(document).ready( function() {
                           // When site loaded, load the Popupbox First
                           // new popup(jQuery("#popup_box"),jQuery("#container")).load();
                         });
                function popup10s(link) {
                  new popup(jQuery("#popup_box"),jQuery("#hikari_topic_exam"),link).load();
                }
                function popup(popup,container,link) {
                 var thisPopup = this,            
                 timer,
                 counter = 2,
                 countDown = jQuery("#countDown").text(counter.toString());
                 thisPopup.load = function() {            
                   container.animate({
                     "opacity": "0.3"  
                   },250, function() {            
                     popup.fadeIn("250");            
                   });
                   container.addClass('hikari_div_disable');
                 /*container.off("click").on("click", function() {
                     thisPopup.unload();
                   });*/ 
                   jQuery('#popupBoxClose').off("click").on("click", function() {            
                     thisPopup.unload();
                     container.removeClass('hikari_div_disable');              
                   });
                   timer = setInterval(function() {
                     counter--;
                     if(counter < 0) {                   
                       thisPopup.unload();
                       window.location.href = link;
                     } else {
                       countDown.text(counter.toString());
                     }
                   }, 1000);            
                 }       
                 thisPopup.unload = function() {            
                   clearInterval(timer); 
                   popup.fadeOut("250", function(){
                     container.animate({
                       "opacity": "1"  
                     },250);  
                   });
                 }
               }     
             </script>
                           <div id="popup_box">    <!-- OUR PopupBox DIV-->
                            <a id="popupBoxClose">X</a>  
                            <p style="color: #000; font-size: 20px;">試験は <span id="countDown" style="color: red; font-size: 24px;"></span> 秒後に開始されます。</p>
                            <p style="color: #000; font-size: 20px;">ご準備しておいてください。</p>  
                            <!-- <progress value="10" max="10" id="pageBeginCountdown"></progress> -->
                          </div>
                          <div class="row hikari-topic-exam" id="hikari_topic_exam">
                            @foreach($quizzes as $quiz)
                            <div class="col-sm-4"><!-- rollIn -->
                              <div class="ih-item circle colored effect1 wow  swing">
                                <a href="javascript:void(0);" onclick="popup10s('{{URL_FRONTEND_START_EXAM.$quiz->slug}}')">
                                  <!-- <a href="{{URL_FRONTEND_START_EXAM.$quiz->slug}}" onclick="popup10s()"> -->
                                    <div class="spinner"></div>
                                    <div class="img "><img src="{{IMAGE_PATH_EXAMS.$quiz->image}}" alt="img"></div>
                                    <div class="info">
                                      <div class="info-back">
                                        <h3>{{ change_furigana_text($quiz->title)}}</h3>
                                        <!-- (int)$quiz->total_marks}} 点 -->
                                        <p>{{$quiz->dueration}} 分</p>
                                        <span class="btn btn-blue btn-sm btn-radius" style="padding: 2px;">始めましょう</span>
                                      </div>
                                    </div>
                                  </a>
                                </div>
                                <div>
                                   <h5 class="text-center" style="color: #0d1525; font-size: 17px; font-weight: 550;">{{ change_furigana_text($quiz->title)}}</h5>
                                </div>
                              </div>
                              @endforeach
                      <!-- <div class="col-sm-4">
                        <div class="ih-item circle effect1">
                            <a href="http://elearning.hikariacademy.edu.vn/exams/start-exam/n309051801-ee28c39f6c952c540681d4db9bde74f9e97f6311-3">
                            <div class="spinner"></div>
                            <div class="img"><img src="http://elearning.hikariacademy.edu.vn/public/uploads/exams/categories/30-examimage.png" alt="img"></div>
                            <div class="info">
                              <div class="info-back">
                                <h3>[furi k=#言語知識# f=#げんごちしき#]（[furi k=#文法# f=#ぶんぽう#]）・[furi k=#読解# f=#どっかい#]</h3>
                                 <p>83 点- 70 分</p>
                                 <span class="btn btn-blue btn-sm btn-radius" style="padding: 2px;">始めましょう</span>
                            </div>
                            </div>
                            </a>
                        </div>
                        <div>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="ih-item circle colored effect1">
                            <a href="http://elearning.hikariacademy.edu.vn/exams/start-exam/n309051801-b8a7983792a2671d51aa3178a61bca1dea79b7eb-3">
                                <div class="spinner"></div>
                                <div class="img"><img src="http://elearning.hikariacademy.edu.vn/public/uploads/exams/categories/39-examimage.png" alt="img"></div>
                                <div class="info">
                                  <div class="info-back">
                                    <h3>[furi k=#聴解# f=#ちょうかい#]</h3>
                                     <p>62 点 - 40 分</p>
                                     <span class="btn btn-blue btn-sm btn-radius" style="padding: 2px;">始めましょう</span>
                                  </div>
                                </div>
                            </a>
                        </div>
                      </div>
                    </div> -->
                    <!-- Pagination -->
                    <div class="row text-center">
                      <div class="col-sm-12">
                        <ul class="pagination cs-pagination ">
                          {{ $quizzes->links() }}
                        </ul>
                      </div>
                    </div>
                    <!-- /Pagination -->
                  </div>
                  @endif
                </div>
              </div>
            </div>
            @stop
            @section('footer_scripts')
            <script>
              var my_slug  = "{{$quiz_slug}}";
              if(!my_slug){
                $(".cs-icon-list li").first().addClass("active");
              }
              else{
      $("#"+my_slug).addClass("active");
    }
</script>
@stop