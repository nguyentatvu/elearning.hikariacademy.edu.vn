@extends('admin.layouts.'.getRole().'.'.getRole().'layout')
@section('header_scripts')
<link rel="stylesheet" type="text/css" href="{{CSS}}select2.css">
@stop
@section('content')
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="/"><i class="mdi mdi-home"></i></a> </li>
							<li><a href="{{URL_TOPICS}}">Câu hỏi Mondai</a> </li>
							<li class="active">{{isset($title) ? $title : ''}}</li>
						</ol>
					</div>
				</div>
					@include('admin.errors.errors')
				<!-- /.row -->
							<div class="panel panel-custom col-lg-8 col-lg-offset-2">
					<div class="panel-heading">
						<div class="pull-right messages-buttons">
							<a href="{{URL_TOPICS}}" class="btn  btn-primary button" >Danh sách câu hỏi Mondai</a>
						</div>
					<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body  form-auth-style" ng-controller="angTopicsController">
					<?php $button_name = getPhrase('create'); ?>
					@if ($record)
					 <?php $button_name = getPhrase('update'); ?>
						{{ Form::model($record, 
						array('url' => URL_TOPICS_EDIT.'/'.$record->slug, 
						'method'=>'patch' ,'novalidate'=>'','name'=>'formTopics ')) }}
					@else
						{!! Form::open(array('url' => URL_TOPICS_ADD, 'method' => 'POST', 
						'novalidate'=>'','name'=>'formTopics ')) !!}
					@endif
					 @include('admin.mastersettings.topics.form_elements', 
					 array('button_name'=> $button_name),
					 array('subjects'=>$subjects, 'parent_topics'=>$parent_topics))
					{!! Form::close() !!}
					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->
		<!-- view -->
					<div class="text-center view-action" style="position: fixed;bottom:50px; right: 0;">
						<a class="btn btn-lg btn-info button hikari-view-question-" onclick="load_ajax()">View</a>
					</div>
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				  	<div class="modal-dialog" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title" id="myModalLabel">View Topic</h4>
				      </div>
				      <div class="modal-body">
				      	<div class="row">
							<div class=" col-sm-12">
								<span class="hikari-title">Subject:</span> <span class="model-subject"></span>
							</div>
							<div class=" col-sm-12">
								<span class="hikari-title">Parent:</span> <span class="model-parent"></span>
							</div>
					        <div class=" col-sm-12">
					        	<span class="hikari-title">Topic:</span> <span class="model-question"></span>
					        </div>
					        <div class=" col-sm-12">
					        	<span class="hikari-title">Description:</span> <span class="model-description"></span>
					        </div>
					    </div>
					  </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
				      </div>
				    </div>
				  </div>
				</div>
				<script type="text/javascript">
            function load_ajax() {
            	$.ajax({
        			headers: {
			    		'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
			  		},
                    url : "<?php echo URL_QUESTIONBANK_AJAX_FURIGANA ?>",
                    type : "post",
                    data: {topic_name: $('#topic_name').val(), description: CKEDITOR.instances.description.getData()},
                    success : function (result){
                         var returnedData = jQuery.parseJSON(result);
                         var parent = $('#parent_id option:selected').text();
                         var subject = $('#subject option:selected').text();
                         var parent = $("body").find('#parent option:selected').text();
                         $('.model-question').html(returnedData.topic_name);
                         $('.model-description').html(returnedData.description);
                         $('.model-subject').html(subject);
                         $('.model-parent').html(parent);
                         $('#myModal').modal('show');
                    }
	                });
            }
        </script>
		<!-- #view -->
@stop
@section('footer_scripts')
	<script src="{{JS}}plugins/ckeditorv411/ckeditor.js"></script>
	<script src="{{JS}}plugins/ckfinder/ckfinder.js"></script>
	<script>
		CKEDITOR.replace( 'description', {
		    filebrowserBrowseUrl : "{{JS}}plugins/ckfinder/ckfinder.html",
		    filebrowserUploadUrl : "{{JS}}plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images",
		} );
		</script>
	@include('admin.common.editor')
	@include('admin.mastersettings.topics.scripts.js-scripts');
	@include('admin.common.validations', array('isLoaded'=>TRUE));
@stop
 