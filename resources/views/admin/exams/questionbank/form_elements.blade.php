	<div  class="row">
		<div class="col-md-4">
			<fieldset class="form-group">
				{{ Form::label('book', 'Mã sách') }} 
				{{ Form::text('book', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '')) }}
				<div class="validation-error" ng-messages="formQuestionBank.book.$error" >
					{!! getValidationMessage()!!}
					{{-- {!! getValidationMessage('number')!!} --}}
				</div>
			</fieldset>
		</div> 
		<div class="col-md-4">
			<fieldset class="form-group">
				{{ Form::label('page', 'Trang') }} 
				{{ Form::text('page', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '')) }}
				<div class="validation-error" ng-messages="formQuestionBank.page.$error" >
					{!! getValidationMessage()!!}
					{{-- {!! getValidationMessage('number')!!} --}}
				</div>
			</fieldset>
		</div>
		<div class="col-md-4">
			<fieldset class="form-group">
				{{ Form::label('cau', 'Câu số') }} 
				{{ Form::text('cau', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '')) }}
				<div class="validation-error" ng-messages="formQuestionBank.cau.$error" >
					{!! getValidationMessage()!!}
					{{-- {!! getValidationMessage('number')!!} --}}
				</div>
			</fieldset>
		</div>
	</div>

	<input type="hidden" name="subject_id" value="{{ $subject->id }}">
	<fieldset class="form-group ">
		{{ Form::label('topic_id', 'Chủ đề') }} <span class="text-red">*</span>
		{{Form::select('topic_id', $topics, null, ['class'=>'form-control', "id"=>"topic_id"])}}
	</fieldset>
	<fieldset class="form-group">
		{{ Form::label('question', 'Câu hỏi') }} 
		<!-- <span class="text-red">*</span> -->
		{{ Form::textarea('question', $value = null , $attributes = array('class'=>'form-control ckeditor', 'id'=>'question', 'placeholder' => 'Your question', 'rows' => '5',
		'ng-model'=>'question', 
		'ng-class'=>'{"has-error": formQuestionBank.question.$touched && formQuestionBank.question.$invalid}',
		'ng-minlength' => '',
		)) }}
		<div class="validation-error" ng-messages="formQuestionBank.question.$error" >
			{!! getValidationMessage()!!}
		</div>
	</fieldset>
	<fieldset class="form-group" style="display: none;">
		{{ Form::label('question_l2', getphrase('question_2nd_language')) }} 
		{{ Form::textarea('question_l2', $value = null , $attributes = array('class'=>'form-control ckeditor', 'placeholder' => 'Your question', 'rows' => '5',
		'ng-model'=>'question_l2', 
		'id'=>'question_l2',
		)) }}
	</fieldset>
	<?php 
	$settingsObj 			= new App\GeneralSettings();
		// $question_types 		= $settingsObj->getQuestionTypes();	
		// $exam_max_options 		= $settingsObj->getExamMaxOptions();	
	$exam_difficulty_levels = $settingsObj->getDifficultyLevels();	
		//Chọn mặc định single option
		$question_types = array ('radio'=>'Single Answer');
		$exam_max_options 		= array ('4'=>'4', '3'=>'3');
		?>
		<fieldset class="form-group " style="display: none">
			{{ Form::label('question_type', getphrase('question_type')) }}
			<span class="text-red">*</span>
			<?php 
			$readonly = "";
			if($record)
				$readonly = "disabled";
			?>
			{{Form::select('question_type',$question_types , null, ['class'=>'form-control', "id"=>"question_type", "ng-model"=>"question_type" ,
			'required'=> 'true', 
			'ng-class'=>'{"has-error": formQuestionBank.question_type.$touched && formQuestionBank.question_type.$invalid}',
			$readonly
			])}}
			<?php if($readonly) { ?>
			<input type="hidden" name="question_type" value="{{$record->question_type}}" >
			<?php } ?>
			<div class="validation-error" ng-messages="formQuestionBank.question_type.$error" >
				{!! getValidationMessage()!!}
			</div>
		</fieldset>
		{{-- 
			<fieldset ng-if="question_type=='video'|| question_type=='audio'" class='form-group col-md-6'>
				{{ Form::label('is_having_url', getphrase('is_having_url')) }}
				<span class="text-red">*</span>
				<div class="form-group row">
					<div class="col-md-6">
						{{ Form::radio('is_having_url', null , array('id'=>'optionNo', 'ng-model' => 'have_url' )) }}
						<label for="optionNo"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span> {{getPhrase('No')}}</label> 
					</div>
					<div class="col-md-6">
						{{ Form::radio('is_having_url', null , array('id'=>'optionYes', 'ng-model' => 'have_url')) }}
						<label for="optionYes"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span> {{getPhrase('Yes')}} </label>
					</div>
				</div>
			</fieldset> 
			--}}
			<!-- <div  class="row">
				<div class="col-md-6">
					<fieldset class="form-group" >
						{{ Form::label('question_file', 'Upload file' ) }} 
						{{Form::file('question_file', $attributes = array('class'=>'form-control'))}}
					</fieldset>
				</div> 
				<div class="col-md-6">
					@if($record)
					@if($record->question_file)  
					@include('exams.questionbank.question_partial_audio_preview', array('record'=>$record))
					@endif
					@endif
				</div>
			</div> -->
			<!-- <div  class="row">
				<div class="col-md-6">
					<fieldset class="form-group" >
						{{ Form::label('question_photo', 'Upload photo' ) }} 
						<span>(.jpg, .png)</span>
						{{Form::file('question_photo', $attributes = array('class'=>'form-control'))}}
					</fieldset>
				</div> 
				<div class="col-md-6">
					@if($record)
					@if($record->question_photo)  
					@include('exams.questionbank.question_partial_image_preview', array('record'=>$record))
					@endif
					@endif
				</div>
			</div> -->
			<div  class="row">
				<div class="col-md-6">
					<fieldset class="form-group">
						{{ Form::label('question_file', 'File .mp3') }} 
						<div class="form-inline-url">
							{{ Form::text('question_file', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => 'File .mp3', 'style'=>'display: inline-block; width: 80%;')) }}
							<a class="btn btn-primary" style="padding: 10px 12px;" onclick="openPopup()">Select file</a>
						</div>
						<!-- <div class="validation-error" ng-messages="formQuestionBank.url.$error" >
							{!! getValidationMessage()!!}
						</div> -->
					</fieldset>
				</div> 
				<div class="col-md-6">
					@if($record)
					<fieldset class="form-group">
						<label></label>
						@if($record->question_file)  
							@include('exams.questionbank.question_partial_audio_preview', array('record'=>$record))
						@endif
					</fieldset>
					@endif
				</div>
			</div>
			
			<fieldset class="form-group " style="display: none">
				{{ Form::label('difficulty_level', getphrase('difficulty_level')) }}
				<span class="text-red">*</span>
				{{Form::select('difficulty_level',$exam_difficulty_levels , 'medium', ['class'=>'form-control', "id"=>"difficulty_level" ])}}
			</fieldset>
			<fieldset class="form-group" style="display: none">
				{{ Form::label('hint', getphrase('hint')) }} 
				{{ Form::text('hint', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '')) }}
			</fieldset>
			<fieldset class="form-group">
				{{ Form::label('explanation', 'Mô tả') }} 
				{{ Form::textarea('explanation', $value = null , $attributes = array('class'=>'form-control ckeditor', 'id'=>'explanation', 'placeholder' => '', 'rows' => '5')) }}
			</fieldset>
			<fieldset class="form-group" style="display: none;">
				{{ Form::label('explanation_l2', getphrase('explanation_2nd_language')) }} 
				{{ Form::textarea('explanation_l2', $value = null , $attributes = array('class'=>'form-control ckeditor', 'placeholder' => '', 'rows' => '5','id'=>'explanation_l2')) }}
			</fieldset>
			{{-- 	<div class="row">
				<div class="col-md-6">
					<fieldset class="form-group" >
						{{ Form::label('explanation_file', getPhrase('explanation_file')) }}
						{{Form::file('explanation_file', $attributes = array('class'=>'form-control'))}}
					</fieldset>
				</div> 
				<div class="col-md-6">
					@if($record)
					@if($record->explanation_file)  
					<img src="{{EXAM_UPLOADS.$record->explanation_file}}" height="90" width="90"/> 
					@endif
					@endif
				</div>
			</div>--}}
			<div  class="row">
				<div class="col-md-6">
				<fieldset class="form-group">
					{{ Form::label('marks', 'Điểm') }} 
					<span class="text-red">*</span>
					{{ Form::number('marks', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '',
					'min'=>'1',
					'ng-model'=>'marks', 
					'required'=> 'true', 
					'ng-class'=>'{"has-error": formQuestionBank.marks.$touched && formQuestionBank.marks.$invalid}',
					)) }}
					<div class="validation-error" ng-messages="formQuestionBank.marks.$error" >
						{!! getValidationMessage()!!}
						{{-- {!! getValidationMessage('number')!!} --}}
					</div>
				</fieldset>
				</div>
				<div class="col-md-6">
					<?php
					$question_show_type = array ('0'=>'Ngang', '1'=>'Dọc', '2' =>'Ẩn câu trả lời');
					?>
					<fieldset class="form-group">
						{{ Form::label('question_show_type', 'Hiện thị câu trả lời') }}
						<span class="text-red">*</span>
						{{Form::select('question_show_type',$question_show_type , null, ['class'=>'form-control', "id"=>"question_show_type", 
						'required'=> 'false', 
						])}}
						<div class="validation-error" ng-messages="formQuestionBank.question_show_type.$error" >
							{!! getValidationMessage()!!}
						</div>
					</fieldset>
				</div>
			</div>
			<fieldset class="form-group" style="display: none">
				{{ Form::label('time_to_spend', getphrase('time_to_spend')) }} 
				<span class="text-red">*</span>
				{{ Form::number('time_to_spend', $value = 60 , $attributes = array('class'=>'form-control', 'placeholder' => '',
				'min'=>'0','max'=>'4',
				'ng-model'=>'time_to_spend', 
				'required'=> '', 
				'ng-class'=>'{"has-error": formQuestionBank.time_to_spend.$touched && formQuestionBank.time_to_spend.$invalid}',
				)) }}
				<div class="validation-error" ng-messages="formQuestionBank.time_to_spend.$error" >
					{!! getValidationMessage()!!}
					{{-- {!! getValidationMessage('number')!!} --}}
				</div>
			</fieldset>
				<!-- Load the files start as independent -->
			<?php	
			$image_path = ($record) ? PREFIX.(new ImageSettings())->getExamImagePath(): ''; ?>
			@include('exams.questionbank.form_elements_radio', array('image_path'=>$image_path))
			@include('exams.questionbank.form_elements_checkbox')
			@include('exams.questionbank.form_elements_blanks')
			<?php 
			$show = TRUE;
			if($record) {
				if($record->question_type=='match')
					$show = FALSE;
			} 
			?>
			@if($show)
			@include('exams.questionbank.form_elements_para', array('record'=>$record))
			@endif
			<!-- Load the files end as independent -->
			@if(!$record)
			<div class="buttons text-center">
				<button class="btn btn-lg btn-success button"
				ng-disabled='!formQuestionBank.$valid'>{{ $button_name }}</button>
			</div>
			@else
			<div class="buttons text-center">
				<button class="btn btn-lg btn-success button">{{ $button_name }}</button>
			</div>
			@endif
			<div class="text-center view-action" style="position: fixed;bottom:50px; right: 0;">
				<a class="btn btn-lg btn-info button hikari-view-question-" onclick="load_ajax()">Review</a>
			</div>
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  	<div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="myModalLabel">View Question</h4>
		      </div>
		      <div class="modal-body">
		      	<div class="row">
			        <div class=" col-sm-12">
			        	<span class="hikari-title">Question:</span> <span class="model-question"></span>
			        </div>
			        <div class=" col-sm-12">
			        	<span class="hikari-title">Description:</span> <span class="model-description"></span>
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
		<script src="{{themes('js/jquery-1.12.1.min.js')}}"></script>
		<script type="text/javascript">
			CKEDITOR.replace( 'editor1' );
		</script>
		<script type="text/javascript">
            function load_ajax() {
            	$.ajax({
        			headers: {
			    		'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
			  		},
                    url : "<?php echo URL_QUESTIONBANK_AJAX_FURIGANA ?>",
                    type : "post",
                    data: {question: CKEDITOR.instances.question.getData(), explanation: CKEDITOR.instances.explanation.getData(), answers1: $('#option_0').val(), answers2: $('#option_1').val(), answers3: $('#option_2').val(), answers4: $('#option_3').val()},
                    success : function (result){
                        var returnedData = jQuery.parseJSON(result);
                         var topic_name = $('#topic_id option:selected').text();
                         var book = $('#book').val();
                         var page = $('#page').val();
                         var marks = $('#marks').val();
                         var correct_answers = $('#correct_answers').val();
                         $('.model-question').html(returnedData.question);
                         $('.model-description').html(returnedData.explanation);
                         $('.answers1').html(returnedData.answers1);
                         $('.answers2').html(returnedData.answers2);
                         $('.answers3').html(returnedData.answers3);
                         $('.answers4').html(returnedData.answers4);
                         $('.correct_answers').html(correct_answers);
                         $('.book').text(book);
                         $('.page').text(page);
                         $('.model-marks').html(marks);
                         $('.model-topic').html(topic_name);
                         $('#myModal').modal('show');
                    }
	                });
            }
        </script>
