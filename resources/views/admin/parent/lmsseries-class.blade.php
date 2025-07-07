@extends($layout)
@section('header_scripts')
<link href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css" rel="stylesheet">
<style>
    .dropdown.more .dropdown-menu li button {
        color: #a9c2ca;
        padding: 0px 15px;
        font-size: 14px;
        line-height: 40px;
        cursor: pointer;
        border: none;
        background: none;
    }
</style>
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
        <!-- Row -->
        <div class="panel panel-custom">
            <div class="panel-heading">
                <div class="pull-right messages-buttons">
                    <a href="/parent/class" class="btn  btn-primary button">Danh sách lớp</a>
                </div>
                <h1>{{ $title }}</h1>
            </div>
            <div class="panel-body packages">
                <!-- Form thêm bài thi -->
                {{ Form::model('', array('url' => 'lmsseries/class/add/' . $slug, 'method'=>'post')) }}
                <?php
                    $user_record = '';
                ?>
                <div class="sem-parent-container">
                    <div class="row">
                        <?php
                            $lmsseries_types = array(''=>'--Chọn loại khoá học--') + $lmsseries_types;
                        ?>
                        <fieldset class="form-group col-sm-4">
                            {{ Form::label('lmsseries_type', 'Chọn loại khoá học') }}
                            <span class="text-red">*</span>
                            {{Form::select('lmsseries_type', $lmsseries_types, null, [
                                'class'=>'form-control',
                                'id'=>'lmsseries_type',
                                'ng-model'=>'lmsseries_type',
                                'required'=> 'true',
                                'ng-class'=>''
                            ])}}
                        </fieldset>

                        <fieldset class="form-group col-sm-4">
                            {{ Form::label('lmsseries_list', 'Chọn khoá học') }} <span class="text-red">*</span>
                            {{Form::select('lmsseries_id', [], null, [
                                'class'=>'form-control',
                                "id"=>"lmsseries_list",
                                'required'=> 'true'
                            ])}}
                        </fieldset>

                    </div>
                    <div class="row">
                        <fieldset class="form-group col-sm-4">
                            <div class="buttons" style="display: flex;">
                                <button type="submit" class="btn btn-lg btn-success button">Thêm khoá học</button>
                                <button class="btn btn-lg btn-facebook button" onclick="openExportModal()">Xuất excel tiến trình</button>
                            </div>
                        </fieldset>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div>
                    <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Khoá học</th>
                                <th>{{ getPhrase('action')}}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <div class="modal fade" id="exportExcel" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form method="POST" action="{{ url('/lmsseries/class/export-excel') }}" accept-charset="UTF-8" novalidate="">
                            <input type="hidden" name="class_id" value="{{ $slug }}">
                            {{ csrf_field() }}
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleCommentLongTitle">XUẤT EXCEL</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <label for="business_name">Nhập tên doanh nghiệp</label>
                                <div class="form-group">
                                    <textarea class="form-control" required="true" rows="5" placeholder="Tên doanh nghiệp" name="business_name" cols="40"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Huỷ</button>
                                <button type="summit" class="btn btn-success">Xuất</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<?php $url = URL_LMSSERIES_CLASS_GET_LIST.$slug;  ?>
@section('footer_scripts')
@php
// MAKE DEFAULT VALUE COLUMN
$defaultColumns = ['title', 'action'];
@endphp

@include('admin.common.validations')
@include('admin.common.alertify')
@include('admin.parent.scripts.lms-class-scripts', ['lms_options' => $lms_options])
@include('admin.common.datatables', array('route'=>$url, 'route_as_url' => TRUE, 'table_columns' => $defaultColumns))
@include('admin.common.deletescript', array('route'=>URL_PARENT_CLASS_DELETE))
@stop