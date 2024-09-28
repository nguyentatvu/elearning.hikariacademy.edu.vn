@extends($layout)
@section('header_scripts')
    <link href="{{CSS}}ajax-datatables.css" rel="stylesheet">

@stop
@section('content')
    <div class="card mb-0">
        <div class="card-header">
            <h3 class="card-title">{{$title}}</h3>
        </div>
        <div class="card-body">
            <div class="manged-ad table-responsive border-top userprof-tab">

                <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="text-center align-middle" style="width: 5%;">STT</th>
                        <th>{{$title}} của bạn</th>
                        <th class="text-center align-middle">Bài học</th>
                        <th class="text-center align-middle">Thời gian</th>
                        <th>Trạng thái</th>
                        <th></th>
                        {{--<th>Giá</th>
                        --}}
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($series) > 0)
                        @foreach($series as $r)
                            <tr>
                                <td class="text-center align-middle">
                                    {{$loop->index+1}}
                                </td>
                                <td class="">

                                    <?php

                                    $lmsseries_id = DB::table('lmsseries')
                                        ->select('slug')
                                        ->where('id' ,$r->lmsseries_id)
                                        ->first();


                                    $lmscombo_id = DB::table('lmsseries_combo')
                                        ->select('slug')
                                        ->where('id' ,$r->lmscombo_id)
                                        ->first();

                                    $lmscontent = DB::table('lmscontents')->select('bai')->where('id',$r->lmscontent_id)->first();

                                    ?>
                                    <a href="{{PREFIX.'learning-management/lesson/show/'.$lmscombo_id->slug.'/'.$lmsseries_id->slug.'/'.$r->lmscontent_id}}" class="text-dark">
                                        {{$r->body}}
                                    </a>
                                </td>
                                <td class="text-center align-middle">
                                    <a href="{{PREFIX.'learning-management/lesson/show/'.$lmscombo_id->slug.'/'.$lmsseries_id->slug.'/'.$r->lmscontent_id}}" class="text-dark">
                                        {{$r->title}}<br>
                                        {{$lmscontent->bai}}
                                    </a>

                                </td>
                                <td class="text-center align-middle">
                                    {{date_format(date_create($r->updated_at),"d-m-Y H:m:i")}}
                                </td>
                                <td class="text-center align-middle">
                                    <?php $dr_status = array(0 => 'Chưa xem' , 1 => 'Giáo viên đã trả lời' , 2 =>'Đã xem' ); ?>
                                        @if($r->status == 2)
                                        <span class="label label-pill label-success mt-2">{{$dr_status[$r->status]}}</span>
                                        @elseif($r->status == 0)
                                            <span class="label label-pill label-danger mt-2">{{$dr_status[$r->status]}}</span>
                                        @else
                                            <span class="label label-pill label-info mt-2">{{$dr_status[$r->status]}}</span>
                                        @endif
                                </td>

                                <td class="text-center align-middle">
                                    <a href="{{PREFIX.'learning-management/lesson/show/'.$lmscombo_id->slug.'/'.$lmsseries_id->slug.'/'.$r->lmscontent_id}}" class="btn btn-primary mb-3 mb-xl-0">
                                        <i class="fa fa-comment mr-1"></i>Xem chi tiết
                                    </a>
                                </td>
                            </tr>


                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">
                                <h5 style="color: #ee2833!important">Bạn chưa có {{$title}}</h5>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@stop
{{--
@section('footer_scripts')
        @include('admin.common.datatables', array('route'=>url('lms/exam-categories/comments/getExamList'), 'route_as_url' => true))
@stop--}}
