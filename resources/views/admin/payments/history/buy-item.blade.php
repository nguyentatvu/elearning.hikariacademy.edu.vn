@extends($layout)
@section('content')<div id="page-wrapper">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
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
                <h1>{{ $title }}  </h1>
            </div>
            <div class="panel-body"> 
                <!-- Show table list hoc viên  -->
                <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Khóa học</th>
                            <th>Giá (Xu)</th>
                            <th>Ngày mua</th>
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
    @php
        // MAKE DEFAULT VALUE COLUMN
        $defaultColumns = [
            'item_name', 'cost', 'created_at'
        ];
    @endphp

    @include('admin.common.validations')
    @include('admin.common.alertify')
    @include('admin.classes.scripts.js-scripts')
    <script src="{{JS}}bootstrap-toggle.min.js"></script>
    <script src="{{JS}}jquery.dataTables.min.js"></script>
    <script src="{{JS}}dataTables.bootstrap.min.js"></script>
    @include('admin.common.datatables', array('route'=> PREFIX.'payments-report/buy-item', 'route_as_url' => 'TRUE', 'table_columns' => $defaultColumns))
    @include('admin.common.deletescript', array('route'=> URL_CLASSES_USER_DELETE))
@stop