@extends($layout)
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')
<div id="page-wrapper">
		<div class="container-fluid rac">
			<!-- Page Heading -->
			<div class="row">
				<div class="col-lg-12">
					<ol class="breadcrumb">
						<li><a href="{{URL_ADMIN_DASHBOARD}}"><i class="mdi mdi-home"></i></a> </li>
						<li><!-- {{ $title}} --> Lịch sử bài thi</li>
					</ol>
				</div>
			</div>
			<!-- /.row -->
			<div class="panel panel-custom">
				<div class="panel-heading">
					<h1> Lịch sử bài thi của {{$user->name }}</h1><!-- $title.' '.getPhrase('of').' '. -->
				</div>
				<div class="panel-body packages">
					<div class="table-responsive"> 
					<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Bài thi </th>
								<th>Điểm </th>
								<th>Chi tiết</th>
							</tr>
						</thead>
					</table>
					</div>
					<!-- <div class="row">
						<div class="col-md-6 col-md-offset-3">
							<canvas id="myChart1" width="100" height="110"></canvas>
						</div>
					</div> -->
				</div>
			</div>
			<div class="panel panel-custom">
				<div class="panel-heading">
					<h2> Thông tin chi tiết</h2>
				</div>
				<div class="entry-content">
					<div class="row">
						<div class="col-md-3">
							<h4><span style="color: #ff9900;">1 / Điểm cho GOI -N3</span></h4>
							<p>Mondai 1: 8 câu * 1 điểm = 8 điểm</p>
							<p>Mondai 2: 6 câu * 1 điểm = 6 điểm</p>
							<p>Mondai 3:11 câu * 1 điểm = 11 điểm</p>
							<p>Mondai 4: 5 câu * 1 điểm = 5 điểm</p>
							<p>Mondai 5: 5 câu * 1 điểm = 5 điểm</p>
						</div>
						<div class="col-md-3">
							<h4><span style="color: #ff9900;">2 / Quy mô cho BUNPO-N3</span></h4>
							<p>Mondai 1: 13 điểm * 1 điểm = 13 điểm</p>
							<p>Mondai 2: 5 câu * 1 điểm = 5 điểm</p>
							<p>Mondai 3: 5 câu * 1 điểm = 5 điểm</p>
							<p>Điểm của bạn đạt được = (GOI + BUNPO) : 58 x 60 </p>
							<p>Tổng số điểm GOI-BUNPO: 60 điểm</p>
						</div>
						<div class="col-md-3">
							<h4><span style="color: #ff9900;">3 / Điểm cho phần DOKKAI-N3</span></h4>
							<p>Mondai 4: 4 câu * 3 điểm = 12 điểm</p>
							<p>Mondai 5: 6 điểm * 4 điểm = 24 điểm</p>
							<p>Mondai 6: 4 câu * 4 điểm = 16 điểm</p>
							<p>Mondai 7: 2 câu * 4 điểm = 8 điểm</p>
							<p>Tổng số điểm DOKKAI: 60 điểm</p>
						</div>
						<div class="col-md-3">
							<h4><span style="color: #ff9900;">4 / Quy mô cho CHOUKAI-N3</span></h4>
							<p>Mondai 1: 6 câu * 3 điểm = 18 điểm</p>
							<p>Mondai 2: 6 câu * 2 điểm = 12 điểm</p>
							<p>Mondai 3: 3 câu * 3 điểm = 9 điểm</p>
							<p>Mondai 4: 4 điểm * 3 điểm = 12 điểm</p>
							<p>Mondai 5: 9 câu * 1 điểm = 9 điểm</p>
							<p>Tổng số điểm CHOUKAI: 60 điểm</p>
						</div>
					</div>
					
				
						<p>Tổng điểm của JLPT N3 là 180 điểm. Điểm đỗ là 95 điểm, điểm thành phần tối thiểu như sau:</p>
						<ul>
							<li>Từ vựng, Kanji, Ngữ pháp: 19 điểm</li>
							<li>Đọc hiểu: 19 điểm</li>
							<li>Nghe hiểu: 19 điểm</li>
						</ul>
						<p>Với cách tính điểm như trên chắc các bạn đã có thể tự tính ra điểm số JLPT vừa rồi của mình rồi đúng không nào.</p>
				</div>

			</div>
	</div>
	<!-- /.container-fluid -->
</div>
@endsection
@section('footer_scripts')
 @if(!$exam_record)
 @include('admin.common.datatables', array('route'=>URL_STUDENT_EXAM_GETATTEMPTS.$user->slug.'/'.$quizresultfinish_id, 'route_as_url' => 'TRUE'))
 @else
 @include('admin.common.datatables', array('route'=>URL_STUDENT_EXAM_GETATTEMPTS.$user->slug.'/'.$exam_record->slug, 'route_as_url' => 'TRUE'))
 @endif
 @include('admin.common.chart', array($chart_data,'ids' => array('myChart1')));
@stop
