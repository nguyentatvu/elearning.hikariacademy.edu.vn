@extends('admin.layouts.'.getRole().'.'.getRole().'layout')
@section('custom_div')
<div ng-controller="prepareQuestions">
	@stop
	@section('content')
	<div id="page-wrapper">
		<div class="container-fluid">
			<!-- Page Heading -->
			<div class="row">
				<div class="col-lg-12">
					<ol class="breadcrumb">
						<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
						<li><a href="{{URL_QUIZZES}}">Đề thi</a></li>
						<li class="active">{{isset($title) ? $title : ''}}</li>
					</ol>
				</div>
			</div>
			@include('admin.errors.errors')
			<?php $settings = ($record) ? $settings : ''; ?>
			<div class="panel panel-custom" ng-init="initAngData({{$settings}});" >
				<div class="panel-heading">
					<div class="pull-right messages-buttons">
						<a href="{{URL_QUIZZES}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>
					</div>
					<h1>{{ $title }}  (N{{$category_id}})</h1>
				</div>
				<div class="panel-body" >
					<?php $button_name = getPhrase('create'); ?>
					<div class="row">
						<div class="col-md-6">
							<fieldset class="form-group col-md-6">
								{{ Form::label('subject', 'Tìm theo Mondai') }}
								<span class="text-red">*</span>
								{{Form::select('subject', $subjects, null, ['class'=>'form-control', 'ng-model' => 'subject_id', 
								'placeholder' => '--Chọn Mondai--', 'ng-change'=>'subjectChanged(subject_id)' ])}}
							</fieldset>

							<fieldset class="form-group col-md-6" >
								<label for="topic">Cơ cấu</label>
								<!-- <select ng-model="topic_id" class="form-control" ng-options="topics.topic_name for topics in subjectTopics track by topics.id"> -->
								<select ng-model="topic_id" class="form-control">
										<option value="">--Chọn cơ cấu--</option>
										<option value="" ng-if="false" disabled hidden></option>
										<option ng-repeat="topics in subjectTopics track by $index" value="@{{topics.id}}">@{{topics.topic_name}}</option>
								</select>
							</fieldset>

								
					<fieldset class="form-group col-md-6">
						{{ Form::label('book', 'Tìm theo mã sách') }}
						<select ng-model="book" class="form-control" >
							<option value="4893589378">4893589378</option>	
							<option value="4757418820">4757418820</option>
							<option value="4336051608">4336051608</option>
							
							<option value="4863921290_1">4863921290_1</option>	
							<option value="4863921290_2">4863921290_2</option>
							<option value="4863921290_3">4863921290_3</option>

							<option value="4893588173">4893588173</option>	
							<option value="4757418776">4757418776</option>
							<option value="4863920750">4863920750</option>
							<option value="4872177435">4872177435</option>

							<option value="4863921214_1">4863921214_1</option>	
							<option value="4863921214_2">4863921214_2</option>
							<option value="4863921214_3">4863921214_3</option>

							<option value="4863921375_1">4863921375_1</option>	
							<option value="4863921375_2">4863921375_2</option>
							<option value="4863921375_3">4863921375_3</option>

							<option value="4863921382_1">4863921382_1</option>
							<option value="4863921382_2">4863921382_2</option>
							<option value="4863921382_3">4863921382_3</option>

							<option value="4863921412_1">4863921412_1</option>
							<option value="4863921412_2">4863921412_2</option>
							<option value="4863921412_3">4863921412_3</option>

							<option value="4893588197">4893588197</option>
							<option value="4893588203">4893588203</option>
							<option value="4893588210">4893588210</option>

							<option value="4757419506">4757419506</option>
							<option value="4757419483">4757419483</option>
							<option value="4896894875">4896894875</option>

							<option value="4893587619">4893587619</option>
							<option value="4893587602">4893587602</option>
							<option value="4896894974">4896894974</option>

							<option value="4757422247">4757422247</option>
							<option value="4336052186">4336052186</option>
							<option value="4336059659">4336059659</option>

							<option value="4336053510">4336053510</option>
							<option value="4896894936">4896894936</option>
							<option value="4896894868">4896894868</option>

							<option value="4757420359">4757420359</option>
							<option value="4893588470">4893588470</option>
							<option value="4757419544">4757419544</option>
						</select>
					</fieldset>
					<fieldset class="form-group col-md-6">
						{{ Form::label('question_model', 'Tìm câu hỏi') }}
						{{ Form::text('question_model', $value = null , $attributes = array('class'=>'form-control', 
						'placeholder' => '',
						'ng-model'=>'question_model')) }}
					</fieldset>
					
					{{-- 
						CODES USED WITH EXAM TYPE
						NSNT==> NO SECTION NO TIMER 
						SNT==> SECTION WITH NO TIMER 
						ST==> SECTION WITH TIMER 
						--}}
						@if($record->exam_type!='NSNT')
						<fieldset class="form-group col-md-6">
							{{ Form::label('section_name', 'Section Name') }}
							{{ Form::text('section_name', $value = null , $attributes = array('class'=>'form-control', 
							'placeholder' => 'Section name',
							'ng-model'=>'section_name')) }}
						</fieldset>
						@endif
						@if($record->exam_type != 'NSNT' && $record->exam_type != 'SNT')	
						<fieldset class="form-group col-md-6">
							{{ Form::label('section_time', 'Section Time In Minutes') }}
							{{ Form::text('section_time', $value = null , $attributes = array('class'=>'form-control', 
							'placeholder' => 'Section Time',
							'ng-model'=>'section_time')) }}
						</fieldset>
						@endif
						{{-- <a ng-click="subjectChanged()"><i class="fa fa-refresh pull-right text-info"></i></a> --}}
						<div class="col-md-12" ng-show="contentAvailable">
							<div ng-if="subjectQuestions!=''" class="vertical-scroll" >
								<!-- <h4 class="text-success">Câu hỏi @{{ subjectQuestions.length }} </h4> -->
								<table  
								class="table table-hover">
								<th >Mondai</th>
								<th>Câu hỏi</th>
								<!-- <th>{{getPhrase('difficulty')}}</th> -->
								<!-- <th>Cấu tạo id</th> -->
								<th>Cấu tạo</th>
								<th>Sách</th>
								<th>Trang</th>
								<th>Điểm</th>
								<th>Đ/A</th>	
								<th>{{getPhrase('action')}}</th>	
								<tr ng-repeat="question in subjectQuestions | filter: { book:book, difficulty_level:difficulty, question_type:question_type, show_in_front_end:show_in_front_end , topic_id:topic_id, topic_name:topic_name, sub_topic_id:sub_topic } | filter: question_model track by $index ">
									<td>@{{subject.subject_title}}</td>
									<td title="@{{subjectQuestions[$index].question}}" ng-bind-html="trustAsHtml(question.question)">
									</td>
									<!-- <td>@{{question.difficulty_level | uppercase}}</td> -->
									<!-- <td>@{{question.topic_id}}</td> -->
									<td>@{{question.topic_name}}</td>
									<td>@{{question.book}}</td>
									<td>@{{question.page}}</td>
									<!-- <td>@{{question.question_type | uppercase}}</td> -->
									<td>@{{question.marks}}</td>
									<td>@{{question.correct_answers}}</td>
									<td>@{{question.socautrung}} - <a 
										ng-click="addQuestion(question, subject);" class="btn btn-primary" >{{getPhrase('add')}}</a>
									</td>
								</tr>
							</table>
						</div>	
					</div>
				</div>
				<div class="col-md-6">
					@include('admin.exams.quiz.questions-selection-block')
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
@stop
@section('footer_scripts')
@include('admin.exams.quiz.scripts.js-scripts', ['quiz_record' => $record])
@include('admin.common.alertify')
@stop
@section('custom_div_end')
</div>
@stop