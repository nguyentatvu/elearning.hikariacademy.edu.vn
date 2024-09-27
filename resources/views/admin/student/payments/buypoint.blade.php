@extends($layout)
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')
<div id="page-wrapper">
	<div class="container-fluid">
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
					<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
					<li>{{ $title }}</li>
				</ol>
			</div>
		</div>
		<!-- /.row -->
		<div class="panel panel-custom">
			<div class="panel-heading">
				<h1>{{ $title }}</h1>
			</div>
			<div class="panel-body packages">
				<div class="row">
					<div class="col-md-4 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a href="http://elearning.hikariacademy.edu.vn.localhost:8080/exams/student-exam-series/list"><div class="state-icn bg-icon-info"><i class="fa fa-sun-o"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				<h4 class="card-title"><?php echo Auth::user()->point; ?></h4>
								<a href="#">Hi Xu</a>
				 			</div>
				 		</div>
				 	</div>
				 	<div class="col-md-4 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a href="http://elearning.hikariacademy.edu.vn.localhost:8080/exams/student/exams/all"><div class="state-icn bg-icon-pink"><i class="fa fa-telegram"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				<h4 class="card-title">{{ App\Payment::where('user_id', '=', Auth::user()->id)->get()->count() }}</h4>
								<a href="/payments/buy-item">Đã sử dụng</a>
				 			</div>
				 		</div>
				 	</div>
			 	 	<div class="col-md-4 col-sm-6">
			 	 		<div class="media state-media box-ws">
			 	 			<div class="media-left">
			 	 				<a href="http://elearning.hikariacademy.edu.vn.localhost:8080/exams/student/exams/all"><div class="state-icn bg-icon-purple"><i class="fa fa-building-o"></i></div></a>
			 	 			</div>
			 	 			<div class="media-body">
			 	 				<h4 class="card-title">{{ App\PaymentMethod::where('user_id', '=', Auth::user()->id)->get()->count() }}</h4>
			 					<a href="/payments/history">Số lần nạp</a>
			 	 			</div>
			 	 		</div>
			 	 	</div>
				 	
				</div>
				<p>Bước 1: Đăng Nhập App MoMo</p>
				<p>Bước 2: Bấm Chọn icon  góc phải trên cùng và tiến hành quét QR Code.</p>
				<p>Bước 3: Bấm chọn Xác Nhận trên Ví và hoàn tất.</p>
				<p>
					{!! Form::open(array('url' => '/payments/buypoint', 'method' => 'POST', 'files' => true, 'name'=>'formPoint ', 'novalidate'=>'','files'=>TRUE)) !!}
							<div class="row">

								<fieldset class="form-group col-md-6">

									{{ Form::label('title', 'Số tiền cần nạp (1000vnđ = 1xu)') }}

									<span class="text-red">*</span>

									{{ Form::text('point', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '',

									'ng-model'=>'point', 

									'ng-pattern'=>'', 

									'required'=> 'true', 

									'ng-class'=>'{"has-error": formPoint.point.$touched && formPoint.point.$invalid}',

									'ng-minlength' => '1',

									'ng-maxlength' => '400',

									)) }}

									<div class="validation-error" ng-messages="formPoint.point.$error" >

										{!! getValidationMessage()!!}

										{!! getValidationMessage('pattern')!!}

										{!! getValidationMessage('minlength')!!}

										{!! getValidationMessage('maxlength')!!}

									</div>

									<div class="buttons text-center">

										<button class="btn btn-lg btn-success button"

										ng-disabled='!formQuiz.$valid'>Thanh toán Momo</button>

									</div>

								</fieldset>
							
							</div>

							
					{!! Form::close() !!}
					
				</p>
			</div>
		</div>
	</div>
	<!-- /.container-fluid -->
</div>
@endsection
@section('footer_scripts')
@stop
