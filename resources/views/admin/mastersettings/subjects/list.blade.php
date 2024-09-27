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

								

				<!-- /.row -->

				<div class="panel panel-custom">

					<div class="panel-heading">

						

						<div class="pull-right messages-buttons">

							 

							<!-- <a href="{{URL_SUBJECTS_IMPORT}}" class="btn  btn-primary button" >{{ getPhrase('import')}}</a> -->

							<a href="{{URL_SUBJECTS_ADD}}" class="btn  btn-primary button" >{{ getPhrase('create')}}</a>

							 

						</div>

						<h1>{{ $title }}</h1>

					</div>

					<div class="panel-body packages">

						<div > 

						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">

							<thead>

								<tr>

									 

									<th>{{ getPhrase('id')}}</th>

									<th>Mondai</th>

									<th>Câu hỏi mondai</th>

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
	'id', 'subject_title', 'subject_code', 'action'
];
@endphp

@include('common.datatables', array('route'=>'subjects.dataTable', 'table_columns' => $defaultColumns))

@include('common.deletescript', array('route'=>URL_SUBJECTS_DELETE))

@stop

