<?php

$dr_trinhdo = array(

	1 =>
	'N1',

	2 => 'N2',

	3 => 'N3',

	4 => 'N4',

	5 => 'N5',

	6 => 'Chữ cái',



)

?>
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
						<div class="pull-right messages-buttons">
							<a class="btn btn-primary button" href="{{URL_USERS}}">
								{{ getPhrase('list')}}
							</a>
						</div>
						<h1>{{ $title }} - {{$user->name}}</h1>
					</div>
					<div class="panel-body packages">
						<div> 
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th class="text-center align-middle" style="width: 5%;">
										STT
									</th>
									<th>
										Tên khóa học-khóa luyện thi
									</th>
									<th>
										Ngày bắt đầu
									</th>
									<th>
										Ngày kết thúc
									</th>
									<th>
										Tình trạng
									</th>
									<th style="width: 20%;">
										Hành động
									</th>
								</tr>
							</thead>
							<tbody>

							@if(count($series) > 0)

							@foreach($series as $r)
							<tr>
								<td class="text-center align-middle">
									{{$loop->index+1}}
								</td>
								<td class="title-{{$r->idPayment}}">
									{{$r->title}}
								</td>
								<td>
									{{date_format(date_create($r->created_at),"d-m-Y")}}
								</td>
								<td>
									<?php $dr_time  = array(0 =>
									90, 1 => 180, 2 => 365) ?>

									<?php $dr_day  = array(0 =>
									0, 1 => 30, 3 => 90, 6 => 180, 12 => 365) ?>

									<?php $dayEnd  = date_format(date_add(date_create($r->created_at),date_interval_create_from_date_string($dr_time[$r->time] + $dr_day[$r->month_extend]." days")),"d-m-Y") ?>
									{{$dayEnd}}
								</td>
								<td class="text-center align-middle">
									@if($r->status == 3)
										<p>Vô hiệu</p>
									@elseif(strtotime($dayEnd) < strtotime(date("d-m-Y"))) 
										<p>Hết hạn</p>
									@elseif(strtotime($dayEnd) > strtotime(date("d-m-Y"))) 
										<p>Đang hoạt động</p>
							
									@endif
								</td>
								<td class="text-center align-middle" style="width: 10%;">
									@if($r->status != 3)
									<button class="btn btn-danger btn-disable" data-id="{{$r->idPayment}}">
										<i class="fa fa-lock mr-1"></i>
										Vô hiệu
									</button>
									
									<button class="btn btn-success btn-extend" data-id="{{$r->idPayment}}">
										<i class="fa fa-unlock mr-1">
										</i>
										Gia hạn
									</button>
									@endif
								</td>
							</tr>
							@endforeach

							@else
							<tr>
								<td colspan="6">
									<h5 style="color: #ee2833!important">
										Không có dữ liệu khóa học-khóa luyện thi
									</h5>
								</td>
							</tr>
							@endif

							</tbody>
						</table>
						</div>
						 

					</div>

				</div>
			</div>
		</div>
	</div>
</div>

@endsection
@section('footer_scripts')
<script src="{{admin_asset('js/sweetalert2.js')}}"></script>
<link href="{{admin_asset('css/sweetalert2.css')}}" rel="stylesheet">
<script>
	
	// Handle event extend
	$('.btn-extend').on('click', function(event){
		const currentTarget = event.currentTarget;
		const id = $(currentTarget).data('id');
		const title = $(`.title-${id}`).text();
		const { value: fruit } = Swal.fire({
			title: `Gia hạn ${title}`,
			input: 'select',
			inputOptions: {
				1: '1 tháng',
				3: '3 tháng',
				6: '6 tháng',
				12: '12 tháng'
			},
			inputPlaceholder: 'Vui lòng chọn số tháng',
			showCancelButton: true,
		}).then(function (result) {
			if(result.value) {
				$.ajax({
					headers: {
					'X-CSRF-TOKEN': '{{csrf_token()}}'
					},
					url: '{{url('user/exam-categories/extend')}}',
					type: 'post',
					dataType: "json",
					data: {
						'id': id,
						'month': Number(result.value)
					},
				beforeSend: function() {
					// setting a timeout
					Swal.fire({
						title: 'Đang xử lý vui lòng chờ',
						html: '<img style="position: relative;" src="/public/assets/images/loader.svg" class="loader-img" alt="img">',
						showConfirmButton: false,
						showCancelButton: false,
					});

					},
				success: function(response){
					if(response) {
						const title = response.MessageCode == 500 ? 'error' : 'success'
						Swal.fire({
							title: title,
							icon: title,
							text: response.MessageText,
							showConfirmButton: true,
							showCloseButton: false
						}).then(function (result) {
							window.location.reload();
						})
					} 
				},
				error: function(response){
				}
			});
			}
		})
	});

	// Handle event disable
	$('.btn-disable').on('click', function(event){
		const currentTarget = event.currentTarget;
		const id = $(currentTarget).data('id');
		Swal.fire({

			title: "Bạn có chắc vô hiệu lớp học này",
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: "Đồng ý",
			cancelButtonText: "Hủy bỏ",
			}).then(function (result) {
				if (result.isConfirmed) {
					$.ajax({
						headers: {
						'X-CSRF-TOKEN': '{{csrf_token()}}'
						},
						url: '{{url('user/exam-categories/disable')}}',
						type: 'post',
						dataType: "json",
						data: {
							'id': id
						},
					beforeSend: function() {
						// setting a timeout
						Swal.fire({
							title: 'Đang xử lý vui lòng chờ',
							html: '<img style="position: relative;" src="/public/assets/images/loader.svg" class="loader-img" alt="img">',
							type: '',
							showConfirmButton: false,
							showCancelButton: false,
						});

						},
					success: function(response){
						if(response) {
							const title = response.MessageCode == 500 ? 'error' : 'success'
							Swal.fire({
								title: title,
								icon: title,
								text: response.MessageText,
								confirmButtonText: 'ok',
								showConfirmButton: true,
							}).then(function (result) {
							window.location.reload();
							}) 
						}
					},
					error: function(response){
					}
				})

				} else {
					Swal.close()
				}
			})
	})
</script>
@endsection