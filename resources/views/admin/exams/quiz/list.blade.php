		@extends('admin.layouts.'.getRole().'.'.getRole().'layout')
		@section('header_scripts')
		<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
		@stop
		@section('content')
		<div id="page-wrapper">
		<div class="container-fluid">
		<!-- Page Heading -->
		<div class="row">	
			<div class="col-lg-12">
				<ol class="breadcrumb">
					<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
					<li>{{ $title }}</li>
				</ol>
			</div>
		</div>

		<!-- <div>
			
		    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.2/angular.min.js"></script>
		    <script src="https://cdn.jsdelivr.net/g/jquery@1,jquery.ui@1.10%28jquery.ui.core.min.js+jquery.ui.widget.min.js+jquery.ui.mouse.min.js+jquery.ui.sortable.min.js%29,angularjs@1.2,angular.ui-sortable"></script>
		
		    <div class="container">
			    <div ng-controller="mainController" >
			        
			        <table class="table table-bordered">
			            <tr>
			                <th>Website List</th>
			            </tr>
			            <tbody ui-sortable ng-model="items">
			            <tr ng-repeat="item in items">
			                <td>@{{ item }}</td>
			            </tr>
			            </tbody>
			        </table>
			    </div>
			</div>
		
			<script type="text/javascript">
			    var myApp = angular.module("academia", ['ui.sortable']);        
		
		
			    myApp.controller("mainController", function($scope) {
			      $scope.items = ["ItSolutionStuff.com", "Demo.ItSolutionStuff.com", "HDTuto.com", "NiceSnippets.com"];
		
		
			      $scope.sortableOptions = {
			        update: function(e, ui) { 
			            console.log(e);
			        },
			        axis: 'x'
			      };
			    });
			</script>
		</div>
		 -->
		<!-- /.row -->
		<div class="panel panel-custom">
			<div class="panel-heading">
				<div class="pull-right messages-buttons">
					<a href="{{URL_QUIZ_ADD}}" class="btn  btn-primary button" >{{ getPhrase('create')}}</a>
				</div>
						<!-- <div class="pull-right messages-buttons">
							<a href="{{URL_EXAM_SERIES}}" class="btn  btn-primary button" >{{ getPhrase('create_series')}}</a>
						</div> -->
						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">
						<div> 
							<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>Đề thi</th>
										<th>Thời gian</th>
										<th>Danh mục</th>
										<th>Loại</th>
										<th>Tổng điểm</th>
										<!-- <th>{{ getPhrase('exam_type')}}</th> -->
										<th>{{ getPhrase('action')}}</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
		@endsection
		@section('footer_scripts')
		@php
		// MAKE DEFAULT VALUE COLUMN
		$defaultColumns = [
			'title', 'dueration', 'category', 'type', 'total_marks', 'action'
		];
		@endphp
		@include('admin.common.datatables', array('route'=>URL_QUIZ_GETLIST, 'route_as_url' => TRUE, 'table_columns' => $defaultColumns))
		@include('admin.common.deletescript', array('route'=>URL_QUIZ_DELETE))
		@stop
