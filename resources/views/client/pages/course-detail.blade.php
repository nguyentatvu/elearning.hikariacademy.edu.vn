@extends('client.app-course')

@section('styles')
    <style>
        .comment-section {
            margin: 20px auto;
            background-color: var(--background-color, #fff);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .comment-input {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .comment-input img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .comment-input input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
        }

        .comment {
            display: flex;
            margin-bottom: 20px;
        }

        .comment img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .comment-body {
            flex: 1;
        }

        .comment-body .name {
            font-weight: bold;
            color: var(--primary);
        }

        .comment-body .time {
            color: var(--secondary);
            font-size: 0.9em;
        }

        .comment-body .text {
            margin: 5px 0;
        }

        .comment-body .actions {
            color: var(--primary);
            font-size: 0.9em;
        }

        .comment-body .actions span {
            margin-right: 10px;
            cursor: pointer;
        }

        .comment-body .reply {
            margin-left: 50px;
            margin-top: 10px;
        }

        .comment-body .reply img {
            width: 30px;
            height: 30px;
        }

        .comment-body .reply .name {
            font-weight: bold;
            color: var(--primary);
        }

        .comment-body .reply .time {
            color: var(--secondary);
            font-size: 0.8em;
        }

        .comment-body .reply .text {
            margin: 5px 0;
        }

        .study-main-content {
            height: 100%;
        }

        .nav-tabs .nav-link {
            border: 0;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        .nav-tabs .nav-link.active,
        .nav-tabs .nav-item.show .nav-link {
            border: none !important;
            border-bottom: 4px solid var(--bs-primary) !important;
            background: var(--secondary);
        }

        .list-lesson {
            height: calc(100% - 105px);
            overflow-y: auto;
            margin-top: 50px;
            position: fixed;
            right: 0;
        }

        <style>

        /* Default styles for larger screens */
        .list-lesson {
            display: block;
            /* Default display */
        }

        .tab-content {
            overflow-y: auto;
            /* Ensure overflow is auto */
        }

        .study-main-content iframe {
            height: calc(40vh);
        }

        @media (max-width: 576px) {}

        @media (min-width: 577px) and (max-width: 767px) {

            /* Tablet Portrait */
            .study-main-content iframe {
                height: 40vh;
                /* Adjust height for smaller screens */
            }

            .tab-content {
                height: calc(50vh - 75px);
            }
        }

        @media (min-width: 768px) and (max-width: 991px) {}

        @media (min-width: 992px) and (max-width: 1200px) {

            .nav_course_content,
            .nav_course_button {
                display: none !important;
            }
        }

        @media (min-width: 1201px) {

            .nav_course_content,
            .nav_course_button {
                display: none !important;
            }
        }

        @media (max-width: 991px) {
            .list-lesson {
                display: none !important;
            }

            html,
            body {
                overflow-x: hidden;
                overflow-y: hidden;
            }
        }
    </style>

    </style>
@endsection

@section('content')
    <div class="row study-main-section">
        <div class="col-lg-9 col-12 study-main-content">
            <div class="study-content">
                <iframe width="100%" height="500px" src="https://www.youtube.com/embed/D0xos2XTQPs?si=vy3ftZnn3YbYKsZi"
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
                    <div class="accordion" id="accordionExample">
                        @for ($i = 1; $i <= 16; $i++)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $i }}">
                                    <div class="accordion-button d-block {{ $i === 1 ? '' : 'collapsed' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $i }}"
                                        aria-expanded="{{ $i === 1 ? 'true' : 'false' }}"
                                        aria-controls="collapse{{ $i }}">
                                        <div class="fw-bold"><i class="bi bi-book"></i> Bài {{ $i }}:「はじめまして」
                                        </div>
                                    </div>
                                </h2>
                                <div id="collapse{{ $i }}"
                                    class="accordion-collapse collapse {{ $i === 1 ? 'show' : '' }}"
                                    aria-labelledby="heading{{ $i }}" data-bs-parent="#accordionExample">
                                    <div>
                                        <div class="accordio" id="accordionExample">
                                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="flush-headingOne">
                                                        <div class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                                                            aria-expanded="false" aria-controls="flush-collapseOne">
                                                            <i class="bi bi-stickies mx-3"></i> Từ vụng
                                                        </div>
                                                    </h2>
                                                    <div id="flush-collapseOne" class="accordion-collapse collapse"
                                                        aria-labelledby="flush-headingOne"
                                                        data-bs-parent="#accordionFlushExample">
                                                        <div>
                                                            <ul class="list-group">
                                                                <li class="list-group-item">
                                                                    <div class="mx-5"><i
                                                                            class="bi bi-play-circle-fill"></i> 1.
                                                                        Từ vựng bài 1 Từ vựng bài 1 Từ vựng bài 1
                                                                        <span>7:30</span>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item">
                                                                    <div class="mx-5"><i
                                                                            class="bi bi-play-circle-fill"></i> 1.
                                                                        Từ vựng bài 1 Từ vựng bài 1 Từ vựng bài 1
                                                                        <span>7:30</span>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item">
                                                                    <div class="mx-5"><i
                                                                            class="bi bi-play-circle-fill"></i> 1.
                                                                        Từ vựng bài 1 Từ vựng bài 1 Từ vựng bài 1
                                                                        <span>7:30</span>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="flush-headingTwo">
                                                        <div class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo"
                                                            aria-expanded="false" aria-controls="flush-collapseTwo">
                                                            <i class="bi bi-diagram-2-fill mx-3"></i> Ngữ pháp
                                                        </div>
                                                    </h2>
                                                    <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                                        aria-labelledby="flush-headingTwo"
                                                        data-bs-parent="#accordionFlushExample">
                                                        <div>Placeholder content for this accordion, which
                                                            is intended to demonstrate the <code>.accordion-flush</code>
                                                            class. This
                                                            is the second item's accordion body. Let's imagine this being
                                                            filled
                                                            with some actual content.</div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="flush-headingThree">
                                                        <div class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#flush-collapseThree" aria-expanded="false"
                                                            aria-controls="flush-collapseThree">
                                                            <i class="bi bi-pencil-square mx-3"></i>Bài tập
                                                        </div>
                                                    </h2>
                                                    <div id="flush-collapseThree" class="accordion-collapse collapse"
                                                        aria-labelledby="flush-headingThree"
                                                        data-bs-parent="#accordionFlushExample">
                                                        <div>Placeholder content for this accordion, which
                                                            is intended to demonstrate the <code>.accordion-flush</code>
                                                            class. This
                                                            is the third item's accordion body. Nothing more exciting
                                                            happening here
                                                            in terms of content, but just filling up the space to make it
                                                            look, at
                                                            least at first glance, a bit more representative of how this
                                                            would look
                                                            in a real-world application.</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-12 list-lesson">
            <h4>Nội dung khóa học</h4>
            <div class="accordion" id="accordionExample">
                @for ($i = 1; $i <= 16; $i++)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $i }}">
                            <div class="accordion-button d-block {{ $i === 1 ? '' : 'collapsed' }}" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $i }}"
                                aria-expanded="{{ $i === 1 ? 'true' : 'false' }}"
                                aria-controls="collapse{{ $i }}">
                                <div class="fw-bold"><i class="bi bi-book"></i> Bài {{ $i }}:「はじめまして」</div>
                            </div>
                        </h2>
                        <div id="collapse{{ $i }}"
                            class="accordion-collapse collapse {{ $i === 1 ? 'show' : '' }}"
                            aria-labelledby="heading{{ $i }}" data-bs-parent="#accordionExample">
                            <div>
                                <div class="accordio" id="accordionExample">
                                    <div class="accordion accordion-flush" id="accordionFlushExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingOne">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                                                    aria-expanded="false" aria-controls="flush-collapseOne">
                                                    <i class="bi bi-stickies mx-3"></i> Từ vụng
                                                </button>
                                            </h2>
                                            <div id="flush-collapseOne" class="accordion-collapse collapse"
                                                aria-labelledby="flush-headingOne"
                                                data-bs-parent="#accordionFlushExample">
                                                <div>
                                                    <ul class="list-group">
                                                        <li class="list-group-item">
                                                            <div class="mx-5"><i class="bi bi-play-circle-fill"></i> 1.
                                                                Từ vựng bài 1 Từ vựng bài 1 Từ vựng bài 1 <span>7:30</span>
                                                            </div>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <div class="mx-5"><i class="bi bi-play-circle-fill"></i> 1.
                                                                Từ vựng bài 1 Từ vựng bài 1 Từ vựng bài 1 <span>7:30</span>
                                                            </div>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <div class="mx-5"><i class="bi bi-play-circle-fill"></i> 1.
                                                                Từ vựng bài 1 Từ vựng bài 1 Từ vựng bài 1 <span>7:30</span>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingTwo">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo"
                                                    aria-expanded="false" aria-controls="flush-collapseTwo">
                                                    <i class="bi bi-diagram-2-fill mx-3"></i> Ngữ pháp
                                                </button>
                                            </h2>
                                            <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                                aria-labelledby="flush-headingTwo"
                                                data-bs-parent="#accordionFlushExample">
                                                <div>Placeholder content for this accordion, which
                                                    is intended to demonstrate the <code>.accordion-flush</code> class. This
                                                    is the second item's accordion body. Let's imagine this being filled
                                                    with some actual content.</div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingThree">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#flush-collapseThree"
                                                    aria-expanded="false" aria-controls="flush-collapseThree">
                                                    <i class="bi bi-pencil-square mx-3"></i>Bài tập
                                                </button>
                                            </h2>
                                            <div id="flush-collapseThree" class="accordion-collapse collapse"
                                                aria-labelledby="flush-headingThree"
                                                data-bs-parent="#accordionFlushExample">
                                                <div>Placeholder content for this accordion, which
                                                    is intended to demonstrate the <code>.accordion-flush</code> class. This
                                                    is the third item's accordion body. Nothing more exciting happening here
                                                    in terms of content, but just filling up the space to make it look, at
                                                    least at first glance, a bit more representative of how this would look
                                                    in a real-world application.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
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

                // Check if iframe and tabContent elements exist
                if (iframe.length && tabContent.length) {
                    var windowHeight = window.innerHeight; // Get current window height

                    // Convert heights to vh units for responsive design
                    var headerVH = (headerHeight / windowHeight) * 100; // Header height in vh
                    var footerVH = (footerHeight / windowHeight) * 100; // Footer height in vh
                    var iframeHeight = iframe.outerHeight(); // Get iframe height
                    var iframeVH = (iframeHeight / windowHeight) * 100; // Iframe height in vh
                    var navTabVH = (navTabHeight / windowHeight) * 100; // Nav tab height in vh

                    // Calculate the remaining height available for tabContent
                    var calculatedHeight = 100 - (headerVH + footerVH + iframeVH + navTabVH); // Remaining height

                    // Apply the calculated height in vh to the tabContent
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
