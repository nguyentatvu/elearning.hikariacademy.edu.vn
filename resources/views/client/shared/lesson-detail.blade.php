@extends('client.app-course')

@section('styles')
    <link href="{{ asset('css/pages/lesson-detail/app-course.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row study-main-section">
        <div class="col-lg-9 col-12 study-main-content">
            <div class="study-content">
                <iframe width="100%" src="https://www.youtube.com/embed/D0xos2XTQPs?si=vy3ftZnn3YbYKsZi"
                    title="YouTube video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                </iframe>
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
            </div>

            <div class="tab-content" id="nav-tabContent">
                <!-- Mô tả bài học -->
                <div class="tab-pane fade show active nav_description_content" id="nav-home" role="tabpanel"
                    aria-labelledby="nav-home-tab" tabindex="0">
                    <div class="lesson-container">
                        <h1 class="lesson-title">Bài học tiếng Nhật N5 - Giới thiệu về Kanji</h1>
                        <div class="lesson-content">
                            <h2>Từ vựng</h2>
                            <ul>
                                <li><b>日本</b> (にほん) - Nhật Bản</li>
                                <li><b>学生</b> (がくせい) - Học sinh</li>
                                <li><b>先生</b> (せんせい) - Giáo viên</li>
                            </ul>
                            <h2>Ngữ pháp</h2>
                            <p>Trong bài học này, bạn sẽ học cách dùng trợ từ <b>は</b> để chỉ chủ ngữ trong câu. Ví dụ:</p>
                            <p class="example">私は学生です。(わたしはがくせいです) - Tôi là học sinh.</p>
                            <h2>Kanji</h2>
                            <p>Chữ Kanji mới: <b>月</b> (つき) - mặt trăng, tháng.</p>
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
        const showBuyCourseModal = () => {
            $('#buy_course_modal').modal('show');
        }

        $(document).ready(function() {
            // Function to set heights and margins based on the current layout
            function adjustLayout() {
                var iframe = $('.study-main-content iframe'); // Select the iframe
                var tabContent = $('.tab-content'); // Select the tab content
                var headerHeight = $('.header-study').outerHeight(); // Get header height
                var navTabHeight = $('.navtab-select-menu').outerHeight(); // Get navigation tab height

                // Determine footer height based on visibility
                var footerHeight;
                if ($('.footer-study').css('display') === 'none') {
                    // Use mobile footer height if desktop footer is hidden
                    footerHeight = $('.mobile-footer-study').outerHeight();
                } else {
                    // Use desktop footer height
                    footerHeight = $('.footer-study').outerHeight();
                }

                // Set the top and bottom margins for the main content area
                $('.study-main-content').css({
                    'margin-top': (headerHeight) + 'px', // Top margin
                    'margin-bottom': (footerHeight) + 'px' // Bottom margin
                });

                $('.list-lesson').css({
                    'margin-top': (headerHeight) + 'px', // Top margin
                    'margin-bottom': (footerHeight) + 'px' // Bottom margin
                });

                // Check if iframe and tabContent elements exist
                if (iframe.length && tabContent.length) {
                    var windowHeight = window.innerHeight;

                    var headerVH = (headerHeight / windowHeight) * 100;
                    var footerVH = (footerHeight / windowHeight) * 100;
                    var iframeHeight = iframe.outerHeight();
                    var iframeVH = (iframeHeight / windowHeight) * 100;
                    var navTabVH = (navTabHeight / windowHeight) * 100;

                    var calculatedHeight = 100 - (headerVH + footerVH + iframeVH + navTabVH); // Remaining height

                    tabContent.css('height', calculatedHeight + 'vh');
                }
            }

            adjustLayout(); // Initial adjustment when the document is ready

            // Adjust layout dynamically on window resize
            $(window).resize(function() {
                adjustLayout(); // Recalculate layout on resize
            });
        });
    </script>
@endsection
