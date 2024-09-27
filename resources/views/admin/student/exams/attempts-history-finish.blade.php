
@extends('admin.layouts.student.studentsettinglayout')
@section('content')
<div class="card mb-0">
    <div class="card-header">
        <h3 class="card-title">
            {{$title}}
        </h3>
    </div>
    <div class="card-body">
        <div class="manged-ad table-responsive border-top userprof-tab">
            <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Bộ đề thi</th>
                        <th>Điểm từng phần thi</th>
                        <th style="text-align: center;">Tổng điểm <span style="font-size: 10px"><br/>(đã nhân hệ số)/180</span></th>
                        <th>Kết quả</th>
                        <th>Xem đáp án</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@stop

<style>
div#DataTables_Table_0_wrapper .row:nth-child(1), div#DataTables_Table_0_wrapper .row:nth-child(3) {
    display: none;
}
</style>


@section('footer_scripts')
    @if(!$exam_record)
    @include('admin.common.datatables', array('route'=>URL_STUDENT_EXAM_GETATTEMPTS_FINISH.$user->slug, 'route_as_url' => 'TRUE'))
    @else
    @include('admin.common.datatables', array('route'=>URL_STUDENT_EXAM_GETATTEMPTS_FINISH.$user->slug.'/'.$exam_record->slug, 'route_as_url' => 'TRUE'))
    @endif
@stop
