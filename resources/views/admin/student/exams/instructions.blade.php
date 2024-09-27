@extends('admin.layouts.examlayout')
@section('header_scripts')
@stop
@section('content')
<div id="page-wrapper" ng-model="academia" ng-controller="instructions" onload="max()">
	<div class="container-fluid" style="padding-top: 40px;">
		<!-- Page Heading -->
				<!-- <div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><?php change_furigana_text ( $title); ?></li>
						</ol>
					</div>
				</div> -->
				<!-- /.row -->
				<div class="panel panel-custom col-lg-12" >
					<div class="panel-heading">
						<h1>Hướng dẫn<span class="pull-right text-italic"> Xin vui lòng đọc kỹ các hướng dẫn</span></h1>
					</div>
					<div class="panel-body instruction no-arrow" style="padding: 0px 0px 30px 0px">
						<div class="row">
							<div class="col-md-12">
								<h2 style="color: blue; display: none"><strong>{{change_furigana_text ($record->title)}} </strong></h2>
								@if($instruction_data=='')	
								<h3>{{getPhrase('general_instructions')}}:</h3>
								@else
								<h3>{{$instruction_title}}:</h3>
								@endif
								{!! $instruction_data !!}
							</div>
						</div>
						<hr style="margin: 10px 0">
						<?php
						$paid_type =  false;
						if($record->is_paid && !isItemPurchased($record->id, 'exam'))	
							$paid_type = true;
						?>
						<div class="form-group row">
							{!! Form::open(array('url' => 'exams/student/start-exam/'.$record->slug, 'method' => 'POST')) !!}
							<div class="col-md-12">
								<input type="checkbox" name="option" id="free" checked="" ng-model="agreeTerms" >
								<label for="free" > <span class="fa-stack checkbox-button"> <i class="mdi mdi-check active"></i> </span> Tôi đã đọc và hiểu các hướng dẫn nêu trên. </label>
								<br><span class="text-danger" ng-show="!agreeTerms"><strong> Vui lòng click vào đây trước khi thi </strong></span> 
								<div class="text-center">
									<button ng-if="agreeTerms" class="btn button btn-lg btn-success">Thi ngay</button>
								</div>
							</div>
							{!! Form::close() !!}
						</div>
					</div>
				</div>
			</div>
		</div>
		@endsection
		@section('footer_scripts')
		<script src="{{JS}}angular.js"></script>
		<script>
			var app = angular.module('academia', []);
			app.controller('instructions', function($scope, $http) {
			});
		</script>
<!-- <script type="text/javascript">
   $(document).on('keydown', function(event) {
    $(document).off('keydown');
    $(window).on('resize', function() {
        if ($('body').hasClass('fullscreenOn')) {
            $('body').removeClass('fullscreenOn');
            // Do functions when exiting fullscreen
            $(document).on('keydown'); // Turn keydown back on after functions
            console.log("Exit F11");
        } else {
            $('body').addClass('fullscreenOn');
            // Do functions when entering fullscreen
            $(document).on('keydown'); // Turn keydown back on after functions
            console.log("Enter F11");
        }
    });
});
</script>  -->
<script type="text/javascript">
    /*window.onload = maxWindow;
    function maxWindow() {
        window.moveTo(0, 0);
        if (document.all) {
            top.window.resizeTo(screen.availWidth, screen.availHeight);
        }
        else if (document.layers || document.getElementById) {
            if (top.window.outerHeight < screen.availHeight || top.window.outerWidth < screen.availWidth) {
                top.window.outerHeight = screen.availHeight;
                top.window.outerWidth = screen.availWidth;
            }
        }
    }*/
    $(document).ready(function(){
    	$.is_fs = false;
    	$.requestFullScreen = function(calr)
    	{
    		var element = document.body;
    // Supports most browsers and their versions.
    var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullScreen;
    if (requestMethod) { // Native full screen.
    	requestMethod.call(element);
    } else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
    	var wscript = new ActiveXObject("WScript.Shell");
    	if (wscript !== null) {
    		wscript.SendKeys("{F11}");
    	}
    }
    $.is_fs = true;    
    $(calr).val('Exit Full Screen');
}
$.cancel_fs = function(calr)
{
    var element = document; //and NOT document.body!!
    var requestMethod = element.exitFullScreen || element.mozCancelFullScreen || element.webkitExitFullScreen || element.mozExitFullScreen || element.msExitFullScreen || element.webkitCancelFullScreen;
    if (requestMethod) { // Native full screen.
    	requestMethod.call(element);
    } else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
    	var wscript = new ActiveXObject("WScript.Shell");
    	if (wscript !== null) {
    		wscript.SendKeys("{F11}");
    	}
    }    
    $(calr).val('Full Screen');    
    $.is_fs = false;
}
$.toggleFS = function(calr)
{    
	$.is_fs == true? $.cancel_fs(calr):$.requestFullScreen(calr);
}
});
    window.fullScreen = true;
</script> 
@stop
