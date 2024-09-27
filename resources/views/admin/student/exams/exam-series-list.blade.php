@extends('admin.layouts.student.studentsettinglayout')
@section('header_scripts')
@stop
@section('content')
<?php 
	$thoigianthi = '<div class="no-auth-notifi" style="text-align: center; padding: 20px; width: 100%;">
					<span>
						<p>Thi thử trực tuyến sẽ diễn ra vào lúc 10:00 thứ 7 ngày 27-11-2020 ~ 00:00 Chủ nhật 28-11-2020</p>
                             <p></p> <br> 
                             <p>Mô phỏng gần giống nhất với đề thi JLPT</p>
                    </span>
                </div>';
?>
@if(Auth::user()->is_hocvien == 1)
<div class="card mb-10">
	<div class="card-header">
		<h3 class="card-title">Bộ đề thi chỉ định</h3>
	</div>
	<div class="card-body">
		<div class="manged-ad table-responsive border-top userprof-tab">
			@if(count($series_cd))
			<table class="table table-bordered table-hover mb-0 text-nowrap">
				<thead>
					<tr>
						<th class="text-center align-middle" style="width: 5%;">STT</th>
						<th>Đề thi chỉ định</th>
						<th class="text-center align-middle">Trình độ</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
						@foreach($series_cd as $c)
						<tr>
							<td class="text-center align-middle">
								{{$loop->index+1}}
							</td>
							<td>
								<div class="media mt-0 mb-0">
									<div class="card-aside-img">
										<?php $image = IMAGE_PATH_UPLOAD_SERIES.'n'.$c->category_id.'.png'; ?>
										<a href=""></a><img style="height: auto;" src="{{ $image }}" alt="{{$c->title}}">
									</div>
									<div class="media-body">
										<div class="card-item-desc ml-4 p-0 mt-2">
											<a href="{{URL_STUDENT_EXAM_SERIES_VIEW_ITEM.$c->slug}}" class="text-dark"><h4 class="font-weight-semibold">{{$c->title}}</h4></a>
											<a href="#"><i class="fa fa-clock-o mr-1"></i> {{ $c->total_exams}} bài kiểm tra <i class="fa fa-clock-o mr-1"></i>{{ $c->total_questions}} câu hỏi</a>
										</div>
									</div>
								</div>
							</td>
							<td class="text-center align-middle">N{{$c->category_id}} </td>
							<td class="text-center align-middle" style="width: 10%;">
								<a href="{{URL_STUDENT_EXAM_SERIES_VIEW_ITEM.$c->slug}}" class="btn btn-primary mb-3 mb-xl-0">
									<i class="fa fa-star fa-spin mr-1"></i>Thi ngay</a>
							</td>
						</tr>
						@endforeach
					</tbody>
			</table>
			@else
				<div class="alert alert-primary" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="fa fa-bell-o mr-2" aria-hidden="true"></i> Bạn không có đề thi chỉ định!</div>
			@endif
		</div>
	</div>
</div>
@endif
@for ($i = 1; $i <= 5 ; $i++)
<div class="card mb-10 show_n" style="display: none;" id="show_n{{$i}}">
	<div class="card-header">
		<h3 class="card-title">Bộ đề thi thử N{{$i}}</h3>
	</div>
	<div class="card-body">
		<div class="row">
			<?php $series = 'series_n' . $i; ?>
			@if(!empty($$series) && count($$series) > 0)
				@foreach($$series as $c)
					<?php $image = IMAGE_PATH_UPLOAD_SERIES.'n'.$c->category_id.'.png'; ?>
					<div class="col-lg-6 col-md-12 col-xl-4">
						<div class="card overflow-hidden">
							<div class="item-card9-img">
								<div class="item-card9-imgs">
									<a href="{{URL_STUDENT_EXAM_SERIES_VIEW_ITEM.$c->slug}}"></a>
									<img src="{{$image}}" alt="img" class="cover-image">
								</div>
								<div class="item-overly-trans">
									<a href="{{URL_STUDENT_EXAM_SERIES_VIEW_ITEM.$c->slug}}" class="bg-blue">Trình độ N{{$i}}</a>
								</div>
							</div>
							<div class="card-body">
								<div class="item-card9">
									<a href="{{URL_STUDENT_EXAM_SERIES_VIEW_ITEM.$c->slug}}" class="text-dark mt-2"><h3 class="font-weight-semibold mt-1 mb-3">{{ $c->title }}</h3></a>
									<div class="item-card9-desc mb-2">
										<a href="#" class="mr-4"><span class="text-muted"><i class="fa fa-book text-muted mr-1"></i> Bài thi: {{$c->total_exams}}</span></a>
										<a href="#" class="mr-4"><span class="text-muted"><i class="fa fa-question text-muted mr-1"></i> Câu hỏi: {{$c->total_questions}}</span></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				@endforeach
			@else
				<?php echo $thoigianthi; ?>
			@endif
		</div>
	</div>
</div>
@endfor
<div class="card mb-0">
	<div class="card-header">
		<h3 class="card-title">Bộ đề thi thử</h3>
	</div>
	<div class="card-body">
		<div class="manged-ad table-responsive border-top userprof-tab">
			@if(isset($exam_check) && ($exam_check == 'role_test' || $exam_check == 'exam'  ))
			<table class="table table-bordered table-hover mb-0 text-nowrap">
				<thead>
					<tr>
						<th class="text-center align-middle" style="width: 5%;">STT</th>
						<th>Bộ đề thi</th>
						<th class="text-center align-middle">Trình độ</th>
						<th>Chọn đề thi</th>
					</tr>
				</thead>
				<tbody>
					@for ($i = 1; $i <= 5 ; $i++)
						<?php $image = "/public/uploads/exams/series/n{$i}.png"; ?>
						<tr>
							<td class="text-center align-middle">
								{{$i}}
							</td>
							<td>
								<div class="media mt-0 mb-0">
									<div class="card-aside-img">
										<a href="javascript:void" onclick="show_free({{$i}});"></a><img style="height: auto;" src="{{ $image }}" alt="N">
									</div>
									<div class="media-body">
										<div class="card-item-desc ml-4 p-0 mt-2">
											<a href="javascript:void" class="text-dark" onclick="show_free({{$i}});"><h4 class="font-weight-semibold">Bộ đề thi N{{$i}}</h4></a>
											{{-- <a href="#">123</a> --}}
										</div>
									</div>
								</div>
							</td>
							<td class="text-center align-middle">N{{$i}} </td>
							<td class="text-center align-middle" style="width: 10%;">
								<a href="javascript:void" onclick="show_free({{$i}});" class="btn btn-primary mb-3 mb-xl-0">
									<i class="fe fe-star mr-1"></i>Chọn đề thi</a>
							</td>
						</tr>
					@endfor
				</tbody>
			</table>
			@endif
			@if(($exam_check != "role_test" && $exam_check == null ))
				<div class="alert alert-primary" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> <i class="fa fa-bell-o mr-2" aria-hidden="true"></i> Bạn không có đề thi thử JLPT!</div>
			@endif
		</div>
	</div>
</div>
@stop
@section('footer_scripts')
		<script>
			function show_free(trinhdo) {
				if (trinhdo > 0) {
					$('#show_trinhdo').hide();
					$('.show_n').hide();
					$('#show_n'+trinhdo).show();
					swal({   title: "Bạn đã chọn trình độ N" + trinhdo,   text: "",   timer: 2000,   type: "success", showConfirmButton: false });
				}
			}
		</script>
@stop