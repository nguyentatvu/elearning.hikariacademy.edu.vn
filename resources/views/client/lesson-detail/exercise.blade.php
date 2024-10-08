@extends('client.shared.lesson-detail')

@section('lesson-detail-styles')
    <link rel="stylesheet" href="{{ asset('css/pages/lesson-detail/exercise-lesson.css') }}">
@endsection

@section('lesson-detail-content')
<style>
</style>
<div class="w-100 exercise-content">
    <!--Coursed Description-->
    <div class="card overflow-hidden">
        <div class="card-body">
            <div class="exercise-header item-det mb-4 d-flex justify-content-between align-items-center">
                <a class="text-dark text-primary" href="#">
                    <h3 class="fw-semibold">
                        {{ $detailContent->bai }}
                    </h3>
                </a>
                <div class="d-flex exercise-score-container ms-3 gap-3 fw-bold">
                    <div class="score float-right d-flex align-items-center">
                        <i aria-hidden="true" class="bi bi-question fs-5 text-primary"></i>
                        <span class="cau">1</span>
                        <span>/{{$count_records}} câu</span>
                    </div>
                    <div class="score float-right d-flex align-items-center">
                        <i aria-hidden="true" class="bi bi-star-fill text-primary"></i>
                        <span class="total-les ms-1">0</span>
                        <span>/{{$count_records}} điểm</span>
                    </div>
                </div>
            </div>
            <div class="product-slider" id="edm_player_zone">
                <div class="row lecture-player-main no-margin ">
                    <!-- Header Exercise -->
                    <!-- Body exercise -->
                    <div class="kg-study kg-study-cuttom">
                        <div id="data-exercise-hid">
                        </div>
                    </div>
                    <!-- Footer exercise-->
                    <div class="wp-btn-progress-les">
                        <div class="progress-les">
                            <div class="wp-ct-prg">
                                <div class="bar-prg">
                                    <div class="main-bar" style="width: 0%;"></div>
                                </div>
                                <div class="wp-number-prg d-flex justify-content-between">
                                    <div></div>
                                    <div class="item-prg text-center cpl-status">
                                        <span class="circle"></span>
                                    </div>
                                    <div class="item-prg text-center cpl-status">
                                        <span class="circle"></span>
                                    </div>
                                    <div class="item-prg text-center cpl-status">
                                        <span class="circle"></span>
                                    </div>
                                    <div class="item-prg text-center cpl-status">
                                        <span class="circle opacity-0"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="btn-result">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-10 offset-xl-1">
                                        <div class="ct-btn-result d-flex justify-content-between align-items-center">
                                            <div class="left-ct">
                                                <div class="result-ntf">
                                                </div>
                                            </div>
                                            <div class="right-ct">
                                                <div class="btn-group-les">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('lesson-detail-scripts')
    @include('client.lesson-detail.exercise-js',array('exercise' => $records))
@endsection