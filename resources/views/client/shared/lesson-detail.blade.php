@extends('client.app-course')

@section('styles')
    <link href="{{ asset('css/pages/lesson-detail/app-course.css') }}" rel="stylesheet">
    @yield('styles-content')
    @yield('lesson-detail-styles')
@endsection

@section('content')
    <div class="row study-main-section">
        <div class="col-lg-9 col-12 study-main-content">
            <div class="study-content">
                @yield('lesson-detail-content')
            </div>

            <div class="nav nav-tabs navtab-select-menu" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                    type="button" role="tab" aria-controls="nav-home" aria-selected="true">
                    @if (isset($flashcardDetail))
                        Danh sách từ vựng
                    @else
                        Mô tả bài học
                    @endif
                </button>
                <button class="nav-link" id="nav-questions-tab" data-bs-toggle="tab" data-bs-target="#nav-questions"
                    type="button" role="tab" aria-controls="nav-questions" aria-selected="false">Câu hỏi của
                    bạn</button>
                <button class="nav-link nav_course_button" id="nav-example-tab" data-bs-toggle="tab"
                    data-bs-target="#nav-course-content" type="button" role="tab" aria-controls="nav-example"
                    aria-selected="false">Nội dung khoá học</button>
                <div id="btn_hide_tab_content" class="btn btn-primary">
                    <i class="bi bi-chevron-double-up"></i>
                </div>
            </div>

            <div class="tab-content" id="nav-tabContent" style="height: 0px !important;">
                <!-- Mô tả bài học -->
                <div class="tab-pane fade show active nav_description_content" id="nav-home" role="tabpanel"
                    aria-labelledby="nav-home-tab" tabindex="0">
                    <div class="lesson-container">
                        @if (isset($flashcardDetail))
                            @include('client.lesson-detail.flashcard-detail')
                        @else
                            <h4 class="lesson-title">Bài học tiếng Nhật N5 - Giới thiệu về Kanji</h4>
                            <div class="lesson-content">
                                <div class="my-5">
                                    <div class="row">
                                        <h4 class="text-primary">Khóa Học Tiếng Nhật Cơ Bản</h4>
                                        <p class="lead">Khám phá ngôn ngữ và văn hóa Nhật Bản qua khóa học cơ bản này, phù
                                            hợp
                                            cho người mới bắt đầu!</p>
                                        <ul class="list-group list-group-flush mb-4">
                                            <li class="list-group-item">Thời lượng: 40 giờ</li>
                                            <li class="list-group-item">Số buổi: 20 buổi</li>
                                            <li class="list-group-item">Cấp độ: Cơ bản</li>
                                        </ul>
                                        <a href="#" class="btn btn-primary btn-lg">Đăng ký ngay</a>
                                        <a href="#" class="btn btn-outline-secondary btn-lg">Xem chi tiết</a>
                                    </div>
                                </div>

                            </div>
                        @endif
                    </div>
                </div>

                <div class="tab-pane fade nav_comment_content" id="nav-questions" role="tabpanel"
                    aria-labelledby="nav-questions-tab" tabindex="0">
                    <div class="comment-section">
                        <div id="comment_input" class="comment-input">
                            <img alt="User avatar" height="40" src="{{ getFullUserImage(Auth::user()->image) }}"
                                width="40" />
                            <input id="comment_input_area" placeholder="Nhập bình luận mới của bạn" type="text" />
                        </div>
                        <div id="comment" class="comment">
                            <!-- Comment -->
                            @foreach ($comments as $index => $comment)
                                <div class="comment-user">
                                    <img alt="User avatar" height="40"
                                        src="{{ $comment->user->image ? getFullUserImage($comment->user->image) : asset('images/no-avatar.png') }}"
                                        width="40" />
                                    <div class="comment-body">
                                        <div class="name">{{ $comment->user_name }}</div>
                                        <div class="time">{{ $comment->created_at->diffForHumans() }}</div>
                                        <div class="text">{{ $comment->body }}</div>
                                        <div class="actions reply-btn" data-comment-id="{{ $comment->id }}">
                                            <span>Phản hồi</span>
                                        </div>
                                        <div class="reply-input" data-comment-id="${commentId}">
                                            <input type="text" class="reply-area" data-comment-id="{{ $comment->id }}"
                                                placeholder="Nhập tin nhắn của bạn..." />
                                        </div>
                                        <div class="comment-reply mt-3" data-comment-id="{{ $comment->id }}">
                                            @foreach ($comment->childComments as $indexComment => $childComment)
                                                <div class="comment-user">
                                                    @if ($childComment->admin_id)
                                                        <img alt="User avatar" height="40"
                                                            src="{{ $childComment->admin->image ? getFullUserImage($childComment->admin->image) : asset('images/no-avatar.png') }}"
                                                            width="40" />
                                                    @else
                                                        <img alt="User avatar" height="40"
                                                            src="{{ $childComment->user->image ? getFullUserImage($childComment->user->image) : asset('images/no-avatar.png') }}"
                                                            width="40" />
                                                    @endif
                                                    <div class="comment-body">
                                                        <div class="name">
                                                            {{ $childComment->admin_id ? $childComment->admin->name : $childComment->user_name }}
                                                        </div>
                                                        <div class="time">
                                                            {{ $childComment->created_at->diffForHumans() }}
                                                        </div>
                                                        <div class="text">{{ $childComment->body }}</div>
                                                        <div class="actions reply-btn"
                                                            data-comment-id="{{ $comment->id }}">
                                                            <span>Phản hồi</span>
                                                        </div>
                                                        <div class="reply-input">
                                                            <input type="text" data-comment-id="{{ $comment->id }}"
                                                                class="reply-area"
                                                                placeholder="Nhập tin nhắn của bạn..." />
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Nội dung khóa học -->
                <div class="tab-pane fade nav_course_content" id="nav-course-content" role="tabpanel"
                    aria-labelledby="nav-course-content-tab" tabindex="0">
                    <h4>Nội dung khóa học</h4>
                    <div class="accordion" id="accordion_container">
                        @include('client.components.content-tree', ['contents' => $contents])
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="buy_course_modal" tabindex="-1" aria-labelledby="course_modal_label"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="course_modal_label">
                            <i class="bi bi-book me-2"></i> {{ $seriesCombo->title }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Khóa học này sẽ giúp bạn nâng cao kỹ năng tiếng Nhật của mình thông qua các bài học tương tác và
                            thực hành chuyên sâu.</p>
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <span class="course-price">
                                <i class="bi bi-currency-dollar"></i> {{ formatCurrencyVND($seriesCombo->cost) }} VNĐ
                            </span>
                            <button class="btn btn-buy">Mua khóa học</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <p class="text-muted small">Mua khóa học để mở khóa toàn bộ nội dung và bắt đầu học ngay!</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3 list-lesson">
            <h4>Nội dung khóa học</h4>
            <div class="accordion" id="accordion_container">
                @include('client.components.content-tree', ['contents' => $contents])
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ admin_asset('js/moment-with-locales.min.js') }}"></script>
    <script>
        moment.locale('vi');
        // Function to display the Buy Course modal
        const showBuyCourseModal = () => $('#buy_course_modal').modal('show');
        const username = '{{ Auth::user()->name }}' ?? '';
        const userId = '{{ Auth::user()->id }}' ?? '';
        const userImage =
            "{{ Auth::user()->image ? getFullUserImage(Auth::user()->image) : asset('images/no-avatar.png') }}"

        $(document).ready(function() {
            setTimeout(() => {
                toggleLoadingOverlay(false);
            }, 50);

            let isHidden = true;

            @if (isset($flashcardDetail) && !empty($flashcardDetail))
                isHidden = false;
                $('#btn_hide_tab_content').find('i').toggleClass('bi-chevron-double-up bi-chevron-double-down');
            @endif

            // Helper function to get the outer height of an element or return 0 if not found
            const getOuterHeight = selector => $(selector).outerHeight() || 0;

            const adjustLayout = () => {
                const windowHeight = window.innerHeight;
                const headerHeight = getOuterHeight('.header-study');

                // Determine which footer is visible: desktop or mobile
                const footerSelector = $('.footer-study').is(':visible') ? '.footer-study' :
                    '.mobile-footer-study';
                const footerHeight = getOuterHeight(footerSelector);
                const navTabHeight = getOuterHeight('.navtab-select-menu');
                let additionalHeight = 0;

                // Calculate the available height for the study content
                const studyContentHeight = windowHeight - headerHeight - footerHeight - navTabHeight;

                if ($('.wp-btn-progress-les').length) {
                    additionalHeight = getOuterHeight('.wp-btn-progress-les');
                    $('.audit-show').css('margin-top', additionalHeight);
                }

                // Adjust margins for main content and lesson list based on header and footer heights
                $('.study-main-content, .list-lesson').css({
                    'margin-top': `${headerHeight}px`,
                    'margin-bottom': `${footerHeight}px`
                });

                if (isHidden) {
                    // When tab content is hidden, set its height to 0 and adjust study content height
                    $('.tab-content').css('height', 0);
                    $('.study-content').css('height', studyContentHeight);

                    if ($('.wp-btn-progress-les').length) {
                        // Set max-height and enable scrolling for audit-show when hidden
                        $('.audit-show').css({
                            'max-height': studyContentHeight - additionalHeight,
                            'height': studyContentHeight - additionalHeight,
                            'overflow-y': 'auto',
                            'box-sizing': 'border-box'
                        });
                    } else if ($('.vjs-theme-fantasy').length) {
                        // Set max-height and enable scrolling for video player when hidden
                        $('.vjs-theme-fantasy').css({
                            'min-height': studyContentHeight - additionalHeight,
                            'height': studyContentHeight - additionalHeight,
                            'overflow-y': 'auto',
                            'box-sizing': 'border-box'
                        });
                    } else if ($('.exercise-content').length) {
                        // Set max-height and enable scrolling for exercise content when hidden
                        $('.exercise-content').css({
                            'height': studyContentHeight - additionalHeight,
                            'overflow-y': 'auto',
                            'box-sizing': 'border-box'
                        });
                    } else if ($('.flashcard-body').length) {
                        // Set max-height and enable scrolling for exercise content when hidden
                        $('.flashcard-body').css({
                            'height': studyContentHeight - additionalHeight,
                            'overflow-y': 'auto',
                            'box-sizing': 'border-box'
                        });
                    } else if ($('.pronunciation-body').length) {
                        // Set max-height and enable scrolling for exercise content when hidden
                        $('.pronunciation-body').css({
                            'height': studyContentHeight - additionalHeight,
                            'overflow-y': 'auto',
                            'box-sizing': 'border-box'
                        });
                    }

                } else {
                    // Calculate the percentage height for tab content based on total fixed elements
                    var studyContentVh = (studyContentHeight / windowHeight) * 100;
                    const totalVH = (headerHeight + footerHeight + navTabHeight + studyContentVh) /
                        windowHeight * 100;
                    const tabContentHeightVH = 100 - totalVH;

                    $('.tab-content').css('height', `${tabContentHeightVH}vh`);
                    $('.study-content').css('height', 'auto');

                    if ($('.sptb').length) {
                        // Set a fixed maximum height for audit-show when tab content is visible
                        $('.audit-show').css('max-height', '40vh');
                    } else if ($('.vjs-theme-fantasy').length) {
                        $('.vjs-theme-fantasy').css('min-height', '40vh');
                    } else if ($('.exercise-content').length) {
                        $('.exercise-content').css('height', '40vh');
                    } else if ($('.handwriting-container').length) {
                        $('.handwriting-container').css('height', '40vh');
                    } else if ($('.flashcard-body').length) {
                        $('.flashcard-body').css('height', '40vh');
                        $('.flashcard-detail-container').css('height', '80vh');
                    } else if ($('.pronunciation-body').length) {
                        $('.pronunciation-body').css('height', '40vh');
                    }
                }
            };

            // Toggle the visibility of the tab content and adjust layout accordingly
            $('#btn_hide_tab_content').on('click', function() {
                isHidden = !isHidden;
                // Toggle the icon direction to indicate the current state
                $(this).find('i').toggleClass('bi-chevron-double-up bi-chevron-double-down');
                adjustLayout();
            });

            // Initial layout adjustment and bind the adjustLayout function to window resize
            adjustLayout();
            $(window).resize(adjustLayout);
        });

        function animateHicoin(increasedPoints = 0) {
            if (increasedPoints === 0) {
                return;
            }

            const hicoinAnimation = $('.hicoin-animation');
            const displayedIncreasedPoints = $('.hicoin-animation .increased-point');
            const displayedOwnedPoints = $(".header-my-coin .owned-point");
            const pointContainer = $('.header-my-coin');

            const ownedPoints = parseInt(displayedOwnedPoints.text().replace(/,/g, ""));
            displayedIncreasedPoints.text(increasedPoints);
            displayedOwnedPoints.text((ownedPoints + increasedPoints).toLocaleString('en-US'));

            // Play confetti animation effect
            party.confetti(pointContainer[0], {
                count: party.variation.range(30, 40),
                spread: party.variation.range(40, 50),
                origin: {
                    x: 0.5,
                    y: 0.5 + (50 / pointContainer[0].offsetHeight)
                }
            });

            // Play tada and float point animation effect
            pointContainer.addClass("animate__tada animate__animated");
            hicoinAnimation.addClass('hicoin-float');
            setTimeout(() => {
                hicoinAnimation.removeClass('hicoin-float');
                pointContainer.removeClass("animate__tada animate__animated");
            }, 2000);
        }

        // Finish content
        const earnPointFinishContent = (contentId, earnedPoints = 1, contentType = '') => {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: '{{ route('learning-management.lesson.exercise.finish-content') }}',
                type: "post",
                data: {
                    content_id: contentId,
                    earned_points: earnedPoints,
                    content_type: contentType
                },
            });
        }

        function showDailyStreak(contentId) {
            $.ajax({
                url: '{{ route('learning-management.lesson.daily-streak') }}', // Gọi route với tên đầy đủ
                type: 'POST',
                data: {},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Bảo mật với CSRF token
                },
                success: function(response) {
                    earnPointFinishContent(contentId, response, 'streak');
                    animateHicoin(response);
                    $('#modalLoginStreak').modal('show');
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }


        function checkFinishContent() {
            const contentId = '{{ $detailContent->id }}';
            const checkImage = $('img[data-content-id="' + contentId + '"]');
            imageSource = checkImage.attr('src').replace('empty-box.svg', 'checked-box.png');
            checkImage.attr('src', imageSource).addClass('animate__bounceIn animate__animated');
        }

        // Send comment
        function sendComment(commentText, commentId, parentId = 0) {
            let data = {};
            let newCommentHtml = "";

            const urlParts = window.location.pathname.split('/');
            const lmscombo_slug = urlParts[4];
            const lmsseries_slug = urlParts[5];
            const lmscontent_id = urlParts[6];

            data.body = commentText;
            data.lmscombo_slug = lmscombo_slug;
            data.lmsseries_slug = lmsseries_slug;
            data.lmscontent_id = lmscontent_id;
            data.user_id = userId;
            data.parent_id = parentId;

            return new Promise((resolve, reject) => {
                $.ajax({
                    url: "{{ route('comment.add') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        if (commentId === "") {
                            commentId = response.id
                        }
                        newCommentHtml = `
                            <div class="comment-user">
                                <img alt="User avatar" height="40" src="${userImage}" width="40" />
                                <div class="comment-body">
                                    <div class="name">${response.user_name}</div>
                                    <div class="time">${moment(response.created_at).fromNow()}</div>
                                    <div class="text">${response.body}</div>
                                    <div class="actions reply-btn" data-comment-id="${commentId}"><span>Phản hồi</span></div>
                                    <div class="reply-input" data-comment-id="${commentId}">
                                        <input type="text"
                                            class="reply-area"
                                            data-comment-id="${commentId}"
                                            placeholder="Nhập tin nhắn của bạn..." />
                                    </div>
                                    ${
                                        parentId === 0
                                        ? `<div class="comment-reply mt-3" data-comment-id="${commentId}"></div>` 
                                        : ''
                                    }
                                </div>
                            </div>
                        `;
                        resolve(newCommentHtml);
                    },
                    error: function() {
                        reject("Gửi tin nhắn thất bại. Vui lòng thử lại.");
                    },
                });
            });
        }

        $('#comment_input_area').on('keypress', function(event) {
            if (event.which === 13) {
                event.preventDefault();

                const commentText = $(this).val().trim();

                if (commentText === '') {
                    return;
                }

                const inputField = $('#comment_input_area');
                const commentInput = $('#comment_input');
                inputField.prop('disabled', true);
                commentInput.after(
                    '<span class="comment-loading" style="font-size: 16px; color: #166bc9">Đang gửi...</span>');

                sendComment(commentText, "", 0)
                    .then(newCommentHtml => {
                        inputField.val('');
                        $('#comment').prepend(newCommentHtml);
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Lỗi',
                            text: error,
                            icon: 'error'
                        });
                    })
                    .finally(() => {
                        inputField.prop('disabled', false);
                        $('.comment-loading').remove();
                    });
            }
        })

        $('#comment').on('click', '.reply-btn', function() {
            $('.reply-input').not($(this).siblings('.reply-input')).hide().find('input').val(
                '');

            const replyInput = $(this).siblings('.reply-input');
            replyInput.toggle();

            if (!replyInput.is(':visible')) {
                replyInput.find('input').val('');
            }
        });

        $('#comment').on('keypress', '.reply-area', function(event) {
            if (event.which === 13) {
                event.preventDefault();

                const commentId = $(this).data('comment-id');
                const inputField = $(this);
                const replyText = inputField.val().trim();

                if (replyText) {
                    const commentInput = $(this).parent();
                    inputField.prop('disabled', true);
                    commentInput.after(
                        '<span class="comment-loading" style="font-size: 16px; color: #166bc9">Đang gửi...</span>'
                    );

                    sendComment(replyText, commentId, commentId)
                        .then(newCommentHtml => {
                            inputField.val('');
                            $(`.comment-reply[data-comment-id="${commentId}"]`).append(newCommentHtml);
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Lỗi',
                                text: error,
                                icon: 'error'
                            });
                        })
                        .finally(() => {
                            inputField.prop('disabled', false);
                            $('.comment-loading').remove();
                        });

                }
            }
        });
    </script>


    @yield('scripts-content')
    @yield('lesson-detail-scripts')
    @include('client.components.streak');
@endsection
