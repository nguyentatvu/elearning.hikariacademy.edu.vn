@extends('client.app-course')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/exercise/audit.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/exercise/bundle.min.css') }}">
    <link href="{{ asset('css/pages/lesson-detail/app-course.css') }}" rel="stylesheet">
    @yield('styles-content')
@endsection

@section('content')
    <div class="row study-main-section">
        <div class="col-lg-9 col-12 study-main-content">
            <div class="study-content">
                @yield('lesson-detail-content')
            </div>

            <div class="nav nav-tabs navtab-select-menu" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                    type="button" role="tab" aria-controls="nav-home" aria-selected="true">Mô tả bài học</button>
                <button class="nav-link" id="nav-questions-tab" data-bs-toggle="tab" data-bs-target="#nav-questions"
                    type="button" role="tab" aria-controls="nav-questions" aria-selected="false">Câu hỏi của
                    bạn</button>
                <button class="nav-link nav_course_button" id="nav-example-tab" data-bs-toggle="tab"
                    data-bs-target="#nav-course-content" type="button" role="tab" aria-controls="nav-example"
                    aria-selected="false">Nội dung khoá học</button>
                <div id="btn_hide_tab_content" class="btn btn-primary">
                    <i class="bi bi-chevron-double-down"></i>
                </div>
            </div>

            <div class="tab-content" id="nav-tabContent">
                <!-- Mô tả bài học -->
                <div class="tab-pane fade show active nav_description_content" id="nav-home" role="tabpanel"
                    aria-labelledby="nav-home-tab" tabindex="0">
                    <div class="lesson-container container">
                        <h4 class="lesson-title">Bài học tiếng Nhật N5 - Giới thiệu về Kanji</h4>
                        <div class="lesson-content">
                            <div class="my-5">
                                <div class="row">
                                    <h4 class="text-primary">Khóa Học Tiếng Nhật Cơ Bản</h4>
                                    <p class="lead">Khám phá ngôn ngữ và văn hóa Nhật Bản qua khóa học cơ bản này, phù hợp
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
                    </div>
                </div>

                <!-- Câu hỏi của bạn -->
                <div class="tab-pane fade nav_comment_content" id="nav-questions" role="tabpanel"
                    aria-labelledby="nav-questions-tab" tabindex="0">
                    <div class="comment-section">
                        <div class="comment-input">
                            <img alt="User avatar" height="40" src="user-avatar-url.png" width="40" />
                            <input placeholder="Nhập bình luận mới của bạn" type="text" />
                        </div>

                        <div class="comment-count">
                            <strong>132 bình luận</strong>
                            <span class="text-muted float-end">Nếu thấy bình luận spam, các bạn bấm report giúp admin
                                nhé</span>
                        </div>

                        <!-- Bình luận 1 -->
                        <div class="comment">
                            <img alt="User avatar" height="40" src="user-avatar-url.png" width="40" />
                            <div class="comment-body">
                                <div class="name">Học viên A</div>
                                <div class="time">2 tháng trước</div>
                                <div class="text">Theo em thấy thì học xong một ngôn ngữ hướng đối tượng rồi qua học
                                    javascript sẽ dễ hiểu hơn ở phần này</div>
                                <div class="actions"><span>Phản hồi</span></div>
                            </div>
                        </div>

                        <!-- Bình luận 1 -->
                        <div class="comment">
                            <img alt="User avatar" height="40" src="user-avatar-url.png" width="40" />
                            <div class="comment-body">
                                <div class="name">Học viên A</div>
                                <div class="time">2 tháng trước</div>
                                <div class="text">Theo em thấy thì học xong một ngôn ngữ hướng đối tượng rồi qua học
                                    javascript sẽ dễ hiểu hơn ở phần này</div>
                                <div class="actions"><span>Phản hồi</span></div>
                            </div>
                        </div>

                        <!-- Bình luận 1 -->
                        <div class="comment">
                            <img alt="User avatar" height="40" src="user-avatar-url.png" width="40" />
                            <div class="comment-body">
                                <div class="name">Học viên A</div>
                                <div class="time">2 tháng trước</div>
                                <div class="text">Theo em thấy thì học xong một ngôn ngữ hướng đối tượng rồi qua học
                                    javascript sẽ dễ hiểu hơn ở phần này</div>
                                <div class="actions"><span>Phản hồi</span></div>
                            </div>
                        </div>

                        <!-- Bình luận 1 -->
                        <div class="comment">
                            <img alt="User avatar" height="40" src="user-avatar-url.png" width="40" />
                            <div class="comment-body">
                                <div class="name">Học viên A</div>
                                <div class="time">2 tháng trước</div>
                                <div class="text">Theo em thấy thì học xong một ngôn ngữ hướng đối tượng rồi qua học
                                    javascript sẽ dễ hiểu hơn ở phần này</div>
                                <div class="actions"><span>Phản hồi</span></div>
                            </div>
                        </div>

                        <!-- Bình luận 1 -->
                        <div class="comment">
                            <img alt="User avatar" height="40" src="user-avatar-url.png" width="40" />
                            <div class="comment-body">
                                <div class="name">Học viên A</div>
                                <div class="time">2 tháng trước</div>
                                <div class="text">Theo em thấy thì học xong một ngôn ngữ hướng đối tượng rồi qua học
                                    javascript sẽ dễ hiểu hơn ở phần này</div>
                                <div class="actions"><span>Phản hồi</span></div>
                            </div>
                        </div>

                        <!-- Bình luận 1 -->
                        <div class="comment">
                            <img alt="User avatar" height="40" src="user-avatar-url.png" width="40" />
                            <div class="comment-body">
                                <div class="name">Học viên A</div>
                                <div class="time">2 tháng trước</div>
                                <div class="text">Theo em thấy thì học xong một ngôn ngữ hướng đối tượng rồi qua học
                                    javascript sẽ dễ hiểu hơn ở phần này</div>
                                <div class="actions"><span>Phản hồi</span></div>
                            </div>
                        </div>

                        <!-- Bình luận 2 -->
                        <div class="comment">
                            <img alt="User avatar" height="40" src="user-avatar-url.png" width="40" />
                            <div class="comment-body">
                                <div class="name">Học viên B</div>
                                <div class="time">3 tháng trước</div>
                                <div class="text">Nếu không có từ khóa new thì có ảnh hưởng gì không nhỉ?</div>
                                <div class="actions">
                                    <span>Phản hồi</span>
                                    <span>Xem 1 câu trả lời</span>
                                </div>
                            </div>
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
    <script>
        const showBuyCourseModal = () => $('#buy_course_modal').modal('show');

        $(document).ready(function() {
            let isHidden = false;

            const getOuterHeight = selector => $(selector).outerHeight() || 0;

            const adjustLayout = () => {
                const windowHeight = window.innerHeight;
                const headerHeight = getOuterHeight('.header-study');
                const footerSelector = $('.footer-study').is(':visible') ? '.footer-study' :
                    '.mobile-footer-study';
                const footerHeight = getOuterHeight(footerSelector);
                const navTabHeight = getOuterHeight('.navtab-select-menu');
                let additionalHeight = 0;
                const studyContentHeight = windowHeight - headerHeight - footerHeight - navTabHeight;

                if ($('.wp-btn-progress-les').length) {
                    additionalHeight = getOuterHeight('.wp-btn-progress-les');
                    $('.audit-show').css('margin-top', additionalHeight);
                }

                $('.study-main-content, .list-lesson').css({
                    'margin-top': `${headerHeight}px`,
                    'margin-bottom': `${footerHeight}px`
                });

                if (isHidden) {
                    $('.tab-content').css('height', 0);
                    $('.study-content').css('height', studyContentHeight);
                    $('.audit-show').css({
                        'max-height': studyContentHeight - additionalHeight,
                        'overflow-y': 'auto',
                        'box-sizing': 'border-box'
                    });
                } else {
                    const totalVH = headerHeight + footerHeight + navTabHeight;
                    const tabContentHeightVH = 100 - ((totalVH / windowHeight) * 100);
                    $('.tab-content').css('height', `${tabContentHeightVH}vh`);
                    $('.study-content').css('height', 'auto');
                    $('.audit-show').css('max-height', '40vh');
                }
            };

            $('#btn_hide_tab_content').on('click', function() {
                isHidden = !isHidden;
                $(this).find('i').toggleClass('bi-chevron-double-up bi-chevron-double-down');
                adjustLayout();
            });

            adjustLayout();
            $(window).resize(adjustLayout);
        });
    </script>

    @yield('scripts-content')
    @yield('lesson-detail-scripts')
@endsection
