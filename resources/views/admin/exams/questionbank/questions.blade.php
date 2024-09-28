@extends('admin.layouts.'.getRole().'.'.getRole().'layout')

@section('header_scripts')

<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">

@stop

@section('content')

<?php $image_path = PREFIX.(new App\ImageSettings())->getExamImagePath(); ?>

<div id="page-wrapper">

	<div class="container-fluid">

		<!-- Page Heading -->

		<div class="row">

			<div class="col-lg-12">

				<ol class="breadcrumb">

					<li><a href="{{url('/')}}"><i class="mdi mdi-home"></i></a> </li>

					<li><a href="{{URL_QUIZ_QUESTIONBANK}}">Ngân hàng câu hỏi</a></li>

					<!-- <li><a href="{{URL_QUESTIONBAMK_IMPORT}}">{{ getPhrase('import_questions') }}</a></li> -->

					<li>{{ $title }}</li>

				</ol>

			</div>

		</div>

		<!-- /.row -->

		<div class="panel panel-custom">

			<div class="panel-heading">

				<div class="pull-right messages-buttons">

					<a href="{{URL_QUESTIONBANK_ADD_QUESTION.$subject->slug}}" class="btn  btn-primary button" >{{ getPhrase('create')}}</a>

				</div>

				<h1>{{ $title }}</h1>

			</div>

			<div class="panel-body packages">

				<div class="table-responsive"> 

					<table class="table table-striped table-bordered datatable" id="hikari-table-view" cellspacing="0" width="100%">

						<thead>

							<tr>

								<!-- <th width="20%">Mondai</th> -->

								<th width="5%">Chủ đề</th>
								<th width="20%">Câu hỏi</th>
								<th width="20%">Mô tả</th>
								<th width="10%">Trả lời 1</th>
								<th width="10%">Trả lời 2</th>
								<th width="10%">Trả lời 3</th>
								<th width="10%">Trả lời 4</th>
								<th width="3%">Câu đúng</th>
								<th width="3%">{{ getPhrase('action')}}</th>

							</tr>

						</thead>

					</table>

				</div>

			</div>

		</div>

	</div>

	<!-- /.container-fluid -->

</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

	<div class="modal-dialog" role="document">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

				<h4 class="modal-title" id="myModalLabel">Xem câu hỏi</h4>

			</div>

			<div class="modal-body">

				<div class="row">

					<div class=" col-sm-12">

						<span class="hikari-title">Câu hỏi:</span> <span class="model-question"></span>

					</div>

				</div>

				<div class="row" style="padding-top: 5px; ">

					<div class="col-sm-6 ">

						<span class="hikari-title">1:</span> <span class="answers1 answers"></span>

					</div>

					<div class="col-sm-6">

						<span class="hikari-title">2:</span> <span class="answers2 answers"></span>

					</div>

				</div>

				<div class="row">

					<div class="col-sm-6">

						<span class="hikari-title">3:</span> <span class="answers3 answers"></span>

					</div>

					<div class="col-sm-6">

						<span class="hikari-title">4:</span> <span class="answers4 answers"></span>

					</div>

				</div>

				<div class="row" style="padding-top: 20px;">

					<div class=" col-sm-6">

						<span class="hikari-title">Correct:</span> <span class="correct_answers"></span>

					</div>

					<div class=" col-sm-6">

						<span class="hikari-title">Mark(s):</span> <span class="model-marks"></span>

					</div>

					<div class=" col-sm-6">

						<span class="hikari-title">Book:</span> <span class="book"></span>

					</div>

					<div class=" col-sm-6">

						<span class="hikari-title">Page:</span> <span class="page"></span>

					</div>

					<div class=" col-sm-12">

						<span class="hikari-title">Topic:</span> <span class="model-topic"></span>

					</div>

				</div>

			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>

			</div>

		</div>

	</div>

</div>

<style type="text/css">

.hikari-title {

	font-size: 16px;

	font-weight: 700;

}

.answers, .model-question {

	font-size: 18px;

}

</style>

<script src="{{admin_asset('js/jquery-1.12.1.min.js')}}"></script>

<script type="text/javascript">

	$( document ).ready(function() {

		jQuery('#hikari-table-view').on("click", ".hikari-view-question",function(){

			var question = $(this).data("question");

			var topic_name = $(this).data("topic_name");

			var marks = $(this).data("marks");

			var answers1 = $(this).data("answer1");

			var answerfile1 = $(this).data("answerfile1");

			var answers2 = $(this).data("answer2");

			var answerfile2 = $(this).data("answerfile2");

			var answers3 = $(this).data("answer3");

			var answerfile3 = $(this).data("answerfile3");

			var answers4 = $(this).data("answer4");

			var answerfile4 = $(this).data("answerfile4");

			var book = $(this).data("book");

			var page = $(this).data("page");

			var correct_answers = $(this).data("correct_answers");

			if (answerfile1 != "") {

				answers1 = answers1 + '<img src="<?php echo $image_path; ?>'+answerfile1+'" width="150px">';

			}

			if (answerfile2 != "") {

				answers2 = answers2 + '<img src="<?php echo $image_path; ?>'+answerfile2+'" width="150px">';

			}

			if (answerfile3 != "") {

				answers3 = answers3 + '<img src="<?php echo $image_path; ?>'+answerfile3+'" width="150px">';

			}

			if (answerfile4 != "") {

				answers4 = answers4 + '<img src="<?php echo $image_path; ?>'+answerfile4+'" width="150px">';

			}

			$('.model-question').html(question);

			$('.answers1').html(answers1);

			$('.answers2').html(answers2);

			$('.answers3').html(answers3);

			$('.answers4').html(answers4);

			$('.correct_answers').html(correct_answers);

			$('.book').html(book);

			$('.page').html(page);

			$('.model-marks').html(marks);

			$('.model-topic').html(topic_name);

			$('#myModal').modal('show');

		});

	});

</script>

@endsection

@section('footer_scripts')

{{-- <script src="{{JS}}bootstrap-toggle.min.js"></script>

<script src="{{JS}}jquery.dataTables.min.js"></script>

<script src="{{JS}}dataTables.bootstrap.min.js"></script> --}}

@php
// MAKE DEFAULT VALUE COLUMN
$defaultColumns = [
	'topic_name', 'question','explanation','answers_1','answers_2','answers_3','answers_4', 'correct_answers', 'action'
];
@endphp

@include('admin.common.datatables', array('route'=>URL_QUESTIONBANK_GETQUESTION_LIST.$subject->slug, 'route_as_url' => 'TRUE', 'excel'=>'0,1,2,3,4,5,6', 'table_columns' => $defaultColumns))

@include('admin.common.deletescript', array('route'=>URL_QUESTIONBANK_DELETE))

@stop