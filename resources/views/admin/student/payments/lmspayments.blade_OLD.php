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

                <li><a href="#tab2" data-toggle="tab" class=""><i class="fa fa-credit-card"></i>  Thanh toán qua thẻ ATM</a></li>

                <li><a href="#tab3" data-toggle="tab" class=""><i class="fa fa-university"></i>  Chuyển khoản ngân hàng</a></li>

            </ul>

            <div class="tab-content">

                <div class="tab-pane active show" id="tab1">

                    <div class="card-body">

                        

                        <div class="row">

                            <div class="col-4">

                                <a href="/payments/momoqr"><img src="https://static.mservice.io/img/momo-upload-api-191008171059-637061514597580950.png" class="mw-100" alt="image"></a>

                            </div>

                        </div>

                        <div class="row" style="margin-top: 30px">

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

                            <a href="/payments/momoqr/{{$lmsseries->cost}}" class="btn btn-success">Click vào đây để thanh toán</a>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="tab-pane" id="tab2">

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

                    <div class="card-body">

                        <ul class="list_cart-2 clearfix" style="list-style: none;" id="list-bank">

                            <li>

                                <label for="VIETCOMBANK">

                                    <a href="/payments/atm/sml/{{$lmsseries->cost}}"><img src="https://pay.vnpay.vn/images/bank/vietcombank_logo.png" width="200" height="40" alt="VIETCOMBANK"></a>

                                </label>

                            </li>

                            <li>

                                <label for="VIETINBANK">

                                    <img src="https://pay.vnpay.vn/images/bank/vietinbank_logo.png" width="200" height="40" alt="VIETINBANK">

                                </label>

                            </li>

                            <li>

                                <label for="BIDV">

                                    <img src="https://pay.vnpay.vn/images/bank/bidv_logo.png" width="200" height="40" alt="BIDV">

                                </label>

                            </li>

                            <li>

                                <label for="AGRIBANK">

                                    <img src="https://pay.vnpay.vn/images/bank/agribank_logo.png" width="200" height="40" alt="AGRIBANK">

                                </label>

                            </li>

                            <li>

                                <label for="SACOMBANK">

                                    <img src="https://pay.vnpay.vn/images/bank/sacombank_logo.png" width="200" height="40" alt="SACOMBANK">

                                </label>

                            </li>

                            <li>

                                <label for="TECHCOMBANK">

                                    <img src="https://pay.vnpay.vn/images/bank/techcombank_logo.png" width="200" height="40" alt="TECHCOMBANK">

                                </label>

                            </li>

                        </ul>

                        <br>

                    </div>

                </div>

                <div class="tab-pane" id="tab3">

                    <h6 class="font-weight-semibold">Thông tin ngân hàng</h6>

                    <dl class="card-text">

                        <dt>Ngân hàng: </dt>

                        <dd> Vietcombank</dd>

                    </dl>

                    <dl class="card-text">

                        <dt>Số tài khoản: </dt>

                        <dd> 000011112222</dd>

                    </dl>

                    <dl class="card-text">

                        <dt>MST: </dt>

                        <dd>0305322160</dd>

                    </dl>

                    <p class="mb-0"><strong>Ghi chú:</strong> Người mua cần nhập số điện thoại đã đăng ký tài khoản hoặc tên đăng nhập (username) tài khoản  trong phần nội dung thanh toán khi thiết lập lệnh chuyển tiền. </p>

                    <p class="mb-0">

                    Căn cứ vào nội dung thanh toán trong biên lai để kiểm tra tính xác thực của biên lai thanh toán trong vòng 1-3 ngày làm việc sau khi thành công.</p>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="card mb-0">

    <div class="card-header">

        <h3 class="card-title">Payments</h3>

    </div>

    <div class="card-body">

        <div class="table-responsive border-top">

            <table class="table table-bordered table-hover text-nowrap">

                <thead>

                    <tr>

                        <th>Invoice ID</th>

                        <th>Category</th>

                        <th>Timings</th>

                        <th>Fees</th>

                        <th>Duration</th>

                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td>#INV-348</td>

                        <td>NetWorking</td>

                        <td>9 Am- 11 Am</td>

                        <td class="font-weight-semibold fs-16">$89</td>

                        <td>3 Months</td>

                        <td>

                            <a class="btn btn-primary btn-sm text-white mb-1" data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i></a>

                            <a class="btn btn-secondary btn-sm text-white mb-1" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash-o"></i></a><br>

                        </td>

                    </tr>

                    <tr>

                        <td>#INV-186</td>

                        <td>Web Design</td>

                        <td>10 Am- 1 Pm</td>

                        <td class="font-weight-semibold fs-16">$14,276</td>

                        <td>2 Months</td>

                        <td>

                            <a class="btn btn-primary btn-sm text-white mb-1" data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i></a>

                            <a class="btn btn-secondary btn-sm text-white mb-1" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash-o"></i></a><br>

                        </td>

                    </tr>

                    <tr>

                        <td>#INV-831</td>

                        <td>Science</td>

                        <td>8 Am- 11 Am</td>

                        <td class="font-weight-semibold fs-16">$25,000</td>

                        <td>5 Months</td>

                        <td>

                            <a class="btn btn-primary btn-sm text-white mb-1" data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i></a>

                            <a class="btn btn-secondary btn-sm text-white mb-1" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash-o"></i></a><br>

                        </td>

                    </tr>

                    <tr>

                        <td>#INV-672</td>

                        <td>Litarature</td>

                        <td>9 Am- 11 Am</td>

                        <td class="font-weight-semibold fs-16">$50.00</td>

                        <td>4 Months</td>

                        <td>

                            <a class="btn btn-primary btn-sm text-white mb-1" data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i></a>

                            <a class="btn btn-secondary btn-sm text-white mb-1" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash-o"></i></a><br>

                        </td>

                    </tr>

                    <tr>

                        <td>#INV-428</td>

                        <td>Electornics</td>

                        <td>2 Pm- 5 Pm</td>

                        <td class="font-weight-semibold fs-16">$99.99</td>

                        <td>3 Months</td>

                        <td>

                            <a class="btn btn-primary btn-sm text-white mb-1" data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i></a>

                            <a class="btn btn-secondary btn-sm text-white mb-1" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash-o"></i></a><br>

                        </td>

                    </tr>

                    <tr>

                        <td>#INV-543</td>

                        <td>UI Developer</td>

                        <td>10 Am- 2 Pm</td>

                        <td class="font-weight-semibold fs-16">$145</td>

                        <td>6 Months</td>

                        <td>

                            <a class="btn btn-primary btn-sm text-white mb-1" data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i></a>

                            <a class="btn btn-secondary btn-sm text-white mb-1" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash-o"></i></a><br>

                        </td>

                    </tr>

                    <tr>

                        <td>#INV-986</td>

                        <td>Data Science</td>

                        <td>9 Am - 1 Pm</td>

                        <td class="font-weight-semibold fs-16">$378</td>

                        <td>2 Months</td>

                        <td>

                            <a class="btn btn-primary btn-sm text-white mb-1" data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i></a>

                            <a class="btn btn-secondary btn-sm text-white mb-1" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash-o"></i></a><br>

                        </td>

                    </tr>

                    <tr>

                        <td>#INV-867</td>

                        <td>Digital Marketing</td>

                        <td>11 Am - 1 Pm</td>

                        <td class="font-weight-semibold fs-16">$509.00</td>

                        <td>4 Months</td>

                        <td>

                            <a class="btn btn-primary btn-sm text-white mb-1" data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i></a>

                            <a class="btn btn-secondary btn-sm text-white mb-1" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash-o"></i></a><br>

                        </td>

                    </tr>

                    <tr>

                        <td>#INV-893</td>

                        <td>Computer</td>

                        <td>8 Am - 1 Pm</td>

                        <td class="font-weight-semibold fs-16">$347</td>

                        <td>8 Months</td>

                        <td>

                            <a class="btn btn-primary btn-sm text-white mb-1" data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i></a>

                            <a class="btn btn-secondary btn-sm text-white mb-1" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash-o"></i></a><br>

                        </td>

                    </tr>

                    <tr>

                        <td>#INV-267</td>

                        <td>Python Classes </td>

                        <td>9 Am - 11 Am</td>

                        <td class="font-weight-semibold fs-16">$895</td>

                        <td>5 Months</td>

                        <td>

                            <a class="btn btn-primary btn-sm text-white mb-1" data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i></a>

                            <a class="btn btn-secondary btn-sm text-white mb-1" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash-o"></i></a><br>

                        </td>

                    </tr>

                    <tr>

                        <td>#INV-931</td>

                        <td>HTML</td>

                        <td>11 Am - 1 Pm</td>

                        <td class="font-weight-semibold fs-16">$765</td>

                        <td>6 Months</td>

                        <td>

                            <a class="btn btn-primary btn-sm text-white mb-1" data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i></a>

                            <a class="btn btn-secondary btn-sm text-white mb-1" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash-o"></i></a><br>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

        <ul class="pagination">

            <li class="page-item page-prev disabled">

                <a class="page-link" href="#" tabindex="-1">Prev</a>

            </li>

            <li class="page-item active"><a class="page-link" href="#">1</a></li>

            <li class="page-item"><a class="page-link" href="#">2</a></li>

            <li class="page-item"><a class="page-link" href="#">3</a></li>

            <li class="page-item page-next">

                <a class="page-link" href="#">Next</a>

            </li>

        </ul>

    </div>

</div>

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

@stop