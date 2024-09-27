@extends($layout)
@section('header_scripts')
    <link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
    <link href="{{admin_asset('css/datepicker.css')}}" rel="stylesheet">
@stop
@section('content')
    <div id="page-wrapper" ng-controller="payments_report">
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
                    <div class="pull-right">
                        <button id="export" class="btn btn-primary button" type="button" data-toggle="modal"
                        data-target="#export_modal" disabled>EXPORT</button>
                    </div>
                    <h1>{{ $title }}</h1>
                </div>
                <div class="panel-body packages">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Họ tên</th>
                                    <th>HID</th>
                                    <th>Khóa học</th>
                                    <th>Hoàn thành</th>
                                    <th>Tiến trình</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
        <!-- Modal -->
    </div>
    @include('admin.payments.order.export-modal')
@endsection
@section('footer_scripts')
    @php
        // MAKE DEFAULT VALUE COLUMN
        $defaultColumns = [
            'stt', 'name', 'username', 'courses', 'total_course', 'progress'
        ];
	@endphp
    @include('admin.common.datatables', array('route'=>url('payments-process/getList'), 'route_as_url' => TRUE, 'table_columns' => $defaultColumns))
    @include('admin.payments.scripts.js-scripts');
    @include('admin.common.loading-dialog');
@stop
