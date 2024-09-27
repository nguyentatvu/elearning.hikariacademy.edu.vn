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
							@include('student.exams.exam-form-mp3', array($questions))
						<?php }	?> 	

					</h3>

					<div class="answers-page-pagination question-palette">
						@include('student.exams.exam-leftbar-subjects', array('subjects' => $subjects))
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
					<h3 class="text-primary" id="text-nop-bai"><?php if ($quiz->type != 1) { echo 'THỜI GIAN CÒN LẠI'; } else { echo 'NỘP BÀI';}   ?></h3>
					<?php if ($quiz->type != 1) { ?>
					<div id="timerdiv" class="countdown-styled" style="color: #337ab7;">
						<!-- <span id="hours">{{$data['time_hours']}}</span> : --> 
						<span id="mins">{{ $data['time_minutes']}}</span> : 
						<span id="seconds">00</span>
						<span class="submit-question" style="text-align: center; padding-top: 0px;">
							<a href="javascript:void(0);" class="btn btn-lg btn-danger button <?php echo $finish; ?>" type="button" style="padding: 10px;">NỘP BÀI </a>
						</span>
					</div>
					<?php } else {?>
					<div id="timerdiv" class="countdown-styled" style="color: #337ab7;">
						<span class="submit-question-nghe" style="text-align: center; padding-top: 0px;">
							<a href="javascript:void(0);" class="btn btn-lg btn-danger button <?php echo $finish; ?>" type="button" style="padding: 10px; margin-top: 8px;">NỘP BÀI</a>
						</span>
						<span class="submit-audio-star" style="text-align: center; padding-top: 0px; display: none;">
							<a href="javascript:void(0);" class="btn btn-lg btn-danger button start_audio" type="button" style="padding: 10px; margin-top: 8px;">BẮT ĐẦU</a>
						</span>
					</div>
					<?php } ?>
				</div>
				<div class="col-xs-4 cl3">
					<div class="question-palette hikari-h130" id="question-palette">
						
						@if ($quiz->category_id == 1)
							@if ($quiz->type == 1)
							<table class="table table-bordered hikari-table-answer" style="width: 60%;"> 
								<!-- Cau tra loi -->
								{!! Form::open(array('url' => URL_STUDENT_EXAM_FINISH_EXAM.$quiz->slug, 'method' => 'POST', 'id'=>'onlineexamform')) !!}
								{!! Form::hidden('time','00:00') !!}
								<?php
								$i_question = 1;
								$index_question = 1;
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
									<td colspan="6"><h6 class="text-primary" style="margin: 0px;"><ruby>問題<rt>もんだい</rt></ruby> <?php echo $mondai; ?></h6></td>
								</tr>
								<?php 
								$checked_vd = 1;
								$hik_checked_style = 'checked="checked"';
								// Show 3 answer rei
								$show_4_rei = 0;
								$hidden_rei = 0;
								if ($rei_mondai == 1) {
									switch ($mondai) {
										case 1:
										$checked_vd = 2;
										break;
										case 2:
										$checked_vd = 3;
										break;
										case 3:
										$checked_vd = 3;
										break;
										case 4:
										$checked_vd = 3;
										$show_4_rei = 1;
										break;
										case 5:
										$hidden_rei = 1;
										break;
									}
								}
								
								?>
								<?php if ($quiz->type == 1 && $hidden_rei == 0) { ?>
									<tr>
										<td colspan="2"><span style="font-size: 14px; font-weight: 600;">れい</span></td>
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
																<i class="mdi mdi-check active" ></i> 
																<span class="answers-number"><?php echo $i_vd; ?></span>
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

							@if ($index_question <= 35)
							<tr>
								<td colspan="2"><span style="font-size: 14px; font-weight: 600;"><?php echo $i_question; ?></span></td>
								<?php 
								$count_answser = $question->total_answers; 
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
													<i class="mdi mdi-check active"></i> 
													<span class="answers-number"><?php echo $i; ?></span>
												</span>
											</label>
										</div>
									</div>
								</td>
								<?php if (($count_answser == 3) && ($i == 3)) { echo "<td></td>"; }?>
								<?php $i++;?>
								@endforeach
							</tr>
							@endif
							
							@if ($index_question == 36)
							<tr>
								<td rowspan="2" align="center" style="vertical-align: middle; text-align: center;" class="table-middle">
									<span style="font-size: 14px; font-weight: 600;">{{ $i_question }}</span>
								</td>
								<td>
									(1)
								</td>
								<?php 
								$count_answser = $question->total_answers; 
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
													<i class="mdi mdi-check active"></i> 
													<span class="answers-number"><?php echo $i; ?></span>
												</span>
											</label>
										</div>
									</div>
								</td>
								<?php if (($count_answser == 3) && ($i == 3)) { echo "<td></td>"; }?>
								<?php $i++;?>
								@endforeach
							</tr>
							@endif

							@if ($index_question == 37)
							<tr>
								<td>
									(2)
								</td>
								<?php 
								$count_answser = $question->total_answers; 
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
													<i class="mdi mdi-check active"></i> 
													<span class="answers-number"><?php echo $i; ?></span>
												</span>
											</label>
										</div>
									</div>
								</td>
								<?php if (($count_answser == 3) && ($i == 3)) { echo "<td></td>"; }?>
								<?php $i++;?>
								@endforeach
							</tr>
							@endif

							<!-- Cau tra loi -->
							<?php $i_question++; $index_question++; ?>
							@endforeach
							{!! Form::close() !!}
						</table>
						@endif
						
						@if ($quiz->type != 1)
							<table class="table table-bordered hikari-table-answer" style="width: 60%;"> 
									<!-- Cau tra loi -->
									{!! Form::open(array('url' => URL_STUDENT_EXAM_FINISH_EXAM.$quiz->slug, 'method' => 'POST', 'id'=>'onlineexamform')) !!}
								{!! Form::hidden('time','00:00') !!}
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
									$hidden_rei = 0;
									?>
						
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
									$count_answser = $question->total_answers; 
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
														<i class="mdi mdi-check active"></i> 
														<span class="answers-number"><?php echo $i; ?></span>
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

						@endif
					<!-- endif N1 -->
					@endif

					@if ($quiz->category_id == 2)
						@if ($quiz->type == 1)
							<table class="table table-bordered hikari-table-answer" style="width: 60%;"> 
								<!-- Cau tra loi -->
								{!! Form::open(array('url' => URL_STUDENT_EXAM_FINISH_EXAM.$quiz->slug, 'method' => 'POST', 'id'=>'onlineexamform')) !!}
								{!! Form::hidden('time','00:00') !!}
								<?php
								$i_question = 1;
								$index_question = 1;
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
									<td colspan="6"><h6 class="text-primary" style="margin: 0px;"><ruby>問題<rt>もんだい</rt></ruby> <?php echo $mondai; ?></h6></td>
								</tr>
								<?php 
								$checked_vd = 1;
								$hik_checked_style = 'checked="checked"';
								// Show 3 answer rei
								$show_4_rei = 0;
								$hidden_rei = 0;
								if ($rei_mondai == 2) {
									switch ($mondai) {
										case 1:
										$checked_vd = 3;
										break;
										case 2:
										$checked_vd = 3;
										break;
										case 3:
										$checked_vd = 2;
										break;
										case 4:
										$checked_vd = 2;
										$show_4_rei = 1;
										break;
										case 5:
										$hidden_rei = 1;
										break;
									}
								}
								
								?>
								<?php if ($quiz->type == 1 && $hidden_rei == 0) { ?>
									<tr>
										<td colspan="2"><span style="font-size: 14px; font-weight: 600;">れい</span></td>
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
																<i class="mdi mdi-check active" ></i> 
																<span class="answers-number"><?php echo $i_vd; ?></span>
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

							@if ($index_question <= 30)
							<tr>
								<td colspan="2"><span style="font-size: 14px; font-weight: 600;"><?php echo $i_question; ?></span></td>
								<?php 
								$count_answser = $question->total_answers; 
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
													<i class="mdi mdi-check active"></i> 
													<span class="answers-number"><?php echo $i; ?></span>
												</span>
											</label>
										</div>
									</div>
								</td>
								<?php if (($count_answser == 3) && ($i == 3)) { echo "<td></td>"; }?>
								<?php $i++;?>
								@endforeach
							</tr>
							@endif
							
							@if ($index_question == 31)
							<tr>
								<td rowspan="2" align="center" style="vertical-align: middle; text-align: center;" class="table-middle">
									<span style="font-size: 14px; font-weight: 600;">{{ $i_question }}</span>
								</td>
								<td>
									(1)
								</td>
								<?php 
								$count_answser = $question->total_answers; 
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
													<i class="mdi mdi-check active"></i> 
													<span class="answers-number"><?php echo $i; ?></span>
												</span>
											</label>
										</div>
									</div>
								</td>
								<?php if (($count_answser == 3) && ($i == 3)) { echo "<td></td>"; }?>
								<?php $i++;?>
								@endforeach
							</tr>
							@endif

							@if ($index_question == 32)
							<tr>
								<td>
									(2)
								</td>
								<?php 
								$count_answser = $question->total_answers; 
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
													<i class="mdi mdi-check active"></i> 
													<span class="answers-number"><?php echo $i; ?></span>
												</span>
											</label>
										</div>
									</div>
								</td>
								<?php if (($count_answser == 3) && ($i == 3)) { echo "<td></td>"; }?>
								<?php $i++;?>
								@endforeach
							</tr>
							@endif

							<!-- Cau tra loi -->
							<?php $i_question++; $index_question++; ?>
							@endforeach
							{!! Form::close() !!}
						</table>
						@endif
						
						@if ($quiz->type != 1)
							<table class="table table-bordered hikari-table-answer" style="width: 60%;"> 
									<!-- Cau tra loi -->
									{!! Form::open(array('url' => URL_STUDENT_EXAM_FINISH_EXAM.$quiz->slug, 'method' => 'POST', 'id'=>'onlineexamform')) !!}
								{!! Form::hidden('time','00:00') !!}
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
									$hidden_rei = 0;
									?>
						
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
									$count_answser = $question->total_answers; 
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
														<i class="mdi mdi-check active"></i> 
														<span class="answers-number"><?php echo $i; ?></span>
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

						@endif
					<!-- endif N1 -->
					@endif

					@if ($quiz->category_id == 3 || $quiz->category_id == 4 || $quiz->category_id == 5)
					<table class="table table-bordered hikari-table-answer" style="width: 60%;"> 
							<!-- Cau tra loi -->
							{!! Form::open(array('url' => URL_STUDENT_EXAM_FINISH_EXAM.$quiz->slug, 'method' => 'POST', 'id'=>'onlineexamform')) !!}
						{!! Form::hidden('time','00:00') !!}
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
							$hidden_rei = 0;
							
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
									$show_4_rei = 1;
									break;
									case 5:
									$checked_vd = 0;
									$show_4_rei = 1;
									break;
								}
							}
							if ($rei_mondai == 4) {
								switch ($mondai) {
									case 1:
									$checked_vd = 2;
									break;
									case 2:
									$checked_vd = 3;
									break;
									case 3:
									$checked_vd = 3;
									$show_4_rei = 1;
									break;
									case 4:
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
							<?php if ($quiz->type == 1 && $hidden_rei == 0) { ?>
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
															<i class="mdi mdi-check active" ></i> 
															<span class="answers-number"><?php echo $i_vd; ?></span>
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
							$count_answser = $question->total_answers; 
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
												<i class="mdi mdi-check active"></i> 
												<span class="answers-number"><?php echo $i; ?></span>
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
					@endif


				</div>
			</div>
		</div>
		<div class="hikari-morong"> 
			<span id="hikari-morong-button" style="padding: 0px 4px;" data-display="1"><i class="fa fa-arrow-down"></i></span> 
		</div>
	</div>
</div> <!-- //============================END ROW HEADER -->
</div><!-- //============================END ANSWER HEADER -->
<script type="text/javascript">

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
					td.table-middle {
						vertical-align: middle !important;
					}
				</style>
				