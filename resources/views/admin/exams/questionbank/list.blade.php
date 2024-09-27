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

					<li>{{ SITE_URL }}</li>

				</ol>

			</div>

		</div>

		<!-- /.row -->

		<div class="panel panel-custom">

			<div class="panel-heading">

				<div class="pull-right messages-buttons">

							<!-- <a href="{{URL_QUESTIONBAMK_IMPORT}}" class="btn  btn-primary button" >{{ getPhrase('import_questions')}}</a>

								<a href="{{URL_SUBJECTS_ADD}}" class="btn  btn-primary button" >{{ getPhrase('add_subject')}}</a> -->

							</div>

							<h1>{{ $title }}</h1>

						</div>

						<div class="panel-body packages">

							<div> 

								<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">

									<thead>

										<tr>

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
				'subject_title', 'subject_code', 'action'
			];
			@endphp

			@include('common.datatables', array('route'=> URL_QUESTIONBANK_GETLIST, 'route_as_url' => 'TRUE', 'table_columns' => $defaultColumns))

			@include('common.deletescript', array('route'=> URL_QUESTIONBANK_DELETE))

			@stop