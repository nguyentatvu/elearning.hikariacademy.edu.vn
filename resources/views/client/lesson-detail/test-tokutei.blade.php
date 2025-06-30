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

    .demo-container {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .demo-button {
        background: #4CAF50;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 25px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
    }

    .demo-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
    }

    .demo-button.fail {
        background: #f44336;
        box-shadow: 0 4px 15px rgba(244, 67, 54, 0.3);
    }

    .demo-button.fail:hover {
        box-shadow: 0 6px 20px rgba(244, 67, 54, 0.4);
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
    }

    .modal.show {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: white;
        border-radius: 20px;
        padding: 0;
        max-width: 460px;
        width: 90vw;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.4s ease-out;
        overflow: hidden;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .result-modal .modal-header {
        background: linear-gradient(135deg, #4285f4 0%, #34a853 100%);
        color: white;
        padding: 25px 30px 20px;
        text-align: center;
        position: relative;
        display: block !important;
    }

    .modal-header.fail {
        background: linear-gradient(135deg, #ea4335 0%, #fbbc05 100%);
    }

    .close {
        position: absolute;
        right: 20px;
        top: 20px;
        color: rgba(255, 255, 255, 0.8);
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s ease;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .close:hover {
        color: white;
        background: rgba(255, 255, 255, 0.2);
    }

    .result-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .result-score {
        font-size: 18px;
        opacity: 0.9;
    }

    .modal-body {
        padding: 30px;
        text-align: center;
    }

    .section-results {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 25px;
    }

    .section-item {
        flex: 1;
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px 15px;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .section-item.pass {
        border-color: #28a745;
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    }

    .section-item.fail {
        border-color: #dc3545;
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    }

    .section-title {
        font-size: 14px;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .section-score {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .section-item.pass .section-score {
        color: #155724;
    }

    .section-item.fail .section-score {
        color: #721c24;
    }

    .section-status {
        font-size: 12px;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 20px;
        display: inline-block;
    }

    .section-item.pass .section-status {
        background: #28a745;
        color: white;
    }

    .section-item.fail .section-status {
        background: #dc3545;
        color: white;
    }

    .overall-message {
        font-size: 16px;
        line-height: 1.6;
        color: #495057;
        margin-bottom: 25px;
    }

    .overall-message.success {
        color: #155724;
    }

    .overall-message.fail {
        color: #721c24;
    }

    .action-button {
        background: linear-gradient(135deg, #56c8f5 0%, #4285f4 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(66, 133, 244, 0.3);
    }

    .action-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(66, 133, 244, 0.4);
    }

    .action-button.retry {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
    }

    .action-button.retry:hover {
        box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
    }

    .stars {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 20px;
    }

    .star {
        width: 24px;
        height: 24px;
        color: #ffc107;
        opacity: 0.3;
    }

    .star.filled {
        opacity: 1;
    }

    .sparkle {
        font-size: 20px;
        margin: 0 8px;
        animation: sparkle 2s infinite;
    }

    @keyframes sparkle {
        0%, 100% { opacity: 0.6; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.2); }
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
        <div class="modal fade result-modal" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header {{ (isset($passed) && $passed) ? '' : 'fail' }}">
                        <span class="close" onclick="closeModal()">&times;</span>
                        <div class="result-title">Kết quả: {{ isset($acc_score) ? $acc_score : 0 }} / {{ isset($max_score) ? $max_score : 0 }} điểm</div>
                        @if (isset($passed) && $passed)
                            <div class="result-score">Chúc mừng! Bạn đã vượt qua bài kiểm tra</div>
                        @else
                            <div class="result-score">Chưa đạt yêu cầu bài kiểm tra</div>
                        @endif
                    </div>
                    <div class="modal-body">
                        <div class="section-results">
                            @php
                                $sections = $test_structure['section'] ?? [];
                            @endphp
                            @foreach ($sections as $section_id => $section)
                                @php
                                    $score_by_section = isset($score_by_section) ? $score_by_section : [];

                                    $section_passed = $passed_result_by_section[$section_id] ?? false;
                                    $css_class = $section_passed ? 'pass' : 'fail';
                                    $section_label = $section['label'];
                                    $section_student_score = $score_by_section[$section_id] ?? 0;
                                    $section_max_score = $section['maxScore'];
                                    $section_result_label = $section_passed ? 'ĐẬU' : 'TRƯỢT';
                                @endphp
                                <div class="section-item {{ $css_class }}">
                                    <div class="section-title">Phần {{ $section_id }}: {{ $section_label }}</div>
                                    <div class="section-score">{{ $section_student_score }}/{{ $section_max_score }}</div>
                                    <div class="section-status">{{ $section_result_label }}</div>
                                </div>
                            @endforeach
                        </div>

                        @if (isset($passed) && $passed)
                            <div class="overall-message success">
                                <span class="sparkle">✨</span>
                                Xuất sắc! Bạn đã hoàn thành tốt cả hai phần thi với điểm số tuyệt vời!
                                <span class="sparkle">✨</span>
                            </div>
                        @else
                            <div class="overall-message fail">
                                Bạn đã làm tốt, nhưng hãy thử ôn tập lại kĩ trước khi làm lại nhé!
                            </div>

                            <a class="action-button retry" href="{{ route('learning-management.lesson.test-tokutei.store', $params) }}">
                                Làm lại 🔄
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts-content')
<script>
    function closeModal() {
        const resultModal = document.querySelector('.result-modal');
        const backDrop = document.querySelector('.modal-backdrop');

        resultModal.classList.remove('show');
        resultModal.style.display = 'none';
        backDrop.remove();
    }

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
