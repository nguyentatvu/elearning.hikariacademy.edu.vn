@extends($layout)

@section('header_scripts')
    <style>

    .table-fixed-header {
      position: relative;
    }

    .table-fixed-header thead {
      position: sticky;
      top: 0;
      background-color: #fff;
      z-index: 1;
    }

    .table-scroll-body {
      max-height: 382px;
      overflow-y: auto;
      border: 1px solid #ddd;
    }

    .table-fixed-header th:nth-child(1),
    .table-fixed-header td:nth-child(1) {
      width: 20%;
    }

    .table-fixed-header th:nth-child(2),
    .table-fixed-header td:nth-child(2) {
      width: 60%;
    }

    .table-fixed-header th:nth-child(3),
    .table-fixed-header td:nth-child(3) {
      width: 20%;
    }

    .table-fixed-header th,
    .table-fixed-header td {
      border: 1px solid #ddd !important;
      padding: 12px !important;
    }

    .table-fixed-header tbody tr:hover {
      background-color: #f5f5f5;
    }
    </style>
@stop

@section('content')
	<div id="page-wrapper" class="details-learning-content">
		<div class="container-fluid">
			<!-- Page Heading -->
			<div class="row">
				<div class="col-lg-12">
					<ol class="breadcrumb">
						<li><a href="{{URL_ADMIN_DASHBOARD}}"><i class="mdi mdi-home"></i> </a> </li>
						@if(checkRole(getUserGrade(2)))
						<li><a href="{{URL_USERS}}">{{ getPhrase('users') }}</a> </li>
						@endif

						@if(checkRole(getUserGrade(7)))
						<li><a href="{{URL_PARENT_CHILDREN}}">{{ getPhrase('users') }}</a> </li>
						@endif

						<li><a href="javascript:void(0);">{{ $title }}</a> </li>
					</ol>
				</div>
			</div>

			<div class="panel panel-custom">
				<div class="panel-heading">
					<h1>CHI TIẾT TIẾN TRÌNH HỌC CỦA {{ $record->name }}</h1>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="row">
								<div class="col-sm-12">
									<div class="profile-details text-center">
										<div class="profile-img"><img src="{{ getProfilePath($record->image,'profile')}}"
												alt=""></div>
										<div class="aouther-school">
											<h2>{{ $record->name}}</h2>
											<p><span>{{$record->email}}</span></p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 col-sm-6">
								<div class="media state-media box-ws">
									<div class="media-body">
										<p>Khoá học học viên đang học: <span class="text-primary">{{ $learning_path
												}}</span></p>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="media state-media box-ws">
									<div class="media-body">
										<!-- When user login update remember token -> get first time user login -->
										<p>Thời điểm login gần nhất của học viên: <span class="text-primary">{{
												$record->updated_at }}</span></p>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 col-sm-6">
								<div class="media state-media box-ws h-100">
									<div class="media-body">
										<p>Bài học sau cùng học viên đã học: <span class="text-primary">{{ $last_lesson
												}}</span></p>
									</div>
								</div>
                                <div class="media state-media box-ws">
									<div class="media-body">
										<p>Điểm trung bình các bài test</p>
										<p>Trong 1 tháng: <span class="text-primary">{{ $total_score_of_month }}</span></p>
										<p>Trong toàn khóa học: <span class="text-primary">{{ $total_score }}</span></p>
									</div>
								</div>
                                <div class="media state-media box-ws">
									<div class="media-body">
										<p>Thời điểm học viên tiến hành học: <span class="text-primary">{{ $study }}</span>
										</p>
										<p>Thời điểm học viên tiến hành làm bài tập: <span class="text-primary">{{
												$exercises }}</span></p>
										<p>Thời điểm học viên tiến hành thi: <span class="text-primary">{{ $exams }}</span>
										</p>
									</div>
								</div>
                                <div class="media state-media box-ws">
									<div class="media-body">
										<p>Clip trung bình học viên đã xem/tháng: <span class="text-primary">{{
												$average_videos_per_month }}</span></p>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="media state-media box-ws">
									<div class="media-body">
										<p>Danh sách điểm các bài thi thử: </p>
                                        <div class="panel-body" style="padding-top: 2px;">
                                            <div class="table-scroll-body">
                                                @if ($mock_exam_results->count() > 0)
                                                    <table class="table table-hover table-fixed-header" style="margin-bottom: 0px;">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 25%;">Thời gian</th>
                                                                <th>Tên bộ đề thi</th>
                                                                <th style="width: 20%;">Tổng điểm</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($mock_exam_results as $result)
                                                                <tr>
                                                                    <td>{{ $result->created_at }}</td>
                                                                    <td>{{ $result->title }}</td>
                                                                    <td>{!! $result->totalMark !!}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @else
                                                    <span>Học viên chưa làm bài thi thử nào!</span>
                                                @endif
                                            </div>
                                        </div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="media state-media box-ws">
									<div class="media-body">
										<p>Số clip đã xem: <span class="text-primary">{{ $video_watched }}</span></p>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="media state-media box-ws">
									<div class="media-body">
										<p>Lộ trình học học viên đang chọn</p>
										<div>
											@if (isset($data_roadmap_user) && !empty($data_roadmap_user))
												@foreach ($data_roadmap_user as $roadmap)
													<div class="text-primary">{{ $roadmap->lmsseries->title }} - {{ $roadmap->duration_months != 0 ? 'Lộ trình '.$roadmap->duration_months.' tháng' : 'Lộ trình tự do' }}</div>
												@endforeach
											@else
												<div class="text-primary">
													<div>Học viên chưa chọn lộ trình học cho các khóa học.</div>
													<div>Vui lòng lựa chọn lộ trình phù hợp để bắt đầu hành trình học tập hiệu quả.</div>
												</div>
											@endif
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- <div class="row">
							<div class="col-md-6">
								<div class="panel panel-primary dsPanel">
									<div class="panel-heading">
										<i class="fa fa-bar-chart-o"></i>
										Tổng số khóa học học viên đang tham gia
									</div>
									<div class="panel-body">
										<?php $ids = [];?>
										@for($i = 0; $i < count($number_of_courses); $i++)
											<div class="panel-body">
												<div class="row">
													<div class="col-md-12">
														<canvas id="number_of_courses" width="100" height="110"></canvas>
													</div>
												</div>
											</div>
										@endfor
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="panel panel-primary dsPanel">
									<div class="panel-heading">
										<i class="fa fa-bar-chart-o"></i>
										Tỉ lệ % hoàn thành khóa học
									</div>
									<div class="panel-body">
										<?php $ids = [];?>
										@for($i = 0; $i < count($percentage_of_course); $i++)
											<div class="panel-body">
												<div class="row">
													<div class="col-md-12">
														<canvas id="percentage_of_course" width="100" height="110"></canvas>
													</div>
												</div>
											</div>
										@endfor
									</div>
								</div>
							</div>
						</div> -->
					</div>
				</div>

				<!-- Main Modal -->
				<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
					aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLongTitle">Xuất đồng loạt ra PDF/Excel phục vụ báo
									cáo
								</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								Bạn muốn PDF/Excel cho {{ $record->name }} hay xuất toàn bộ?
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-primary" id="exportSingle" data-type="single">{{
									$record->name }}</button>
								<button type="button" class="btn btn-primary" id="exportAll" data-type="all">Toàn
									bộ</button>
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>

				<!-- Format Selection Modal -->
				<div class="modal fade" id="formatModal" tabindex="-1" role="dialog" aria-labelledby="formatModalTitle"
					aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content format-selection">
							<div class="modal-header">
								<h5 class="modal-title" id="formatModalTitle">Chọn định dạng xuất</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-primary" id="exportPDF">PDF</button>
								<button type="button" class="btn btn-primary" id="exportExcel">Excel</button>
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('footer_scripts')

@include('admin.common.charts', array('chart_data' => $number_of_courses, 'ids' => array('number_of_courses'), 'scale' => TRUE))
@include('admin.common.charts', array('chart_data' => $percentage_of_course, 'ids' => array('percentage_of_course'), 'scale' => TRUE))

@stop