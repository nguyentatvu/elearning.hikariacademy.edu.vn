<?php
		//dump($result_record);
$questions 	= $data['questions'];
$quiz 		= $data['quiz'];
if(isset($data['current_state']))
	$cState     = $data['current_state'];

$answers = (array)json_decode($result_record->answers);
$submitted_answers = array();
foreach ($answers as $key => $value) {
    $submitted_answers[$key] = $value;
}
$correct_answer_questions = [];
$correct_answer_questions = (array) 
                            json_decode($result_record->correct_answer_questions);

?>

<style>

	input[type="checkbox"]:checked + label .active{

		border-radius: 50%;
	}
	input[type="checkbox"]:checked + label.check_correct_answers .active{
		background-color: #28a745;
	}

	input[type="checkbox"]:checked + label.check_incorrect_answers .active{
		background-color: #dc3545;

	}

	span.answers-number{
		top: -2px;
		left: 8px;
	}
</style>
<div class="container-fluid answers-header">
	<div class="row">
		<div class="panel panel-custom" style="position: relative;">
			<a onclick="toggleFullScreen(document.body)" style="color: #fff"><span class="btn-fullscreen"><i class="fa fa-arrows-alt"></i></span></a>
			<!-- <div class="panel-heading">Panel heading without title</div> -->
			<div class="panel-body" >
				<div class="col-xs-4 cl1" id="question-palette1">
					<h3 class="text-primary">
						<?php  
						switch ($quiz->category_id) {
		                    case '1':
		                        switch ($quiz->type) {
		                            case '2':
		                                $title = 'TỪ VỰNG - NGỮ PHÁP - ĐỌC HIỂU';
		                                break;
		                            case '1':
		                                $title = 'NGHE HIỂU';
		                                break;
		                        }
		                        break;
		                    case '2':
		                        switch ($quiz->type) {
		                            case '2':
		                                $title = 'TỪ VỰNG - NGỮ PHÁP - ĐỌC HIỂU';
		                                break;
		                            case '1':
		                                $title = 'NGHE HIỂU';
		                                break;
		                        }
		                        break;
		                    case '3':
		                       switch ($quiz->type) {
		                           case '2':
		                               $title = 'TỪ VỰNG';
		                               break;
		                           case '3':
		                               $title = 'NGỮ PHÁP - ĐỌC HIỂU' ;
		                               break;
		                           case '1':
		                               $title = 'NGHE HIỂU';
		                               break;
		                        }
		                        break;
		                    case '4':
		                       switch ($quiz->type) {
		                           case '2':
		                               $title = 'TỪ VỰNG';
		                               break;
		                           case '3':
		                               $title = 'NGỮ PHÁP - ĐỌC HIỂU' ;
		                               break;
		                           case '1':
		                               $title = 'NGHE HIỂU';
		                               break;
		                        }
		                        break;
		                    case '5':
		                       switch ($quiz->type) {
		                           case '2':
		                               $title = 'TỪ VỰNG';
		                               break;
		                           case '3':
		                               $title = 'NGỮ PHÁP - ĐỌC HIỂU';
		                               break;
		                           case '1':
		                               $title = 'NGHE HIỂU';
		                               break;
		                        }
		                        break;
		                	}
						echo $title;


						if ($quiz->type == 1) { ?>
							<span id="loa-icon" style="display:inline-block"><img src="/public/uploads/exams/images/common/loa-icon.gif" style="width: 18px; float: right;"></span>
							@include('admin.student.exams.exam-form-mp3', array($questions))
						<?php }	?> 	

					</h3>

					<div class="answers-page-pagination question-palette">
						@include('admin.student.exams.exam-leftbar-subjects', array('subjects' => $subjects))
					</div>
				</div>
				<?php 
				$finish = '';  
				if(checkRole(getUserGrade(5)) || checkRole(getUserGrade(6)))
				      {
				            $finish = 'finish finish_exam';      
				      } 
				      
				?>
				<div class="col-xs-4 cl2" id="question-palette2">
				{{--	<h3 class="text-primary">
						THỜI GIAN THI
					</h3>
					
					<div class="countdown-styled" style="color: #337ab7;">
						<span>{{ $data['time_minutes']}}</span>:<span>00</span>
					</div>--}}
					<div class="text-center">
						<h3 class="text-primary">
							THỜI GIAN THI
						</h3>
						<span>{!! $result_record->time_total_answers !!}</span> /  <span>{{ $data['time_minutes']}}</span>:<span>00</span>
					</div>

					<div class="text-left">

						<div class="select-answer">
							<div class="inline-block">

								<input disabled="" id="detail_correct"  type="checkbox" checked="checked">
								<label for="detail_correct" class="check_correct_answers">
										<span class="fa-stack radio-button answer-relative">
											<i class="mdi mdi-check active"></i>
										</span>
									Số câu chọn đúng: <span class="text-success"> {!! count(json_decode($result_record->correct_answer_questions)) !!}</span>
								</label>
							</div>
						</div>

						<div class="select-answer mt-3">
							<div class="inline-block">
								<input disabled="" id="detail_incorrect" type="checkbox" checked="checked">
								<label for="detail_incorrect" class="check_incorrect_answers">
										<span class="fa-stack radio-button answer-relative">
											<i class="mdi mdi-check active"></i>
										</span>
									Số câu chọn sai: <span class="text-danger"> {!! count(json_decode($result_record->wrong_answer_questions)) !!}</span>
								</label>
							</div>
						</div>



						<div class="select-answer mt-3">
							<div class="inline-block">
								<input disabled="" id="detail_incorrect" type="checkbox" checked="checked">
								<label for="detail_incorrect">
										<span class="fa-stack radio-button answer-relative">
											<i class="mdi mdi-check active" ></i>
										</span>
									Đáp án đúng
								</label>
							</div>
						</div>

						<div class="select-answer mt-3">
							<div class="inline-block">
								<input disabled="" id="detail_incorrect" type="checkbox" checked="checked">
								<label for="detail_incorrect">
										<span class="fa-stack radio-button answer-relative">
											<i class="mdi mdi-check active" style="background-color: transparent;"></i>
										</span>
									Số câu chưa trả lời: <span class="text-secondary"> {!! count(json_decode($result_record->not_answered_questions)) !!}</span>
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-4 cl3">
					<div class="question-palette hikari-h130" id="question-palette">
						@switch($quiz->category_id)
						    @case(1)
						        @include('admin.student.exams.answers.answers_n1')
						        @break
						    @case(2)
						        @include('admin.student.exams.answers.answers_n2')
						        @break
						    @case(3)
						        @include('admin.student.exams.answers.answers_n3')
						        @break
						    @case(4)
						        @include('admin.student.exams.answers.answers_n4')
						        @break
						    @case(5)
						        @include('admin.student.exams.answers.answers_n5')
						        @break
						@endswitch
					</div>
				</div>
			</div>
		<div class="hikari-morong"> 
			<span id="hikari-morong-button" style="padding: 0px 4px;" data-display="1"><i class="fa fa-arrow-down"></i></span> 
		</div>
	</div>
</div> <!-- //============================END ROW HEADER -->
</div><!-- //============================END ANSWER HEADER -->
<script>

	$( document ).ready(function() {
	    var iOS = !!navigator.platform && /iPad|iPhone|iPod/.test(navigator.platform);
	    if(iOS == true) {
	    	$('.submit-question-nghe').hide();
	    	$('.submit-audio-star').show();
	    	$('#loa-icon').hide();
	    	<?php if ($quiz->type == 1) { ?>
	    	$('#text-nop-bai').text('BẮT ĐẦU NGHE');
	    	<?php } ?>
	    }
	    $('body').on('click', '.start_audio', function(){
	      $('.submit-question-nghe').show();
	      $('.submit-audio-star').hide();
	      $('#loa-icon').show();
	      $('#text-nop-bai').show();
	      $('#text-nop-bai').text('NỘP BÀI');
	      alertify.success('Bắt đầu nghe');	
	      audioPlayer();
	    });
	});


	function toggleFullScreen(elem) {
	    	if ((document.fullScreenElement !== undefined && document.fullScreenElement === null) || (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) || (document.mozFullScreen !== undefined && !document.mozFullScreen) || (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)) {
	    		if (elem.requestFullScreen) {
	    			elem.requestFullScreen();
	    			compressarrowsalt();
	    		} else if (elem.mozRequestFullScreen) {
	    			elem.mozRequestFullScreen();
	    			compressarrowsalt();
	    		} else if (elem.webkitRequestFullScreen) {
	    			elem.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
	    			compressarrowsalt();
	    		} else if (elem.msRequestFullscreen) {
	    			elem.msRequestFullscreen();
	    			compressarrowsalt();
	    		}
	    	} else {
	    		if (document.cancelFullScreen) {
	    			document.cancelFullScreen();
	    			arrowsalt();
	    		} else if (document.mozCancelFullScreen) {
	    			document.mozCancelFullScreen();
	    			arrowsalt();
	    		} else if (document.webkitCancelFullScreen) {
	    			document.webkitCancelFullScreen();
	    			arrowsalt();
	    		} else if (document.msExitFullscreen) {
	    			document.msExitFullscreen();
	    			arrowsalt();
	    		}
	    	}
	    }
	    function arrowsalt() {
	    	$('.btn-fullscreen').html('<i class="fa fa-arrows-alt"></i>');
	    }
	    function compressarrowsalt(){
	    	$('.btn-fullscreen').html('<i class="fa fa-compress" aria-hidden="true"></i>');
	    }
	</script>
		<style type="text/css">
		.btn-fullscreen {
			padding: 8px 12px;
			background: #44a1ef;
			position: absolute;
			border-radius: 50%;
			font-size: 16px;
			z-index: 99999;
			cursor: pointer;
		}
		.answers-header h3 {
			text-align: center;
			margin: 10px 0px;
		}
		.answers-page-pagination {
			text-align: center;
			padding-top: 5px;
		}
		.question-palette {
			margin: 0;
			padding: 0;
			list-style: none;
			margin: 0 0px;
			overflow: auto;
			text-align: center;
		}
		.inline-block {
			display: inline-block;
			position: relative;
		}
		.inline-block label{
			margin: 0px !important;
		}
		input[type="checkbox"] + label span.fa-stack, input[type="checkbox"] + label span.fa-stack {
			float: none;
			margin: 0 0 0 0;
		}
		.table.hikari-table-answer>tbody>tr>td, .table.hikari-table-answer>tbody>tr>th, .table.hikari-table-answer>tfoot>tr>td, .table.hikari-table-answer>tfoot>tr>th, .table.hikari-table-answer>thead>tr>td, .table.hikari-table-answer>thead>tr>th {
			padding: 1px 1px;
		}
		.table.hikari-table-answer {
			margin: auto;
		}
		.table.hikari-table-answer>tbody>tr>td {
			width: 20px;
		}
		.table-bordered>tbody>tr>td {
			/*border: none;*/
		}
		.table.hikari-table-answer.table-bordered>tbody>tr>td {
			/*border: none;*/
		}
		.table..hikari-table-answer.table-bordered>tbody>tr>td:first-child {
			border: 1px solid;
		}
		a:hover {
			text-decoration: none;
		}
		td.table-middle {
			vertical-align: middle !important;
		}
	</style>
				