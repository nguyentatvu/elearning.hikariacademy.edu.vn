@extends($layout)
@section('content')

<nav aria-label="breadcrumb">
	<ol class="breadcrumb breadcrumb-custom bg-inverse-info">
		<li class="breadcrumb-item"><a href="/home"><i class="mdi mdi-home menu-icon"></i></a></li>
		<li class="breadcrumb-item active" aria-current="page"><span>Khóa học</span></li>
	</ol>
</nav>

<div class="row">
	<div class="col-md-12">
		<div class="card" >
			<div class="card-body">
				<div id="page-wrapper">
					@if(count($series) > 0)
					<h3 style="color: #ee2833!important">Khóa học</h3>
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
													<a href="{{PREFIX.'learning-management/lesson/show/'.$r->slug}}" class="btn btn-primary">Xem chi tiết</a>
												</div>
											</div>
										</div>
										<div class="item-details">
											<h3>{{ $r->title }}</h3>
										</div>
									</div>
								</div>
							</div>
							<?php
						}
						?>
					</div>
					@else
						<h3 style="color: #ee2833!important">Khóa học</h3>
						<p>Bạn chưa có khóa học</p>
					@endif

					@if(count($series_selected) > 0)
						<h3 style="color: #ee2833!important">Khóa luyện thi</h3>
						<div class="row library-items">
							<?php
							foreach($series_selected as $r){
							?>
							<div class="col-md-3">
								<div class="library-item mouseover-box-shadow">
									<div class="">
										<div class="item-image">
											<img src="{{ IMAGE_PATH_UPLOAD_LMS_SERIES.$r->image}}" alt="{{$r->title}}">
											<div class="hover-content">
												<div class="buttons">
													<a href="{{PREFIX.'learning-management/lesson/show/'.$r->slug}}" class="btn btn-primary">Xem chi tiết</a>
												</div>
											</div>
										</div>
										<div class="item-details">
											<h3>{{ $r->title }}</h3>
										</div>
									</div>
								</div>
							</div>
							<?php
							}
							?>
						</div>
					@else
							<h3 style="color: #ee2833!important">Khóa luyện thi</h3>
							<p>Bạn chưa có khóa luyện thi</p>
					@endif
				</div>
			</div>
		</div>


	</div>
</div>
<!-- /#page-wrapper -->
@stop
