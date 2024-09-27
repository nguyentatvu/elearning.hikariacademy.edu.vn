@extends($layout)
@section('header_scripts')
    <link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i,800" rel="stylesheet">

   {{-- <link href="{{admin_asset('css/exercise/bundles.min.css')}}" rel="stylesheet">--}}
    <link href="{{admin_asset('css/file/application.css')}}" rel="stylesheet">
    <link href="{{admin_asset('css/flashcard/animate.min.css')}}" rel="stylesheet">
    <link href="{{admin_asset('css/flashcard/flash.css')}}" rel="stylesheet">
    <style>
        .none-after{
            content: none !important;
        }

        #accordian h3 a.none-after:after{
            content: none;
        }
        #accordian .active> h3 a.none-after:after{
            content: none;
        }
        #accordian h3 a {
            padding: 0 10px;
            font-size: 15px;
            line-height: 34px;
            display: block;
            color: #232731;
            text-decoration: none;
            position: relative;
            text-align: start;
            padding-right: 45px;

        }

        #accordian h3:hover {
            text-shadow: 0 0 1px rgba(255, 255, 255, 0.7);
        }

        #accordian ul{
            padding-left: 5px;
        }
        #accordian li {
            list-style-type: none;
        }

        #accordian ul ul li a,
        #accordian h4 {
            color: #232731;
            text-decoration: none;
            font-size: 13px;
            line-height: 27px;
            display: block;
            padding: 0 15px;
            transition: all 0.15s;
            position: relative;
            text-align: start;
        }

        #accordian ul ul li a:hover {
            background: #185384;
            border-left: 3px solid #f48133;
            color: #fafaf4!important;
        }

        #accordian ul ul {
            display: none;
        }

        #accordian li.active>ul {
            display: block;
        }

        #accordian ul ul ul {
            margin-left: 15px;
            border-left: 1px dotted rgba(0, 0, 0, 0.5);
        }

        #accordian ul li ul {
            margin-left: 15px;
            border-left: 1px dotted rgba(0, 0, 0, 0.5);
        }
        #accordian a:not(:only-child):after {
            content: "\f104";
            font-family: fontawesome;
            position: absolute;
            right: 15px;
            top: 0;
            font-size: 14px;
            /*color: #232731;*/
        }

        #accordian .active>a:not(:only-child):after {
            content: "\f107";
        }

        #accordian h3 a:after {
            content: "\f104";
            font-family: fontawesome;
            position: absolute;
            right: 15px;
            font-size: 14px;
        }
        #accordian .active> h3 a:after {
            content: "\f107";
        }
        #accordian ul li a i {
            color: #2196f3;
        }
        .header-les .score i {
            margin-right: 0;
        }

        .disable-links {
            pointer-events: none;
        }


        .switch_3_ways_v2 {
            margin: auto;
            font-size: 1em;
            height: 2em;
            line-height: 2em;
            background: #e2e1e1;
            position:relative;
            display:block;
            float:left;
            border-radius: 5px;
        }

        .switch2.monthly,
        .switch2.semester,
        .switch2.annual {
            cursor: pointer;
            position:relative;
            display:block;
            float:left;
            -webkit-transition: 300ms ease-out;
            -moz-transition: 300ms ease-out;
            transition: 300ms ease-out;
            padding: 0 1em;
        }
        .switch2.active{
            color:white;
            border-radius:0.3em;
            -moz-box-shadow: 0px 0px 7px 1px #656565;
            -webkit-box-shadow: 0px 0px 7px 1px #656565;
            -o-box-shadow: 0px 0px 7px 1px #656565;
            box-shadow: 0px 0px 7px 1px #656565;
            background-color:#1a17ee;
            filter:progid:DXImageTransform.Microsoft.Shadow(color=#656565, Direction=NaN, Strength=7);

        }

        .selector {
            text-align: center;
            position: absolute;
            width: 0;
            box-sizing: border-box;
            -webkit-transition: 300ms ease-out;
            -moz-transition: 300ms ease-out;
            transition: 300ms ease-out;
            border-radius: 0.3em;
            color: white;
            -moz-box-shadow: 0px 2px 13px 0px #9b9b9b;
            -webkit-box-shadow: 0px 2px 13px 0px #9b9b9b;
            -o-box-shadow: 0px 2px 13px 0px #9b9b9b;
            box-shadow: 0px 2px 13px 0px #9b9b9b;
            filter: progid:DXImageTransform.Microsoft.Shadow(color=#9b9b9b, Direction=180, Strength=13);
        }

    </style>
@stop

@section('content')


    <!--Breadcrumb-->
    <div class="bg-white border-bottom">
        <div class="container">
            <div class="page-header">
                <h3 class=" text-primary ">
                    {{ $current_series->title }}
                </h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/home">
                            Trang chủ
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{PREFIX.'lms/exam-categories/list'}}">
                            Khóa học
                        </a>
                    </li>
                    <li aria-current="page" class="breadcrumb-item active">
                        {{ $current_series->title }}
                    </li>
                </ol>
            </div>
        </div>
    </div>
    <!--/Breadcrumb-->


    <section class="sptb">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-md-12">
                    <!--Coursed Description-->
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="item-det mb-4">
                                <a class="text-dark text-primary" href="#">
                                    <h3 class="font-weight-semibold">
                                        {{$current_lesson}}
                                    </h3>
                                </a>
                            </div>
                            <div class="product-slider" id="edm_player_zone">
                                <div class="row lecture-player-main no-margin justify-content-center">
                                    {{--<input type="checkbox" id="loop" onclick="checkloop(this)" value=""  aria-hidden="true">--}}

                                    @if(count($records) >0)
                                        @foreach($records as  $r)

                                            <div class="flashcard" data-card-id="{{$loop->index+1}}" >
                                                <input type="checkbox" id="card{{$loop->index+1}}" class="more" aria-hidden="true">
                                                <div class="content">
                                                    <!-- <div class="front" aria-hidden="true" style="background-image: url({{PREFIX.'public/assets/images/banners/subscribe.jpg'}})"> -->
													<div class="front" aria-hidden="true">
                                                        <label for="card{{$loop->index+1}}"  aria-hidden="true">
                                                            <div class="inner">
                                                                <p class="front1">{!!  change_furigana( $r->m1tuvung ,'echo'); !!}</p>
                                                                <p class="front2">{!!  change_furigana( $r->m2cachdoc ,'echo'); !!}</p>
                                                                <p class="front3">{!!  change_furigana( $r->m1vidu ,'echo'); !!}</p>
                                                                
                                                                <div class="tutorial">
                                                                    <p style="color:#d7d8d5;font-size: 0.8em;">クリックして反転</p>
                                                                </div>
                                                            </div>
                                                            <div id="audio{{$loop->index+1}}" data-audio ="{{$r->mp3}}" style="display: none"></div>

                                                        </label>

                                                    </div>
                                                    <div class="back">
                                                        <label for="card{{$loop->index+1}}"  aria-hidden="true">
                                                            <div class="inner">
                                                                    <p class="back1">{!!  change_furigana( $r->m2ynghia ,'echo'); !!}</p>
                                                                    <p class="back2">{!!  change_furigana( $r->m2amhanviet ,'echo'); !!}</p>
                                                                    <p class="back3">{!!  change_furigana( $r->m2vidu ,'echo'); !!}</p>
                                                                
                                                                <div class="tutorial">
                                                                    <p>Click để lật mặt</p>
                                                                </div>
                                                            </div>
                                                        </label>

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                </div>

                                <div class="text-center justify-content-center">
                                    <a  class="page page-prev align-middle text-primary"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
                                    <a  class="total_counter justify-content-center align-middle fs-16 text-primary">1</a>
                                    <a  class="page page-next align-middle text-primary"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>

                                </div>
                                {{--<div class="text-center justify-content-end">
                                    <label class="custom-switch  align-middle">
                                        <input type="checkbox" name="loop" id="loop" onclick="checkloop(this)" class="custom-switch-input">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Tự động chuyển</span>
                                    </label>

                                    <label class="custom-switch  align-middle">
                                        <input type="checkbox" name="rloop" id="rloop" onclick="randomloop(this)" class="custom-switch-input">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Xem ngẫu nhiên</span>
                                    </label>
                                </div>--}}



                                    <div class="text-center justify-content-end mt-3">
                                       <div style="display: inline-flex" class="align-middle">
                                           <div class="switch_3_ways_v2 ">
                                               <div id="monthly2" data-loop="1" class="switch2 monthly" onclick="change_period2('monthly')">Tự động chuyển</div>
                                               <div id="semester2" data-loop="0"  class="switch2 semester active" onclick="change_period2('semester')">Ngừng</div>
                                               <div id="annual2" data-loop="2"  class="switch2 annual" onclick="change_period2('annual')">Xem ngẫu nhiên</div>
                                               <div id="selector" class="selector"></div>
                                           </div>
                                       </div>


                                    </div>
                                
                                    <div class="text-center justify-content-end mt-3">
                                        <label class="custom-switch  align-middle">
                                            <input type="checkbox" name="sAudio" id="sAudio" {{--onclick="switchAudio(this)"--}} class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">Âm thanh</span>
                                        </label>
                                    </div>

                            </div>
                        </div>
                    </div>

                    @if(count($records) >0)
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Danh sách từ vựng</h3>
                            </div>
                        @foreach($records as  $r)


                                <div class="card-body p-0">
                                    <div class="media mt-0 p-2 px-5">
                                        <div class="d-flex w-5   ">
                                            <p class="font-13  mb-2 mt-2">{{$loop->index+1}}</p>
                                        </div>
                                        <div class="d-flex w-25">
                                            <p class="font-13  mb-2 mt-2">{!!  change_furigana( $r->m1tuvung ,'echo'); !!}</p>
                                        </div>
                                        <div class="media-body w-65">
                                            <p class="font-13  mb-2 mt-2">{!!  change_furigana( $r->m2ynghia ,'echo'); !!}</p>

                                            <p class="font-13  mb-2 mt-2">{!!  change_furigana( $r->m2cachdoc ,'echo'); !!}</p>
                                            <p class="font-13  mb-2 mt-2">{!!  change_furigana( $r->m2amhanviet ,'echo'); !!}</p>
                                        </div>
                                        <div class="d-flex w-5 justify-content-end " >
                                            <p onclick="audioPlay('{{'/public/uploads/flashcard/'.$r->mp3}}')"  class="pt-5 volume-p justify-content-center align-middle" data-toggle="tooltip" data-placement="top" title="Phát âm">
                                                <i class="fa  fa-volume-up" aria-hidden="true"></i>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                        @endforeach
                            </div>
                    @endif

                    <div class="card panel panel-primary">
                        <div class="tab-menu-heading">
                            <div class="tabs-menu ">
                                <!-- Tabs -->
                                <ul class="nav panel-tabs">
                                    <li class="">
                                        <a class="active font-weight-bold  fs-18" data-toggle="tab" href="#tab1">
                                            Mô tả khóa học
                                        </a>
                                    </li>
                                    @if(Auth::check() && ((isset($checkpay->payment) && $checkpay->payment > 0) ||Auth::user()->role_id == 6 ))
                                        <li>
                                            <a class="font-weight-bold  fs-18" data-toggle="tab" href="#tab2">
                                                Đặt câu hỏi
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body tabs-menu-body">
                            <div class="tab-content">
                                <div class="tab-pane active " id="tab1">
                                    <p>
                                        {!!  change_furigana_title($hi_koi->description) !!}
                                    </p>
                                </div>
                                <div class="tab-pane " id="tab2">
                                    @if(Auth::check() && ((isset($checkpay->payment) && $checkpay->payment > 0) ||Auth::user()->role_id == 6 ))


                                        <div class="">
                                            <div class="card-header">
                                                <h3 class="card-title">Câu hỏi của bạn</h3>
                                            </div>

                                            <div class="card-body p-0" id="comment_boby">
                                                @if(count($comment) > 0)
                                                    @foreach($comment as $r)

                                                        <?php

                                                        $name = $r->user_name;
                                                        if ($r ->admin_id !== null ){
                                                            $name = DB::table('users')
                                                                ->select('name')
                                                                ->where('id' ,$r ->admin_id)
                                                                ->first()->name;
                                                        }
                                                        ?>
                                                        <div class="media mt-0 p-5 border-top" id="comment_id_{{$r->id}}">
                                                            <div class="media-body">
                                                                <h4 class="mt-0 mb-1 font-weight-bold">
                                                                    {{$name}}
                                                                    <span class="fs-14 ml-0" data-toggle="tooltip" data-placement="top" title="" data-original-title="verified"><i class="fa fa-check-circle-o text-success"></i></span>
                                                                </h4>
                                                                <small class="text-muted">
                                                                    <i class="fa fa-calendar"></i>
                                                                    {{date_format(date_create($r->created_at),"d-m-Y H:m:i")}}
                                                                </small>
                                                                <p class="font-13  mb-2 mt-2">
                                                                    {{$r->body}}
                                                                </p>

                                                                <a href="javascript:void(0)" class="mr-2" onclick="myModal({{$r->id}})"><span class="badge badge-default">Comment</span></a>
                                                                @if(count($comment_child) > 0)
                                                                    @foreach($comment_child as $cr)
                                                                        @if($cr->parent_id == $r->id)
                                                                            <?php
                                                                            $cname = $cr->user_name;
                                                                            if ($cr ->admin_id !== null ){
                                                                                $cname = DB::table('users')
                                                                                    ->select('name')
                                                                                    ->where('id' ,$cr ->admin_id)
                                                                                    ->first()->name;
                                                                            }
                                                                            ?>
                                                                            <div class="media mt-5">
                                                                                <div class="d-flex mr-5">
                                                                                </div>
                                                                                <div class="media-body">
                                                                                    <h4 class="mt-0 mb-1 font-weight-bold">{{$cname}} <span class="fs-14 ml-0" data-toggle="tooltip" data-placement="top" title="" data-original-title="verified"><i class="fa fa-check-circle-o text-success"></i></span></h4>
                                                                                    <small class="text-muted">
                                                                                        <i class="fa fa-calendar"></i> {{date_format(date_create($r->created_at),"d-m-Y H:m:i")}}
                                                                                    </small>
                                                                                    <p class="font-13  mb-2 mt-2">
                                                                                        {{$cr->body}}
                                                                                    </p>
                                                                                    <a href="javascript:void(0)" onclick="myModal({{$r->id}})"><span class="badge badge-default">Comment</span></a>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @endif

                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="media mt-0 p-5 border-top">
                                                        <div class="media-body">
                                                            <p class="mt-0 ml-5 mb-1 ">
                                                                Bạn chưa có câu hỏi
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <p></p>
                                                @endif
                                            </div>
                                        </div>


                                        <div class=" mb-lg-0">
                                            <div class="card-header">
                                                <h3 class="card-title">Đặt câu hỏi</h3>
                                            </div>
                                            <div class="card-body">
                                                {{ Form::model(null,array('url' => url('comments/add'),'method'=>'post', 'files' => false, 'name'=>'formLms', 'novalidate'=>'')) }}
                                                <input hidden name="user_id" value="{{Auth::id()}}">
                                                <input hidden name="lmsseries_slug" value="{{$series}}">
                                                <input hidden name="lmscombo_slug" value="{{$combo_slug}}">
                                                <input hidden name="lmscontent_id" value="{{$slug}}">
                                                <input hidden name="parent_id" value="0">
                                                <div class="form-group">

                                                    {{ Form::textarea('body', $value = null , $attributes = array('class'=>'form-control','required'=> 'true', 'rows'=>'5')) }}
                                                </div>

                                                <div class="text-right">
                                                    <button onclick="onComment(event)" class="btn btn-primary ">Gửi</button>
                                                </div>

                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-12">

                    <div class="card overflow-hidden">
                        {{--<div class="ribbon ribbon-top-right text-danger">
                            <span class="bg-danger">
                                Đã mua
                            </span>
                        </div>--}}
                        <div class="card-header">
                            <h3 class="card-title">
                                Bài học
                            </h3>
                        </div>
                        <div class="card-body item-user" style="padding: 1.5rem 0.75rem">
                            <div class="profile-pic mb-0">
                                <div class="container-fluid" id="wrapper_new_lecture">
                                    <div class="content_lecture">
                                        <div class="show_lecture_course ">
                                            <div aria-expanded="true" class="content_show_lecture" style="display: block;">
                                                <div id="accordian">
                                                    <ul>
                                                        {!!  $lesson_menu !!}
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(isset($total_course))
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Tiến độ khóa học
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <p class="mb-2">
                                            <span class="fs-14 ml-2">
                                                <i class="fa fa-star text-yellow mr-2">
                                                </i>

                                                Hoàn thành: {{$current_course}}/{{$total_course}} bài học

                                            </span>
                                        </p>
                                        <div class="progress position-relative">
                                            <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo (int)ceil((($current_course/$total_course)*100))?>" class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?php echo ceil((($current_course/$total_course)*100))?>%">
                                            </div>
                                            <small class="justify-content-center d-flex position-absolute w-100">
                                                <?php echo (int)ceil((($current_course/$total_course)*100))?>
                                                %
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!--Right Side Content-->

            </div>
        </div>
    </section>



    <!--Comment Modal -->
    @if(Auth::check() && ((isset($checkpay->payment) && $checkpay->payment > 0) ||Auth::user()->role_id == 6 ))
        <div aria-hidden="true" class="modal fade" id="Comment" role="dialog" style="display: none;" tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleCommentLongTitle">
                            Đặt câu hỏi
                        </h5>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                        <span aria-hidden="true">
                            ×
                        </span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ Form::model(null,array('url' => url('comments/add'),'method'=>'post', 'files' => false, 'name'=>'formComments', 'novalidate'=>'')) }}
                        <input hidden="" name="user_id" value="{{Auth::id()}}">
                        <input hidden="" name="parent_id" value="">
                        <input hidden="" name="lmsseries_slug" value="{{$series}}">
                        <input hidden="" name="lmscombo_slug" value="{{$combo_slug}}">
                        <input hidden="" name="lmscontent_id" value="{{$slug}}">
                        <div class="form-group">
                            {{ Form::textarea('body', $value = null , $attributes = array('class'=>'form-control','required'=> 'true', 'rows'=>'5', 'placeholder' => getPhrase('Comment'))) }}
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" data-dismiss="modal" type="button">
                            Hủy
                        </button>
                        <button class="btn btn-success" onclick="upComment(event)" type="button">
                            Gửi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- /Comment Modal -->
@stop



@section('footer_scripts')



    <script src="{{admin_asset('js/scroll-to-comment.js')}}"></script>
    <script>
        $(document).ready(function() {
            $("#accordian a").click(function() {
                let link = $(this);
                let closest_ul = link.closest("ul");
                let parallel_active_links = closest_ul.find(".active")
                let closest_li = link.closest("li");
                let link_status = closest_li.hasClass("active");
                let count = 0;

                closest_ul.find("ul").slideUp(function() {
                    if (++count === closest_ul.find("ul").length)
                        parallel_active_links.removeClass("active");
                });

                if (!link_status) {
                    closest_li.children("ul").slideDown();
                    closest_li.addClass("active");
                }
            })


            let _elmentActive = $('li.lesson_active');
            if (_elmentActive.find('ul').length === 0){
                _elmentActive.parent().parent().children().children().css('color','white');
                _elmentActive.parent().parent().addClass("active");
                _elmentActive.parent().parent().parent().parent().addClass("active");
                _elmentActive.children().addClass('active-content');
                _elmentActive.parent().parent().children('a:first').addClass("active-content");
                $('#accordian').find('li.active h3 a').addClass("active-content");
                _elmentActive.parent().slideDown();
            }
        })
        function showpayment(){
            swal({
                title: "Yêu cầu sở hữu khóa học",
                text: "Vui lòng sở hữu khóa học để xem nội này",
                type: "warning",
                showCancelButton: false,
                confirmButtonColor: '#8CD4F5',
                /* confirmButtonClass: "btn-danger",*/
                confirmButtonText: "Đồng ý",
                /*  cancelButtonText: "Hủy bỏ",*/
                closeOnConfirm: false,
                closeOnCancel: true
            });
        }
        @if(Auth::check() && ((isset($checkpay->payment) && $checkpay->payment > 0) || Auth::user()->role_id == 6))
        function onComment(e){
            e.preventDefault();
            let form = $('form[name="formLms"]');

            if (form.find('textarea').val().length == 0){
                swal({
                    title: "Thông báo",
                    text: "Vui lòng nhập thông tin phản hồi",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: '#8CD4F5',
                    confirmButtonText: "Đồng ý",
                    closeOnConfirm: false,
                    closeOnCancel: true

                });
                return;
            }


            let route = form.attr('action');
            let data = form.serialize();


            $.ajax({
                headers: {

                    'X-CSRF-TOKEN':'{{csrf_token()}}'
                },
                url:route,
                type: 'post',
                dataType: "json",
                data: data,
                beforeSend: function() {
                    // setting a timeout
                    swal({
                        html:true,
                        title: 'Đang xử lý vui lòng chờ',
                        text: '<img style="position: relative;" src="/public/assets/images/loader.svg" class="loader-img" alt="img">',
                        type: '',
                        showConfirmButton: false,
                        showCancelButton: false,

                    });
                },
                success:function(data){

                    //console.log(data)
                    if(data.error === 1){
                        $('textarea[name="body"]').val('');

                        $.ajax({
                            headers: {

                                'X-CSRF-TOKEN':'{{csrf_token()}}'
                            },
                            url: '{{url('comments/index')}}',
                            type: 'post',
                            dataType: "json",
                            data: {
                                slug : '{{$series}}',
                                combo_slug : '{{$combo_slug}}',
                                id : '{{$slug}}',
                            },
                            success:function(data){
                                console.log(data)
                                if(data.error === 1) {
                                    $('#comment_boby').empty();
                                    $('#comment_boby').html(data.message)
                                }
                            }
                        })
                        swal({
                            title: 'Thông báo',
                            text: data.message,
                            type: 'success',
                            showConfirmButton: false,
                            showCancelButton: false,
                            timer: 3000,
                        });
                    }else {
                        swal({
                            title: 'Thông báo',
                            text: data.message,
                            type: 'warning',
                            showConfirmButton: false,
                            showCancelButton: false,
                            timer: 3000,
                        });
                    }

                }
            })

        }


        function myModal(id){

            let form = $('form[name="formComments"]');
            /* $('input[name="parent_id"]').val(id);*/

            form.find('input[name="parent_id"]').val(id)
            $('#Comment').modal('show')
        }

        function upComment(e){
            e.preventDefault();




            let form = $('form[name="formComments"]');

            if (form.find('textarea').val().length == 0){
                swal({
                    title: "Thông báo",
                    text: "Vui lòng nhập thông tin phản hồi",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: '#8CD4F5',
                    confirmButtonText: "Đồng ý",
                    closeOnConfirm: false,
                    closeOnCancel: true

                });
                return;
            }


            let route = form.attr('action');
            let data = form.serialize();


            $.ajax({
                headers: {

                    'X-CSRF-TOKEN':'{{csrf_token()}}'
                },
                url:route,
                type: 'post',
                dataType: "json",
                data: data,
                beforeSend: function() {
                    // setting a timeout
                    swal({
                        html:true,
                        title: 'Đang xử lý vui lòng chờ',
                        text: '<img style="position: relative;" src="/public/assets/images/loader.svg" class="loader-img" alt="img">',
                        type: '',
                        showConfirmButton: false,
                        showCancelButton: false,

                    });
                },
                success: function(data){

                    //console.log(data)
                    if(data.error === 1){
                        $('textarea[name="body"]').val('');

                        $.ajax({
                            headers: {

                                'X-CSRF-TOKEN':'{{csrf_token()}}'
                            },
                            url: '{{url('comments/index')}}',
                            type: 'post',
                            dataType: "json",
                            data: {
                                slug : '{{$series}}',
                                combo_slug : '{{$combo_slug}}',
                                id : '{{$slug}}',
                            },
                            success:function(data){
                                //console.log(data)
                                if(data.error === 1) {
                                    $('#comment_boby').empty();
                                    $('#comment_boby').html(data.message)
                                }
                            }
                        })


                        swal({
                            title: 'Thông báo',
                            text: data.message,
                            type: 'success',
                            showConfirmButton: false,
                            showCancelButton: false,
                            timer: 3000,
                        });
                    }else {
                        swal({
                            title: 'Thông báo',
                            text: data.message,
                            type: 'warning',
                            showConfirmButton: false,
                            showCancelButton: false,
                            timer: 3000,
                        });
                    }
                    $('#Comment').modal('hide');
                }


            })

        }
        @endif


    </script>

    @include('admin.student.flashcard.script.flashscript',array('$records' =>$records))
@stop