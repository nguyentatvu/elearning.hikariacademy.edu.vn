@extends($layout)
@section('content')

<!-- <nav aria-label="breadcrumb">
  <ol class="breadcrumb breadcrumb-custom bg-inverse-info">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item"><a href="/exams/student-exam-series/list">Bộ đề thi</a></li>
    <li class="breadcrumb-item active" aria-current="page"><span><?php change_furigana_text ($title); ?></span></li>
  </ol>
</nav> -->

<div id="page-wrapper">
			
	<div class="row">
		<div class="col-lg-12">
			<ol class="breadcrumb">
				<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
				<li> <a href="{{URL_STUDENT_EXAM_SERIES_LIST}}">Luyện thi </a> </li>
				<li class="active"> <?php change_furigana_text ($title); ?></li>
			</ol>
		</div>
	</div>
	<div class="panel panel-custom">
		<div class="panel-heading">
		<?php 
		$image_path = IMAGE_PATH_UPLOAD_EXAMSERIES_DEFAULT;
		$image_path_thumb = IMAGE_PATH_UPLOAD_EXAMSERIES_DEFAULT;
		if($item->image)
		{
			$image_path = IMAGE_PATH_UPLOAD_SERIES.$item->image;
			$image_path_thumb = IMAGE_PATH_UPLOAD_SERIES_THUMB.$item->image;
		}
		?>
			<h1><img src="{{$image_path_thumb}}" alt="{{$item->title}}" > <?php change_furigana_text ($title); ?></h1>
		</div>
		<div class="panel-body packages">
			<div class="row">
				<div class="col-md-4">
					<img src="{{$image_path}}" alt="{{$item->title}}" class="img-responsive">
				</div>
				<div class="col-md-8">
				<h4>{{getPhrase('overview')}}:</h4>
					{!!$item->short_description!!}
					<br>
					<b>{{getPhrase('type')}} : </b> {!! ($item->is_paid) ? '<span class="label label-primary">'.getPhrase('paid').'</span>' : '<span class="label label-warning">'.getPhrase('free').'</span>' !!}
					<br>
					@if($item->is_paid)
					<b>{{getPhrase('validity')}} :</b> {{$item->validity.' '.getPhrase('days')}} 
					@endif
				</div>
			</div>
		 	{{-- <div class="row">
				{!!$item->description!!}
			</div> --}}
			<hr>
			{{-- <div class="row">
				<div class="col-md-12 text-center">
					<div class="payment-type"> 
					@if($item->is_paid && !isItemPurchased($item->id, 'combo'))
					<a href="{{URL_PAYMENTS_CHECKOUT.'combo/'.$item->slug}}" class="btn-lg btn button btn-paypal"><i class="icon-credit-card"></i> {{getPhrase('buy_now')}}</a> 
					@else
					<a href="#" class="btn-lg btn button btn-card"><i class="fa fa-plane"></i>  Thi ngay </a>  </div>
					@endif
				</div>
			</div>	  --}}
		</div>
	</div>
			
</div>
@stop