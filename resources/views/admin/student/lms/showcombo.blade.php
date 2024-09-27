<?php /*dump($checkpay);
die();*/?>

@extends('admin.layouts.sitelayout')

@section('content')
    <!--Breadcrumb-->
    <div class="bg-white border-bottom">
        <div class="container">
            <div class="page-header">
                <h4 class="page-title">{{$record_combo->title}}</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{PREFIX.'lms/exam-categories/list'}}">Khóa học</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{$record_combo->title}}</li>
                </ol>
            </div>
        </div>
    </div>
    <!--/Breadcrumb-->

    <!--/Section-->
    <section class="sptb">
        <div class="container">
            <div class="row">



                <!--Right Side Content-->

                @if((isset($checkpay->payment) && $checkpay->payment > 0) || (Auth::check() && Auth::user()->role_id == 6))


                    <div class="col-xl-8 col-lg-8 col-md-12">
                        <!--Coursed lists-->
                        <div class=" mb-lg-0">
                            <div class="">
                                <div class="item2-gl ">
                                    <div class="tab-content" style="padding-top: 0;">
                                        <div class="tab-pane active" id="tab-11">


                                            @if(count($series) > 0)
                                                @foreach($series as $r)
                                                    <div class="card overflow-hidden">
                                                        <div class="d-md-flex">
                                                            <div class="item-card9-img">
                                                                <div class="item-card9-imgs">
                                                                    @if((isset($r->payment) && $r->payment > 0) || (Auth::check() && Auth::user()->role_id == 6))
                                                                        <a href="{{PREFIX.'learning-management/lesson/show/'.$record_combo->slug.'/'.$r->slug}}"></a>
                                                                    @elseif($r->try_lmscontents > 0)
                                                                        <a href="{{PREFIX.'learning-management/lesson/show/'.$record_combo->slug.'/'.$r->slug}}"></a>
                                                                    @else
                                                                        <a href="javascript:void(0)" onclick="showpayment()" ></a>
                                                                    @endif
                                                                    <img src="{{ IMAGE_PATH_UPLOAD_LMS_SERIES.$r->image}}" alt="img" class="cover-image">
                                                                </div>
                                                              {{--  <div class="item-card9-icons">
                                                                    <a href="#" class="item-card9-icons1 text-danger"> <i class="fa fa-heart"></i></a>
                                                                    <a href="#" class="item-card9-icons1 bg-black-trasparant"> <i class="fa fa fa-share-alt"></i></a>
                                                                </div>--}}
                                                                <div class="item-overly-trans">
                                                                    @if((isset($r->payment) && $r->payment > 0) || (Auth::check() && Auth::user()->role_id == 6))
                                                                        <a class="bg-primary" href="{{PREFIX.'learning-management/lesson/show/'.$record_combo->slug.'/'.$r->slug}}">{{ $r->title }}</a>
                                                                    @elseif($r->try_lmscontents > 0)
                                                                        <a class="bg-primary" href="{{PREFIX.'learning-management/lesson/show/'.$record_combo->slug.'/'.$r->slug}}">{{ $r->title }}</a>
                                                                    @else
                                                                        <a class="bg-primary" href="javascript:void(0)" onclick="showpayment()" >{{ $r->title }}</a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="card border-0 mb-0">
                                                                <div class="card-body ">
                                                                    <div class="item-card9">

                                                                        @if((isset($r->payment) && $r->payment > 0) || (Auth::check() && Auth::user()->role_id == 6))

                                                                            <a href="{{PREFIX.'learning-management/lesson/show/'.$record_combo->slug.'/'.$r->slug}}" class="text-dark"><h3 class="font-weight-semibold mt-1">{{ $r->title }}</h3></a>
                                                                        @elseif($r->try_lmscontents > 0)

                                                                            <a href="{{PREFIX.'learning-management/lesson/show/'.$record_combo->slug.'/'.$r->slug}}" class="text-dark"><h3 class="font-weight-semibold mt-1">{{ $r->title }}</h3></a>
                                                                        @else
                                                                            <a  href="javascript:void(0)" onclick="showpayment()"  class="text-dark"><h3 class="font-weight-semibold mt-1">{{ $r->title }}</h3></a>
                                                                        @endif

                                                                        <div class="mt-2 mb-2">
                                                                            <?php $time_options = array(0 => '3 tháng', 1 => '6 tháng' , 2 => '12 tháng');  ?>
                                                                            <a href="#" class="mr-4"><span class="text-muted fs-13">Thời gian:</span> <span class="text-muted">{{$time_options[$record_combo->time]}}</span></span></a>
                                                                            <a href="#" class="mr-4"><span class="text-muted fs-13">Bài học:</span><span class="font-weight-bold text-muted text-danger"> {{$r->lmscontents}}</span></a>
                                                                        </div>
                                                                            {!! limit_words($r->short_description,26) !!}
                                                                    </div>
                                                                </div>
                                                                {{-- <div class="card-footer pt-4 pb-4">
                                                                    <div class="item-card9-footer d-flex">
                                                                    </div>
                                                                </div> --}}
                                                            </div>
                                                        </div>
                                                    </div>

                                                @endforeach
                                            @else
                                                <h3 class="text-primary">Chưa có khóa học</h3>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Mô tả khóa học</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4 description">
                                            <p>{!!  $record_combo->description !!}</p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--/Coursed lists-->
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-12">

                        <div class="card overflow-hidden">
                           {{-- <div class="ribbon ribbon-top-left text-danger"><span class="bg-danger">Khuyễn mại</span></div>--}}

                            <div class="card-body">
                                <div class="mb-5">
                                    <div class="text-dark mb-2">
                                        <img src="{{ '/public/uploads/lms/combo/'.$record_combo->image}}" alt="{{$record_combo->title}}">
                                    </div>
                                    <div class="text-dark mb-2 text-right">
                                        <span class="text-primary font-weight-semibold h3">{{ number_format($record_combo->cost, 0, 0, '.')}}đ</span>
                                        @if($record_combo->cost != $record_combo->selloff)
                                        <span class="text-muted h4 font-weight-normal ml-1"><span class="strike-text">{{ number_format($record_combo->selloff, 0, 0, '.')}}đ</span></span>
                                        @endif
                                    </div>

                                    {{-- @if(Auth::check())
                                    <p class="text-danger"><i class="fe fe-clock mr-1"></i>còn 5 ngày giảm giá</p>
                                    @endif --}}
                                </div>

                                <div class="">
                                 {{--   @if($record_combo->cost > 0)
                                        <a href="/payments/lms/{{$record_combo->slug}}"  class="btn btn-primary btn-lg btn-block">Mua ngay</a>
                                    @else
                                        <a href="#"   class="btn btn-primary btn-lg btn-block">Miễn phí</a>
                                    @endif--}}
                                    <a href="javascript:void(0)"   class="disabled btn btn-primary btn-lg btn-block">Bạn đã mua</a>
                                </div>
                            </div>

                        </div>
                    </div>

                @else
                    <div class="col-xl-8 col-lg-8 col-md-12">
                        <!--Coursed lists-->
                        <div class=" mb-lg-0">
                            <div class="">
                                <div class="item2-gl ">
                                    <div class="tab-content" style="padding-top: 0;">
                                        <div class="tab-pane active" id="tab-11">


                                            @if(count($series) > 0)
                                                @foreach($series as $r)
                                                    <div class="card overflow-hidden">
                                                        <div class="d-md-flex">
                                                            <div class="item-card9-img">
                                                                <div class="item-card9-imgs">
                                                                    @if((isset($r->payment) && $r->payment > 0) || (Auth::check() && Auth::user()->role_id == 6))
                                                                        <a href="{{PREFIX.'learning-management/lesson/show/'.$record_combo->slug.'/'.$r->slug}}"></a>
                                                                    @elseif($r->try_lmscontents > 0)
                                                                        <a href="{{PREFIX.'learning-management/lesson/show/'.$record_combo->slug.'/'.$r->slug}}"></a>
                                                                    @else
                                                                        <a href="javascript:void(0)" onclick="showpayment()" ></a>
                                                                    @endif
                                                                    <img src="{{ IMAGE_PATH_UPLOAD_LMS_SERIES.$r->image}}" alt="img" class="cover-image">
                                                                </div>
                                                                {{--  <div class="item-card9-icons">
                                                                      <a href="#" class="item-card9-icons1 text-danger"> <i class="fa fa-heart"></i></a>
                                                                      <a href="#" class="item-card9-icons1 bg-black-trasparant"> <i class="fa fa fa-share-alt"></i></a>
                                                                  </div>--}}
                                                                <div class="item-overly-trans">
                                                                    @if((isset($r->payment) && $r->payment > 0) || (Auth::check() && Auth::user()->role_id == 6))
                                                                        <a class="bg-primary" href="{{PREFIX.'learning-management/lesson/show/'.$record_combo->slug.'/'.$r->slug}}">{{ $r->title }}</a>
                                                                    @elseif($r->try_lmscontents > 0)
                                                                        <a class="bg-primary" href="{{PREFIX.'learning-management/lesson/show/'.$record_combo->slug.'/'.$r->slug}}">{{ $r->title }}</a>
                                                                    @else
                                                                        <a class="bg-primary" href="javascript:void(0)" onclick="showpayment()" >{{ $r->title }}</a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="card border-0 mb-0">
                                                                <div class="card-body ">
                                                                    <div class="item-card9">

                                                                        @if((isset($r->payment) && $r->payment > 0) || (Auth::check() && Auth::user()->role_id == 6))

                                                                            <a href="{{PREFIX.'learning-management/lesson/show/'.$record_combo->slug.'/'.$r->slug}}" class="text-dark"><h3 class="font-weight-semibold mt-1">{{ $r->title }}</h3></a>
                                                                        @elseif($r->try_lmscontents > 0)

                                                                            <a href="{{PREFIX.'learning-management/lesson/show/'.$record_combo->slug.'/'.$r->slug}}" class="text-dark"><h3 class="font-weight-semibold mt-1">{{ $r->title }}</h3></a>
                                                                        @else
                                                                            <a  href="javascript:void(0)" onclick="showpayment()"  class="text-dark"><h3 class="font-weight-semibold mt-1">{{ $r->title }}</h3></a>
                                                                        @endif

                                                                        <div class="mt-2 mb-2">
                                                                            <?php $time_options = array(0 => '3 tháng', 1 => '6 tháng' , 2 => '12 tháng');  ?>
                                                                            <a href="#" class="mr-4"><span class="text-muted fs-13">Thời gian:</span> <span class="text-muted">{{$time_options[$record_combo->time]}}</span></span></a>
                                                                            <a href="#" class="mr-4"><span class="text-muted fs-13">Bài học:</span><span class="font-weight-bold text-muted text-danger"> {{$r->lmscontents}}</span></a>
                                                                        </div>
                                                                        {!! limit_words($r->short_description,26) !!}
                                                                    </div>
                                                                </div>
                                                                <div class="card-footer pt-4 pb-4">
                                                                    <div class="item-card9-footer d-flex">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                @endforeach
                                            @else
                                                <h3 class="text-primary">Chưa có khóa học</h3>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Mô tả khóa học</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4 description">
                                            <p>{!!  $record_combo->description !!}</p>
                                        </div>
                                    
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--/Coursed lists-->
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-12">

                        <div class="card overflow-hidden">
                            <div class="ribbon ribbon-top-left text-danger"><span class="bg-danger">Khuyễn mại</span></div>

                            <div class="card-body">
                                <div class="mb-5">
                                    <div class="text-dark mb-2">
                                        <img src="{{ '/public/uploads/lms/combo/'.$record_combo->image}}" alt="{{$record_combo->title}}">
                                    </div>
                                    <!-- <div class="text-dark mb-2"><span class="text-primary font-weight-semibold h2">{{ number_format($record_combo->cost, 0, 0, '.')}}đ</span>
                                        <span class="text-muted h3 font-weight-normal ml-1"><span class="strike-text">{{ number_format($record_combo->selloff, 0, 0, '.')}}đ</span></span>
                                    </div> -->
                                    <div class="text-dark mb-2 text-right">
                                        
                                    @if($record_combo->timefrom != null && $record_combo->timeto != null && (int)$record_combo->selloff < (int)$record_combo->cost)

                                        @if(strtotime(date("Y-m-d")) >= strtotime(date("Y-m-d", strtotime($record_combo->timefrom)))
                                            && strtotime(date("Y-m-d")) <= strtotime(date("Y-m-d", strtotime($record_combo->timeto))))
                                            <span class="text-primary font-weight-semibold h2">
                                                {{ number_format($record_combo->selloff, 0, 0, '.')}}đ
                                            </span>

                                            <span class="text-muted h3 font-weight-normal ml-1">
                                                <span class="strike-text">
                                                    {{ number_format($record_combo->cost, 0, 0, '.')}}đ
                                                </span>
                                            </span>
                                        @else 
                                            {{ number_format($record_combo->cost, 0, 0, '.')}}đ
                                        @endif
                                        @elseif((int)$record_combo->cost  == 0)
                                        Miễn phí 
                                        @else 
                                        {{ number_format($record_combo->cost, 0, 0, '.')}}đ
                                    @endif
                                    </div>


                                    {{-- @if(Auth::check())
                                    <p class="text-danger"><i class="fe fe-clock mr-1"></i>còn 5 ngày giảm giá</p>
                                    @endif --}}
                                </div>

                                <div class="">
                                    @if($record_combo->cost > 0)
                                        <a href="/payments/lms/{{$record_combo->slug}}"  class="btn btn-primary btn-lg btn-block">Mua ngay</a>
                                    @else
                                        <a href="#"   class="btn btn-primary btn-lg btn-block">Miễn phí</a>
                                    @endif

                                </div>
                            </div>

                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>


    <!--/Section-->
@stop
@section('footer_scripts')

    <script>
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
    </script>
@stop

