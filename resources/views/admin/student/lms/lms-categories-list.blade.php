<?php

$dr_trinhdo = array(

		1 =>
'N1',

		2=> 'N2',

		3=> 'N3',

		4=> 'N4',

		5=> 'N5',

		6=> 'Chữ cái',



)
?>

@extends($layout)

@section('content')
<style>
    .modal-dialog {
        max-width: 800px;
        width: 800px;
    }

    body:has(.modal.fade.show)::-webkit-scrollbar{
        display: none;
    }

    .qa-course-detail {
        color: #333;
        line-height: 1.25;
        margin-left: 20px;
    }

    .section-title {
        margin-bottom: 20px;
        font-size: 24px;
        font-weight: 700;
        padding-bottom: 0px;
    }

    .view-all-qa {
        font-size: 16px;
        border: 0;
        border-radius: 3px;
        padding: 4px;
        padding-right: 8px;
        padding-left: 8px;
    }

    .qa-lesson {
        margin-bottom: 30px;
    }

    .qa-lesson button {
        width: 100%;
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 20px;
        border-width: 0;
        display: flex;
        justify-content: space-between;
        background-color: transparent;
        align-items: center;
    }

    .qa-lesson-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .qa-lesson .qa-content {
        background-color: #f9f9f9;
        border-radius: 5px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .qa-lesson .qa-content h2 {
        font-size: 20px;
        font-weight: 600;
        margin-top: 0;
        color: #1a0267;
    }

    .qa-lesson .qa-content p {
        margin: 0;
        color: #666;
        font-size: 1rem;
    }

    .qa-lesson .char-before {
        font-size: 18px;
        font-weight: 600;
    }

    .targeted-unit-lesson {
        display: flex;
        gap: 10px;
        font-size: 18px;
        display: inline-block;
    }

    .targeted-unit-lesson a {
        color: #ec296b;
        font-size: 1rem;
    }

    .targeted-unit-lesson a:hover {
        text-decoration: underline;
    }
</style>
<div class="card mb-0">
    <div class="card-header">
        <h3 class="card-title">
            {{$title}}
        </h3>
    </div>
    <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center align-middle" style="width: 5%;">
                            STT
                        </th>
                        <th style="width: 20%;">
                            {{$title}} của bạn
                        </th>
                        <th class="text-center align-middle" style="width: 5%;">
                            LỘ TRÌNH
                        </th>$
                        <th class="text-center align-middle" style="width: 10%;">
                            Trình độ
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($series) > 0)
                    @foreach($series as $r)
                    <tr>
                        <td class="text-center align-middle" style="width: 5%;">
                            {{$loop->index+1}}
                        </td>
                        <td style="width: 20%;">
                            <div class="media mt-0 mb-0">
                                <div class="card-aside-img">
                                    <img alt="{{$r->title}}" src="{{ '/public/uploads/lms/combo/'.$r->combo_image}}" style="height: auto;"></img>
                                    <button type="button" class="btn btn-primary mb-xl-0" onclick="loadSeriesModal('{{ $r->slug }}', '{{ $r->combo_slug }}')" data-toggle="modal" data-target="#series_detail_modal_{{ $r->slug }}">
                                        <i class="fa fa-info mr-1"></i>
                                        Tổng quan
                                    </button>
                                </div>
                                <div class="media-body">
                                    <div class="card-item-desc ml-4 p-0 mt-2">
                                        <?php $dr_time  = array(0 => '3 tháng' , 1 =>'6 tháng' , 2 => '12 tháng')?>
                                        <a class="text-dark" href="#">
                                            <h4 class="font-weight-semibold">
                                                {{$r->title}} ({{$dr_time[$r->time]}})
                                            </h4>
                                        </a>
                                        <a href="#">
                                            Ngày mua: {{date_format(date_create($r->created_at),"d-m-Y")}}
                                        </a>
                                        <br>
                                        <?php $dr_time  = array(0 => 90 , 1 =>180 , 2 => 365)?>
                                        <?php $dr_day  = array(0 => 0, 1 => 30, 3 => 90, 6 => 180, 12 => 365) ?>
                                        <a href="#">
                                            Ngày hết hạn:
                                            {{date_format(date_add(date_create($r->created_at),date_interval_create_from_date_string($dr_time[$r->time]
                                            + $dr_day[$r->month_extend]." days")),"d-m-Y")}}
                                        </a>
                                        </br>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center align-middle" style="width: 5%;">Text</td>
                        <td class="text-center align-middle" style="width: 10%;">
                            <div class="media mt-0 mb-0">
                                <div class="media-body">
                                    <div class="card-item-desc ">
                                        <p class="mb-2">
                                            <span class="fs-14 ml-2">
                                                <i class="fa fa-star text-yellow mr-2">
                                                </i>
                                                Hoàn thành: {{$r->current_course}}/{{$r->total_course}} bài học
                                            </span>
                                        </p>
                                        <?php
                                        $percent = 0;
                                        if($r->current_course > 0) {
                                            $percent =	(int)ceil((($r->current_course/$r->total_course)*100));
                                        }
                                        ?>
                                        <div class="progress position-relative">
                                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                                role="progressbar" style="width: {{$percent}}%" aria-valuenow="{{$percent}}"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                            <small class="justify-content-center d-flex position-absolute w-100">
                                                {{$percent}}%
                                            </small>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center align-middle" style="width: 5%;">
                            <?php $dayEnd  = date_format(date_add(date_create($r->created_at),date_interval_create_from_date_string($dr_time[$r->time] + $dr_day[$r->month_extend]." days")),"d-m-Y") ?>
                            @if($r->status == 3)
                            <p>Vô hiệu</p>
                            @elseif(strtotime($dayEnd) < strtotime(date("d-m-Y"))) <p>Hết hạn</p>
                                @elseif(strtotime($dayEnd) > strtotime(date("d-m-Y")))
                                <a class="btn btn-primary mb-3 mb-xl-0"
                                    href="{{PREFIX.'learning-management/lesson/show/'.$r->combo_slug.'/'.$r->slug}}">
                                    <i class="fa fa-leanpub mr-1">
                                    </i>
                                    Học ngay
                                </a>
                                @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="5">
                            <h5 style="color: #ee2833!important">
                                Bạn chưa có {{$title}}
                            </h5>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        <!-- Modal -->
        <!--End Modal -->
    </div>
</div>
<script>
    function toggleQA(event) {
        const btnToggle = $(event.target);
        btnToggle.closest('.qa-lesson').find('.qa-lesson-container').slideToggle();
    }

    function showAllQA() {
        $('.qa-lesson-container').show();
    }

    $('.modal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })

    function loadSeriesModal(seriesSlug, comboSeriesSlug) {
        $lmsSeriesModal = $('#series_detail_modal_' + seriesSlug);
        if($lmsSeriesModal.length > 0) {
            $lmsSeriesModal.modal('show');
        } else {
            $.ajax({
                url: '{{ route('lms.series.overview-content') }}' + `?lms_combo_series_slug=${comboSeriesSlug}&lms_series_slug=${seriesSlug}`,
                type: 'GET',
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                beforeSend: function () {
                    showLoadingSpinner();
                },
                success: function (response) {
                    $('.card-body table').after(response);
                    $('#series_detail_modal_' + seriesSlug).modal('show');
                },
                error: function (response) {
                    swal({
                            title: 'Thông báo',
                            text: 'Tạm thời không thể xem tổng quan khóa học, vui lòng thử lại sau.',
                            type: 'warning',
                            showConfirmButton: false,
                            showCancelButton: false,
                            timer: 1500,
                        });
                },
                complete: function () {
                    closeLoadingSpinner();
                }
            });
        }
    }
</script>
@stop
