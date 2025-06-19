@extends('client.shared.lesson-detail')

@section('styles-content')
<link href="{{ asset('css/pages/lesson-detail/audit.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/admin/css/exercise/audit.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/noto_sans_jp_font.css') }}">

<style>
    .form-check {
        display: flex;
        justify-content: start;
        align-items: center;
        gap: 10px;
    }

    .item-check-select label {
        margin: 0;
    }

    .kg-study.kg-study-cuttom {
        font-size: 1.5rem;
        padding-left: 60px;
        padding-right: 60px;
    }

    .kg-study.kg-study-cuttom.child-question {
        padding-left: 80px;
        margin-top: -36px;
    }

    .title-block-type {
        width: 100%;
        display: flex;
        align-items: center;
    }

    .title-block-type .parent-line {
        flex: 1;
        height: 2px;
        margin-left: 10px;
        background-color: #1669cc;
    }

    .child-question .title-block-type a {
        font-size: 13px !important;
    }

    .font-weight-bold {
        min-width: 15px;
    }

    .progress-les .bar-prg {
        bottom: 22px;
    }

    .main-bar.warning {
        background: linear-gradient(to right, #fecf41, #ffc107) !important;
    }

    .main-bar.danger {
        background: linear-gradient(to right, #fd6776, #dc3545) !important;
    }

    .cpl-status .circle.warning {
        background-color: #ffc107 !important;
    }

    .cpl-status .circle.danger {
        background-color: #dc3545 !important;
    }

    .text-question-les .text-primary {
        margin-top: 16px;
        margin-bottom: 8px !important;
    }

    .child-question-content {
        display: flex;
        font-size: 17px !important;
    }

    .correct-status::before, .incorrect-status::before {
        content: "" !important;
    }

    .wp-btn-progress-les {
        position: fixed;
        top: 49px;
    }

    @media (min-width: 768px) and (max-width: 991px) {
        .ct-lesson17 .block-type {
            width: 100%;
        }
    }

    @media (min-width: 576px) and (max-width: 767px) {
        .ct-lesson17 .item-check-select {
            width: 100%;
        }
    }

    @media (max-width: 601px) {
        .content-image-question {
            flex-direction: column;
        }

        .align-self-start-md{
            align-self: start;
        }
    }

    @media (max-width: 575px) {
        .wp-btn-progress-les {
            top: 70px;
        }

        .wp-btn-progress-les .main-bar {
            top: -10px !important;
        }
    }

</style>
@endsection

@section('lesson-detail-content')
    @php
        $params = [
            'combo_slug' => $combo_slug,
            'slug' => $slug,
            'stt' => $stt,
        ];
    @endphp
    <section class="sptb">
        <div class="card overflow-hidden">
            @if (optional($sections_questions)->isNotEmpty())
                {{ Form::model(null, ['url' => route('learning-management.lesson.test-tokutei.store', $params), 'method' => 'post', 'novalidate' => '', 'name' => 'formTest', 'files' => 'false']) }}
                {!! Form::hidden('time', null) !!}

                <div class="wp-content-les audit-show">
                    @php $flat = true; @endphp
                    @foreach ($sections_questions as $section_key => $section_questions)
                        @component('client.components.tokutei-test.section-question',
                            ['section_key' => $section_key,
                            'section_questions' => $section_questions,
                            'test_structure' => $test_structure,
                            'acc_score' => isset($acc_score) ? $acc_score : null])
                        @endcomponent
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
                                                @if (!isset($acc_score))
                                                    <button type="submit" class="btn-nav-les btn-check-les bg-primary"
                                                        style="border: none; cursor: pointer; outline: none;">
                                                        Nộp bài&nbsp;<i class="fa fa-cloud-upload"
                                                            aria-hidden="true"></i>
                                                    </button>
                                                @else
                                                    @if ($passed == true)
                                                        <a href="{{ $sendUrl }}"
                                                            class="btn-nav-les btn-result-corect-les">
                                                            Bài tiếp&nbsp;<i class="fa fa-angle-double-right"
                                                                aria-hidden="true"></i>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('learning-management.lesson.test-tokutei.store', $params) }}"
                                                            class="btn-nav-les finish-les">
                                                            Làm lại&nbsp;
                                                            <i class="bi bi-arrow-clockwise bi-spin" aria-hidden="true"></i>
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
            @else
                <div style="text-align: center; font-size: 24px; padding: 20px;">
                    Nội dung bài thi đang được chuẩn bị, sẽ sớm được đưa lên!
                </div>
            @endif
        </div>
        <div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        @if (isset($acc_score))
                            <div class="ct-cpl-screen">

                                <div class="info-cpl text-center">

                                    <h4 class="above-text text-primary">Kết quả: {{ $acc_score }} / {{ $max_score }}
                                        điểm</h4>

                                    <h5 class="below-text {{ $passed < 0.65 ? 'text-danger' : 'text-success' }}">
                                        {{ $passed < 0.65 ? 'Chưa đạt yêu cầu bài kiểm tra' : 'Đạt yêu cầu bài kiểm tra' }}
                                    </h5>

                                    @if ($passed < 0.65)
                                        <div class="d-flex justify-content-center">
                                            <i class="bi bi-stars text-warning icon-encourage"></i>
                                            <p class="text-encourage">Bạn đã làm tốt, nhưng hãy thử ôn tập lại kĩ trước khi làm lại nhé!</p>
                                            <i class="bi bi-stars text-warning icon-encourage"></i>
                                        </div>
                                        <a href="{{ route('learning-management.lesson.test-tokutei.store', $params) }}"
                                            class="btn-nav-les finish-les">
                                            Làm lại&nbsp; <i class="bi bi-arrow-clockwise bi-spin" aria-hidden="true"></i>
                                        </a>
                                    @endif

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
    $('.kg-study.kg-study-cuttom.child-question').each(function(index, element) {
        const zIndex = 9999 - index;
        $(element).css({
            position: 'relative',
            zIndex: zIndex
        });
    });
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
<script>
    @if (isset($acc_score))
        $(document).ready(function() {
            setTimeout(function() {
                $('#resultModal').modal('show');
                let logged = '{{ Auth::user()->has_logged_in }}';

                if (logged == false) {
                    showDailyStreak('{{ $detailContent->id }}');
                }
            }, 1500);
        });
    @else
        var HOURS = 0;
        var MINUTES = 0;
        var SECONDS = 0;
        let AJAX_CALL_TIME = 1;
        let AJAX_CALL_MAX_SECONDS = 5;
        const TOTAL_TEST_TIME = {{ $test_duration }};

        let _elementTime = $('#timerdiv');
        let _elementMin = $('#mins');
        let _elementSeconds = $('#seconds');
        let _remaningTimeBar = $('.main-bar');
        let _remainingTimePoints = $('.cpl-status .circle');

        $(document).ready(function() {
            let current_minutes = TOTAL_TEST_TIME;
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

            const remainingTime = ((MINUTES * 60 + SECONDS) / (TOTAL_TEST_TIME * 60)) * 100;
            _remaningTimeBar.css('width', parseInt(remainingTime) + '%');

            if (SECONDS <= 0) {
                MINUTES--;
                checkTimer();

                _elementMin.text(MINUTES);

                if (MINUTES === 25) {
                    _elementTime.removeClass('text-success').addClass('text-warning');

                    _remaningTimeBar.addClass('warning');
                    _remainingTimePoints.addClass('warning');
                }

                if (MINUTES === 9) {
                    _elementTime.removeClass('text-warning').addClass('text-danger');

                    _remaningTimeBar.removeClass('warning').addClass('danger');
                    _remainingTimePoints.removeClass('warning').addClass('danger');
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
    @endif
</script>
<script>
function updateProgressWidth() {
var contentWidth = $('.wp-content-les').outerWidth();
    $('.wp-btn-progress-les').width(contentWidth);
}

updateProgressWidth();

$(window).on('resize', function() {
    updateProgressWidth();
});
</script>
@endsection
