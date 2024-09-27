

@extends('admin.layouts.student.studentsettinglayout')

@section('header_scripts')

<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
<style>
    .payment-discount-container {
        margin-top: 10px;
        display: flex;
    }

    .discount-coin-container {
        margin: 15px 0;
    }

    .discount-coin-container input {
        width: 200px;
        margin-top: 10px
    }
</style>

@stop

@section('content')

<div class="card">

    <div class="card-header">

        <h3 class="card-title">Phương thức thanh toán</h3>

    </div>

    <div class="card-body">

        <div class="card-pay">

            <ul class="tabs-menu nav">
                <li><a href="#tab1" data-toggle="tab" class="active"><i class="fa fa-money">  </i> Thanh toán qua VNPAY</a></li>
                <li class=""><a href="#tab2" data-toggle="tab"><i class="fa fa-money"></i>Ví MoMo (Mã QR)</a></li>
                <li><a href="#tab3" data-toggle="tab" class=""><i class="fa fa-university"></i>  Chuyển khoản ngân hàng</a></li>

            </ul>

            <div class="tab-content">

				<div class="tab-pane active show" id="tab1">
                    <div class="card-body" >

                        <div class="row" style="">

                            <div class="col-12 mb-4">

                                <h5>Thông tin thanh toán:</h5>

                                {{-- <h5><i class="fa fa-star text-primary" aria-hidden="true"></i> Khóa học: {{$lmsseries->title}}</h5> --}}

                                <h5>
                                    <span class="text-primary font-weight-semibold h4"><i class="fa fa-star text-primary" aria-hidden="true"></i> {{$lmsseries->title}} (#{{$lmsseries->code}}) - 
                                <!-- {{ number_format($lmsseries->cost, 0, 0, '.')}}đ -->
                                @if($lmsseries->timefrom != null && $lmsseries->timeto != null && (int)$lmsseries->selloff < (int)$lmsseries->cost)

                                    @if(strtotime(date("Y-m-d")) >= strtotime(date("Y-m-d", strtotime($lmsseries->timefrom)))
                                        && strtotime(date("Y-m-d")) <= strtotime(date("Y-m-d", strtotime($lmsseries->timeto))))

                                        {{ number_format($lmsseries->selloff, 0, 0, '.')}}đ

                                    @else 
                                        {{ number_format($lmsseries->cost, 0, 0, '.')}}đ
                                    @endif
                                @elseif((int)$lmsseries->cost  == 0)
                                    Miễn phí 
                                @else 
                                    {{ number_format($lmsseries->cost, 0, 0, '.')}}đ
                                @endif
                            </span>
                            </h5>

                                {{-- <h5><span class="text-primary font-weight-semibold h4"><i class="fa fa-money text-primary" aria-hidden="true"></i> {{ number_format($lmsseries->cost, 0, 0, '.')}}đ</span></h5> --}}

                            </div>

                            

                            <div class="col-12">

                                <ul class="list-unstyled widget-spec mb-0">

                                    <li class="">

                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 1: Từ màn hình thanh toán VNPAY, chọn phương thức thanh toán. Các phương thức thanh toán hiện có: 
										<br>* Thanh toán quét mã VNPAY
										<br>* Thẻ ATM và tài khỏan ngân hàng
										<br>* Thẻ thanh toán quốc tế
										<br>* Ví điện tử VNPAY
                                    </li>

                                    <li class="">
                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 2: Nhập các thông tin yêu cầu, nhấn Tiếp tục.
                                    </li>

									<li class="">
                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 3: Kiểm tra thông tin giao dịch
                                    </li>

									<li class="">
                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 4: Nhấn Xác nhận.
                                    </li>

									<li class="">
                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 5: Nhập mã OTP xác thực giao dịch.
                                    </li>
                                </ul>

                            </div>
                            @if ($is_redeemed)
                                <div class="col-12">
                                    <p>Số HI Coin hiện tại: <strong>{{ auth()->user()->reward_point + auth()->user()->recharge_point }}</strong></p>
                                    <p>Số coin cần để giảm giá khóa học: <strong>{{ $required_redeem_point }}</strong></p>
                                </div>
                                <div class="col-12">
                                    <a href="/payments/vnpay/{{ $lmsseries->slug }}?is_redeemed={{ $is_redeemed ? '1' : '0' }}" class="btn btn-success payment-link">Thanh toán với điểm thưởng!</a>
                                </div>
                            @else
                                <div class="col-12">
                                    <a href="/payments/vnpay/{{ $lmsseries->slug }}" class="btn btn-success payment-link">Click vào đây để thanh toán</a>
                                </div>
                            @endif

                        </div>
                    </div>

                </div>

                <div class="tab-pane" id="tab2">

                    <div class="card-body">

                        <div class="row" style="">

                            <div class="col-12 mb-4">

                                <h5>Thông tin thanh toán:</h5>

                                {{-- <h5><i class="fa fa-star text-primary" aria-hidden="true"></i> Khóa học: {{$lmsseries->title}}</h5> --}}

                                <h5>
                                    <span class="text-primary font-weight-semibold h4"><i class="fa fa-star text-primary" aria-hidden="true"></i> {{$lmsseries->title}} (#{{$lmsseries->code}}) - 
                                <!-- {{ number_format($lmsseries->cost, 0, 0, '.')}}đ -->
                                @if($lmsseries->timefrom != null && $lmsseries->timeto != null && (int)$lmsseries->selloff < (int)$lmsseries->cost)

                                    @if(strtotime(date("Y-m-d")) >= strtotime(date("Y-m-d", strtotime($lmsseries->timefrom)))
                                        && strtotime(date("Y-m-d")) <= strtotime(date("Y-m-d", strtotime($lmsseries->timeto))))

                                        {{ number_format($lmsseries->selloff, 0, 0, '.')}}đ

                                    @else 
                                        {{ number_format($lmsseries->cost, 0, 0, '.')}}đ
                                    @endif
                                @elseif((int)$lmsseries->cost  == 0)
                                    Miễn phí 
                                @else 
                                    {{ number_format($lmsseries->cost, 0, 0, '.')}}đ
                                @endif
                            </span>
                            </h5>

                                {{-- <h5><span class="text-primary font-weight-semibold h4"><i class="fa fa-money text-primary" aria-hidden="true"></i> {{ number_format($lmsseries->cost, 0, 0, '.')}}đ</span></h5> --}}

                            </div>

                            

                            <div class="col-12">

                                <ul class="list-unstyled widget-spec mb-0">

                                    <li class="">

                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 1: Mở Ví MoMo, chọn “Quét Mã”

                                    </li>

                                    <li class="">

                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 2: Quét mã QR. Di chuyển Camera để thấy và quét mã QR

                                    </li>

                                    <li class="">

                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 3: Kiểm tra & Bấm “Xác nhận”

                                    </li>

                                </ul>

                            </div>

                            <div class="col-12">

                                <a href="/payments/momoqr/{{$lmsseries->slug}}" class="btn btn-success">Click vào đây để thanh toán</a>

                            </div>

                        </div>

                        {{-- <div class="row mt-4">

                            <div class="col-4">

                                <a href="/payments/momoqr/{{$lmsseries->slug}}"><img src="https://static.mservice.io/img/momo-upload-api-191008171059-637061514597580950.png" class="mw-100" alt="image"></a>

                            </div>

                        </div> --}}

                    </div>

                </div>

                 <div class="tab-pane" id="tab3">

                    <div class="card-body">

                        <div class="row">

                            <div class="col-12 mb-4">

                                <h5>Thông tin thanh toán:</h5>
                                <h5>
                                    <span class="text-primary font-weight-semibold h4"><i class="fa fa-star text-primary" aria-hidden="true"></i> {{$lmsseries->title}} (#{{$lmsseries->code}}) - 
                                    <!-- {{ number_format($lmsseries->cost, 0, 0, '.')}}đ -->
                                    @if($lmsseries->timefrom != null && $lmsseries->timeto != null && (int)$lmsseries->selloff < (int)$lmsseries->cost)

                                        @if(strtotime(date("Y-m-d")) >= strtotime(date("Y-m-d", strtotime($lmsseries->timefrom)))
                                            && strtotime(date("Y-m-d")) <= strtotime(date("Y-m-d", strtotime($lmsseries->timeto))))

                                            {{ number_format($lmsseries->selloff, 0, 0, '.')}}đ

                                        @else 
                                            {{ number_format($lmsseries->cost, 0, 0, '.')}}đ
                                        @endif
                                    @elseif((int)$lmsseries->cost  == 0)
                                        Miễn phí 
                                    @else 
                                        {{ number_format($lmsseries->cost, 0, 0, '.')}}đ
                                    @endif
                            
                                    </span>
                                    
                            </h5>

                                {{-- <h5><span class="text-primary font-weight-semibold h4"><i class="fa fa-money text-primary" aria-hidden="true"></i> {{ number_format($lmsseries->cost, 0, 0, '.')}}đ</span></h5> --}}

                            </div>



                            <div class="col-12 ">



                                <ul class="list-unstyled widget-spec mb-0">

                                    <li class="">

                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 1: Click vào Tạo đơn hàng

                                    </li>

                                    <li class="">

                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 2: Chuyển khoản với nội dung&nbsp; <strong class="text-primary">"{{Auth::user()->username}} {{$lmsseries->code}}" </strong> &nbsp;đến tài khoản ngân hàng của trung tâm.

                                    </li>

                                    <li class="">

                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 3: Sau khi nhận được thanh toán, trung tâm sẽ kích hoạt khóa học ngay cho bạn.

                                    </li>

                                </ul>

                                



                                <h6 class="font-weight-semibold mt-5">Thông tin ngân hàng</h6>

                                <ul class="list-group">

                                    <li class="listunorder">Ngân hàng: <strong>Vietcombank – Chi nhánh Tân Bình</strong></li>

                                    <li class="listunorder">Số tài khoản: <strong>0441000688321</strong></li>

                                    <li class="listunorder">Tên thụ hưởng: <strong>TRUNG TAM NHAT NGU QUANG VIET</strong></li>

                                </ul>
                                
                            </div>


                            @if ($is_redeemed)
                                <div class="col-12 mt-5">
                                    <p>Số HI Coin hiện tại: <strong>{{ auth()->user()->reward_point + auth()->user()->recharge_point }}</strong></p>
                                    <p>Số coin cần để giảm giá khóa học: <strong>{{ $required_redeem_point }}</strong></p>
                                </div>
                                <div class="col-12">
                                    <a href="javascript:void(0)" onclick="showpayment(true)" class="btn btn-success">Tạo đơn hàng với HiCoin!</a>
                                </div>
                            @else
                                <div class="col-12 mb-5 mt-5">
                                    <a href="javascript:void(0)"  onclick="showpayment()" class="btn btn-success">Tạo đơn hàng</a>
                                </div>
                            @endif

                        </div>

                        <p class="mb-0">
						Ghi chú: Tùy thuộc vào ngân hàng, hình thức chuyển tiền hay thời điểm thanh toán (ngoài giờ làm việc hay bị trùng ngày nghỉ/ngày lễ) mà việc xác nhận thanh toán có thể dao động từ 2 đến 72 tiếng. 
						</p>

                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

{{-- <div class="card mb-0">

    <div class="card-header">

        <h3 class="card-title">Lịch sử thanh toán</h3>

    </div>

    <div class="card-body">

        <div class="table-responsive border-top">

            <table class="table table-bordered table-hover text-nowrap">

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Khóa học</th>

                        <th class="text-center align-middle">Giá</th>

                        <th class="text-center align-middle">Thời gian</th>

                        <th class="text-center align-middle">Phương thức</th>

                    </tr>

                </thead>

                <tbody>

                @if(count($payments_history) > 0)

                    @foreach($payments_history as $r)

                        <tr>

                            <td>{{$r->orderId}}</td>

                            <td>{{$r->item_name}}</td>

                            <td class="text-center align-middle font-weight-semibold fs-16">{{ number_format($r->amount, 0, 0, '.')}}đ</td>

                            <td class="text-center align-middle">{{date_format(date_create($r->created_at),"d-m-Y")}}</td>

                            <td class="text-center align-middle text-uppercase">{{$r->orderType}}</td>

                    @endforeach

                @endif

                </tbody>

            </table>

        </div>

    </div>

</div> --}}

<style type="text/css">

ul#list-bank {

list-style: none;

padding: 30px 10px;

}

ul#list-bank li {

width: 33%;

height: auto;

text-align: center;

float: left;

padding: 5px;

}

ul#list-bank li label {

color: #434a54;

font-weight: 500;

padding: 0;

height: 4.5em;

border: 1px solid #dcdcdf;

-webkit-border-radius: 5px;

border-radius: 5px;

line-height: 4.5em;

display: block;

position: relative;

margin-bottom: 0;

-webkit-transition: all .15s ease;

-o-transition: all .15s ease;

transition: all .15s ease;

text-align: center;

-webkit-box-shadow: 1px 2px 3px 0 rgba(0,0,0,.08);

box-shadow: 1px 2px 3px 0 rgba(0,0,0,.08);

}

</style>

@endsection

@section('footer_scripts')



    @if(Auth::check())

        <script>
            $(document).ready(function () {
                $('input#discount').on('change', function () {
                    console.log('change');
                    var discount = $(this).val();
                    const paymentLink = $('a.payment-link');
                    const link = '/payments/vnpay/' + '{{ $lmsseries->slug }}';

                    paymentLink.attr('href', link + '?discount=' + discount);

                });
            });

        function showpayment(isRedeemed = false){

            swal({



                    title: "Xác nhận tạo đơn hàng",



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



                        let route = '{{url('payments/transfer')}}';

                        let token = '{{csrf_token()}}';

                        let slug  =  '{{$lmsseries->slug}}';



                        $.ajax({



                            url:route,



                            type: 'post',

                            dataType: "json",

                            data: {

                                _method: 'post',

                                _token :token,

                                slug : slug,
                                isRedeemed
                            },

                            beforeSend: function() {

                                // setting a timeout

                                swal({

                                    html:true,

                                    title: 'Đang xử lý... \nVui lòng chờ giây lát!',

                                    text: '<img style="position: relative;" src="/public/assets/images/loader.svg" class="loader-img" alt="img">',

                                    type: '',

                                    showConfirmButton: false,

                                    showCancelButton: false,



                                });

                            },

                            success: function(data){
                                if (data.error == 1) {
                                    swal({
                                        title: 'Thông báo',
                                        text: data.message,
                                        type: 'success',
                                        showConfirmButton: false,
                                        showCancelButton: false,
                                        timer: 3000
                                    });
                                }
                                else {
                                    swal({
                                        title: 'Thông báo',
                                        text: data.message,
                                        type: 'error',
                                        showConfirmButton: false,
                                        showCancelButton: false,
                                        timer: 3000
                                    });
                                }
                            }
                        })



                        //location.reload()

                    } else {



                        swal("Hủy bỏ", "Đơn hàng của bạn đã bị hủy bỏ", "error");



                    }



                });

        }

    </script>

    @endif

@stop