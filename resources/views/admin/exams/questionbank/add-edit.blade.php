@extends('admin.layouts.'.getRole().'.'.getRole().'layout')

@section('content')

<div id="page-wrapper">

			<div class="container-fluid">

				<!-- Page Heading -->

				<div class="row">

					<div class="col-lg-12">

						<ol class="breadcrumb">

							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>

							<li><a href="{{URL_QUIZ_QUESTIONBANK}}">Ngân hàng câu hỏi</a></li>

							<li><a href="{{URL_QUESTIONBANK_VIEW.$subject->slug}}">{{ $subject->subject_title }}</a></li>

							<li>{{ $title }}</li>

						</ol>

					</div>

				</div>

				@include('errors.errors')

				<!-- /.row -->

				<?php $settings = ($record) ? $settings : ''; ?>

				<div class="panel panel-custom  col-lg-12" ng-init="initAngData('{{ $settings }}');"

				 ng-controller="questionsController">

					<div class="panel-heading">

						<div class="pull-right messages-buttons">

							<a href="{{URL_QUIZ_QUESTIONBANK}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>

						</div>

					<h1>{{ $title }}  </h1>

					</div>

					<div class="panel-body" id="app">

					<?php $button_name = getPhrase('create'); ?>

					@if ($record)

					 <?php $button_name = getPhrase('update'); ?>

						{{ Form::model($record, 

						array('url' => URL_QUESTIONBANK_EDIT.'/'.$record->slug, 

						'method'=>'patch', 'files' => TRUE, 'name'=>'formQuestionBank ', 'novalidate'=>'',  'class'=>'validation-align')) }}

					@else

						{!! Form::open(array('url' => URL_QUESTIONBANK_ADD, 'method' => 'POST', 'files' => TRUE, 'name'=>'formQuestionBank ', 'novalidate'=>'', 'class'=>'validation-align')) !!}

					@endif



					 @include('exams.questionbank.form_elements', 

					 array('button_name'=> $button_name),

					 array('topics' => $topics, 'subject' => $subject, 'record'=>$record))

					 

					{!! Form::close() !!}

					 



					</div>

				</div>

			</div>

			<!-- /.container-fluid -->

		</div>

		<!-- /#page-wrapper -->

@stop



@section('footer_scripts')

	<!-- <script src="{{JS}}plugins/ckeditor/ckeditor.js"></script>

	<script src="{{JS}}plugins/ckeditor/config.js"></script>

	<script src="{{JS}}plugins/ckeditor/build-config.js"></script>

	<script src="{{JS}}plugins/ckeditor/styles.js"></script> -->

	<script src="{{JS}}plugins/ckeditorv411/ckeditor.js"></script>

	<script src="{{JS}}plugins/ckfinder/ckfinder.js"></script>

	<script>

         function openPopup() {

             CKFinder.popup( {

                 chooseFiles: true,

                 onInit: function( finder ) {

                     finder.on( 'files:choose', function( evt ) {

                         var file = evt.data.files.first();

                         document.getElementById( 'question_file' ).value = file.getUrl();

                     } );

                     finder.on( 'file:choose:resizedImage', function( evt ) {

                         document.getElementById( 'question_file' ).value = evt.data.resizedUrl;

                     } );

                 }

             } );

         }

     </script>

	<script>

		/*CKEDITOR.replace( 'ckeditor',

		{

			filebrowserBrowseUrl : "{{JS}}plugins/ckfinder/ckfinder.html",

			filebrowserImageBrowseUrl : "{{JS}}plugins/ckfinder/ckfinder.html?type=Images",

			filebrowserFilesBrowseUrl : "{{JS}}plugins/ckfinder/ckfinder.html?type=Files",

			filebrowserUploadUrl : "{{JS}}plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files",

			filebrowserImageUploadUrl : "{{JS}}plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images",

			filebrowserFilesUploadUrl : "{{JS}}plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files",

			

		});*/

		CKEDITOR.replace( 'question', {

			

		    filebrowserBrowseUrl : "{{JS}}plugins/ckfinder/ckfinder.html",

		    filebrowserUploadUrl : "{{JS}}plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files",

		} );

		CKEDITOR.replace( 'explanation',

		{

			filebrowserBrowseUrl : "{{JS}}plugins/ckfinder/ckfinder.html",

		    filebrowserUploadUrl : "{{JS}}plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files",

		});

		CKEDITOR.add;

		</script>

	@include('exams.questionbank.scripts.js-scripts')

	@include('common.validations', array('isLoaded'=>TRUE))

	<!-- include('common.editor') -->

	

	@if($record)

		@if($record->question_type=='video')

			@include('common.video-scripts')

	 	@endif

	@endif

@stop