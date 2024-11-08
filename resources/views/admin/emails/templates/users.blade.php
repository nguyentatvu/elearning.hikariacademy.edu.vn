@extends('admin.layouts.admin.adminlayout')

@section('header_scripts')

<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">


@stop

@section('content')

<style> 
	.d-none {display: none;}

	.custom-primary {background-color: #438afe !important; color: #fff !important;}

	.custom-dark {background-color: #656f7d !important; color: #fff !important;}

	.custom-success{color: #fff !important; background-color: #5cb85c !important; border-color: #4cae4c !important;}

	.w-100 {width: 100px;}
</style>

<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->

				<div class="row">

					<div class="col-lg-12">

						<ol class="breadcrumb">

							<li><a href="{{URL_ADMIN_DASHBOARD}}"><i class="mdi mdi-home"></i></a> </li>

							<li>{{ $title }}</li>

						</ol>

					</div>

				</div>

				<!-- /.row -->

				<div class="panel panel-custom">

					<div class="panel-heading">
					<div class="pull-right messages-buttons">
						<a href="{{URL_EXAM_SERIES_FREE}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>
						</div>
						<h1>{{ $titles }}</h1>

					</div>

					@if($examFree != null)
						<div class="panel-body packages">
							<div class="form-group">
								<h4>Tổng số email phải gửi: {{count($users)}}</h4> 
								<input id="total-email" value="{{count($users)}}" hidden />
							</div>

							<div class="form-group">
							<?php
								function secondsToTime($s)
								{
									$h = floor($s / 3600);
									$s -= $h * 3600;
									$m = floor($s / 60);
									$s -= $m * 60;
									return $h.':'.sprintf('%02d', $m).':'.sprintf('%02d', $s);
								}
							?>
								<h4>Tổng thời gian gửi: {{ secondsToTime(6 * count($users)) }}</h4> 
							</div>
							<div class="form-group">
								<input id="value-mode" hidden value="1"/>
								<h4>Tự động gửi: <input id="switch-mode" checked class="d-inline" type="checkbox" data-toggle="toggle"> 
								</h4>
								<h4>
									Tự động gửi vào: {{ $dateTime }}
								</h4>
							<div> 
							<div> 

							<div class="form-group text-center">

								<button disabled id="btn-send-mail" type="button"  class="btn btn-primary custom-primary w-100">Gửi Email</button>
								<button  disabled id="btn-cancel" type="button" class="btn btn-dark custom-dark w-100">Dừng</button>
								<button disabled id="btn-resend" type="button"  class="btn btn-success custom-success w-100">Gửi lại</button>
							</div>

							<div class="form-group">
								<div class="progress">
									<div class="progress-bar" id="progressbar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
								</div>
							</div>

							<div class="form-group">
								Số mail đã gửi được/ tổng số mail : <div style="display:inline " id="total-mail-send">0</div>/{{count($users)}}
							</div>

							<div class="alert alert-success d-none" id="message-success" role="alert">
								Đã hoàn thành gửi {{count($users)}} email
							</div>

							<div class="alert alert-danger d-none" id="message-error" role="alert">
								Nội dung lỗi được thông báo tại đây
							</div>


							</div>

						</div>
					@else
					<div class='col-12 form-group panel-body'>
						<div class="alert alert-danger" role="alert">
								Dữ liệu không tồn tại
						</div>
					</div>
					@endif
				</div>

			</div>
			@php
				$count = 0;
			@endphp

			<input id="current-slug" hidden value="{{$slug}}" />
			<input id="current-count" hidden value="0" />
			<input id="total-email" hidden value="{{count($users)}}" />

			@foreach ($users as $user)
				<input id="input-{{$count ++}}" hidden value="{{ $user->email }}" />
				
			@endforeach

			<!-- /.container-fluid -->

		</div>
@endsection
@section('footer_scripts')
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
 <script>
		$('#switch-mode').on('change', function() {
			const totalEmail = Number($('#total-email').val()) || 0;
			if(totalEmail > 0) {
				const value = $('#value-mode').val();
				if(value == "1") {
					$('#value-mode').val('0');
					$('#btn-send-mail').removeAttr('disabled');
					// $('#btn-cancel').removeAttr('disabled');
					// $('#btn-resend').removeAttr('disabled');
					
				} else {
					$('#value-mode').val('1');
					
					$('#btn-send-mail').attr('disabled','disabled');
					$('#btn-cancel').attr('disabled','disabled');
					$('#btn-resend').attr('disabled','disabled');
				}
			}
		});

		var xhr;
		var interval;
		function sendMail() {
			const currentSlug = $(`#current-slug`).val();
			const currentCount = Number($('#current-count').val()) || 0;
			const currentEmail = $(`#input-${currentCount}`).val();
			$('#message-error').addClass('d-none');
			$('#message-success').addClass('d-none');
			$('#btn-cancel').removeAttr('disabled');
			xhr = $.ajax({
					headers: {
					'X-CSRF-TOKEN': '{{csrf_token()}}'
					},
					url: '{{url('email/guimaithithu')}}',
					type: 'post',
					dataType: "json",
					data: {
						'email': currentEmail,
						'slug':currentSlug
					},
					success: function(response){
						if(response) {
							if(response.status == 500){
								$('#message-error').removeClass('d-none');
								$('#message-error').text(response.message);
								stopSendMail();
								return;
							}

							const totalEmail = Number($('#total-email').val());
							const totalCount = currentCount + 1;
							const totalPercent = totalCount/totalEmail * 100;
							
							$('#progressbar').text(`${totalPercent.toFixed(2)}%`);
							$('#progressbar').css("width", `${totalPercent.toFixed(2)}%`)
							$('#current-count').val(totalCount);
							$('#total-mail-send').text(totalCount);
							if(!interval) {
								interval = setInterval(sendMail, 6000);
							}
							if(totalCount == totalEmail) {
								stopSendMail();
								$('#message-success').removeClass('d-none');
								sendMailToAdmin();
								$('#btn-send-mail').attr('disabled','disabled');
								$('#btn-cancel').attr('disabled','disabled');
								$('#btn-resend').attr('disabled','disabled');
							}
						}
					},
					error: function(response){
						$('#message-error').removeClass('d-none');
						$('#message-error').text(response.message);
						stopSendMail();
					}
				});
			
		}

		$('#btn-cancel').on('click', function() {
			stopSendMail();
			$('#btn-cancel').attr('disabled','disabled');
			$('#btn-resend').removeAttr('disabled');
			$('#message-error').removeClass('d-none');
			$('#message-error').html('Đã dừng xử lý gửi mail');
		});

		function stopSendMail() {
			xhr.abort();
			clearInterval(interval);
			interval = null;
		}

		$('#btn-send-mail').on('click', function() {
			$('#btn-send-mail').attr('disabled','disabled');
			sendMail();
		});

		$('#btn-resend').on('click', function() {
			$('#btn-resend').attr('disabled','disabled');
			$('#btn-cancel').removeAttr('disabled');
			sendMail();

		});

		function sendMailToAdmin() {
			const currentSlug = $(`#current-slug`).val();
			$.ajax({
					headers: {
					'X-CSRF-TOKEN': '{{csrf_token()}}'
					},
					url: '{{url('email/sendmailadmin')}}',
					type: 'post',
					dataType: "json",
					data: {
						'slug':currentSlug
					},
					success: function(response){
						if(response.status == 500){
							$('#message-error').removeClass('d-none');
							$('#message-error').text(response.message);
						}
					},
					error: function(response){
						$('#message-error').removeClass('d-none');
						$('#message-error').text(response.message);
					}
				});
			}
 </script>
@stop

