@extends($layout)
@section('content')<div id="page-wrapper">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{URL_ADMIN_DASHBOARD}}"><i class="mdi mdi-home"></i></a> </li>
                    @if(checkRole(getUserGrade(2)))
                    <li><a href="{{URL_USERS}}">Thanh toán</a> </li>
                    <li><a href="{{URL_USERS}}">Online</a> </li>
                    <li class="active">{{isset($title) ? $title : ''}}</li>
                    @else
                    <li class="active">{{$title}}</li>
                    @endif
                </ol>
            </div>
        </div>
        @include('admin.errors.errors')
        <!-- /.row -->
        <div class="panel panel-custom " ng-controller="users_controller">
            <div class="panel-heading">
                @if(checkRole(getUserGrade(2))) 
                <div class="pull-right messages-buttons"><a href="/payments-report/online" class="btn  btn-primary button" >Thanh toán Online</a></div>
                @endif
                <h1>{{ $title }}  </h1> {{ getPhrase('dashboard') }}
            </div>
            <div class="panel-body"> 
                <!-- Show table list hoc viên  -->
                <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên học viên</th>
                            <th>Khóa</th>
                            <th>Mã thanh toán</th>
                            <th>Số tiền (VNĐ)</th>
                            <th>Phương thức</th>

                            <th>Ngày thanh toán</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
@endsection
@section('footer_scripts')
    @include('admin.common.validations')
    @include('admin.common.alertify')
    @include('admin.classes.scripts.js-scripts')
    <script src="{{JS}}bootstrap-toggle.min.js"></script>
    <script src="{{JS}}jquery.dataTables.min.js"></script>
    <script src="{{JS}}dataTables.bootstrap.min.js"></script>
    @php
        // MAKE DEFAULT VALUE COLUMN
        $defaultColumns = [
            'stt', 'name', 'orderInfo', 'orderId', 'amount', 'orderType', 'created_at'
        ];
    @endphp
    @include('admin.common.datatables', array('route'=> PREFIX.'payments-report/index-online', 'route_as_url' => 'TRUE', 'excel'=>'0,1,2,3,4,5,6', 'table_columns' => $defaultColumns))
    @include('admin.common.deletescript', array('route'=> URL_CLASSES_USER_DELETE))
@stop