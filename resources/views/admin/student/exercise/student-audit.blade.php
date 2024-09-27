@extends('admin.layouts.sitelayout')
@section('header_scripts')

<link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i,800" rel="stylesheet">

<style>
	.kg-study-cuttom{
		width: 100%;
		position: relative;
		min-height: 50px;
		overflow: hidden;
	}

	.paragraph-les-cuttom {
		/*max-height: 40vh;*/
		overflow: auto;

	}

	.ct-lesson17{
	   margin-top: 15px!important;
	}

	.ct-lesson17 .block-type-cuttom {
		width: 100%;
		margin-left: 0!important;
		margin-bottom: 0!important;
	}

	.ct-lesson4 .block-type-cuttom {
		width: 100%!important;
	}

	.paragraph-les{
		margin-bottom: 40px;
		position: static;
		top: auto;
		bottom: auto;
		left: auto;
		width: auto;
		z-index: 10;
	}
	.width-25{
		width: 25% !important;
	}

	.margin012{
		margin-bottom: 0!important;
		margin-top: 12px!important;
	}

	.hikari_question_box {
		border: 1px dotted;
		line-height: 40px;
		padding: 10px;
		margin-top: 5px;
	}

	span.hikari_question_border {
		border: 2px solid #575b84;
		padding: 5px 5px;
		margin: 5px 5px 5px 5px;

	}

	.paragraph-les{
		padding: 30px!important;
	}

	.style-countdown{
		font-size: 21px;
		letter-spacing: -.7px;
	}

</style>

@stop



<?php



$array_char = ['a','b','c','d'];

$headerBlock = '<div class="kg-study kg-study-cuttom">

                                        <div id="data-exercise-hid">

                                            <!--<h3 class="guide-user-les desc-web text-danger">Hãy đọc đoạn văn dưới đây và trả lời các câu hỏi liên quan</h3>-->

                                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-10 offset-xl-1 ">

                                                <div class="ct-lesson ct-lesson17">';



$footerBlock = '</div></div></div></div>';



?>

@section('content')

    <div id="page-wrapper">

        <div class="header-les d-flex justify-content-between">

            <div  class="name-les-back">

                <a  href="{{$back_url}}" class="back-icon">

                    <i  aria-hidden="true" class="fa fa-angle-left"></i>

                </a>

                <span  class="name-les">Quay lại bài học</span>

                <div  class="text-center"><h4 class="font-weight-bold text-success"><i class="fa fa-star mr-2" aria-hidden="true"></i>{!! $name->bai !!}</h4></div>

            </div>



        </div>

        @if(isset($records))



            {{ Form::model( null,array('url' => 'learning-management/lesson/audit/'.$combo_slug.'/'.$series.'/'.$slug, 'method'=>'post','novalidate'=>'','name'=>'formTest', 'files'=>'false' )) }}



           {!! Form::hidden('content_id',$slug) !!}

            {!! Form::hidden('time',null) !!}

                <div class="wp-content-les">

                <div class="container">

                    <div class="row">



                        <?php $flat = true;?>

                        @foreach ($records as $key => $record)

                                {!! $headerBlock !!}

                                 @if($record->dang == 7)

                                   @if($flat)

                                        <div class="paragraph-les paragraph-les-cuttom jp-font vue-sticky-el">

                                            {!! $record->mota !!}

                                        </div>

                                    @endif

                                    <?php $flat = false;?>

                                @endif



                                <div  class="wp-block-type d-flex justify-content-between">

                                    <div class="block-type block-type-cuttom  {{(isset($record->correct) ? ($record->correct ==1 ? "correct-status ": "incorrect-status") ." none-clicks" : "")}} ">

                                        <div class="title-block-type " style="color: #fff;">

                                            <a>Câu số {{$record->cau}}</a>

                                        </div>

                                        @if($record->dang != 7)

                                        <div  class="text-question-les jp-font " style="text-align: left;margin-bottom: 0;">

                                            <p class="text-primary"><strong>{!!$record->mota  !!}</strong></p>

                                        </div>

                                        @endif

                                        <div class="list-select-les ">

                                            @foreach($record->answers as $keyanswers => $answers )

                                                <div class="item-check-select {{$record->display == 1 ? "width-25" :""}} {{(isset($record->check) && $record->dapan == ((int)$keyanswers +1) ? "correct-answer" : "")}}   {{(isset($record->check) && $record->check == ((int)$keyanswers +1)  ? (isset($record->correct) ? ($record->correct ==1 ? "correct-answer": "incorrect-answer"): ""): "")}} ">

                                                    <div class="form-check">

                                                        <span class="font-weight-bold">{!! $array_char[$keyanswers] !!}</span>

                                                        <input  {{(isset($record->check) && $record->check == ((int)$keyanswers +1)  ? "checked": "")}}   type="radio" name="quest_{{$record->id}}" id="answers_{{$record->cau}}_{{$keyanswers}}" class="form-check-input " value="{{((int)$keyanswers +1)}}">

                                                        <label  for="answers_{{$record->cau}}_{{$keyanswers}}" class="form-kana text-type">

                                                                <span class="fa-stack icon-input icon-incorrect">

                                                                    <i class="fa fa-square-o fa-stack-1x"></i>

                                                                    <i class="fa fa-times fa-stack-1x fa-inner-close"></i>

                                                                </span>

                                                            <span  class="icon-input icon-no-checked ">

                                                                    <i  aria-hidden="true" class="fa fa-square-o "></i>

                                                                </span>

                                                            <span  class="icon-input icon-checked ">

                                                                    <i  aria-hidden="true" class="fa fa-check-square-o "></i>

                                                                </span>

                                                            <span  class="icon-input icon-correct">

                                                                        <i  aria-hidden="true" class="fa fa-check-square-o"></i>

                                                                </span>

                                                            <span  class="text-label jp-font"><p> {!! $answers !!}</p></span>

                                                        </label>

                                                    </div>



                                                </div>

                                            @endforeach

                                        </div>

                                    </div>

                                </div>



                                {!! $footerBlock !!}

                        @endforeach



                    </div>

                </div>

            </div>



            <div class="wp-btn-progress-les">

        <div class="progress-les">

            <div class="wp-ct-prg">

                <div class="bar-prg">

                    <div class="main-bar" style="width: 100%;"></div>

                </div>

                <div class="wp-number-prg d-flex justify-content-between">

                    <div></div>

                    <div class="item-prg text-center cpl-status">

                        <span class="circle"></span>

                    </div>

                    <div class="item-prg text-center cpl-status">

                        <span class="circle"></span>

                    </div>

                    <div  class="item-prg text-center cpl-status">

                        <span class="circle"></span>

                    </div>

                    <div class="item-prg text-center cpl-status">

                        <span  class="circle opacity-0"></span>

                    </div>

                </div>

            </div>

        </div>

        <div  class="btn-result pt-0" >

            <div class="container">

                <div class="row">

                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-10 offset-xl-1">

                        <div  class="ct-btn-result d-flex justify-content-between">

                            <div  class="left-ct">

                                <div class="result-ntf">

                                    <span class="mr-3 " style="font-weight: 700;font-size: 14px;color: #625f6f;">Thời gian còn lại:</span>

                                    <div id="timerdiv" class="d-inline font-weight-bold style-countdown text-success">

                                        <!-- <span id="hours">01</span> : -->

                                        <span id="mins">00</span> :

                                        <span id="seconds">00</span>



                                    </div>

                                </div>

                            </div>

                            <div class="right-ct">

                                <div class="btn-group-les">



                                    @if(!isset($value))

                                        <button type="submit"  style="border: none;cursor: pointer; outline:none;" class="btn-nav-les btn-check-les">Nộp bài&nbsp;<i class="fa fa-cloud-upload" aria-hidden="true"></i></button>

                                    @else

                                        @if($passed == 1)

                                            <a href="{{$sendUrl}}" class="btn-nav-les btn-result-corect-les">Bài tiếp&nbsp;<i  aria-hidden="true" class="fa fa-angle-double-right"></i></a>

                                        @else

                                            <a href="{{PREFIX}}learning-management/lesson/audit/{{$combo_slug}}/{{$series}}/{{$slug}}" class="btn-nav-les finish-les">Làm lại&nbsp; <i class="fa fa-refresh fa-spin" aria-hidden="true"></i></a>

                                        @endif





                                    @endif



                                </div>





                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



            {!! Form::close() !!}

        @endif



        <!-- footer page -->





        @if(isset($value))

            <div class="modal fade" id="exampleModal" >

                <div class="modal-dialog modal-lg" role="document">

                    <div class="modal-content">

                        <div class="modal-body">

                            <div class="ct-cpl-screen">

                                <div class="info-cpl text-center">

                                    <h4 class="above-text text-primary">Kết quả: {{$value}} / {{$point}} điểm</h4>

                                    {{--<h4 class="below-text text-danger">Tổng:  điểm</h4>--}}

                                    <h5 class="below-text {{$passed == 0 ? "text-danger" : "text-success"}}">{{$passed == 0 ? "Chưa đạt yêu cầu bài kiểm tra" : "Đạt yêu cầu bài kiểm tra" }}</h5>

                                    <div class="score-bg-title"><img src="{{admin_asset("images/exercise/score-bg.png")}}" alt=""></div>

                                </div>



                            </div>



                        </div>

                    </div>

                </div>

            </div>

        @endif

    </div>



@stop



@section('footer_scripts')

    <script src="{{admin_asset('js/sweetalert-dev.js')}}"></script>



    @if(isset($point))

        <script>

            $(document).ready(function () {

                setTimeout(

                    function() {

                        $('#exampleModal').modal('show');

                    }, 1500);





            });





        </script>

    @else

        <script>

            var HOURS                           = 0;

            var MINUTES                         = 0;

            var SECONDS                         = 0;

            let AJAX_CALL_TIME                  = 1;

            let AJAX_CALL_MAX_SECONDS           = 5;



            let _elementTimne                   = $('#timerdiv');

            let _elementMin                     = $('#mins');

            let _elementSeconds                 = $('#seconds')

            $(document).ready(function () {

                let current_hours = 1;

                let current_minutes = 45;

                let current_seconds = 0;

                intilizetimer(current_hours, current_minutes, current_seconds);

                _elementMin.text(current_minutes);

                _elementSeconds.text('00');



            });



            function intilizetimer(hrs, mins, sec) {

                HOURS       = 0;

                MINUTES     = mins;

                SECONDS     = sec;

                startInterval();



            }



            function startInterval() {

                timer= setInterval("tictac()", 1000);

            }



            function checkTimer() {

                if(AJAX_CALL_MAX_SECONDS === AJAX_CALL_TIME) {
                    @if(Auth::check())
                    saveResumeExamData()
                    @endif
                    AJAX_CALL_TIME = 1;

                } else{

                    AJAX_CALL_TIME++;

                }



            }

            async function tictac(){

                SECONDS--;

                $('input[name="time"]').val(MINUTES+':'+ SECONDS);

                if(SECONDS<=0) {

                    MINUTES--;

                    checkTimer();



                    _elementMin.text(MINUTES);



                    if(MINUTES === 25) {

                        _elementTimne.removeClass('text-success');

                        _elementTimne.addClass('text-warning');

                        // alertify.alert("Bạn còn 10 phút để làm bài!", function(){});

                    }



                    if(MINUTES === 9) {

                        _elementTimne.removeClass('text-warning');

                        _elementTimne.addClass('text-danger');

                        // alertify.alert("Bạn còn 10 phút để làm bài!", function(){});

                    }

                    if(MINUTES <0) {



                        /*if(HOURS!=0) {

                          MINUTES = 59;

                          HOURS =  HOURS-1;

                          SECONDS = 59;

                          $("#mins").text(MINUTES);

                             $("#hours").text(HOURS);

                          return;

                        }*/

                        await stopInterval();

                        _elementMin.text('00');

                        _elementSeconds.text('00');

                        swal({   title: "Hết thời gian", type: 'success',  text: "Bạn đã hoàn thành bài thi",   timer: 3000,   showConfirmButton: false });

                       // await saveResumeExamData()

                        await setTimeout(

                             function() {

                                 $('form[name="formTest"]').submit();

                             }, 2200);

                    }

                    SECONDS = 59;

                }

                if(MINUTES >=0)

                    if (SECONDS < 10) {

                    _elementSeconds.text("0" + SECONDS);

                    } else {

                        _elementSeconds.text(SECONDS);

                    }

                else

                    _elementSeconds.text('00');

            }



            function stopInterval() {

                clearInterval(timer);

            }


            @if(Auth::check())
            function saveResumeExamData(){



                let formData = $('form[name="formTest"]').serialize();

                $.ajax({

                    headers: {

                        'X-CSRF-TOKEN':'{{csrf_token()}}'

                    },

                    url : '{{route('testLog')}}',

                    type : "post",

                    data:formData ,

                })/*.done(function(data) {

                  console.log(data)

                })*/;

            }
            @endif

        </script>

    @endif





{{--<script>

    var formSubmitting = false;

    var setFormSubmitting = function() { formSubmitting = true; };



    window.onload = function() {

        window.addEventListener("beforeunload", function (e) {

            if (formSubmitting) {

                return undefined;

            }



            var confirmationMessage = 'It looks like you have been editing something. '

                + 'If you leave before saving, your changes will be lost.';



            (e || window.event).returnValue = confirmationMessage; //Gecko + IE

            return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.

        });

    };

</script>--}}

@stop