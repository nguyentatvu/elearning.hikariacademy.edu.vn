@extends('admin.layouts.sitelayout')
@section('header_scripts')
<link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i,800" rel="stylesheet">

<link href="{{admin_asset('css/exercise/bundles.min.css')}}" rel="stylesheet">
<link href="{{admin_asset('css/file/application.css')}}" rel="stylesheet">
<style>
	.item-select >a {
		color: #0a0a0a;
	}
/*
	ruby {
		display: inline-flex;
		flex-direction: column-reverse;
	}

	rb, rt {
		display: inline;
		line-height: 1;
	}
*/
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

	.modal-dialog{
		margin-bottom: 0;
	}

	.kg-study .modal-content{
		border: none;
	}
	.kg-study .modal-container{
		box-shadow: none;
		background: transparent;
		padding: 0;
		margin: 0 auto;
		transition: all .3s ease;
		font-family: Helvetica,Arial,sans-serif;
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

	.kg-study .close-popup-les i{
		margin-right: 0;
	}
	.ques-block-empty .text-focus-ques{
		margin: 15px 4px!important;
	}
	.wp-btn-progress-les{
		position: unset;
	}
	.header-les .score i {
		margin-right: 0;
	}
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
		padding-right: 1.75rem;
		font-weight: 600;
	}
	#accordian h3:hover {
		text-shadow: 0 0 1px rgba(255, 255, 255, 0.7);
	}
	i {
		margin-right: 10px;
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
		font-weight: 500;
		text-align: start;
		padding-right: 45px;
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
		top: 0;
	}
	#accordian .active> h3 a:after {
		content: "\f107";
	}
	#accordian ul li a i {
		color: #2196f3;
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
                                <div class="row lecture-player-main no-margin ">
                                    <!-- header page -->
                                    <div class="header-les  justify-content-between pr-0" style="width: 100%;">
                                        <h5  class="score float-right fs-14">

                                             <span  class="ani-star animate">

                        <i aria-hidden="true" class="fa fa-star-o star1"></i>

                        <i  aria-hidden="true" class="fa fa-star-o star2"></i>

                        <i  aria-hidden="true" class="fa fa-star-o star3"></i>

                    </span>

                                            <i  aria-hidden="true" class="fa fa-star-o star-main"></i>

                                            <span  class="total-current animated bounceIn"></span>

                                            <span  class="total-les">0</span>

                                            <span>/ {{$count_records}} điểm</span>

                                        </h5>
                                        <h5  class="score float-right fs-14">

                                            <span  class="ani-star animate">

                        <i aria-hidden="true" class="fa fa-question star1"></i>

                        <i  aria-hidden="true" class="fa fa-question star2"></i>

                        <i  aria-hidden="true" class="fa fa-question star3"></i>

                    </span>

                                            <i  aria-hidden="true" class="fa fa-question star-main"></i>

                                            <span  class="total-current animated bounceIn"></span>

                                            <span  class="cau">1</span>

                                            <span>/ {{$count_records}} câu</span>

                                        </h5>

                                    </div>

                                    <!-- body page -->
                                    <div class="kg-study kg-study-cuttom">

                                        <div id="data-exercise-hid">

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

                                                                <div class="result-ntf">

                                                                </div>

                                                            </div>

                                                            <div class="right-ct">

                                                                <div class="btn-group-les">

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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

                    @if(Auth::check())
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



    @include('admin.student.exercise.js.exercise-js',array('exercise' =>$records))

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
                _elmentActive.parent().parent().parent().parent().find('h3 a').addClass("active-content");
                _elmentActive.parent().slideDown();
            }
        })

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

@stop