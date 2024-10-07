@extends('admin.layouts.' . getRole() . '.' . getRole() . 'layout')
@section('header_scripts')
    <link href="{{ CSS }}ajax-datatables.css" rel="stylesheet">

    <style>
        .tr-head {
            pointer-events: none;
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
                        <li>
                            <a href="{{ PREFIX }}">
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
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <div class="pull-right messages-buttons">
                        <a class="btn btn-primary button" href="/lms/handwriting/add">
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
                                    <th class="tr-head">
                                        Luyện viết
                                    </th>
                                    <th class="tr-head">
                                        Loại
                                    </th>
                                    <th class="tr-head">
                                        {{ getPhrase('action') }}
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer_scripts')
    @php
        // MAKE DEFAULT VALUE COLUMN
        $defaultColumns = ['title', 'type', 'action'];
    @endphp
    @include('admin.common.datatables', [
        'route' => route('lms.handwriting.list'),
        'route_as_url' => 'TRUE',
        'table_columns' => $defaultColumns,
    ])
    @include('admin.common.deletescript', ['route' => '/lms/handwriting/delete/'])
@stop
</link>
