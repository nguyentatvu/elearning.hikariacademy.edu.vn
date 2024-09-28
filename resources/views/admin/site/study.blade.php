<section class="sptb">
    <div class="container">
        <div class="section-title center-block text-center">
            <h2 style="color: #ca282d;">KHÓA LUYỆN THI ONLINE</h2>
            <span class="sectiontitle-design"><span class="icons"></span></span>
            <p>Không khoảng cách, không giới hạn thời gian, tính ứng dụng cao, không những vậy khoá học trực tuyến còn giúp bạn tăng tính độc lập trong việc học. Hikari Academy cung cấp cho bạn một khóa học với các trình độ và kĩ năng, đáp ứng nhu cầu của mọi người.</p>
        </div>
        <div class="panel panel-primary">
            <div class="">
                <div class="tabs-menu ">
                    <!-- Tabs -->
                    <ul class="nav panel-tabs eductaional-tabs mb-6">
                        <li class=""><a href="#tab1" class="active show" data-toggle="tab">Tất cả</a></li>
                       {{-- <li><a href="#tab2" data-toggle="tab" class="">Khóa luyện thi N5</a></li>--}}
                        <li><a href="#tab3" data-toggle="tab" class="">Khóa luyện thi N4</a></li>
                        <li><a href="#tab4" data-toggle="tab" class="">Khóa luyện thi N3</a></li>
                    </ul>
                </div>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active show" id="tab1">
                        <div class="row">
                            @if(count($series) > 0)
                                @foreach($series as $r)
                                    <div class="col-xl-4 col-md-6">
                                        <div class="card overflow-hidden">
                                            <!-- @if( (int)$r->selloff > (int)$r->cost )
                                                <div class="ribbon ribbon-top-left text-danger">
                                                    <span class="bg-danger">
                                                        Khuyến mại
                                                    </span>
                                                </div>
                                            @endif
                                            @if( (int)$r->cost  == 0)
                                                <div class="ribbon ribbon-top-left text-success">
                                                    <span class="bg-success">
                                                        Miễn phí
                                                    </span>
                                                </div>
                                            @endif -->
                                            @if($r->timefrom != null && $r->timeto != null && (int)$r->selloff < (int)$r->cost)

                                                @if(strtotime(date("Y-m-d")) >= strtotime(date("Y-m-d", strtotime($r->timefrom)))
                                                    && strtotime(date("Y-m-d")) <= strtotime(date("Y-m-d", strtotime($r->timeto))))

                                                    <div class="ribbon ribbon-top-left text-danger">
                                                        <span class="bg-danger">
                                                            Khuyến mại
                                                        </span>
                                                    </div>

                                                @endif
                                            @endif
                                            @if( (int)$r->cost  == 0)
                                                <div class="ribbon ribbon-top-left text-success">
                                                    <span class="bg-success">
                                                        Miễn phí
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="item-card7-img">
                                                <div class="item-card7-imgs">
                                                    @if((isset($r->payment) && $r->payment > 0) || (Auth::check() && Auth::user()->role_id == 6))
                                                        @if($r->total_items == 1)
                                                            <a href="{{PREFIX.'learning-management/lesson/show/'.$r->slug.'/'.$r->slug_lmscontents}}">
                                                            </a>
                                                        @else
                                                            <a href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                            </a>
                                                        @endif
                                                    @elseif($r->try_lmscontents > 0)
                                                        @if($r->total_items == 1)
                                                            <a href="{{PREFIX.'learning-management/lesson/show/'.$r->slug.'/'.$r->slug_lmscontents}}">
                                                            </a>
                                                        @else
                                                            <a href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                            </a>
                                                        @endif
                                                    @else
                                                        <a href="/payments/lms/{{$r->slug}}">
                                                        </a>
                                                    @endif
                                                    <img alt="img" class="cover-image" src="{{ '/public/uploads/lms/combo/'.$r->image}}">
                                                    </img>
                                                </div>
                                                <div class="item-card7-overlaytext">
                                                    <a class="text-white" href="#">
                                                        #{{$r->code}}
                                                    </a>
                                                    
                                                    <h4 class="mb-0 font-weight-semibold fs-16">
                                                        <!-- @if((int)$r->cost > 0 )
                                                            {{ number_format($r->cost, 0, 0, '.')}}đ
                                                            @if( (int)$r->selloff > (int)$r->cost )
                                                                <del class="h4 text-muted ml-2 fs-12">
                                                                    {{ number_format($r->selloff, 0, 0, '.')}}đ
                                                                </del>
                                                            @endif
                                                        @else 
                                                            Miễn phí
                                                        @endif   -->
                                                        @if($r->timefrom != null && $r->timeto != null && (int)$r->selloff < (int)$r->cost)

                                                            @if(strtotime(date("Y-m-d")) >= strtotime(date("Y-m-d", strtotime($r->timefrom)))
                                                                && strtotime(date("Y-m-d")) <= strtotime(date("Y-m-d", strtotime($r->timeto))))

                                                                {{ number_format($r->selloff, 0, 0, '.')}}đ

                                                                <del class="h4 text-muted ml-2 fs-12">
                                                                        {{ number_format($r->cost, 0, 0, '.')}}đ
                                                                </del>
                                                            @else 
                                                                {{ number_format($r->cost, 0, 0, '.')}}đ
                                                            @endif
                                                        @elseif((int)$r->cost  == 0)
                                                            Miễn phí 
                                                        @else 
                                                            {{ number_format($r->cost, 0, 0, '.')}}đ
                                                        @endif
                                                    </h4>
                                                    
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="item-card7-desc">
                                                    <div class="item-card7-text" style="max-height: 7vh;height: 5vh;">
                                                        @if((isset($r->payment) && $r->payment > 0) || (Auth::check() && Auth::user()->role_id == 6))
                                                            @if($r->total_items == 1)
                                                                <a class="text-dark" href="{{PREFIX.'learning-management/lesson/show/'.$r->slug_lmscontents}}">
                                                                    @else
                                                                        <a class="text-dark" href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                                            @endif
                                                                            @elseif($r->try_lmscontents > 0)
                                                                                @if($r->total_items == 1)
                                                                                    <a class="text-dark" href="{{PREFIX.'learning-management/lesson/show/'.$r->slug.'/'.$r->slug_lmscontents}}">
                                                                                        @else
                                                                                            <a class="text-dark" href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                                                                @endif
                                                                                                @else
                                                                                                    <a class="text-dark" href="/payments/lms/{{$r->slug}}">
                                                                                                        @endif
                                                                                                        <h3 class="font-weight-semibold">
                                                                                                            {{ $r->title }}
                                                                                                        </h3>
                                                                                                    </a>
                                                                                            </a>
                                                                                    </a>
                                                                        </a>
                                                                </a>
                                                    </div>
                                                    {!! limit_words($r->short_description,20) !!}
                                                    <p  style="cursor: pointer"class="text-center text-primary modelSeries" data-image="{{ '/elearning/public/uploads/lms/combo/'.$r->image}}" data-title="{{ $r->title }}" data-detail="{{ change_furigana_title($r->description)  }}" data-toggle="modal" >Xem thêm</p>
                                                </div>
                                            </div>
                                            <div class="card-body p-4 pl-5">
                                                <?php $time_options = array(0 =>
                                                    '3 tháng', 1 => '6 tháng' , 2 => '12 tháng');  ?>
                                                <a class="mr-4">
                                            <span class="font-weight-bold">
                                                Thời gian:
                                            </span>
                                                    <span class="text-muted">
                                                {{$time_options[$r->time]}}
                                            </span>
                                                </a>
                                                <a class="mr-4 float-right">
                                            <span class="font-weight-bold">
                                                Bài học:
                                            </span>
                                                    <span class="font-weight-bold text-muted text-danger">
                                                {{$r->lmscontents}}
                                            </span>
                                                </a>
                                            </div>
                                            <div class="card-body">
                                                @if((isset($r->payment) && $r->payment > 0) || (Auth::check() && Auth::user()->role_id == 6))
                                                    @if($r->total_items == 1)
                                                        <a class="btn btn-primary btn-block" href="{{PREFIX.'learning-management/lesson/show/'.$r->slug.'/'.$r->slug_lmscontents}}">
                                                            Học ngay
                                                        </a>
                                                    @else
                                                        <a class="btn btn-primary btn-block" href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                            Mua ngay
                                                        </a>
                                                    @endif
                                                @elseif($r->try_lmscontents > 0)
                                                    @if($r->total_items == 1)
                                                        <a class="btn btn-primary btn-block" href="{{PREFIX.'learning-management/lesson/show/'.$r->slug.'/'.$r->slug_lmscontents}}">
                                                            Học thử
                                                        </a>
                                                    @else
                                                        <a class="btn btn-primary btn-block" href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                            Mua ngay
                                                        </a>
                                                    @endif
                                                @else
                                                    <a class="btn btn-primary btn-block" href="/payments/lms/{{$r->slug}}">
                                                        Mua ngay
                                                    </a>
                                                @endif
                                                {{--
                                        <a class="btn btn-primary btn-block" href="/payments/lms/{{$r->slug}}">
                                            Mua khóa luyện thi
                                        </a>
                                        --}}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <h3 class="text-primary">Chưa có khóa luyện thi</h3>
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane" id="tab2">
                        <div class="row">
                            @if(count($series_5) > 0)
                                @foreach($series_5 as $r)
                                    <div class="col-xl-4 col-md-6">
                                        <div class="card overflow-hidden">
                                            <!-- @if( (int)$r->selloff > (int)$r->cost )
                                                <div class="ribbon ribbon-top-left text-danger">
                                                    <span class="bg-danger">
                                                        Khuyến mại
                                                    </span>
                                                </div>
                                            @endif
                                            @if( (int)$r->cost  == 0)
                                                <div class="ribbon ribbon-top-left text-success">
                                                    <span class="bg-success">
                                                        Miễn phí
                                                    </span>
                                                </div>
                                            @endif -->
                                            @if($r->timefrom != null && $r->timeto != null && (int)$r->selloff < (int)$r->cost)

                                                @if(strtotime(date("Y-m-d")) >= strtotime(date("Y-m-d", strtotime($r->timefrom)))
                                                    && strtotime(date("Y-m-d")) <= strtotime(date("Y-m-d", strtotime($r->timeto))))

                                                    <div class="ribbon ribbon-top-left text-danger">
                                                        <span class="bg-danger">
                                                            Khuyến mại
                                                        </span>
                                                    </div>

                                                @endif
                                            @endif
                                            @if( (int)$r->cost  == 0)
                                                <div class="ribbon ribbon-top-left text-success">
                                                    <span class="bg-success">
                                                        Miễn phí
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="item-card7-img">
                                                <div class="item-card7-imgs">
                                                    @if((isset($r->payment) && $r->payment > 0) || (Auth::check() && Auth::user()->role_id == 6))
                                                        @if($r->total_items == 1)
                                                            <a href="{{PREFIX.'learning-management/lesson/show/'.$r->slug.'/'.$r->slug_lmscontents}}">
                                                            </a>
                                                        @else
                                                            <a href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                            </a>
                                                        @endif
                                                    @elseif($r->try_lmscontents > 0)
                                                        @if($r->total_items == 1)
                                                            <a href="{{PREFIX.'learning-management/lesson/show/'.$r->slug.'/'.$r->slug_lmscontents}}">
                                                            </a>
                                                        @else
                                                            <a href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                            </a>
                                                        @endif
                                                    @else
                                                        <a href="/payments/lms/{{$r->slug}}">
                                                        </a>
                                                    @endif
                                                    <img alt="img" class="cover-image" src="{{ '/public/uploads/lms/combo/'.$r->image}}">
                                                    </img>
                                                </div>
                                                <div class="item-card7-overlaytext">
                                                    <a class="text-white" href="#">
                                                        #{{$r->code}}
                                                    </a>
                                                    
                                                    <h4 class="mb-0 font-weight-semibold fs-16">
                                                        <!-- @if((int)$r->cost > 0 )
                                                            {{ number_format($r->cost, 0, 0, '.')}}đ
                                                            @if( (int)$r->selloff > (int)$r->cost )
                                                                <del class="h4 text-muted ml-2 fs-12">
                                                                    {{ number_format($r->selloff, 0, 0, '.')}}đ
                                                                </del>
                                                            @endif
                                                        @else 
                                                            Miễn phí
                                                        @endif   -->
                                                        @if($r->timefrom != null && $r->timeto != null && (int)$r->selloff < (int)$r->cost)

                                                            @if(strtotime(date("Y-m-d")) >= strtotime(date("Y-m-d", strtotime($r->timefrom)))
                                                                && strtotime(date("Y-m-d")) <= strtotime(date("Y-m-d", strtotime($r->timeto))))

                                                                {{ number_format($r->selloff, 0, 0, '.')}}đ

                                                                <del class="h4 text-muted ml-2 fs-12">
                                                                        {{ number_format($r->cost, 0, 0, '.')}}đ
                                                                </del>
                                                            @else 
                                                                {{ number_format($r->cost, 0, 0, '.')}}đ
                                                            @endif
                                                        @elseif((int)$r->cost  == 0)
                                                            Miễn phí 
                                                        @else 
                                                            {{ number_format($r->cost, 0, 0, '.')}}đ
                                                        @endif
                                                    </h4>
                                                   
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="item-card7-desc">
                                                    <div class="item-card7-text" style="max-height: 7vh;height: 5vh;">
                                                        @if((isset($r->payment) && $r->payment > 0) || (Auth::check() && Auth::user()->role_id == 6))
                                                            @if($r->total_items == 1)
                                                                <a class="text-dark" href="{{PREFIX.'learning-management/lesson/show/'.$r->slug_lmscontents}}">
                                                                    @else
                                                                        <a class="text-dark" href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                                            @endif
                                                                            @elseif($r->try_lmscontents > 0)
                                                                                @if($r->total_items == 1)
                                                                                    <a class="text-dark" href="{{PREFIX.'learning-management/lesson/show/'.$r->slug.'/'.$r->slug_lmscontents}}">
                                                                                        @else
                                                                                            <a class="text-dark" href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                                                                @endif
                                                                                                @else
                                                                                                    <a class="text-dark" href="/payments/lms/{{$r->slug}}">
                                                                                                        @endif
                                                                                                        <h3 class="font-weight-semibold">
                                                                                                            {{ $r->title }}
                                                                                                        </h3>
                                                                                                    </a>
                                                                                            </a>
                                                                                    </a>
                                                                        </a>
                                                                </a>
                                                    </div>
                                                    {!! limit_words($r->short_description,20) !!}
                                                    <p  style="cursor: pointer"class="text-center text-primary modelSeries" data-image="{{ '/elearning/public/uploads/lms/combo/'.$r->image}}" data-title="{{ $r->title }}" data-detail="{{ change_furigana_title($r->description)  }}" data-toggle="modal" >Xem thêm</p>
                                                </div>
                                            </div>
                                            <div class="card-body p-4 pl-5">
                                                <?php $time_options = array(0 =>
                                                    '3 tháng', 1 => '6 tháng' , 2 => '12 tháng');  ?>
                                                <a class="mr-4">
                                            <span class="font-weight-bold">
                                                Thời gian:
                                            </span>
                                                    <span class="text-muted">
                                                {{$time_options[$r->time]}}
                                            </span>
                                                </a>
                                                <a class="mr-4 float-right">
                                            <span class="font-weight-bold">
                                                Bài học:
                                            </span>
                                                    <span class="font-weight-bold text-muted text-danger">
                                                {{$r->lmscontents}}
                                            </span>
                                                </a>
                                            </div>
                                            <div class="card-body">
                                                @if((isset($r->payment) && $r->payment > 0) || (Auth::check() && Auth::user()->role_id == 6))
                                                    @if($r->total_items == 1)
                                                        <a class="btn btn-primary btn-block" href="{{PREFIX.'learning-management/lesson/show/'.$r->slug.'/'.$r->slug_lmscontents}}">
                                                            Học ngay
                                                        </a>
                                                    @else
                                                        <a class="btn btn-primary btn-block" href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                            Mua ngay
                                                        </a>
                                                    @endif
                                                @elseif($r->try_lmscontents > 0)
                                                    @if($r->total_items == 1)
                                                        <a class="btn btn-primary btn-block" href="{{PREFIX.'learning-management/lesson/show/'.$r->slug.'/'.$r->slug_lmscontents}}">
                                                            Học thử
                                                        </a>
                                                    @else
                                                        <a class="btn btn-primary btn-block" href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                            Mua ngay
                                                        </a>
                                                    @endif
                                                @else
                                                    <a class="btn btn-primary btn-block" href="/payments/lms/{{$r->slug}}">
                                                        Mua ngay
                                                    </a>
                                                @endif
                                                {{--
                                        <a class="btn btn-primary btn-block" href="/payments/lms/{{$r->slug}}">
                                            Mua khóa luyện thi
                                        </a>
                                        --}}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <h3 class="text-primary">Chưa có khóa luyện thi N5</h3>
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane" id="tab3">
                        <div class="row">
                            @if(count($series_4) > 0)
                                @foreach($series_4 as $r)
                                    <div class="col-xl-4 col-md-6">
                                        <div class="card overflow-hidden">
                                            <!-- @if( (int)$r->selloff > (int)$r->cost )
                                                <div class="ribbon ribbon-top-left text-danger">
                                                    <span class="bg-danger">
                                                        Khuyến mại
                                                    </span>
                                                </div>
                                            @endif
                                            @if( (int)$r->cost  == 0)
                                                <div class="ribbon ribbon-top-left text-success">
                                                    <span class="bg-success">
                                                        Miễn phí
                                                    </span>
                                                </div>
                                            @endif -->
                                            @if($r->timefrom != null && $r->timeto != null && (int)$r->selloff < (int)$r->cost)

                                                @if(strtotime(date("Y-m-d")) >= strtotime(date("Y-m-d", strtotime($r->timefrom)))
                                                    && strtotime(date("Y-m-d")) <= strtotime(date("Y-m-d", strtotime($r->timeto))))

                                                    <div class="ribbon ribbon-top-left text-danger">
                                                        <span class="bg-danger">
                                                            Khuyến mại
                                                        </span>
                                                    </div>

                                                @endif
                                            @endif
                                            @if( (int)$r->cost  == 0)
                                                <div class="ribbon ribbon-top-left text-success">
                                                    <span class="bg-success">
                                                        Miễn phí
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="item-card7-img">
                                                <div class="item-card7-imgs">
                                                    @if((isset($r->payment) && $r->payment > 0) || (Auth::check() && Auth::user()->role_id == 6))
                                                        @if($r->total_items == 1)
                                                            <a href="{{PREFIX.'learning-management/lesson/show/'.$r->slug.'/'.$r->slug_lmscontents}}">
                                                            </a>
                                                        @else
                                                            <a href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                            </a>
                                                        @endif
                                                    @elseif($r->try_lmscontents > 0)
                                                        @if($r->total_items == 1)
                                                            <a href="{{PREFIX.'learning-management/lesson/show/'.$r->slug.'/'.$r->slug_lmscontents}}">
                                                            </a>
                                                        @else
                                                            <a href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                            </a>
                                                        @endif
                                                    @else
                                                        <a href="/payments/lms/{{$r->slug}}">
                                                        </a>
                                                    @endif
                                                    <img alt="img" class="cover-image" src="{{ '/public/uploads/lms/combo/'.$r->image}}">
                                                    </img>
                                                </div>
                                                <div class="item-card7-overlaytext">
                                                    <a class="text-white" href="#">
                                                        #{{$r->code}}
                                                    </a>
                                                    
                                                    <h4 class="mb-0 font-weight-semibold fs-16">
                                                        <!-- @if((int)$r->cost > 0 )
                                                            {{ number_format($r->cost, 0, 0, '.')}}đ
                                                            @if( (int)$r->selloff > (int)$r->cost )
                                                                <del class="h4 text-muted ml-2 fs-12">
                                                                    {{ number_format($r->selloff, 0, 0, '.')}}đ
                                                                </del>
                                                            @endif
                                                        @else 
                                                            Miễn phí
                                                        @endif   -->
                                                        @if($r->timefrom != null && $r->timeto != null && (int)$r->selloff < (int)$r->cost)

                                                            @if(strtotime(date("Y-m-d")) >= strtotime(date("Y-m-d", strtotime($r->timefrom)))
                                                                && strtotime(date("Y-m-d")) <= strtotime(date("Y-m-d", strtotime($r->timeto))))

                                                                {{ number_format($r->selloff, 0, 0, '.')}}đ

                                                                <del class="h4 text-muted ml-2 fs-12">
                                                                        {{ number_format($r->cost, 0, 0, '.')}}đ
                                                                </del>
                                                            @else 
                                                                {{ number_format($r->cost, 0, 0, '.')}}đ
                                                            @endif
                                                        @elseif((int)$r->cost  == 0)
                                                            Miễn phí 
                                                        @else 
                                                            {{ number_format($r->cost, 0, 0, '.')}}đ
                                                        @endif
                                                    </h4>
                                                    
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="item-card7-desc">
                                                    <div class="item-card7-text" style="max-height: 7vh;height: 5vh;">
                                                        @if((isset($r->payment) && $r->payment > 0) || (Auth::check() && Auth::user()->role_id == 6))
                                                            @if($r->total_items == 1)
                                                                <a class="text-dark" href="{{PREFIX.'learning-management/lesson/show/'.$r->slug_lmscontents}}">
                                                                    @else
                                                                        <a class="text-dark" href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                                            @endif
                                                                            @elseif($r->try_lmscontents > 0)
                                                                                @if($r->total_items == 1)
                                                                                    <a class="text-dark" href="{{PREFIX.'learning-management/lesson/show/'.$r->slug.'/'.$r->slug_lmscontents}}">
                                                                                        @else
                                                                                            <a class="text-dark" href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                                                                @endif
                                                                                                @else
                                                                                                    <a class="text-dark" href="/payments/lms/{{$r->slug}}">
                                                                                                        @endif
                                                                                                        <h3 class="font-weight-semibold">
                                                                                                            {{ $r->title }}
                                                                                                        </h3>
                                                                                                    </a>
                                                                                            </a>
                                                                                    </a>
                                                                        </a>
                                                                </a>
                                                    </div>
                                                    {!! limit_words($r->short_description,20) !!}
                                                    <p  style="cursor: pointer"class="text-center text-primary modelSeries" data-image="{{ '/elearning/public/uploads/lms/combo/'.$r->image}}" data-title="{{ $r->title }}" data-detail="{{ change_furigana_title($r->description)  }}" data-toggle="modal" >Xem thêm</p>
                                                </div>
                                            </div>
                                            <div class="card-body p-4 pl-5">
                                                <?php $time_options = array(0 =>
                                                    '3 tháng', 1 => '6 tháng' , 2 => '12 tháng');  ?>
                                                <a class="mr-4">
                                            <span class="font-weight-bold">
                                                Thời gian:
                                            </span>
                                                    <span class="text-muted">
                                                {{$time_options[$r->time]}}
                                            </span>
                                                </a>
                                                <a class="mr-4 float-right">
                                            <span class="font-weight-bold">
                                                Bài học:
                                            </span>
                                                    <span class="font-weight-bold text-muted text-danger">
                                                {{$r->lmscontents}}
                                            </span>
                                                </a>
                                            </div>
                                            <div class="card-body">
                                                @if((isset($r->payment) && $r->payment > 0) || (Auth::check() && Auth::user()->role_id == 6))
                                                    @if($r->total_items == 1)
                                                        <a class="btn btn-primary btn-block" href="{{PREFIX.'learning-management/lesson/show/'.$r->slug.'/'.$r->slug_lmscontents}}">
                                                            Học ngay
                                                        </a>
                                                    @else
                                                        <a class="btn btn-primary btn-block" href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                            Mua ngay
                                                        </a>
                                                    @endif
                                                @elseif($r->try_lmscontents > 0)
                                                    @if($r->total_items == 1)
                                                        <a class="btn btn-primary btn-block" href="{{PREFIX.'learning-management/lesson/show/'.$r->slug.'/'.$r->slug_lmscontents}}">
                                                            Học thử
                                                        </a>
                                                    @else
                                                        <a class="btn btn-primary btn-block" href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                            Mua ngay
                                                        </a>
                                                    @endif
                                                @else
                                                    <a class="btn btn-primary btn-block" href="/payments/lms/{{$r->slug}}">
                                                        Mua ngay
                                                    </a>
                                                @endif
                                                {{--
                                        <a class="btn btn-primary btn-block" href="/payments/lms/{{$r->slug}}">
                                            Mua khóa luyện thi
                                        </a>
                                        --}}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <h3 class="text-primary">Chưa có khóa luyện thi N4</h3>
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane" id="tab4">
                        <div class="row">
                            @if(count($series_3) > 0)
                                @foreach($series_3 as $r)
                                    <div class="col-xl-4 col-md-6">
                                        <div class="card overflow-hidden">
                                            <!-- @if( (int)$r->selloff > (int)$r->cost )
                                                <div class="ribbon ribbon-top-left text-danger">
                                                    <span class="bg-danger">
                                                        Khuyến mại
                                                    </span>
                                                </div>
                                            @endif
                                            @if( (int)$r->cost  == 0)
                                                <div class="ribbon ribbon-top-left text-success">
                                                    <span class="bg-success">
                                                        Miễn phí
                                                    </span>
                                                </div>
                                            @endif -->
                                            @if($r->timefrom != null && $r->timeto != null && (int)$r->selloff < (int)$r->cost)

                                                @if(strtotime(date("Y-m-d")) >= strtotime(date("Y-m-d", strtotime($r->timefrom)))
                                                    && strtotime(date("Y-m-d")) <= strtotime(date("Y-m-d", strtotime($r->timeto))))

                                                    <div class="ribbon ribbon-top-left text-danger">
                                                        <span class="bg-danger">
                                                            Khuyến mại
                                                        </span>
                                                    </div>

                                                @endif
                                            @endif
                                            @if( (int)$r->cost  == 0)
                                                <div class="ribbon ribbon-top-left text-success">
                                                    <span class="bg-success">
                                                        Miễn phí
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="item-card7-img">
                                                <div class="item-card7-imgs">
                                                    @if((isset($r->payment) && $r->payment > 0) || (Auth::check() && Auth::user()->role_id == 6))
                                                        @if($r->total_items == 1)
                                                            <a href="{{PREFIX.'learning-management/lesson/show/'.$r->slug.'/'.$r->slug_lmscontents}}">
                                                            </a>
                                                        @else
                                                            <a href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                            </a>
                                                        @endif
                                                    @elseif($r->try_lmscontents > 0)
                                                        @if($r->total_items == 1)
                                                            <a href="{{PREFIX.'learning-management/lesson/show/'.$r->slug.'/'.$r->slug_lmscontents}}">
                                                            </a>
                                                        @else
                                                            <a href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                            </a>
                                                        @endif
                                                    @else
                                                        <a href="/payments/lms/{{$r->slug}}">
                                                        </a>
                                                    @endif
                                                    <img alt="img" class="cover-image" src="{{ '/public/uploads/lms/combo/'.$r->image}}">
                                                    </img>
                                                </div>
                                                <div class="item-card7-overlaytext">
                                                    <a class="text-white" href="#">
                                                        #{{$r->code}}
                                                    </a>
                                                    <!-- @if($r->cost > 0)
                                                        <h4 class="mb-0 font-weight-semibold fs-16">
                                                            {{ number_format($r->cost, 0, 0, '.')}}đ
                                                            @if($r->cost != $r->selloff)
                                                                <del class="h4 text-muted ml-2 fs-12">
                                                                    {{ number_format($r->selloff, 0, 0, '.')}}đ
                                                                </del>
                                                            @endif
                                                        </h4>
                                                    @endif -->
                                                    <h4 class="mb-0 font-weight-semibold fs-16">
                                                    @if($r->timefrom != null && $r->timeto != null && (int)$r->selloff < (int)$r->cost)

                                                        @if(strtotime(date("Y-m-d")) >= strtotime(date("Y-m-d", strtotime($r->timefrom)))
                                                            && strtotime(date("Y-m-d")) <= strtotime(date("Y-m-d", strtotime($r->timeto))))

                                                            {{ number_format($r->selloff, 0, 0, '.')}}đ

                                                            <del class="h4 text-muted ml-2 fs-12">
                                                                    {{ number_format($r->cost, 0, 0, '.')}}đ
                                                            </del>
                                                        @else 
                                                            {{ number_format($r->cost, 0, 0, '.')}}đ
                                                        @endif
                                                    @elseif((int)$r->cost  == 0)
                                                        Miễn phí 
                                                    @else 
                                                        {{ number_format($r->cost, 0, 0, '.')}}đ
                                                    @endif

                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="item-card7-desc">
                                                    <div class="item-card7-text" style="max-height: 7vh;height: 5vh;">
                                                        @if((isset($r->payment) && $r->payment > 0) || (Auth::check() && Auth::user()->role_id == 6))
                                                            @if($r->total_items == 1)
                                                                <a class="text-dark" href="{{PREFIX.'learning-management/lesson/show/'.$r->slug_lmscontents}}">
                                                                    @else
                                                                        <a class="text-dark" href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                                            @endif
                                                                            @elseif($r->try_lmscontents > 0)
                                                                                @if($r->total_items == 1)
                                                                                    <a class="text-dark" href="{{PREFIX.'learning-management/lesson/show/'.$r->slug.'/'.$r->slug_lmscontents}}">
                                                                                        @else
                                                                                            <a class="text-dark" href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                                                                @endif
                                                                                                @else
                                                                                                    <a class="text-dark" href="/payments/lms/{{$r->slug}}">
                                                                                                        @endif
                                                                                                        <h3 class="font-weight-semibold">
                                                                                                            {{ $r->title }}
                                                                                                        </h3>
                                                                                                    </a>
                                                                                            </a>
                                                                                    </a>
                                                                        </a>
                                                                </a>
                                                    </div>
                                                    {!! limit_words($r->short_description,20) !!}
                                                    <p  style="cursor: pointer"class="text-center text-primary modelSeries" data-image="{{ '/elearning/public/uploads/lms/combo/'.$r->image}}" data-title="{{ $r->title }}" data-detail="{{ change_furigana_title($r->description)  }}" data-toggle="modal" >Xem thêm</p>
                                                </div>
                                            </div>
                                            <div class="card-body p-4 pl-5">
                                                <?php $time_options = array(0 =>
                                                    '3 tháng', 1 => '6 tháng' , 2 => '12 tháng');  ?>
                                                <a class="mr-4">
                                            <span class="font-weight-bold">
                                                Thời gian:
                                            </span>
                                                    <span class="text-muted">
                                                {{$time_options[$r->time]}}
                                            </span>
                                                </a>
                                                <a class="mr-4 float-right">
                                            <span class="font-weight-bold">
                                                Bài học:
                                            </span>
                                                    <span class="font-weight-bold text-muted text-danger">
                                                {{$r->lmscontents}}
                                            </span>
                                                </a>
                                            </div>
                                            <div class="card-body">
                                                @if((isset($r->payment) && $r->payment > 0) || (Auth::check() && Auth::user()->role_id == 6))
                                                    @if($r->total_items == 1)
                                                        <a class="btn btn-primary btn-block" href="{{PREFIX.'learning-management/lesson/show/'.$r->slug.'/'.$r->slug_lmscontents}}">
                                                            Học ngay
                                                        </a>
                                                    @else
                                                        <a class="btn btn-primary btn-block" href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                            Mua ngay
                                                        </a>
                                                    @endif
                                                @elseif($r->try_lmscontents > 0)
                                                    @if($r->total_items == 1)
                                                        <a class="btn btn-primary btn-block" href="{{PREFIX.'learning-management/lesson/show/'.$r->slug.'/'.$r->slug_lmscontents}}">
                                                            Học thử
                                                        </a>
                                                    @else
                                                        <a class="btn btn-primary btn-block" href="{{PREFIX.'learning-management/lesson/combo/'.$r->slug}}">
                                                            Mua ngay
                                                        </a>
                                                    @endif
                                                @else
                                                    <a class="btn btn-primary btn-block" href="/payments/lms/{{$r->slug}}">
                                                        Mua ngay
                                                    </a>
                                                @endif
                                                {{--
                                        <a class="btn btn-primary btn-block" href="/payments/lms/{{$r->slug}}">
                                            Mua khóa luyện thi
                                        </a>
                                        --}}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <h3 class="text-primary">Chưa có khóa luyện thi N3</h3>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="cover-image about-widget sptb bg-background-color" data-image-src="/public/assets/images/banners/banner4.jpg" style="background: url('/public/assets/images/banners/banner4.jpg') center center;">
        <div class="content-text mb-0">
            <div class="container">
                <div class="text-center text-white ">
                    <h2 class="mb-2 font-weight-400">HỌC TIẾNG NHẬT CÙNG HIKARI ACADEMY</h2>
                    <p>Hikari Academy không ngừng chú trọng phát triển nội dung nhằm đạt chất lượng cao, luôn lắng nghe phản hồi của khách hàng và hành động, ngày càng góp phần nâng cao lòng tin của khách hàng. Động lực để đạt được chất lượng cao sẽ dễ dàng và nhanh chóng hơn nhiều khi bạn có các đối tác kinh doanh phù hợp.</p>
                    <div class="mt-5">
                        <a href="/register" class="btn btn-lg btn-primary">Đăng ký ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="sptb">
            <div class="container">
                <div class="section-title center-block text-center">
                    <h2>TIẾNG NHẬT KHÓ ĐÃ CÓ HIKARI ACADEMY</h2>
                    <span class="sectiontitle-design"><span class="icons"></span></span>
                    <p>Hikari Academy đem đến cho bạn một khóa học với các bài giảng xuyên suốt các chủ đề rõ ràng và quen thuộc với hầu hết những kĩ năng cần thiết.</p>
                </div>
                <div class="row ">
                    <div class="col-md-6 col-lg-4 features">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="feature">
                                    <div class="fa-stack fa-lg  fea-icon bg-success mb-3">
                                        <i class="fa fa-bullhorn  text-white"></i>
                                    </div>
                                    <h3 class="font-weight-semibold">KHÓA HỌC</h3>
                                    <p>Đa dạng theo mọi trình độ từ N5 đến N1</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 features">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="feature">
                                    <div class="fa-stack fa-lg  fea-icon bg-primary mb-3">
                                        <i class="fa fa-heart  text-white"></i>
                                    </div>
                                    <h3 class="font-weight-semibold">LỚP HỌC</h3>
                                    <p>Tính năng lớp học dành riêng cho doanh nghiệp, trường học</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 features">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="feature">
                                    <div class="fa-stack fa-lg  fea-icon bg-secondary mb-3">
                                        <i class="fa fa-bookmark  text-white"></i>
                                    </div>
                                    <h3 class="font-weight-semibold">KỸ NĂNG TOÀN DIỆN</h3>
                                    <p>Từ vựng, ngữ pháp, đọc hiểu, nghe, thoại hội, luyện tập</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 features">
                        <div class="card mb-lg-0">
                            <div class="card-body text-center">
                                <div class="feature">
                                    <div class="fa-stack fa-lg  fea-icon bg-warning mb-3">
                                        <i class="fa fa-line-chart   text-white"></i>
                                    </div>
                                    <h3 class="font-weight-semibold">LUYỆN PHÁT ÂM</h3>
                                    <p>Ứng dụng công nghệ hàng đầu kiểm tra phát âm theo giọng bản xứ</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 features">
                        <div class="card mb-lg-0 mb-md-0">
                            <div class="card-body text-center">
                                <div class="feature">
                                    <div class="fa-stack fa-lg  fea-icon bg-danger mb-3">
                                        <i class="fa fa-handshake-o   text-white"></i>
                                    </div>
                                    <h3 class="font-weight-semibold">CHỮ HÁN</h3>
                                    <p>Tính năng thông minh hỗ trợ nhớ nhanh Hán Tự</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 features">
                        <div class="card mb-0">
                            <div class="card-body text-center">
                                <div class="feature">
                                    <div class="fa-stack fa-lg  fea-icon bg-info mb-3">
                                        <i class="fa fa-phone  text-white"></i>
                                    </div>
                                    <h3 class="font-weight-semibold">ĐỀ THI</h3>
                                    <p>Phong phú, đa dạng mọi trình độ JLPT N5 - N1</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>

        </div>
    </div>
</div>
@section('footer_scripts')
    <script>
        $(document).ready(function() {

            $(document).on('click', '.modelSeries', function (){

                $('.modal-body').html($(this).data('detail'));
                $('#exampleModalLongTitle').html($(this).data('title'))
                $('#exampleModalLong').modal('show')
            });

        })

    </script>
@stop