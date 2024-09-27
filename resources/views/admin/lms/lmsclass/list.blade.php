@extends($layout)
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
					<li><a href="{{url('/')}}"><i class="mdi mdi-home"></i></a> </li>
					<li>{{ $title }}</li>
				</ol>
			</div>
		</div>
		
		<!-- /.row -->
		<div class="panel panel-custom">
			<div class="panel-heading">
				
				<div class="pull-right messages-buttons">
					
					<a href="{{$URL_LMS_CLASS_ADD}}" class="btn  btn-primary button" >{{ getPhrase('Thêm khóa học')}}</a>
					
				</div>
				<h1>{{ $title }}</h1>
			</div>
			<div class="panel-body packages">
				<div> 
					<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>{{ getPhrase('Tên lớp')}}</th>
								<th>{{ getPhrase('Tên khóa học')}}</th>
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

@include('admin.common.datatables', array('route'=>$url_datatable, 'route_as_url' => true))

@stop
