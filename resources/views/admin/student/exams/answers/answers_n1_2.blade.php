<?php 
	$count_answser = $question->total_answers; 
	$i=1;
	$user_answers   = FALSE;
	if(array_key_exists($question->id, $submitted_answers)) {
	    $user_answers = $submitted_answers[$question->id];
	}
?>

@foreach($answers as $answer)
<?php 
	$submitted_value = '';
	$class_check_correct_answers = '';
    if($user_answers && count($user_answers))  {
        if($user_answers[0] == $i) {
            $submitted_value = 'checked';
            $class_check_correct_answers = 'check_incorrect_answers';

        }
        if ($user_answers[0] == $question->correct_answers) {
        	$class_check_correct_answers = 'check_correct_answers';
        }
    }
?>
<td>
	<div class="select-answer">
		<div class="inline-block">
			<input disabled id="{{ $answer->option_value}}{{$question->id}}{{$i}}" value="{{$i}}" name="{{$question->id}}[]" 
			type="checkbox" {{$submitted_value}} <?php if ($i == $question->correct_answers) { echo $hik_checked_style;} ?> />
			<label for="{{$answer->option_value}}{{$question->id}}{{$i}}" class="{{$class_check_correct_answers}}">
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