@extends('admin.layouts.student.studentlayout')
@section('content')
<div id="page-wrapper">

	<div class="row">
	    <div class="col-md-12 grid-margin">
	        <div class="card bg-white">
	          <div class="card-body d-flex align-items-center justify-content-between">
	            <h4 class="mt-1 mb-1">Chào bạn <span style="text-transform: capitalize;">{{Auth::user()->name}}</span></h4>
	          </div>
	        </div>
	    </div>
	</div>

	<div class="row">
        
        <div class="col-md-3 grid-margin stretch-card">
          <div class="card border-0 border-radius-2 bg-info">
            <div class="card-body">
              <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between">
                <div class="icon-rounded-inverse-info icon-rounded-lg">
                  <i class="mdi mdi-basket"></i>
                </div>
                <div class="text-white">
                  <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left">Khóa học</p>
                  <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                    <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">3</h3>
                    <small class="mb-0">đã mua</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
          <div class="card border-0 border-radius-2 bg-warning">
            <div class="card-body">
              <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between">
                <div class="icon-rounded-inverse-warning icon-rounded-lg">
                  <i class="mdi mdi-chart-multiline"></i>
                </div>
                <div class="text-white">
                  <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left">Đề thi</p>
                  <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                    <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">0</h3>
                    <small class="mb-0">đã mua</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
          <div class="card border-0 border-radius-2 bg-success">
            <div class="card-body">
              <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between">
                <div class="icon-rounded-inverse-success icon-rounded-lg">
                  <i class="mdi mdi-arrow-top-right"></i>
                </div>
                <div class="text-white">
                  <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left">Số coin</p>
                  <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                    <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">{{Auth::user()->point}}</h3>
                    <!-- <small class="mb-0">This month</small> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
          <div class="card border-0 border-radius-2 bg-danger">
            <div class="card-body">
              <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between">
                <div class="icon-rounded-inverse-danger icon-rounded-lg">
                  <i class="mdi mdi-chart-donut-variant"></i>
                </div>
                <div class="text-white">
                  <p class="font-weight-medium mt-md-2 mt-xl-0 text-md-center text-xl-left">Số lần nạp</p>
                  <div class="d-flex flex-md-column flex-xl-row flex-wrap align-items-baseline align-items-md-center align-items-xl-baseline">
                    <h3 class="mb-0 mb-md-1 mb-lg-0 mr-1">4</h3>
                    <small class="mb-0">lần</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>

    <div class="row">
    	<div class="col-md-12">
	    	<div class="card">
		        <div class="card-body">
		          <p class="card-title">Thông báo</p>
		            <div class="row">
			            <div class="col-md-12">
			                <p class="text-muted mb-3">Các thông tin về Hikari Elearning bạn sẽ nhận được ở đây</p>
			            </div>
		            </div>
		            <div class="table-responsive">
			            <table class="table table-striped">
			              <thead>
			                <tr class="border-top-0">
			                  <th class="text-muted">Thông báo</th>
			                  <th class="text-muted">Ngày</th>
			                </tr>
			              </thead>
			              <tbody>
			                <tr>
			                  <td>Khóa luyện thi N4 sẽ được mở ngày 23/03/2020</td>
			                  <td>02/02/2020</td>
			                </tr>
			                <tr>
			                  <td>Đề thi thử N4 sẽ được mở vào ngày 25/03/2020 lúc 17h~21h</td>
			                  <td>02/02/2020</td>
			                </tr>
			              </tbody>
			            </table>
		            </div>
		        </div>
	      	</div>
	    </div>
	</div>

</div>
<!-- /#page-wrapper -->
@stop
@section('footer_scripts')
@stop