@extends('client.app')

@section('styles')
    <link href="{{ asset('css/pages/mypage.css') }}" rel="stylesheet">
    <style>
        .custom-tooltip {
           --bs-tooltip-bg: var(--primary);
           --bs-tooltip-color: var(--bs-white);
           --bs-tooltip-max-width: 300px;
       }

       .custom-tooltip  div {
           text-align: start;
       }

       .custom-tooltip ul li {
           list-style: decimal;
       }
   </style>
    @yield('mypage-styles')
@endsection

@section('content')
    <div class="w-100">
        <div class="container pb-5 layout-my-page">
            <a href="/" class="navigate-back">
                <i class="bi bi-chevron-left fs-4"></i>
                <span id="tittle_my_page" class="fs-3">Điểm tích lũy</span>
            </a>
            <div class="mypage-navigation">
                <nav class="mypage-navigation__list">
                    <ul>
                        <li><a href="{{ url('mypage/my-personal') }}"
                                class="{{ setActiveClass('mypage/my-personal') }}">Trang cá nhân</a></li>
                        <li><a href="{{ url('mypage/reward-point') }}"
                                class="{{ setActiveClass('mypage/reward-point') }}">Điểm tích lũy</a></li>
                        <li><a href="{{ url('mypage/leaderboard') }}"
                                class="{{ setActiveClass('mypage/leaderboard') }}">Bảng xếp hạng</a></li>
                        <li><a href="{{ url('mypage/my-courses') }}" class="{{ setActiveClass('mypage/my-courses') }}">Khóa
                                học</a></li>
                        <li><a href="{{ url('mypage/my-exams') }}" class="{{ setActiveClass('mypage/my-exams') }}">Khóa
                                luyện thi</a></li>
                        <li><a href="{{ url('mypage/my-comments') }}"
                                class="{{ setActiveClass('mypage/my-comments') }}">Câu hỏi của bạn</a></li>
                        <li><a href="{{ url('mypage/mock-exam/list') }}"
                                class="{{ setActiveClass('mypage/mock-exam/list') }}">Phòng thi của bạn</a></li>
                        <li><a href="{{ url('mypage/my-exam-result') }}"
                                class="{{ setActiveClass('mypage/my-exam-result') }}">Kết quả thi</a></li>
                        <li><a href="{{ url('mypage/payment-management') }}"
                                class="{{ setActiveClass('mypage/payment-management') }}">Quản lý thanh toán</a></li>
                        <li><a href="{{ url('mypage/recharge-point') }}"
                                class="{{ setActiveClass('mypage/recharge-point') }}">Nạp</a></li>
                    </ul>
                </nav>
            </div>
            <div class="mypage-content">
                @yield('mypage-content')
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @yield('mypage-scripts')
    <script>
        $(document).ready(function() {
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

            const activeText = $('.mypage-navigation__list a.active').text();

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
