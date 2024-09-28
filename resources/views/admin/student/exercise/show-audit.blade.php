@php
    $array_char = ['a','b','c','d'];

    $headerBlock = '<div class="kg-study kg-study-cuttom">

                                            <div id="data-exercise-hid">

                                                <!--<h3 class="guide-user-les desc-web text-danger">Hãy đọc đoạn văn dưới đây và trả lời các câu hỏi liên quan</h3>-->

                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">

                                                    <div class="ct-lesson ct-lesson17">';



    $footerBlock = '</div></div></div></div>';

@endphp

@extends('admin.layouts.sitelayout')
@section('header_scripts')
<link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i,800" rel="stylesheet">

<link href="{{admin_asset('css/file/application.css')}}" rel="stylesheet">
{{--<link href="{{admin_asset('css/exercise/bundle.min.css')}}" rel="stylesheet">--}}
<link href="{{admin_asset('css/exercise/audit.css')}}" rel="stylesheet">
<style>
	.none-after{
		content: none !important;
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
	.audit-show{
		max-height: 150vh;
		overflow-y: scroll;
	}

	.audit-show::-webkit-scrollbar-thumb {
		background-color: #464d5d;
		border-radius: 3px;
	}
	.audit-show::-webkit-scrollbar {
		width: 6px;
		background-color: #fff;
	}
	.audit-show::-webkit-scrollbar {
		background-color: #f0f1f4;
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
                        <div class="card-body " >
                            <div class="item-det mb-4">
                                <a class="text-dark text-primary" href="#">
                                    <h3 class="font-weight-semibold">
                                        {{$current_lesson}}
                                    </h3>
                                </a>
                            </div>
                            @if(isset($records))



                                {{ Form::model( null,array('url' => 'learning-management/lesson/audit/'.$combo_slug.'/'.$series.'/'.$slug, 'method'=>'post','novalidate'=>'','name'=>'formTest', 'files'=>'false' )) }}



                                {!! Form::hidden('content_id',$slug) !!}

                                {!! Form::hidden('time',null) !!}

                                <div class="wp-content-les audit-show   ">

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



                                                                @if(!isset($totalValue))

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

    <div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    @if(isset($point))
                        <div class="ct-cpl-screen">

                            <div class="info-cpl text-center">

                             {{--   @php
                                    if (!$value){$value = 0;}
                                @endphp--}}
                                <h4 class="above-text text-primary">Kết quả: {{$totalValue}} / {{$point}} điểm</h4>

                               {{-- <h4 class="below-text text-danger">Tổng:  điểm</h4>--}}

                                <h5 class="below-text {{$passed == 0 ? "text-danger" : "text-success"}}">{{$passed == 0 ? "Chưa đạt yêu cầu bài kiểm tra" : "Đạt yêu cầu bài kiểm tra" }}</h5>

                                <div class="score-bg-title"><img src="{{admin_asset("images/exercise/score-bg.png")}}" alt=""></div>

                            </div>

                            @endif

                        </div>
                </div>
            </div>
        </div>
    </div>

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


    @if(isset($point))

        <script>

            $(document).ready(function () {

                setTimeout(

                    function() {

                        $('#resultModal').modal('show');

                    }, 1500);

            });





        </script>

    @else

        <script>
            /*$(document).ready(function () {

                setTimeout(

                    function() {

                        $('#resultModal').modal('show');

                    }, 1500);

            });*/

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
@stop