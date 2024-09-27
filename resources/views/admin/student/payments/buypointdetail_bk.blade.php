@extends($layout)
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')
<div class="container" style="background-color: #fff;">

    <div class="row">

        <div class="col-sm-12">
           

            <div style="padding: 20px 0px">

              <nav aria-label="breadcrumb">

                <ol class="breadcrumb breadcrumb-custom bg-inverse-info">

                  <li class="breadcrumb-item"><a href="/home"><i class="mdi mdi-home menu-icon"></i></a></li>
                  <li class="breadcrumb-item"><a href="/site/shop">Cửa hàng</a></li>
                  <li class="breadcrumb-item active" aria-current="page"><span>Phương thức thanh toán</span></li>

                </ol>

              </nav>
          
              <div class="ed_heading_top col-sm-12">

               <h3 class="tilte-h3 wow fadeInDown text-danger animated text-uppercase" style="visibility: visible;">Phương thức thanh toán</h3>

             </div>
			


			<div class="row pricing-table">
              
                 <div class="col-md-6 col-xl-4 grid-margin stretch-card pricing-card">
                  <div class="card border-primary border border-success pricing-card-body">
                    <div class="text-center pricing-card-head">
                      <h3><img src="/Themes/themeone/assets/images/icon-bank.png" style="width: 50px;"></h3>
                      <p class="text-success">Mua Hi Koi với giá khuyễn mãi</p>
                      <h1 class="font-weight-normal mb-4 text-danger text-capitalize">600 Hi Koi</h1>


                      <h3 class="font-weight-normal mb-4">479.000 đ</h3>
                      <h4 class="font-weight-normal mb-4"><del>600.000 đ</del></h4>
                    </div>
                    <div class="wrapper">
                      <a href="#" class="btn btn-outline-primary btn-block">Chờ thanh toán</a>
                    </div>
                  </div>
                </div>
              
              <div class="col-md-8">
                  <div class="accordion accordion-bordered" id="accordion-2" role="tablist">
                      <div class="card">
                        <div class="card-header" role="tab" id="heading-4">
                          <h6 class="mb-0">
                            <a data-toggle="collapse" href="#collapse-4" aria-expanded="false" aria-controls="collapse-4" class="collapsed">
                              Thanh toán qua Ví Momo
                            </a>
                          </h6>
                        </div>
                        <div id="collapse-4" class="collapse" role="tabpanel" aria-labelledby="heading-4" data-parent="#accordion-2" style="">
                          <div class="card-body">
                          	<ol class="pl-3">
                              <li>Mở Ví MoMo, chọn “Quét Mã”</li>
                              <li>Quét mã QR. Di chuyển Camera để thấy và quét mã QR</li>
                              <li>Kiểm tra & Bấm “Xác nhận”</li>
                            </ol>
                            <a href="{{$momo}}">Click vào đây để thanh toán </a>
                            <div class="row">
                              <div class="col-3">
                                <a href="{{$momo}}"><img src="https://static.mservice.io/img/logo-momo.png" class="mw-100" alt="image"></a>                              
                              </div>
                              <div class="col-9">
                                <!-- <p class="mb-0"><a href="#">Click vào đây để thanh toán </a></p>                              -->
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card">
                        <div class="card-header" role="tab" id="heading-5">
                          <h6 class="mb-0">
                            <a class="collapsed" data-toggle="collapse" href="#collapse-5" aria-expanded="false" aria-controls="collapse-5">
                              Thanh toán qua thẻ ATM
                            </a>
                          </h6>
                        </div>
                        <div id="collapse-5" class="collapse" role="tabpanel" aria-labelledby="heading-5" data-parent="#accordion-2" style="">
                          <div class="card-body">
                              <p>Chọn ngân hàng để thanh toán</p>
	                            <ul class="list_cart-2 clearfix" style="list-style: none;" id="list-bank">
                                        <li>
                                            <label for="VIETCOMBANK">
                                                <a href="{{$vcb}}"><img src="https://pay.vnpay.vn/images/bank/vietcombank_logo.png" width="200" height="40" alt="VIETCOMBANK"></a>
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
                      </div>
                      <div class="card">
                        <div class="card-header" role="tab" id="heading-6">
                          <h6 class="mb-0">
                            <a class="collapsed" data-toggle="collapse" href="#collapse-6" aria-expanded="false" aria-controls="collapse-6">
                              Thanh toán chuyển khoản
                            </a>
                          </h6>
                        </div>
                        <div id="collapse-6" class="collapse" role="tabpanel" aria-labelledby="heading-6" data-parent="#accordion-2" style="">
                          <div class="card-body">
                            <p class="mb-0">If you wish to deactivate your account, you can go to the Settings page of your account. Click on Account Settings and then click on Deactivate.
                            You can join again as and when you wish.</p>
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
