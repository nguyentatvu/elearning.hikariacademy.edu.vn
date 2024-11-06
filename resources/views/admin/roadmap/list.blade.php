@extends('admin.layouts.owner.ownerlayout')

@section('header_scripts')
<link href="{{ admin_asset('css/sweetalert2.css') }}" rel="stylesheet">
<style>
    .container-fluid {
        padding: 30px 15px;
    }
    #page-wrapper {
        background: white;
    }
    .roadmap-wrapper {
        display: flex;
        flex-direction: column;
    }
    .roadmap-day {
        width: 100%;
        min-width: 100px;
        max-width: 200px;
        min-height: 100px;
        border: 1px solid #ddd;
        display: inline-block;
        margin: 5px;
        text-align: center;
        vertical-align: top;
        cursor: pointer;
        padding: 5px;
        overflow: hidden;
        position: relative;
    }

    .roadmap-month {
        border: 2px solid #337ab7;
        padding: 10px;
        margin-bottom: 20px;
    }

    .lesson-item {
        font-size: 0.8em;
        background-color: white;
        color: #166AC9;
        border: 1px solid #166AC9;
        margin: 8px 4px;
        padding: 4px;
        border-radius: 3px;
        width: max-content;
        max-width: 184px;
        text-align: start;
    }

    .roadmap-wrapper {
        background-color: white !important;
    }

    .clearfix:after, .clearfix:before {
        content: none !important;
    }

    .list-group-item.clearfix {
        border: 1px solid #ddd !important;
        padding-left: 15px !important;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .list-group-item.clearfix:hover {
        background: #f4f4f4;
    }

    .list-group-item.active {
        border: 1px solid #166AC9 !important;
        border-bottom: 2px solid #166AC9 !important;
    }

    .badge.label-danger,
    .badge.label-success {
        padding: 6px 8px;
        height: fit-content;
        min-width: fit-content;
    }

    .month-roadmap-wrapper {
        width: 100%;
        height: fit-content;
        overflow-x: auto;
    }

    .table-calendar>tbody>tr>td {
        min-width: 152px;
        max-width: 250px;
        width: fit-content;
    }

    /* multiselect */
    .lesson-list {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 10px;
    }
    .lesson-select-item {
        cursor: pointer;
        padding: 5px;
        margin-bottom: 5px;
        border-radius: 3px;
        background-color: #f5f5f5;
        border: 1px solid transparent;
        cursor: auto;
        user-select: none;
        text-decoration: underline;
    }
    .lesson-select-item.chosable:hover {
        border: 1px solid #166AC9;
    }
    .lesson-select-item.chosable {
        background: white;
        padding: 5px 20px;
        cursor: pointer;
        text-decoration: none;
    }
    .lesson-select-item.selected {
        background-color: #d9edf7;
        border: 1px solid #166AC9;
    }
    .lesson-select-item.disabled {
        border: 1px solid #b8e7ff;
        opacity: 0.6;
        cursor: not-allowed;
    }
    .table-day, .week-table-day {
        border-top: none !important;
    }

    .week-table-day {
        vertical-align: middle !important;
        min-width: 20px !important;
    }

    .week-table-day > p {
        text-align: center;
        font-size: 16px;
    }

    .close-table-day {
        display:none;
        position: absolute;
        top: 3px;
        right: 6px;
        font-size: 24px;
        line-height: 0.6;
        padding: 1px 2px 6px 2px;
    }

    .close-table-day:hover {
        background: #f6adad;
    }

    .table-day.removable .roadmap-day{
        border: 1px solid #166AC9;
    }

    .table-day.removable:last-of-type:hover span.text-danger {
        display: inline-block !important;
    }
    /* end multiselect */

    .view-roadmap-btn {
        padding: 4px 8px !important;
    }

    .roadmap-course-item {
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 4px;
        overflow: hidden;
    }
    .roadmap-course-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    .roadmap-course-info {
        padding: 15px;
    }
    .roadmap-course-title {
        margin-top: 0;
        margin-bottom: 10px;
    }
    .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 3px;
        font-weight: bold;
    }
    .status-active {
        background-color: #2ecc71;
        color: white;
    }
    .status-inactive {
        background-color: #e74c3c;
        color: white;
    }

    .roadmap-title {
        margin-left: 14px;
        padding: 4px 6px;
        color: #166AC9;
        border: 1px solid #166AC9;
        border-radius: 4px;
        font-size: 16px;
    }

    .roadmap-status {
        margin-left: 14px;
        padding: 4px 6px;
        background-color: #438afe;
        color: white;
        border-radius: 4px;
        font-size: 16px;
    }

    .list-group-item.active {
        background-color: #438afe !important;
    }

    .list-group-item.active>.badge {
        color: #438afe !important;
    }
</style>
@endsection

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="roadmap-wrapper">
            <!-- Section 1: Course List -->
            <h3 style="width: fit-content; margin-bottom: 0;">Danh sách khoá học</h3>
            <div class="row">
                <div class="col-md-6">
                    <h4>Khoá học</h4>
                    <ul id="courseList" class="list-group">
                        @foreach ($course_series_list as $series)
                            <li class="list-group-item clearfix" role="button" data-course-id="{{ $series['id'] }}" onclick="showCourseRoadmaps({{ $series['id'] }})">
                                <span>{{ $series['title'] }}</span>
                                @if (count($series['roadmaps']))
                                    <div class="badge pull-right label-success">{{ count($series['roadmaps']) }} lộ trình</div>
                                @else
                                    <span class="badge pull-right label-danger">Không lộ trình</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-6">
                    <h4>Khoá học luyện thi</h4>
                    <ul id="courseList" class="list-group">
                        @foreach ($exam_series_list as $series)
                            <li class="list-group-item clearfix" role="button" data-course-id="{{ $series['id'] }}" onclick="showCourseRoadmaps({{ $series['id'] }})">
                                <span>{{ $series['title'] }}</span>
                                @if (count($series['roadmaps']))
                                    <div class="badge pull-right label-success">{{ count($series['roadmaps']) }} lộ trình</div>
                                @else
                                    <span class="badge pull-right label-danger">Không lộ trình</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <!-- Section 2: Roadmap Details -->
            <div class="col-md-12 d-none" id="roadmapDetailsWrapper">
                <div class="d-flex" style="align-items: center; gap: 10px; padding: 10px 0; justify-content: space-between;" id="roadmap_info">
                    <div class="d-flex" style="align-items: center;">
                        <h3 style="width: fit-content; margin-right: 10px; margin: 0;">Chi tiết lộ trình:&nbsp;</h3>
                        <span id="roadmapTitle" class="roadmap-title"></span>
                        <span class="roadmap-status">
                            Số ngày:&nbsp;
                            <strong id="roadmapDayCount">60</strong>&nbsp;
                            Số buổi học:&nbsp;
                            <strong id="roadmapLessonDayCount">40</strong>
                        </span>
                    </div>
                    <div class="d-flex" style="gap: 10px;">
                        <span class="btn" id="deleteRoadmapBtn" style="height: fit-content; background: #f16a43; color: white;">
                            <i class="fa fa-fw fa-trash" style="vertical-align: middle; color: white;"></i>
                            Xoá lộ trình
                        </span>
                        <span class="btn btn-primary" style="height: fit-content;" onclick="saveRoadmap()">Lưu lộ trình</span>
                    </div>
                </div>
                <div id="roadmapDetails">
                    <!-- Roadmap details will be shown here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Course Roadmaps Modal -->
    <div class="modal fade" id="courseRoadmapsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Danh sách lộ trình của khoá học</h4>
                </div>
                <div class="modal-body">
                    <ul id="roadmapList" class="list-group">
                        <!-- Roadmaps will be listed here -->
                    </ul>
                    <div class="form-group" style="margin-top: 15px;">
                        <select id="newRoadmapDuration" class="form-control">
                            <!-- Duration months will be populated here -->
                        </select>
                    </div>
                    <button id="addNewRoadmap" class="btn btn-primary">Tạo lộ trình mới</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Lesson Modal -->
    <div class="modal fade" id="addLessonModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Thêm bài học cho ngày</h4>
                </div>
                <div class="modal-body">
                    <div id="lessonSelect" class="lesson-list">
                        <!-- Lessons will be populated here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveLessonToDay">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer_scripts')
<script>
    let lessons = @json($lessons);
    let courseSeriesList = @json($course_series_list);
    let examSeriesList = @json($exam_series_list);
    let courses = [
        ...Object.values(courseSeriesList),
        ...Object.values(examSeriesList)
    ];
    let roadMapDetailsList = @json($all_roadmaps);
    let lessonTypeMap = @json($lesson_type_map);

    const contentChapterType = {{ \App\LmsContent::LESSON }};
    const contentTopicType = {{ \App\LmsContent::LESSON_TOPIC }};

    const SAVE_ROADMAP_URL = "{{ route('roadmap.save-roadmap') }}";
    const DELETE_ROADMAP_URL = "{{ route('roadmap.delete-roadmap') }}";
</script>
<script src="{{ admin_asset('js/roadmap/index.js') }}"></script>
<script src="{{ admin_asset('js/sweetalert2.js') }}"></script>
@endsection