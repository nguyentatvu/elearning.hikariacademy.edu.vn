@extends('client.shared.lesson-detail')

@section('styles-content')
    <link href="{{ asset('css/pages/lesson-detail/audit.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/exercise/audit.css') }}">
    <style>
        .checkmark i {
            margin: 0;
        }
    </style>
@endsection

@section('lesson-detail-content')
    @php
        $array_char = ['a', 'b', 'c', 'd'];
        $headerBlock = '
        <div class="kg-study kg-study-cuttom">
            <div id="data-exercise-hid">
                <div class="col-12">
                    <div class="ct-lesson ct-lesson17">';
        $footerBlock = '</div></div></div></div>';
    @endphp

    <section class="sptb">
        <!-- Course Description -->
        <div class="card overflow-hidden">
            <div>
                <div class="item-det mb-4">
                    {{-- Course Title --}}
                </div>
                @if (isset($records))
                    {{ Form::model(null, ['url' => 'learning-management/lesson/audit/' . $combo_slug . '/' . $slug . '/' . $stt, 'method' => 'post', 'novalidate' => '', 'name' => 'formTest', 'files' => 'false']) }}
                    {!! Form::hidden('content_id', $slug) !!}
                    {!! Form::hidden('time', null) !!}

                    <div class="wp-content-les audit-show">
                        @php $flat = true; @endphp
                        @foreach ($records as $key => $record)
                            {!! $headerBlock !!}
                            @if ($record->dang == 7 && $flat)
                                <div class="paragraph-les paragraph-les-cuttom jp-font vue-sticky-el">
                                    {!! $record->mota !!}
                                </div>
                                @php $flat = false; @endphp
                            @endif

                            <div class="wp-block-type d-flex justify-content-between position-relative">
                                <div
                                    class="block-type block-type-cuttom {{ isset($record->correct) ? ($record->correct == 1 ? 'correct-status' : 'incorrect-status') . ' none-clicks' : '' }}">
                                    <div class="title-block-type" style="color: #fff;">
                                        <a>Câu số {{ $record->cau }}</a>
                                    </div>
                                    @if ($record->dang != 7)
                                        <div class="text-question-les jp-font" style="text-align: left; margin-bottom: 0;">
                                            <p class="text-primary"><strong>{!! $record->mota !!}</strong></p>
                                        </div>
                                    @endif
                                    <div class="list-select-les">
                                        @foreach ($record->answers as $keyanswers => $answers)
                                            <div
                                                class="item-check-select {{ $record->display == 1 ? 'width-25' : '' }} {{ isset($record->check) && $record->dapan == (int) $keyanswers + 1 ? 'correct-answer' : '' }} {{ isset($record->check) && $record->check == (int) $keyanswers + 1 ? (isset($record->correct) ? ($record->correct == 1 ? 'correct-answer' : 'incorrect-answer') : '') : '' }}">
                                                <div class="form-check">
                                                    <span class="font-weight-bold">{!! $array_char[$keyanswers] !!}</span>
                                                    <input type="radio" name="quest_{{ $record->id }}"
                                                        id="answers_{{ $record->cau }}_{{ $keyanswers }}"
                                                        class="form-check-input" value="{{ (int) $keyanswers + 1 }}"
                                                        {{ isset($record->check) && $record->check == (int) $keyanswers + 1 ? 'checked' : '' }}>
                                                    <label for="answers_{{ $record->cau }}_{{ $keyanswers }}"
                                                        class="form-kana text-type">
                                                        <span class="fa-stack icon-input icon-incorrect">
                                                            <i class="bi bi-x-square"></i>
                                                        </span>
                                                        <span class="icon-input icon-no-checked">
                                                            <i class="bi bi-square"></i>
                                                        </span>
                                                        <span class="icon-input icon-checked">
                                                            <i class="bi bi-check-square"></i>
                                                        </span>
                                                        <span class="icon-input icon-correct">
                                                            <i class="bi bi-check-square"></i>
                                                        </span>
                                                        <span class="text-label jp-font">
                                                            <p>{!! $answers !!}</p>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            {!! $footerBlock !!}
                        @endforeach
                    </div>

                    <div class="wp-btn-progress-les">
                        <div class="progress-les">
                            <div class="wp-ct-prg">
                                <div class="bar-prg">
                                    <div class="main-bar" style="width: 100%;"></div>
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
                        <div class="btn-result pt-0">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="ct-btn-result d-flex justify-content-between">
                                            <div class="left-ct">
                                                <div class="result-ntf">
                                                    <span class="mr-3"
                                                        style="font-weight: 700; font-size: 14px; color: #625f6f;">Thời gian
                                                        còn lại:</span>
                                                    <div id="timerdiv"
                                                        class="d-inline font-weight-bold style-countdown text-success">
                                                        <span id="mins">00</span> : <span id="seconds">00</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="right-ct">
                                                <div class="btn-group-les">
                                                    @if (!isset($totalValue))
                                                        <button type="submit" class="btn-nav-les btn-check-les bg-primary"
                                                            style="border: none; cursor: pointer; outline: none;">
                                                            Nộp bài&nbsp;<i class="fa fa-cloud-upload"
                                                                aria-hidden="true"></i>
                                                        </button>
                                                    @else
                                                        @if ($passed == 1)
                                                            <a href="{{ $sendUrl }}"
                                                                class="btn-nav-les btn-result-corect-les">
                                                                Bài tiếp&nbsp;<i class="fa fa-angle-double-right"
                                                                    aria-hidden="true"></i>
                                                            </a>
                                                        @else
                                                            <a href="{{ PREFIX }}learning-management/lesson/audit/{{ $combo_slug }}/{{ $slug }}/{{ $stt }}"
                                                                class="btn-nav-les finish-les">
                                                                Làm lại&nbsp;<i class="fa fa-refresh fa-spin"
                                                                    aria-hidden="true"></i>
                                                            </a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                @endif
            </div>
        </div>
        <div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        @if (isset($point))
                            <div class="ct-cpl-screen">

                                <div class="info-cpl text-center">

                                    {{--   @php
                                    if (!$value){$value = 0;}
                                @endphp --}}
                                    <h4 class="above-text text-primary">Kết quả: {{ $totalValue }} / {{ $point }}
                                        điểm</h4>

                                    {{-- <h4 class="below-text text-danger">Tổng:  điểm</h4> --}}

                                    <h5 class="below-text {{ $passed == 0 ? 'text-danger' : 'text-success' }}">
                                        {{ $passed == 0 ? 'Chưa đạt yêu cầu bài kiểm tra' : 'Đạt yêu cầu bài kiểm tra' }}
                                    </h5>

                                    <div class="score-bg-title"><img
                                            src="{{ admin_asset('images/exercise/score-bg.png') }}" alt=""></div>

                                </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts-content')
    <script>
        $(document).ready(function() {
            $("#accordian a").click(function() {
                let link = $(this);
                let closest_ul = link.closest("ul");
                let parallel_active_links = closest_ul.find(".active");
                let closest_li = link.closest("li");
                let link_status = closest_li.hasClass("active");
                let count = 0;

                closest_ul.find("ul").slideUp(function() {
                    if (++count === closest_ul.find("ul").length) {
                        parallel_active_links.removeClass("active");
                    }
                });

                if (!link_status) {
                    closest_li.children("ul").slideDown();
                    closest_li.addClass("active");
                }
            });

            let _elementActive = $('li.lesson_active');
            if (_elementActive.find('ul').length === 0) {
                _elementActive.parent().parent().children().children().css('color', 'white');
                _elementActive.parent().parent().addClass("active");
                _elementActive.parent().parent().parent().parent().addClass("active");
                _elementActive.children().addClass('active-content');
                _elementActive.parent().parent().children('a:first').addClass("active-content");
                _elementActive.parent().parent().parent().parent().find('h3 a').addClass("active-content");
                _elementActive.parent().slideDown();
            }
        });

        @if (Auth::check() && ((isset($checkpay->payment) && $checkpay->payment > 0) || Auth::user()->role_id == 6))
            function onComment(e) {
                e.preventDefault();
                let form = $('form[name="formLms"]');
                if (form.find('textarea').val().length == 0) {
                    Swal.fire({
                        title: "Thông báo",
                        text: "Vui lòng nhập thông tin phản hồi",
                        icon: "warning", // 'type' đổi thành 'icon'
                        showCancelButton: false,
                        confirmButtonColor: '#8CD4F5',
                        confirmButtonText: "Đồng ý",
                        allowOutsideClick: false, // tương đương với closeOnConfirm
                    });
                    return;
                }

                let route = form.attr('action');
                let data = form.serialize();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    url: route,
                    type: 'post',
                    dataType: "json",
                    data: data,
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Đang xử lý vui lòng chờ',
                            html: '<img src="/public/assets/images/loader.svg" class="loader-img" alt="img">',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                        });
                    },
                    success: function(data) {
                        if (data.error === 1) {
                            $('textarea[name="body"]').val('');

                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                url: '{{ url('comments/index') }}',
                                type: 'post',
                                dataType: "json",
                                data: {
                                    slug: '{{ $slug }}',
                                    combo_slug: '{{ $combo_slug }}',
                                    id: '{{ $slug }}',
                                },
                                success: function(data) {
                                    if (data.error === 1) {
                                        $('#comment_boby').empty();
                                        $('#comment_boby').html(data.message);
                                    }
                                }
                            });

                            Swal.fire({
                                title: 'Thông báo',
                                text: data.message,
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: false,
                            });
                        } else {
                            Swal.fire({
                                title: 'Thông báo',
                                text: data.message,
                                icon: 'warning',
                                timer: 3000,
                                showConfirmButton: false,
                            });
                        }
                    }
                });
            }

            function myModal(id) {
                let form = $('form[name="formComments"]');
                form.find('input[name="parent_id"]').val(id);
                $('#Comment').modal('show');
            }

            function upComment(e) {
                e.preventDefault();

                let form = $('form[name="formComments"]');

                if (form.find('textarea').val().length == 0) {
                    Swal.fire({
                        title: "Thông báo",
                        text: "Vui lòng nhập thông tin phản hồi",
                        icon: "warning",
                        showCancelButton: false,
                        confirmButtonColor: '#8CD4F5',
                        confirmButtonText: "Đồng ý",
                        allowOutsideClick: false,
                    });
                    return;
                }

                let route = form.attr('action');
                let data = form.serialize();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    url: route,
                    type: 'post',
                    dataType: "json",
                    data: data,
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Đang xử lý vui lòng chờ',
                            html: '<img src="/public/assets/images/loader.svg" class="loader-img" alt="img">',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                        });
                    },
                    success: function(data) {
                        if (data.error === 1) {
                            $('textarea[name="body"]').val('');

                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                url: '{{ url('comments/index') }}',
                                type: 'post',
                                dataType: "json",
                                data: {
                                    slug: '{{ $slug }}',
                                    combo_slug: '{{ $combo_slug }}',
                                    id: '{{ $slug }}',
                                },
                                success: function(data) {
                                    if (data.error === 1) {
                                        $('#comment_boby').empty();
                                        $('#comment_boby').html(data.message);
                                    }
                                }
                            });

                            Swal.fire({
                                title: 'Thông báo',
                                text: data.message,
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: false,
                            });
                        } else {
                            Swal.fire({
                                title: 'Thông báo',
                                text: data.message,
                                icon: 'warning',
                                timer: 3000,
                                showConfirmButton: false,
                            });
                        }
                        $('#Comment').modal('hide');
                    }
                });
            }
        @endif
    </script>

    @if (isset($point))
        <script>
            $(document).ready(function() {
                setTimeout(function() {
                    $('#resultModal').modal('show');
                    let lastLogin =
                        '{{ \Carbon\Carbon::parse(Auth::user()->last_login_date)->format('Y-m-d') }}';
                    let today = '{{ \Carbon\Carbon::today()->format('Y-m-d') }}';

                    if (lastLogin != today) {
                        showDailyStreak('{{ $detailContent->id }}');
                    }
                }, 1500);
            });
        </script>
    @else
        <script>
            var HOURS = 0;
            var MINUTES = 0;
            var SECONDS = 0;
            let AJAX_CALL_TIME = 1;
            let AJAX_CALL_MAX_SECONDS = 5;

            let _elementTime = $('#timerdiv');
            let _elementMin = $('#mins');
            let _elementSeconds = $('#seconds');

            $(document).ready(function() {
                let current_minutes = 45;
                let current_seconds = 0;
                initializeTimer(current_minutes, current_seconds);
                _elementMin.text(current_minutes);
                _elementSeconds.text('00');
            });

            function initializeTimer(mins, sec) {
                MINUTES = mins;
                SECONDS = sec;
                startInterval();
            }

            function startInterval() {
                timer = setInterval(tickTock, 1000);
            }

            function checkTimer() {
                if (AJAX_CALL_MAX_SECONDS === AJAX_CALL_TIME) {
                    @if (Auth::check())
                        saveResumeExamData();
                    @endif
                    AJAX_CALL_TIME = 1;
                } else {
                    AJAX_CALL_TIME++;
                }
            }

            async function tickTock() {
                SECONDS--;
                $('input[name="time"]').val(MINUTES + ':' + SECONDS);

                if (SECONDS <= 0) {
                    MINUTES--;
                    checkTimer();

                    _elementMin.text(MINUTES);

                    if (MINUTES === 25) {
                        _elementTime.removeClass('text-success').addClass('text-warning');
                    }

                    if (MINUTES === 9) {
                        _elementTime.removeClass('text-warning').addClass('text-danger');
                    }

                    if (MINUTES < 0) {
                        await stopInterval();
                        _elementMin.text('00');
                        _elementSeconds.text('00');
                        Swal.fire({
                            title: "Hết thời gian",
                            text: "Bạn đã hoàn thành bài thi",
                            icon: 'success',
                            timer: 3000,
                            showConfirmButton: false
                        });
                        await setTimeout(function() {
                            $('form[name="formTest"]').submit();
                        }, 2200);
                    }
                    SECONDS = 59;
                }

                if (MINUTES >= 0) {
                    _elementSeconds.text(SECONDS < 10 ? '0' + SECONDS : SECONDS);
                } else {
                    _elementSeconds.text('00');
                }
            }

            function stopInterval() {
                clearInterval(timer);
            }

            @if (Auth::check())
                function saveResumeExamData() {
                    let formData = $('form[name="formTest"]').serialize();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        url: '{{ route('testLog') }}',
                        type: "post",
                        data: formData,
                    });
                }
            @endif
        </script>
    @endif
@endsection
