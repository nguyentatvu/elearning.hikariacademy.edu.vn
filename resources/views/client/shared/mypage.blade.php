@extends('client.app')

@section('styles')
    <link href="{{ asset('css/pages/mypage.css') }}" rel="stylesheet">
    <style>
        /* Core variables */
        :root {
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 70px;
            --transition-speed: 0.3s;
        }

        /* Layout */
        #wrapper {
            min-height: 100vh;
        }

        .mypage-content {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar-my-page {
            width: var(--sidebar-width);
            background-color: #fff;
            padding: 1.25rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
            transition: all var(--transition-speed) ease;
            z-index: 1030;
        }

        .sidebar-my-page.collapsed {
            width: var(--sidebar-collapsed-width);
            padding: 1.25rem 0;
        }

        /* Hide text when collapsed */
        .sidebar-my-page.collapsed .nav-text,
        .sidebar-my-page.collapsed .profile-section h5,
        .sidebar-my-page.collapsed .badge-container,
        .sidebar-my-page.collapsed .section-title {
            display: none;
        }

        /* Center icons when collapsed */
        .sidebar-my-page.collapsed .nav-link-sidebar,
        .sidebar-my-page.collapsed .study-button {
            justify-content: center;
            padding: 0.75rem 0;
        }

        .sidebar-my-page.collapsed .nav-link-sidebar i,
        .sidebar-my-page.collapsed .study-button i {
            margin: 0;
            font-size: 1.2rem;
        }

        /* Profile image adjustments */
        .profile-section {
            text-align: center;
            margin-bottom: 1.875rem;
            transition: all var(--transition-speed) ease;
            display: flex;
            justify-content: center;
            flex-direction: column;
        }

        .profile-image img {
            width: 40px;
            height: 40px;
            border-radius: 100%;
            color: white;
            font-size: 1.5rem;
            margin: 0 auto 0.9375rem;
            transition: all var(--transition-speed) ease;
            object-fit: cover;
            display: flex;
            justify-content: center;
        }

        .sidebar-my-page.collapsed .profile-image {
            height: 40px;
            font-size: 1rem;
            margin-bottom: 0;
        }

        /* Navigation */
        .nav-link-sidebar {
            color: #333;
            padding: 0.75rem 0.9375rem;
            border-radius: 8px;
            margin-bottom: 0.3125rem;
            display: flex;
            align-items: center;
            gap: 0.625rem;
            transition: background-color var(--transition-speed) ease;
        }

        .nav-link-sidebar:hover {
            background-color: #f8f9fa;
        }

        /* Study button */
        .study-button {
            background-color: var(--secondary);
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 8px;
            width: 100%;
            margin: 0.625rem 0;
            display: flex;
            align-items: center;
            gap: 0.625rem;
            transition: all var(--transition-speed) ease;
        }

        .study-button:hover {
            background-color: var(--secondary);
            color: white;
        }

        /* Toggle button */
        .toggle-btn {
            position: absolute;
            left: var(--sidebar-width);
            top: 0;
            background-color: var(--secondary);
            color: white;
            border: none;
            border-radius: 0 8px 8px 0;
            padding: 0.625rem;
            cursor: pointer;
            z-index: 1031;
            transition: left var(--transition-speed) ease;
        }

        .toggle-btn.collapsed {
            left: var(--sidebar-collapsed-width);
        }

        /* Main content */
        .main-content-area {
            flex-grow: 1;
            padding: 1.25rem;
            transition: margin-left var(--transition-speed) ease;
            width: calc(100% - var(--sidebar-width));
        }

        .main-content-area.expanded {
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .mypage-content {
                display: unset;
            }

            .sidebar-my-page {
                width: 100%;
                position: absolute;
            }

            .sidebar-my-page.collapsed {
                transform: translateX(-200%);
            }

            .main-content-area.expanded {
                margin-left: 0;
                width: 100%;
            }

            .toggle-btn {
                left: 0;
            }

            .toggle-btn.collapsed {
                left: 0;
            }
        }
    </style>
    @yield('mypage-styles')
@endsection

@section('content')
    <div id="wrapper">
        <div class="mypage-content position-relative">
            <aside class="sidebar-my-page">
                <div class="profile-section">
                    @if (Auth::check())
                        <div class="profile-image">
                            @php
                                $avatar = Auth::user()->image;
                            @endphp
                            <img src="{{ asset('uploads/users/thumbnail/' . $avatar) }}" alt="User Avatar">
                        </div>
                        <h5>{{ Auth::user()->name }}</h5>
                    @endif
                </div>
                <section>
                    <h6 class="section-title">Quản lý và học tập</h6>
                    <nav class="nav flex-column">
                        <a href="{{ url('mypage/my-personal') }}"
                            class="nav-link-sidebar {{ setActiveClass('mypage/my-personal') }}">
                            <i class="bi bi-person"></i> <span class="nav-text">Trang cá nhân</span>
                        </a>
                        <a href="{{ url('mypage/reward-point') }}"
                            class="nav-link-sidebar {{ setActiveClass('mypage/reward-point') }}">
                            <i class="bi bi-star"></i> <span class="nav-text">Điểm tích lũy</span>
                        </a>
                        <a href="{{ url('mypage/leaderboard') }}"
                            class="nav-link-sidebar {{ setActiveClass('mypage/leaderboard') }}">
                            <i class="bi bi-trophy"></i> <span class="nav-text">Bảng xếp hạng</span>
                        </a>
                        <a href="{{ url('mypage/my-courses') }}"
                            class="nav-link-sidebar {{ setActiveClass('mypage/my-courses') }}">
                            <i class="bi bi-book"></i> <span class="nav-text">Khóa học</span>
                        </a>
                        <a href="{{ url('mypage/my-exams') }}"
                            class="nav-link-sidebar {{ setActiveClass('mypage/my-exams') }}">
                            <i class="bi bi-pencil"></i> <span class="nav-text">Khóa luyện thi</span>
                        </a>
                        <a href="{{ url('mypage/my-comments') }}"
                            class="nav-link-sidebar {{ setActiveClass('mypage/my-comments') }}">
                            <i class="bi bi-chat-dots"></i> <span class="nav-text">Câu hỏi của bạn</span>
                        </a>
                        <a href="{{ url('mypage/mock-exam/list') }}"
                            class="nav-link-sidebar {{ setActiveClass('mypage/mock-exam/*') }}">
                            <i class="bi bi-journal-text"></i> <span class="nav-text">Phòng thi của bạn</span>
                        </a>
                        <a href="{{ url('mypage/my-exam-result') }}"
                            class="nav-link-sidebar {{ setActiveClass('mypage/my-exam-result') }}">
                            <i class="bi bi-bar-chart-line"></i> <span class="nav-text">Kết quả thi</span>
                        </a>
                        <a href="{{ url('mypage/payment-management') }}"
                            class="nav-link-sidebar {{ setActiveClass('mypage/payment-management') }}">
                            <i class="bi bi-credit-card"></i> <span class="nav-text">Quản lý thanh toán</span>
                        </a>
                        <a href="{{ url('mypage/recharge-point') }}"
                            class="nav-link-sidebar {{ setActiveClass('mypage/recharge-point') }}">
                            <i class="bi bi-wallet"></i> <span class="nav-text">Nạp điểm</span>
                        </a>
                    </nav>
                </section>
            </aside>

            <button class="toggle-btn">
                <i class="bi bi-chevron-left" id="toggle-icon"></i>
            </button>

            <main class="main-content-area">
                <div class="px-4">
                    <div class="mb-4">
                        <a href="/">
                            <span>Trang chủ/</span>
                        </a>
                        <strong><span id="tittle_my_page">Điểm tích lũy</span></strong>
                    </div>
                    @yield('mypage-content')
                </div>
            </main>
        </div>
    </div>
@endsection

@section('scripts')
    @yield('mypage-scripts')
    <script>
        $(document).ready(function() {
            const $sidebar = $('.sidebar-my-page');
            const $toggleBtn = $('.toggle-btn');
            const $mainContent = $('.main-content-area');
            const $toggleIcon = $('#toggle-icon');

            function toggleSidebar() {
                $sidebar.toggleClass('collapsed');
                $toggleBtn.toggleClass('collapsed');
                $mainContent.toggleClass('expanded');
                $toggleIcon.toggleClass('bi-chevron-left bi-chevron-right');
            }

            function handleMobileView() {
                const isMobile = $(window).width() <= 768;
                if (isMobile) {
                    $sidebar.addClass('collapsed');
                    $toggleBtn.addClass('collapsed');
                    $mainContent.addClass('expanded');
                }
            }

            // Event listeners
            $toggleBtn.on('click', toggleSidebar);
            $(window).on('resize', handleMobileView);

            // Initial setup
            handleMobileView();

            // Function to add tooltip to specific text
            function addTooltip(elementId, fullText, tooltipPart, tooltipText) {
                // Create the tooltip text by replacing the tooltip part with the icon
                const icon = `
                    <i class="bi bi-info-circle tooltip-icon"
                    style="cursor: pointer;"
                    data-bs-toggle="tooltip"
                    data-bs-html="true"
                    data-bs-placement="bottom"
                    data-bs-custom-class="custom-tooltip"
                    data-bs-title="${tooltipText}"
                    data-bs-trigger="hover"></i>`;
                const updatedText = fullText.replace(tooltipPart, `${tooltipPart} ${icon}`);

                // Update the text in the specified element
                $(`#${elementId}`).html(updatedText);

                // Initialize Bootstrap tooltip
                $('[data-bs-toggle="tooltip"]').tooltip();
            }

            const activeText = $('.sidebar-my-page a.study-button').text();

            // Check if the text is "Điểm tích lũy" and call the function if true
            if (activeText === "Điểm tích lũy") {
                const tooltipText =
                    `<div><strong>Điểm tích lũy</strong> là điểm bạn nhận được khi tham gia các hoạt động học tập như:</div>
                        <ul>
                            <li>Làm bài tập & kiểm tra</li>
                            <li>Xem video bài học</li>
                            <li>Duy trì chuỗi đăng nhập</li>
                        </ul>
                        <div>Điểm có thể dùng để quy đổi các khuyến mãi khả dụng ở mục <strong>“Quy đổi điểm”</strong> bên dưới.</div>
                        <div>Bạn cũng có thể nạp tiền để đổi thành điểm tích lũy và sử dụng cho các khuyến mãi, nhưng điểm nạp sẽ không tính vào xếp hạng tuần.</div>
                `;

                // Call the function with appropriate parameters
                addTooltip('tittle_my_page', activeText, 'Điểm tích lũy', tooltipText);
            } else if (activeText === "Bảng xếp hạng") {
                const tooltipText =
                    `
                        <div><strong>Điểm xếp hạng</strong> của bạn được tính dựa trên điểm tích lũy từ các hoạt động học tập (như làm bài tập, xem video, duy trì chuỗi đăng nhập).</div>
                        <br>
                        <div><strong>Bảng xếp hạng</strong> sẽ được làm mới mỗi tuần, để bạn có thể nhìn thấy sự tiến bộ của bản thân và bạn bè!</div>
                    `;

                // Call the function with appropriate parameters
                addTooltip('tittle_my_page', activeText, 'Bảng xếp hạng', tooltipText);
            } else {
                // Update the text normally if it doesn't match
                $('#tittle_my_page').text(activeText);
            }
        });
    </script>
@endsection
