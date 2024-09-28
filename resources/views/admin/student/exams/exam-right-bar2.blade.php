<?php 
$questions 	= $data['questions'];
$quiz 		= $data['quiz'];
if(isset($data['current_state']))
	$cState     = $data['current_state'];
?>
<div class="container-fluid answers-header">
	<div class="row">
		<div class="panel panel-custom" style="position: relative;">
			<a onclick="toggleFullScreen(document.body)" style="color: #fff"><span class="btn-fullscreen"><i class="fa fa-arrows-alt"></i></span></a>
			<!-- <div class="panel-heading">Panel heading without title</div> -->
			<div class="panel-body" >
				<div class="col-xs-4 cl1" id="question-palette1">
					<h3 class="text-primary"><!-- ページ --><?php change_furigana('[furi k=#問題# f=#もんだい#]'); ?></h3>
					<div class="answers-page-pagination question-palette">
						@include('admin.student.exams.exam-leftbar-subjects', array('subjects' => $subjects))
					</div>
				</div>
				<div class="col-xs-4 cl2" id="question-palette2">
					<h3 class="text-primary"><?php change_furigana_text ('[furi k=#残# f=#のこ#]り[furi k=#時間# f=#じかん#]'); ?> </h3>
					<?php if ($quiz->type != 1) { ?>
					<div id="timerdiv" class="countdown-styled" style="color: #337ab7;">
						<!-- <span id="hours">{{$data['time_hours']}}</span> : --> 
						<span id="mins">{{ $data['time_minutes']}}</span> : 
						<span id="seconds">00</span>
						<span class="submit-question" style="text-align: center; padding-top: 0px;">
							<a href="javascript:void(0);" class="btn btn-lg btn-danger button finish finish_exam" type="button" style="padding: 15px 4px 2px 10px;"><?php change_furigana('[furi k=#試験完了# f=#しけんかんりょう#]'); ?></a>
						</span>
					</div>
					<?php } else {?>
					<div style="text-align: center;">
						<span class="submit-question" style="text-align: center; padding-top: 0px;">
							<a href="javascript:void(0);" class="btn btn-lg btn-danger button finish finish_exam" type="button" style="padding: 15px 4px 2px 10px; margin-top: 8px;"><?php change_furigana('[furi k=#試験完了# f=#しけんかんりょう#]'); ?></a>
						</span>
					</div>
					<?php } ?>
				</div>
				<div class="col-xs-4 cl3">
					<!-- <h3 class="text-primary"><?php change_furigana_text ('[furi k=#答# f=#こた#]'); ?>えシート</h3> -->
					<div class="question-palette hikari-h130" id="question-palette">
						<table class="table table-bordered hikari-table-answer" style="width: 60%;"> 
							<!-- Cau tra loi -->
							{!! Form::open(array('url' => URL_STUDENT_EXAM_FINISH_EXAM.$quiz->slug, 'method' => 'POST', 'id'=>'onlineexamform')) !!}
							<?php 
							$i_question = 1;
							$mondai = 1;
							$subject_check = '';
								// Hiện thị stt câu hỏi phần nghe
							$quiz_type = $quiz->type;
							$rei_mondai = $quiz->category_id;
							?>
							@foreach($questions as $question)
							<?php
							$answers = json_decode($question->answers);
							?>
							<?php if ($subject_check != $question->subject_id) {  ?>
							<tr>
								<td colspan="5"><h6 class="text-primary" style="margin: 0px;"><ruby>問題<rt>もんだい</rt></ruby> <?php echo $mondai; ?></h6></td>
							</tr>
							<?php 
							$checked_vd = 1;
							$hik_checked_style = 'checked="checked"';
										// Show 3 answer rei
							$show_4_rei = 0;
							if ($rei_mondai == 3) {
								switch ($mondai) {
									case 1:
									$checked_vd = 1;
									break;
									case 2:
									$checked_vd = 1;
									break;
									case 3:
									$checked_vd = 2;
									break;
									case 4:
									$checked_vd = 1;
									break;
									case 5:
									$checked_vd = 2;
									$show_4_rei = 1;
									break;
								}
							}
							if ($rei_mondai == 5) {
								switch ($mondai) {
									case 1:
									$checked_vd = 3;
									break;
									case 2:
									$checked_vd = 1;
									break;
									case 3:
									$checked_vd = 2;
									$show_4_rei = 1;
									break;
									case 4:
									$checked_vd = 2;
									$show_4_rei = 1;
									break;
								}
							}
							?>
							<?php if ($quiz->type == 1) { ?>
							<tr>
								<td><span style="font-size: 14px; font-weight: 600;">れい</span></td>
								<?php for ($i_vd=1; $i_vd <=4 ; $i_vd++) { ?>
								<?php 
												// show 4 rei
								if ($show_4_rei == 1 && $i_vd == 4) {
									echo "<td></td>";
								} else { ?>
								<td>
									<div class="select-answer">
										<div class="inline-block">
											<input type="radio" readonly="" disabled="" <?php if ($checked_vd == $i_vd) { echo $hik_checked_style;} ?>>
											<label>
												<span class="fa-stack radio-button answer-relative">
													<i class="mdi mdi-check active" ></i> <span class="answers-number"><?php echo $i_vd; ?></span>
												</span>
											</label>
										</div>
									</div>
								</td>
								<?php } // end show 4 rei ?>
								<?php } ?>
							</tr>
							<?php } ?>
							<?php 
							$mondai++;
							$subject_check = $question->subject_id;
							if ($quiz_type == 1) {
								$i_question = 1;
							}
						} ?>
						<tr>
							<td><span style="font-size: 14px; font-weight: 600;"><?php echo $i_question; ?></span></td>
							<?php 
							$count_answser = count($answers); 
							$i=1;
							?>
							@foreach($answers as $answer)
							<td>
								<div class="select-answer">
									<div class="inline-block">
										<input id="{{ $answer->option_value}}{{$question->id}}{{$i}}" value="{{$i}}" name="{{$question->id}}[]" 
										type="radio"/>
										<label for="{{$answer->option_value}}{{$question->id}}{{$i}}">
											<span class="fa-stack radio-button answer-relative">
												<i class="mdi mdi-check active"></i> <span class="answers-number"><?php echo $i; ?></span>
											</span>
										</label>
									</div>
								</div>
							</td>
							<?php if (($count_answser == 3) && ($i == 3)) { echo "<td></td>"; }?>
							<?php $i++;?>
							@endforeach
						</tr>
						<!-- Cau tra loi -->
						<?php $i_question++; ?>
						@endforeach
						{!! Form::close() !!}
					</table>
				</div>
			</div>
		</div>
		<div class="hikari-morong"> 
			<span id="hikari-morong-button" style="padding: 1px 8px;"><i class="fa fa-arrow-down"></i>拡張</span> 
		</div>
	</div>
</div> <!-- //============================END ROW HEADER -->
</div><!-- //============================END ANSWER HEADER -->
<script type="text/javascript">
	function toggleFullScreen(elem) {
					    // ## The below if statement seems to work better ## if ((document.fullScreenElement && document.fullScreenElement !== null) || (document.msfullscreenElement && document.msfullscreenElement !== null) || (!document.mozFullScreen && !document.webkitIsFullScreen)) {
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
					input[type="radio"] + label span.fa-stack, input[type="checkbox"] + label span.fa-stack {
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
				</style>
				