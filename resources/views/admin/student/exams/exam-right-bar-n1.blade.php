
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
				//$show_4_rei = 1;
				break;
				case 5:
				$hidden_rei = 1;
				//$show_4_rei = 1;
				break;
			}
		}
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
				