@extends('admin.layouts.admin.adminlayout')
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
                        <li><a href="{{PREFIX}}">Khóa combo</a> </li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <div class="pull-right messages-buttons">
                        <a href="{{$create_url}}" class="btn btn-primary button" >{{ getPhrase('create')}}</a>
                    </div>
                </div>
                <div class="panel-body packages">
                    <div>
                        <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên</th>
                                <th>Giá</th>
                                <th>Selloff</th>
                                <th>Số coin quy đổi</th>
                                <th>Thời gian áp dụng Selloff</th>
                                <th>Thời gian</th>
                                <th>Loại</th>
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
        'stt', 'title', 'cost', 'selloff', 'redeem_point', 'timefrom', 'time', 'type', 'action'
    ];
    @endphp
    @include('admin.common.datatables', array('route'=>$datatbl_url, 'route_as_url' => true, 'table_columns' => $defaultColumns))
    @include('admin.common.deletescript', array('route'=>PREFIX.'lms/seriescombo/delete/'))
@stop
