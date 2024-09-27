<?php
$dr_trinhdo = array(
    1 => 'N1',
    2=> 'N2',
    3=> 'N3',
    4=> 'N4',
    5=> 'N5',

);

$dr_loai = array(
    0 => 'Khóa học',
    1 => 'Khóa luyện thi'
)


?>
@extends($layout)
@section('content')
    <div class="card mb-0">
        <div class="card-header">
            <h3 class="card-title">{{$title}}</h3>
        </div>
        <div class="card-body">
            <div class="manged-ad table-responsive border-top userprof-tab">

                <table class="table table-bordered table-hover mb-0 text-nowrap">
                    <thead>
                    <tr>
                        <th class="text-center align-middle" style="width: 5%">STT</th>
                        <th>{{$title}} của bạn</th>
                        {{--<th class="text-center align-middle">Trình độ</th>--}}
{{--                        <th class="text-center align-middle">Loại</th>--}}
                        <th class="text-center align-middle">Giá</th>
                        <th class="text-center align-middle">Phương thức</th>
                        <th>Trạng thái</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($series) > 0)

                        @foreach($series as $r)
                            <tr>
                                <td class="text-center align-middle">
                                    {{$loop->index+1}}
                                </td>
                                <td>
                                    <div class="media mt-0 mb-0">
                                        <div class="card-aside-img">
                                            <a href="#"></a><img style="height: auto;" src="{{ '/public/uploads/lms/combo/'.$r->image}}" alt="{{$r->title}}">
                                        </div>
                                        <div class="media-body">
                                            <div class="card-item-desc ml-4 p-0 mt-2">
                                                <?php $dr_time  = array(0 =>'3 tháng' , 1 =>'6 tháng' , 2 => '12 tháng')?>
                                                <a href="#" class="text-dark"><h4 class="font-weight-semibold">{{$r->title}} ({{$dr_time[$r->time]}})</h4></a>
                                                <a href="#">Ngày mua: {{date_format(date_create($r->created_at),"d-m-Y")}}</a><br>
                                                <?php $dr_time  = array(0 =>90 , 1 =>180 , 2 => 365)?>
                                                <a href="#">Ngày hết hạn: {{date_format(date_add(date_create($r->created_at),date_interval_create_from_date_string($dr_time[$r->time]." days")),"d-m-Y")}}</a><br>
                                               {{-- <a href="#"><i class="fa fa-clock-o mr-1"></i>{{date_format(date_create($r->created_at),"d-m-Y")}}</a>--}}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                {{--<td class="text-center align-middle">{{$dr_trinhdo[$r->lms_category_id]}}</td>--}}
                               {{-- <td class="text-center align-middle">{{$dr_loai[$r->type]}}</td>--}}
                                <td class="font-weight-semibold fs-16 align-middle">
                                    {{ number_format($r->cost, 0, 0, '.')}}đ
                                </td>
                                <td class="text-center align-middle">
                                    <a href="#" class="text-uppercase">{{$r->orderType}}</a>
                                </td>
                                <td class="text-center align-middle">
                                    <?php $dr_status = array(0 => 'Chưa thanh toán', 1 => 'Thành công', 2 =>'Đang xử lý') ?>
                                    <?php $dr_class = array(0 => 'text-danger', 1 => 'text-success', 2 =>'text-danger') ?>
                                        @if($r->status != 0 && $r->status != 1 && $r->status != 2)
                                             <span class="text-danger">Không xác định</span>

                                        @elseif($r->status == 2)
                                            <span class="text-danger">{{$dr_status[$r->status]}}</span>
                                            <br>
                                            <a href="javascript:void(0)" onclick="canpayment({{$r->id}})" class="btn btn-sm btn-primary">
                                                Hủy đơn hàng
                                            </a>
                                        

                                        @else
                                            <span class="{{$dr_class[$r->status]}}">{{$dr_status[$r->status]}}</span>
                                        @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">
                                <h5 style="color: #ee2833!important">Bạn chưa có thanh toán</h5>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@stop
@section('footer_scripts')
    <script>
        function canpayment(slug){
            var slug = slug;
            swal({

                    title: "Xác nhận hủy đơn hàng",

                    text: "",

                    type: "warning",

                    showCancelButton: true,
                    confirmButtonColor: '#8CD4F5',
                    /* confirmButtonClass: "btn-danger",*/

                    confirmButtonText: "Đồng ý",

                    cancelButtonText: "Hủy bỏ",

                    closeOnConfirm: false,

                    closeOnCancel: false

                },

                function(isConfirm) {

                    if (isConfirm) {

                       //alert('ok');
                        let token = '{{csrf_token()}}';

                        route = '{{url('payments/transfer/delete')}}';

                        $.ajax({

                            url:route,

                            type: 'post',
                            dataType: "json",
                            data: {_method: 'post', _token :token,slug : slug},
                            beforeSend: function() {
                                // setting a timeout
                                swal({
                                    html:true,
                                    title: 'Đang xử lý, xin vui lòng chờ!',
                                    text: '<img style="position: relative;" src="/public/assets/images/loader.svg" class="loader-img" alt="img">',
                                    type: '',
                                    showConfirmButton: false,
                                    showCancelButton: false,

                                });
                            },
                            success:function(data){

                                //console.log(data)
                                if(data.error == 1){
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

                        });
                        window.location.reload(1);
                    } else {

                        swal("Hủy bỏ", "Đơn hàng của bạn đã được hủy bỏ", "error");

                    }

                });
        }
    </script>
@stop