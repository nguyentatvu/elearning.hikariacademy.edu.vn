@extends($layout)
@section('content')

<nav aria-label="breadcrumb">
  <ol class="breadcrumb breadcrumb-custom bg-inverse-info">
    <li class="breadcrumb-item"><a href="/home"><i class="mdi mdi-home menu-icon"></i></a></li>
    <li class="breadcrumb-item"><a href="{{$url_categories}}"><span>{{$categories->category}}</span></a></li>
    <li class="breadcrumb-item active" aria-current="page"><span>Series</span></li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
			<div id="page-wrapper">
				<h2>Series Khóa học của tôi</h2>
				<div class="row library-items">
					<?php
						foreach($series as $r){
					?>
						<div class="col-md-3">
							<div class="library-item mouseover-box-shadow">
							<div class="">
								<div class="item-image">
									<img src="{{ IMAGE_PATH_UPLOAD_LMS_SERIES.$r->image}}" alt="{{$r->title}}">
									<div class="hover-content">
									<div class="buttons">
										<a href="{{url('learning-management/lesson/show/'.$r->slug)}}" class="btn btn-primary">Xem chi tiết</a>
									</div>
									</div>
								</div>
								<div class="item-details">
									<h3>{{ $r->title }}</h3>
									<div class="quiz-short-discription">

									{!!$r->short_description!!}
									</div>
									<!-- <ul>
										<li><i class="icon-bookmark"></i> {{ $r->total_items }} bài học</li>
										<li>450 phút</li>

									</ul> -->
								</div>
							</div>
							</div>
						</div>
					<?php
						}
					?>
					</div>
				</div>

			</div>
  		</div>
	</div>
</div>
		<!-- /#page-wrapper -->
@stop
