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
                        <li>
                            <a href="{{URL_ADMIN_DASHBOARD}}">
                                <i class="mdi mdi-home">
                                </i>
                            </a>
                        </li>
                        <li>
                            {{ $title }}
                        </li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <div class="pull-right messages-buttons">
                        {{-- <a class="btn btn-primary button" href="{{URL_QUESTIONBAMK_IMPORT}}">
                            {{ getPhrase('import_questions')}}
                        </a> --}}
                        <a class="btn btn-primary button" href="/lms/flashcard/add">
                            Thêm mới
                        </a>
                    </div>
                    <h1>
                        {{ $title }}
                    </h1>
                </div>
                <div class="panel-body packages">
                    <div>
                        <table cellspacing="0" class="table table-striped table-bordered datatable" width="100%">
                            <thead>
                                <tr>
                                    <th>
                                        Flashcard
                                    </th>
                                    <th>
                                        {{ getPhrase('action')}}
                                    </th>
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
            'name', 'action'
        ];
        @endphp
        @include('admin.common.datatables', array('route'=> '/lms/flashcard/getList', 'route_as_url' => 'TRUE', 'table_columns' => $defaultColumns))
        @include('admin.common.deletescript', array('route'=> '/lms/flashcard/delete/'))
    @stop
</link>