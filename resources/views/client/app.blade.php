<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hikari Elearning</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    @yield('styles')

    <style>
        body {
            overflow: hidden;
        }

        header#header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 99999;
            background: #ffffff;
            border: 1px solid #e6e6e6;
            padding: 2px 0;
            box-shadow: rgba(0, 0, 0, 0.09) 0px 1px 5px;
        }

        .main-content {
            position: relative;
            overflow: scroll;
            overflow-x: hidden;
        }

        .main-content .content {
            min-height: 100vh;
        }

        .robot-guide {
            width: 220px;
            height: 140px;
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 99999;
            transform-origin: bottom;
            animation: bounceIn 1s ease-out;
        }

        /* Robot Head */
        .robot-head {
            width: 145px;
            height: 120px;
            background: linear-gradient(145deg, #ffffff 0%, #6AC4F3 100%);
            border-radius: 70px 70px 40px 40px;
            position: absolute;
            top: 0;
            left: 30px;
            box-shadow:
                inset -8px -8px 16px rgba(0, 0, 0, 0.1),
                0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Face Screen */
        .face-screen {
            width: 100px;
            height: 70px;
            background: #e6f7ff;
            border-radius: 20px;
            position: absolute;
            top: 25px;
            left: 20px;
            overflow: hidden;
        }

        /* Eyes */
        .eyes {
            position: relative;
            width: 100%;
            height: 30px;
            margin-top: 15px;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .eye {
            width: 24px;
            height: 24px;
            background: #1e90ff;
            border-radius: 50%;
            position: relative;
            animation: blinking 3s infinite;
        }

        .eye::after {
            content: '';
            position: absolute;
            top: 4px;
            right: 4px;
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
        }

        /* Cheeks */
        .cheek {
            width: 12px;
            height: 12px;
            background: #ffb7b7;
            border-radius: 50%;
            position: absolute;
            top: 45px;
            opacity: 0.6;
        }

        .cheek.left {
            left: 15px;
        }

        .cheek.right {
            right: 15px;
        }

        /* Mouth */
        .mouth {
            width: 30px;
            height: 10px;
            background: #1e90ff;
            border-radius: 0 0 15px 15px;
            position: absolute;
            bottom: 15px;
            left: 35px;
            transition: all 0.3s ease;
        }

        /* Chest Screen */
        .chest-screen {
            width: 60px;
            height: 40px;
            background: #e6f7ff;
            border-radius: 10px;
            position: absolute;
            top: 40px;
            left: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Speech Bubble */
        .speech-bubble {
            position: fixed;
            bottom: 180px;
            right: 100px;
            background: #fff;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            max-width: 250px;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
            z-index: 99999;
            border: 2px solid #166AC9;
        }

        .speech-bubble.show {
            opacity: 1;
            transform: translateY(0);
        }

        .speech-bubble::after {
            content: '';
            position: absolute;
            bottom: -15px;
            right: 30px;
            border-width: 15px 15px 0;
            border-style: solid;
            border-color: #fff transparent;
        }

        /* Animations */
        @keyframes bounceIn {
            0% {
                transform: scale(0);
            }

            60% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes blinking {

            0%,
            48%,
            52%,
            100% {
                transform: scaleY(1);
            }

            50% {
                transform: scaleY(0.1);
            }
        }

        @keyframes wave {

            0%,
            100% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(-10deg);
            }

            75% {
                transform: rotate(10deg);
            }
        }

        .robot-guide:hover .mouth {
            height: 15px;
            border-radius: 5px 5px 15px 15px;
        }

        .arm.left {
            left: -30px;
            transform: rotate(10deg);
        }

        .arm.right {
            right: -30px;
            transform: rotate(-10deg);
        }

        .hand {
            width: 30px;
            height: 30px;
            background: linear-gradient(145deg, #ffffff 0%, #f0f0f0 100%);
            position: absolute;
            bottom: -15px;
            left: 5px;
            border-radius: 15px;
            box-shadow:
                inset -2px -2px 4px rgba(0, 0, 0, 0.1),
                0 3px 6px rgba(0, 0, 0, 0.1);
        }

        @keyframes pointLeft {
            0% {
                transform: rotate(10deg);
            }

            100% {
                transform: rotate(-60deg) translateY(10px);
            }
        }

        @keyframes pointRight {
            0% {
                transform: rotate(-10deg);
            }

            100% {
                transform: rotate(60deg) translateY(10px);
            }
        }

        /* Animation cho robot nhảy nhẹ khi chỉ tay */
        @keyframes robotJump {
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0);
            }
        }

        /* Khi element được hover, robot sẽ trỏ tay về hướng đó */
        .pointing-left .arm.left {
            animation: pointLeft 0.3s forwards;
        }

        .pointing-right .arm.right {
            animation: pointRight 0.3s forwards;
        }

        .toggle-assistant {
            position: absolute;
            top: 0;
            right: -20px;
        }

        /* CSS */
        .button-toogle-robot {
            background-color: #FFFFFF;
            border: 1px solid rgb(209, 213, 219);
            border-radius: .5rem;
            box-sizing: border-box;
            color: #111827;
            font-family: "Inter var", ui-sans-serif, system-ui, -apple-system, system-ui, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-size: .875rem;
            font-weight: 600;
            line-height: 1.25rem;
            padding: .75rem 1rem;
            text-align: center;
            text-decoration: none #D1D5DB solid;
            text-decoration-thickness: auto;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            cursor: pointer;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
        }

        .button-toogle-robot:hover {
            background-color: rgb(249, 250, 251);
        }

        .button-toogle-robot:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
        }

        .button-toogle-robot:focus-visible {
            box-shadow: none;
        }

        .custom-loader-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: white;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 999999999;
        }

        .custom-loader-image {
            width: 150px;
            height: 150px;
            margin-bottom: 20px;
        }

        .custom-loader-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            animation: custom-bounce 2s infinite ease-in-out;
        }

        .custom-progress-container {
            position: relative;
            width: 300px;
            height: 30px;
            background: rgba(106, 196, 243, 0.2);
            border-radius: 25px;
            padding: 3px;
            margin-top: 20px;
            overflow: hidden;
        }

        .custom-progress {
            width: 0%;
            height: 100%;
            background: #6AC4F3;
            border-radius: 25px;
            animation: custom-loading 2s ease-in-out infinite;
            position: relative;
            display: flex;
            align-items: center;
        }

        .custom-bubble {
            position: absolute;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            animation: custom-float-bubble 2s infinite linear;
        }

        .custom-bubble:nth-child(1) {
            width: 12px;
            height: 12px;
            left: 10%;
            animation-delay: 0.2s;
        }

        .custom-bubble:nth-child(2) {
            width: 8px;
            height: 8px;
            left: 30%;
            animation-delay: 0.6s;
        }

        .custom-bubble:nth-child(3) {
            width: 10px;
            height: 10px;
            left: 50%;
            animation-delay: 0.4s;
        }

        .custom-star {
            position: absolute;
            width: 10px;
            height: 10px;
            background: rgba(255, 255, 255, 0.6);
            clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
            animation: custom-twinkle 1.5s infinite ease-in-out;
        }

        .custom-star:nth-child(4) {
            left: 70%;
            animation-delay: 0.3s;
        }

        .custom-star:nth-child(5) {
            left: 85%;
            animation-delay: 0.7s;
        }

        .custom-loading-text {
            margin-top: 15px;
            color: #6AC4F3;
            font-size: 16px;
            letter-spacing: 2px;
            font-weight: 600;
        }

        .custom-progress::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, 0.2),
                    transparent);
            animation: custom-shimmer 2s infinite;
        }

        @keyframes custom-bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        @keyframes custom-loading {
            0% {
                width: 0%;
            }

            50% {
                width: 100%;
            }

            100% {
                width: 100%;
            }
        }

        @keyframes custom-float-bubble {
            0% {
                transform: translateY(0) scale(1);
                opacity: 0.7;
            }

            50% {
                transform: translateY(-5px) scale(1.2);
                opacity: 1;
            }

            100% {
                transform: translateY(0) scale(1);
                opacity: 0.7;
            }
        }

        @keyframes custom-twinkle {

            0%,
            100% {
                opacity: 0.3;
                transform: scale(0.8);
            }

            50% {
                opacity: 1;
                transform: scale(1.2);
            }
        }

        @keyframes custom-shimmer {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(200%);
            }
        }
    </style>
</head>

<body>
    {{-- <div class="custom-loader-container">
        <div class="custom-loader-image">
            <img src="https://media2.giphy.com/media/v8jUfaclrsG9x8At9Z/giphy.gif?cid=6c09b952f8e8eb8askp67jsi3d7yyr410hbs1r6sr8tbxkd7&ep=v1_internal_gif_by_id&rid=giphy.gif&ct=g"
                alt="Cute Loading Character" />
        </div>
        <div class="custom-progress-container">
            <div class="custom-progress">
                <div class="custom-bubble"></div>
                <div class="custom-bubble"></div>
                <div class="custom-bubble"></div>
                <div class="custom-star"></div>
                <div class="custom-star"></div>
            </div>
        </div>
        <div class="custom-loading-text">Sắp xong rồi nè...bạn chờ một chút nhé 🌸</div>
    </div> --}}
    @if (request()->is('/'))
        <div class="robot-guide">
            <div class="robot-head">
                <div class="face-screen">
                    <div class="eyes">
                        <div class="eye left"></div>
                        <div class="eye right"></div>
                    </div>
                    <div class="cheek left"></div>
                    <div class="cheek right"></div>
                    <div class="mouth"></div>
                </div>
            </div>
            {{-- <div class="robot-body">
            <div class="chest-screen">
                <div class="heart">❤️</div>
            </div>
        </div> --}}
            <button id="toggle_assistant" class="toggle-assistant button-toogle-robot">
                Ẩn hướng dẫn
            </button>
        </div>
        <div class="speech-bubble" id="robot-speech">
        </div>
    @endif

    <div class="layout-wrapper">
        @if (!Request::is('detail*'))
            <header id="header">
                @include('client.layouts.header')
            </header>
        @else
            <header id="header">
                @include('client.layouts.header-study')
            </header>
        @endif

        <div class="main-content">
            <div class="d-flex content">
                @if (!Request::is('detail*') && !Request::is('mypage*'))
                    <aside class="sidebar" id="sidebar">
                        @include('client.layouts.sidebar')
                    </aside>
                @endif
                <div class="container-fluid">
                    <div id="main-wrapper">
                        @yield('content')
                    </div>
                    @component('client.components.common-component')
                    @endcomponent
                    <div class="loading-overlay">
                        <div class="loading-spinner"></div>
                    </div>
                </div>
            </div>
            <footer id="footer">
                @if (!Request::is('detail*'))
                    @include('client.layouts.footer')
                @else
                    @include('client.layouts.footer-study')
                @endif
            </footer>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/client/common.js') }}"></script>
    <script>
        $(document).ready(function() {
            let isHiddenRobot = localStorage.getItem('robotHidden') === 'true'; // Retrieve state from localStorage

            // Toggle function to show or hide assistant
            function toggleAssistant() {
                isHiddenRobot = !isHiddenRobot;

                // Save the new state to localStorage
                localStorage.setItem('robotHidden', isHiddenRobot);

                if (isHiddenRobot) {
                    $('.robot-head').hide(); // Hide the robot
                    $('#robot-speech').hide(); // Hide the speech bubble
                    $('#toggle_assistant').text('Hiện hướng dẫn');
                } else {
                    $('.robot-head').show(); // Show the robot
                    $('#robot-speech').show(); // Show the speech bubble
                    $('#toggle_assistant').text('Ẩn hướng dẫn');
                }
            }

            // Set initial visibility based on localStorage
            if (isHiddenRobot) {
                $('.robot-head').hide(); // Hide the robot if the state is stored as hidden
                $('#robot-speech').hide(); // Hide the speech bubble
                $('#toggle_assistant').text('Hiện hướng dẫn');
            } else {
                $('.robot-head').show(); // Show the robot if the state is stored as shown
                $('#robot-speech').show(); // Show the speech bubble
                $('#toggle_assistant').text('Ẩn hướng dẫn');
            }

            // Bind the toggle function to the button click event
            $('#toggle_assistant').on('click', toggleAssistant);
            // Helper function to get the outer height of an element or return 0 if not found
            const getOuterHeight = selector => $(selector).outerHeight() || 0;

            const adjustLayout = () => {
                const windowHeight = $(window).height(); // jQuery method for consistency
                const headerHeight = getOuterHeight('#header');
                const sideBarHeight = getOuterHeight('#sidebar');
                const footerHeight = getOuterHeight('#footer');

                const contentHeight = windowHeight - headerHeight;

                $('.sidebar-my-page').css({
                    'height': contentHeight,
                })
                // Check if the screen width is for mobile or tablet
                if (window.matchMedia('(max-width: 1024px)').matches) {
                    const contentHeight = windowHeight - headerHeight - sideBarHeight;
                    $('.main-footer').css({
                        'padding-bottom': sideBarHeight + 10,
                    });
                } else {
                    $('.main-footer').css({
                        'padding-bottom': 0,
                    });
                }

                if ($('.layout-my-page').length == 0) {
                    $('.main-content').css({
                        'margin-top': headerHeight,
                        'height': contentHeight,
                    });
                } else {
                    const navigateBackHeight = $('.navigate-back').outerHeight() || 0;
                    const contentHeight = windowHeight - headerHeight;

                    $('.main-content').css({
                        'margin-top': headerHeight + 'px',
                        'height': `calc(100vh - ${headerHeight}px)`,
                    });
                }
            };

            function openModalStreak() {
                $('#modalLoginStreak').modal('show');
            }

            @if (Auth::check())
                if (!localStorage.getItem("lastLoginDate")) {
                    // First time login, store current date and show modal
                    localStorage.setItem("lastLoginDate", new Date().toDateString());
                    openModalStreak();
                } else {
                    // Check if it's a different day
                    const lastLoginDate = new Date(localStorage.getItem("lastLoginDate")).toDateString();
                    const currentDate = new Date().toDateString();

                    if (lastLoginDate !== currentDate) {
                        // It's a different day, show modal and update date
                        openModalStreak();
                        localStorage.setItem("lastLoginDate", currentDate);
                    }
                }

                $('.owned-login-streak').on('click', function() {
                    openModalStreak();
                });
            @else
                localStorage.removeItem("lastLoginDate");
            @endif

            adjustLayout();
            $(window).resize(adjustLayout); // Adjust layout on window resize
            class RobotGuide {
                constructor() {
                    this.speechBubble = document.getElementById('robot-speech');
                    this.defaultMessage =
                        'Chào bạn! Mình là hướng dẫn viên của HIKARI ELEARNING, luôn sẵn sàng giúp bạn khám phá mọi góc nhỏ của trang này. Di chuột qua các phần để mình hướng dẫn bạn nhé! 🌟';
                    this.guides = new Map();
                    this.hoveredElement = false; // Track if we're hovering a guided element
                    this.initialize();
                }

                initialize() {
                    // Show initial message
                    this.showSpeechBubble(this.defaultMessage);

                    // Hide speech bubble when clicking outside
                    document.addEventListener('click', (e) => {
                        if (
                            !e.target.closest('.robot-guide') &&
                            !e.target.closest('.guided-element') &&
                            !e.target.closest('#robot-speech')
                        ) {
                            this.hideSpeechBubble();
                        }
                    });

                    // Prevent the bubble from hiding when hovered over
                    this.speechBubble.addEventListener('mouseenter', () => {
                        this.hoveredElement = true;
                    });
                    this.speechBubble.addEventListener('mouseleave', () => {
                        this.hoveredElement = false;
                        this.showSpeechBubble(this.defaultMessage);
                    });
                }

                setGuides(guidesData) {
                    // Clear existing guides
                    this.guides.clear();

                    // Process and set new guides
                    guidesData.forEach(guide => {
                        const elements = document.querySelectorAll(guide.selector);
                        elements.forEach(element => {
                            // Add guided-element class for styling
                            element.classList.add('guided-element');

                            // Add hover listeners
                            element.addEventListener('mouseenter', () => {
                                this.hoveredElement = true;
                                this.showSpeechBubble(guide.message);
                            });

                            element.addEventListener('mouseleave', () => {
                                this.hoveredElement = false;
                                // Delay hiding to prevent flickering when moving to the bubble
                                setTimeout(() => {
                                    if (!this.hoveredElement) {
                                        this.showSpeechBubble(this
                                            .defaultMessage);
                                    }
                                }, 100); // Adjust delay as needed
                            });

                            // Store in guides map
                            this.guides.set(element, guide);
                        });
                    });
                }

                showSpeechBubble(message) {
                    this.speechBubble.textContent = message;
                    this.speechBubble.classList.add('show');
                }

                hideSpeechBubble() {
                    this.speechBubble.classList.remove('show');
                }

                setDefaultMessage(message) {
                    this.defaultMessage = message;
                }
            }

            // Set guides
            @if (request()->is('/'))
                // Initialize robot guide
                const robotGuide = new RobotGuide();

                // Example usage:
                const guideData = [{
                        selector: '.learning-series-list',
                        message: `
        🎉 Đây là danh sách các khoá học tiếng Nhật được thiết kế siêu trực quan để bạn dễ dàng tìm kiếm và chọn khoá học phù hợp nhất với mình. Hãy click vào bất kỳ khoá học nào để xem chi tiết và khám phá thêm nhé! Chúc bạn tìm được khoá học ưng ý! 🌸
        `
                    },
                    {
                        selector: '.trial-btn',
                        message: 'Để giúp học viên trải nghiệm trước khi đăng ký chính thức, mỗi khoá học có nút "Học thử". Bạn có thể nhấp vào đây để thử nghiệm nội dung mẫu của khoá học.'
                    },
                    {
                        selector: '.date-duration',
                        message: 'Thông tin về thời hạn của khoá học, ví dụ như "1 tháng" hoặc "2 tháng", giúp bạn lên kế hoạch học tập hợp lý.'
                    },
                    {
                        selector: '.info-course-card',
                        message: 'Biểu tượng số bài học và số chương cho thấy độ dài và độ chi tiết của khoá học.'
                    },
                    {
                        selector: '.exam-series-list',
                        message: 'Đây là danh sách các khoá luyện thi tiếng Nhật, được thiết kế trực quan để giúp học viên dễ dàng tìm hiểu và lựa chọn khoá phù hợp với nhu cầu và trình độ của mình của mình. Khi click vào 1 khoá luyện thi có thể xem được chi tiết khoá luyện thi đó đó'
                    },
                    {
                        selector: '.my-icon-info',
                        message: 'Điểm thưởng của bạn nè! 🌟 Con số bên cạnh huy chương này chính là thành quả hiện có bạn đã tích lũy được. Cùng tiếp tục khám phá và tăng số điểm này nhé! 🥰'
                    },
                    {
                        selector: '.owned-login-streak',
                        message: 'Đây là số ngày chuỗi đăng nhập liên tiếp của bạn! 🔥 Hãy cố gắng duy trì chuỗi này mỗi ngày để nhận thêm phần thưởng và giữ "ngọn lửa" học tập luôn bùng cháy nhé! 🚀'
                    },
                    {
                        selector: '.my-course-info',
                        message: `
            Đây là phần "Khóa học của tôi" – nơi bạn có thể theo dõi tiến độ học tập của mình! 📚\n
            Mỗi khóa học sẽ hiển thị mức độ hoàn thành, ngày giờ học gần nhất, và có nút "Tiếp tục học" để bạn dễ dàng quay lại và tiếp tục chinh phục kiến thức.\n
            Hãy duy trì nhịp học đều đặn để nhanh chóng hoàn thành các khóa học nhé! 🚀
        `
                    },
                    {
                        selector: '.article-page',
                        message: `
                            📚 Khi bạn nhấn vào “Bài viết”, bạn sẽ thấy các thông tin mới nhất về sự kiện, tin tức và những bài viết hữu ích. Hãy chọn bài viết bạn thích để khám phá thêm nhé! Nếu chưa thấy bài viết nào, đừng lo, chúng mình sẽ cập nhật sớm thôi! 😊
                        `
                    },
                    {
                        selector: '.user-avatar',
                        message: `
                            Nhấn vào avatar nhỏ xinh để mở ra kho quản lý học tập của bạn nha! Ở đó có đầy đủ các mục như Trang cá nhân, Điểm tích lũy, Khóa học và nhiều điều thú vị khác đang chờ bạn khám phá. Bắt đầu thôi nào! ✨
                        `
                    },
                    {
                        selector: '.avatar-icon-mobile',
                        message: `
                            Nhấn vào avatar nhỏ xinh để mở ra kho quản lý học tập của bạn nha! Ở đó có đầy đủ các mục như Trang cá nhân, Điểm tích lũy, Khóa học và nhiều điều thú vị khác đang chờ bạn khám phá. Bắt đầu thôi nào! ✨
                        `
                    },
                    {
                        selector: '.button-info',
                        message: `
                            Nếu bạn đã sở hữu khoá học, nút ‘Học ngay’ sẽ hiện ra để bạn dễ dàng quay lại học bất cứ lúc nào. Còn nếu thấy nút ‘Mua ngay’, hãy click vào đó và mình sẽ đưa bạn tới trang thanh toán nhanh chóng để sở hữu khoá học đó nhé! Chúc bạn học vui và khám phá thật nhiều điều thú vị! 🌟
                        `
                    }
                ];

                robotGuide.setGuides(guideData);
            @endif
        });
        window.addEventListener('load', () => {
            setTimeout(() => {
                document.querySelector('.custom-loader-container').style.display = 'none';
            }, 800);
        });
    </script>
    @if (Auth::check())
        @include('client.components.streak');
    @endif
    @yield('scripts')
</body>

</html>
