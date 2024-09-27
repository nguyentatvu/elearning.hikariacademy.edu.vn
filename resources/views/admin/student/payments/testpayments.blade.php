@extends('admin.layouts.student.studentsettinglayout')
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Phương thức thanh toán</h3>
    </div>
    <div class="card-body">
        <div class="card-pay">
            <ul class="tabs-menu nav">
                <li class=""><a href="#tab1" class="active" data-toggle="tab"><i class="fa fa-money"></i> Mã QR Code MoMo</a></li>
                <li><a href="#tab2" data-toggle="tab" class=""><i class="fa fa-credit-card"></i> Thẻ ATM (Cổng VnPay)</a></li>
                <li><a href="#tab3" data-toggle="tab" class=""><i class="fa fa-university"></i>  Chuyển khoản ngân hàng</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active show" id="tab1">
                    <div class="card-body">
                        <div class="row" style="">
                            <div class="col-12 mb-4">
                                <h5>Thông tin thanh toán:</h5>
                               
                                <h5><span class="text-primary font-weight-semibold h4"><i class="fa fa-star text-primary" aria-hidden="true"></i> {{$lmsseries->title}} (#{{$lmsseries->code}}) - {{ number_format($lmsseries->cost, 0, 0, '.')}}đ</span></h5>
                              
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
                <div class="tab-pane" id="tab2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-4">
                                <h5>Thông tin thanh toán:</h5>
                                {{-- <h5><i class="fa fa-star text-primary" aria-hidden="true"></i> Khóa học: {{$lmsseries->title}}</h5> --}}
                                <h5><span class="text-primary font-weight-semibold h4"><i class="fa fa-star text-primary" aria-hidden="true"></i> {{$lmsseries->title}} (#{{$lmsseries->code}}) - {{ number_format($lmsseries->cost, 0, 0, '.')}}đ</span></h5>
                                {{-- <h5><span class="text-primary font-weight-semibold h4"><i class="fa fa-money text-primary" aria-hidden="true"></i> {{ number_format($lmsseries->cost, 0, 0, '.')}}đ</span></h5> --}}
                            </div>
                            <div class="col-12">
                                <ul class="list-unstyled widget-spec mb-0">
                                    <li class="">
                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 1: Chọn ngân hàng thanh toán
                                    </li>
                                    <li class="">
                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 2: Nhập thông tin thẻ ATM
                                    </li>
                                    <li class="">
                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 3: Kiểm tra & Bấm “Xác nhận”
                                    </li>
                                </ul>
                            </div>
                            <div class="col-12">
                                <a href="https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?vnp_Amount=1806000&vnp_Command=pay&vnp_CreateDate=20210801153333&vnp_CurrCode=VND&vnp_IpAddr=127.0.0.1&vnp_Locale=vn&vnp_OrderInfo=Thanh+toan+don+hang+%3A5&vnp_OrderType=other&vnp_ReturnUrl=https%3A%2F%2Fdomainmerchant.vn%2FReturnUrl&vnp_TmnCode=DEMOV210&vnp_TxnRef=5&vnp_Version=2.1.0&vnp_SecureHash=3e0d61a0c0534b2e36680b3f7277743e8784cc4e1d68fa7d276e79c23be7d6318d338b477910a27992f5057bb1582bd44bd82ae8009ffaf6d141219218625c42" class="btn btn-success">Click vào đây để thanh toán</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-4">
                                <h5>Thông tin thanh toán:</h5>
                                {{-- <h5><i class="fa fa-star text-primary" aria-hidden="true"></i> Khóa học: {{$lmsseries->title}}</h5> --}}
                                <h5><span class="text-primary font-weight-semibold h4"><i class="fa fa-star text-primary" aria-hidden="true"></i> {{$lmsseries->title}} (#{{$lmsseries->code}}) - {{ number_format($lmsseries->cost, 0, 0, '.')}}đ</span></h5>
                                {{-- <h5><span class="text-primary font-weight-semibold h4"><i class="fa fa-money text-primary" aria-hidden="true"></i> {{ number_format($lmsseries->cost, 0, 0, '.')}}đ</span></h5> --}}
                            </div>
                            <div class="col-12 ">
                                <ul class="list-unstyled widget-spec mb-0">
                                    <li class="">
                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 1: Click vào Tạo đơn hàng
                                    </li>
                                    <li class="">
                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 2: Chuyển khoản với nội dung &nbsp; <strong class="text-primary">"{{Auth::user()->username}} {{$lmsseries->code}}" </strong> 
                                    </li>
                                    {{-- <li class="">
                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 3: Sau khi thanh toán thành công
                                    </li> --}}
                                </ul>
                                {{-- <h6 class="font-weight-semibold mt-5">Thông tin ngân hàng</h6> --}}
                                <ul class="list-group">
                                    <li class="listunorder">Ngân hàng: Vietcombank – Chi nhánh Tân Bình</li>
                                    <li class="listunorder">Số tài khoản: 0441000688321</li>
                                    <li class="listunorder">Tên tài khoản: TRUNG TAM NHAT NGU QUANG VIET</li>
                                </ul>
                                {{-- <dl class="card-text">
                                    <dt>Ngân hàng: </dt>
                                    <dd> Vietcombank – Chi nhánh Tân Bình</dd>
                                </dl>
                                <dl class="card-text">
                                    <dt>Số tài khoản: </dt>
                                    <dd> 0441000688321</dd>
                                </dl>
                                <dl class="card-text">
                                    <dt>Tên tài khoản: </dt>
                                    <dd> TRUNG TAM NHAT NGU QUANG VIET</dd>
                                </dl> --}}
                                {{-- <dl class="card-text">
                                    <dt>MST: </dt>
                                    <dd>0305322160</dd>
                                </dl> --}}
                            </div>
                            <div class="col-12 mb-5 mt-5">
                                <a href="javascript:void(0)"  onclick="showpayment()" class="btn btn-success">Tạo đơn hàng</a>
                            </div>
                        </div>
                        <p class="mb-0"><strong>Ghi chú:</strong> Chuyển khoản với nội dung: <strong class="text-primary">"{{Auth::user()->username}} {{$lmsseries->code}}" </strong> trong phần nội dung thanh toán khi thiết lập lệnh chuyển tiền</p>
                        {{-- <p class="mb-0"><strong>Ghi chú:</strong> Người mua cần nhập số điện thoại đã đăng ký tài khoản hoặc tên đăng nhập (username) tài khoản  trong phần nội dung thanh toán khi thiết lập lệnh chuyển tiền. </p> --}}
                        <p class="mb-0">
                        Căn cứ vào nội dung thanh toán trong biên lai để kiểm tra tính xác thực của biên lai thanh toán trong vòng 1-3 ngày làm việc sau khi thành công.</p>
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
        function showpayment(){
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
                            },
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
                                if(data.error === 1){
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
                        //location.reload()
                    } else {
                        swal("Hủy bỏ", "Đơn hàng của bạn đã hủy bỏ", "error");
                    }
                });
        }
    </script>
    @endif
@stop