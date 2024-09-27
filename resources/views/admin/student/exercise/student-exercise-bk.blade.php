@extends($layout)

@section('header_scripts')

    <link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i,800" rel="stylesheet">

    <style>
        .item-select >a {
            /*text-align: left !important;*/
            color: #0a0a0a;

        }

       
        ruby {
            display: inline-flex;
            flex-direction: column-reverse;
            /*//color: #0a0a0a;*/
        }

        rb, rt {
            display: inline;
            line-height: 1;

        }
        .kg-study-cuttom{

            width: 100%;

            position: relative;

            min-height: 50px;

            overflow: hidden;

        }

        .paragraph-les-cuttom {

            max-height: 40vh;

            overflow: auto;

        }

        .ct-lesson17 .block-type-cuttom {

            width: 100%;

            margin-left: 0!important;

        }





        .ct-lesson4 .block-type-cuttom {

            width: 100%!important;

        }



        .modal-wrapper{

            position: relative;

            overflow: auto;

            padding: 30px 30px 0;

            /*height: 100vh;*/

            height: auto;

        }

        .modal-dialog{

            margin-bottom: 0;

        }

        .modal-container{

            box-shadow: none;

            background: transparent;

            padding: 0;

            margin: 0 auto;

            transition: all .3s ease;

            font-family: Helvetica,Arial,sans-serif;

        }

        .modal-body {

            margin: 20px 20px 0;

            overflow: hidden;

            padding-bottom: 0;

        }

        .button-rest{

            right: 10px!important;

            top: 5px!important;

            left: auto!important;



        }

        .button-rest a{

            background:#cec9c9!important ;

            padding: 4px 12px!important;

            font-size: 12px!important;

        }

        .button-rest a i {

            margin-right: 5px;

        }

        .ques-block-empty .text-focus-ques{
            margin: 15px 4px!important;
        }
    </style>

@stop

@section('content')

    <div id="page-wrapper">



        <div class="header-les d-flex justify-content-between">

            <div  class="name-les-back">

                <a  href="{{$back_url}}" class="back-icon">

                    <i  aria-hidden="true" class="fa fa-angle-left"></i>

                </a>

                <span  class="name-les">Quay lại bài học</span>





            </div>

            <div style="flex: 2;" class="text-center"><h4 class="font-weight-bold text-success"><i class="fa fa-file-text-o mr-2" aria-hidden="true"></i>{!! $name->bai !!}</h4></div>



            <h3  class="score">

                    <span  class="ani-star animate">

                        <i aria-hidden="true" class="fa fa-star-o star1"></i>

                        <i  aria-hidden="true" class="fa fa-star-o star2"></i>

                        <i  aria-hidden="true" class="fa fa-star-o star3"></i>

                    </span>

                <i  aria-hidden="true" class="fa fa-star-o star-main"></i>

                <span  class="total-current animated bounceIn"></span>

                <span  class="total-les">0</span>

                <span>/ {{$count_records}} điểm</span>

            </h3>





            <h3  class="score">

                     <span  class="ani-star animate">

                        <i aria-hidden="true" class="fa fa-question star1"></i>

                        <i  aria-hidden="true" class="fa fa-question star2"></i>

                        <i  aria-hidden="true" class="fa fa-question star3"></i>

                    </span>

                <i  aria-hidden="true" class="fa fa-question star-main"></i>

                <span  class="total-current animated bounceIn"></span>

                <span  class="cau">1</span>

                <span>/ {{$count_records}} câu</span>

            </h3>

            <h3  class="nb-les">

                <span class="nb-cpl">1</span>/<span  class="nb-total">4</span>

            </h3>





            {{--<div class="list-parts-switch">

                <i class="fa fa-list"></i>

                <div class="list-parts-switch-items" style="display: none;">

                    <ul>

                        <li>

                             <a href="javascript:void(0)">Cấu trúc ngữ pháp</a>

                        </li>

                        <li>

                            <a href="javascript:void(0)">Kiểm tra ngữ pháp</a>

                        </li>

                        <li>

                            <a  href="javascript:void(0)">Kéo thả chọn đáp án đúng</a>

                        </li>

                        <li>

                            <a href="javascript:void(0)">Sắp xếp câu</a>

                        </li>

                    </ul>

                </div>

            </div>--}}

        </div>





        <div class="wp-content-les">



            <div class="container">



                <div class="row">

                    <div class="kg-study kg-study-cuttom">

                        <div id="data-exercise-hid">

                        </div>

                    </div>

                </div>

            </div>

        </div>



        <!-- footer page -->

        <div class="wp-btn-progress-les">

            <div class="progress-les">

                <div class="wp-ct-prg">

                    <div class="bar-prg">

                        <div class="main-bar" style="width: 0%;"></div>

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

            <div  class="btn-result">

                <div class="container">

                    <div class="row">

                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-10 offset-xl-1">

                            <div  class="ct-btn-result d-flex justify-content-between">

                                <div  class="left-ct">

                                   {{-- <div class="btn-group-les">



                                    </div>--}}

                                    <div class="result-ntf">



                                    </div>

                                </div>

                                <div class="right-ct">

                                    <div class="btn-group-les">



                                        {{--

                                        <a  href="javascript:;" class="btn-nav-les btn-check-les">Kiểm tra</a>

                                        <a href="javascript:;" class="btn-nav-les btn-result-corect-les">Xem đáp án</a>

                                        <a href="javascript:;" class="btn-nav-les finish-les">Hoàn thành &nbsp; <i  aria-hidden="true" class="fa fa-angle-double-right"></i></a>

                                    --}}

                                    </div>





                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>







@stop



@section('footer_scripts')



@include('admin.student.exercise.js.exercise-js',array('exercise' =>$records))



@stop