@extends('client.app')

@section('styles')
    <style>
        .header {
            color: white;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            margin: 0;
        }

        .header p {
            margin: 0;
        }

        .header img {
            width: 30px;
            height: 30px;
        }

        .news-item {
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            margin-bottom: 10px;
            width: 100%;
        }

        .news-item img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }

        .news-item .content-lesson p {
            margin: 0;
        }

        .news-item i {
            margin-left: auto;
        }

        .left-side {
            background-color: #f8f9fa;
            height: 100vh;
            text-align: center;
            background-image: url('{{ asset('images/icons/vertical-road.svg') }}');
            background-repeat: repeat-y;
            background-position: center;
            background-size: contain;
            position: relative;
            overflow-y: hidden;
        }

        .location-day {
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
            transition: transform 0.5s ease-in-out, box-shadow 0.5s ease-in-out;
        }

        .location-day:hover {
            transform: translateX(-50%) scale(1.1);
            box-shadow: none;
        }

        .left-side .car {
            position: absolute;
            height: 60px;
            left: 50%;
            transform: translateX(-50%);
            top: 0;
            background: transparent;
            z-index: 10;
        }

        .left-side .finish {
            position: absolute;
            height: 60px;
            left: 50%;
            transform: translateX(-50%);
            bottom: 0;
            z-index: 11;
        }

        .left-side .finish-line {
            position: absolute;
            width: 100%;
            left: 50%;
            transform: translateX(-50%);
            bottom: 0;
            z-index: 1;
            padding: 0 10px;
        }

        .left-side .start-gate {
            position: absolute;
            height: 60px;
            left: 50%;
            transform: translateX(-50%);
            top: 0;
        }

        .left-side .start-line {
            position: absolute;
            width: 100%;
            left: 50%;
            transform: translateX(-50%);
            top: 30px;
        }

        .star {
            position: absolute;
            font-size: 24px;
            color: #FFD700;
            transition: transform 0.5s ease, opacity 0.5s ease;
        }

        /* Mobile (phones) */
        @media (max-width: 575.98px) {
            /* CSS cho thiết bị di động */
        }

        /* Tablet (small devices) */
        @media (min-width: 576px) and (max-width: 767.98px) {
            /* CSS cho tablet nhỏ */
        }

        /* Tablet (large devices) */
        @media (min-width: 768px) and (max-width: 991.98px) {
            /* CSS cho tablet lớn */
        }

        /* Laptop (small desktops) */
        @media (min-width: 992px) and (max-width: 1199.98px) {
            /* CSS cho laptop */
        }

        /* Desktop (large screens) */
        @media (min-width: 1200px) {
            /* CSS cho PC và màn hình lớn */
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Left side (1 column) -->
            <div class="col-2 col-sm-1 left-side">
                <div class="row"></div>
                <img class="start-line" src="{{ asset('images/icons/start-line.svg') }}" alt="">
                <img class="car" src="{{ asset('images/icons/car.svg') }}" alt="">
                <img class="start-gate" src="{{ asset('images/icons/start-gate.svg') }}" alt="">
                <img class="finish" src="{{ asset('images/icons/finish.png') }}" alt="">
            </div>

            <!-- Right side (11 columns) -->
            <div class="col-10 col-sm-11">
                <div class="roadmap" id="news-sections"></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const sections = [{
                week: "Tuần 1",
                message: "Các sự kiện nổi bật tuần 1",
                days: [{
                        day: "Thứ 2, 16/10",
                        items: [{
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 1 ngày thứ 2",
                                lms_id: "LMS001"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 2 ngày thứ 2",
                                lms_id: "LMS002"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 3 ngày thứ 2",
                                lms_id: "LMS003"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 4 ngày thứ 2",
                                lms_id: "LMS004"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 5 ngày thứ 2",
                                lms_id: "LMS005"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 6 ngày thứ 2",
                                lms_id: "LMS006"
                            }
                        ]
                    },
                    {
                        day: "Thứ 3, 17/10",
                        items: [{
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 1 ngày thứ 3",
                                lms_id: "LMS007"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 2 ngày thứ 3",
                                lms_id: "LMS008"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 3 ngày thứ 3",
                                lms_id: "LMS009"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 4 ngày thứ 3",
                                lms_id: "LMS010"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 5 ngày thứ 3",
                                lms_id: "LMS011"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 6 ngày thứ 3",
                                lms_id: "LMS012"
                            }
                        ]
                    },
                    {
                        day: "Thứ 4, 18/10",
                        items: [{
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 1 ngày thứ 4",
                                lms_id: "LMS013"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 2 ngày thứ 4",
                                lms_id: "LMS014"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 3 ngày thứ 4",
                                lms_id: "LMS015"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 4 ngày thứ 4",
                                lms_id: "LMS016"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 5 ngày thứ 4",
                                lms_id: "LMS017"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 6 ngày thứ 4",
                                lms_id: "LMS018"
                            }
                        ]
                    },
                    {
                        day: "Thứ 5, 19/10",
                        items: [{
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 1 ngày thứ 5",
                                lms_id: "LMS019"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 2 ngày thứ 5",
                                lms_id: "LMS020"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 3 ngày thứ 5",
                                lms_id: "LMS021"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 4 ngày thứ 5",
                                lms_id: "LMS022"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 5 ngày thứ 5",
                                lms_id: "LMS023"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 6 ngày thứ 5",
                                lms_id: "LMS024"
                            }
                        ]
                    },
                    {
                        day: "Thứ 6, 20/10",
                        items: [{
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 1 ngày thứ 6",
                                lms_id: "LMS025"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 2 ngày thứ 6",
                                lms_id: "LMS026"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 3 ngày thứ 6",
                                lms_id: "LMS027"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 4 ngày thứ 6",
                                lms_id: "LMS028"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 5 ngày thứ 6",
                                lms_id: "LMS029"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 6 ngày thứ 6",
                                lms_id: "LMS030"
                            }
                        ]
                    },
                    {
                        day: "Thứ 7, 21/10",
                        items: [{
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 1 ngày thứ 7",
                                lms_id: "LMS031"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 2 ngày thứ 7",
                                lms_id: "LMS032"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 3 ngày thứ 7",
                                lms_id: "LMS033"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 4 ngày thứ 7",
                                lms_id: "LMS034"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 5 ngày thứ 7",
                                lms_id: "LMS035"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 6 ngày thứ 7",
                                lms_id: "LMS036"
                            }
                        ]
                    },
                    {
                        day: "Chủ nhật, 22/10",
                        items: [{
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 1 ngày chủ nhật",
                                lms_id: "LMS037"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 2 ngày chủ nhật",
                                lms_id: "LMS038"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 3 ngày chủ nhật",
                                lms_id: "LMS039"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 4 ngày chủ nhật",
                                lms_id: "LMS040"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 5 ngày chủ nhật",
                                lms_id: "LMS041"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 6 ngày chủ nhật",
                                lms_id: "LMS042"
                            }
                        ]
                    }
                ]
            },
            {
                week: "Tuần 2",
                message: "Các sự kiện nổi bật tuần 2",
                days: [{
                        day: "Thứ 2, 23/10",
                        items: [{
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 1 ngày thứ 2",
                                lms_id: "LMS043"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 2 ngày thứ 2",
                                lms_id: "LMS044"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 3 ngày thứ 2",
                                lms_id: "LMS045"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 4 ngày thứ 2",
                                lms_id: "LMS046"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 5 ngày thứ 2",
                                lms_id: "LMS047"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 6 ngày thứ 2",
                                lms_id: "LMS048"
                            }
                        ]
                    },
                    {
                        day: "Thứ 3, 24/10",
                        items: [{
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 1 ngày thứ 3",
                                lms_id: "LMS049"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 2 ngày thứ 3",
                                lms_id: "LMS050"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 3 ngày thứ 3",
                                lms_id: "LMS051"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 4 ngày thứ 3",
                                lms_id: "LMS052"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 5 ngày thứ 3",
                                lms_id: "LMS053"
                            },
                            {
                                img: "https://placehold.co/50x50",
                                content: "Bài viết 6 ngày thứ 3",
                                lms_id: "LMS054"
                            }
                        ]
                    },
                ]
            }
        ];

        // Hàm để tạo màu gradient pastel ngẫu nhiên
        function getRandomPastelGradient() {
            const colors = [];
            for (let i = 0; i < 2; i++) {
                const hue = Math.random() * 360; // H hue
                const saturation = Math.random() * 30 + 50; // S saturation từ 50% đến 80%
                const lightness = Math.random() * 20 + 70; // L lightness từ 70% đến 90%
                colors.push(`hsl(${hue}, ${saturation}%, ${lightness}%)`);
            }
            return `linear-gradient(to right, ${colors.join(', ')})`;
        }

        function getDatesForWeek(startDate) {
            const dates = [];
            for (let i = 0; i < 7; i++) {
                const currentDate = new Date(startDate);
                currentDate.setDate(startDate.getDate() + i);
                const formattedDate = currentDate.toLocaleDateString("vi-VN", {
                    weekday: 'short',
                    day: 'numeric',
                    month: 'numeric'
                });
                dates.push(formattedDate);
            }
            return dates;
        }

        const container = document.getElementById("news-sections");

        // Ngày hiện tại làm mốc
        const today = new Date();

        sections.forEach((section, sectionIndex) => {
            // Tạo phần header
            const header = document.createElement('div');
            header.classList.add('header', 'd-flex', 'align-items-center', 'header-week');
            header.style.background = getRandomPastelGradient(); // Áp dụng gradient pastel ngẫu nhiên
            header.innerHTML = `
    <h1 class="me-auto">${section.week}</h1>
    <p>${section.message}</p>
    <img alt="Copilot icon" src="https://placehold.co/30x30" />
`;
            container.appendChild(header);

            // Load các ngày từ section.days
            section.days.forEach(dayData => {
                const dayHeader = document.createElement('h4');
                dayHeader.textContent = dayData.day;
                container.appendChild(dayHeader);

                // Tạo hàng cho các mục tin tức của mỗi ngày
                const row = document.createElement('div');
                row.classList.add('row');

                dayData.items.forEach(item => {
                    const col = document.createElement('div');
                    col.classList.add('col-12', 'col-md-6', 'col-lg-4', 'd-flex');
                    col.innerHTML = `
            <div class="news-item">
                <img alt="Fudan University logo" src="${item.img}" />
                <div class="content-lesson">
                    <p>${item.content}</p>
                </div>
                <i class="fas fa-link"></i>
            </div>
        `;
                    row.appendChild(col);
                });

                container.appendChild(row);
            });
        });


        // jQuery function
        $(document).ready(function() {
            let dayPositions = [];
            let roadMapHeight = $('.roadmap').outerHeight();
            let headerHeight = $('#header').outerHeight();

            $('.left-side').css('height', roadMapHeight);

            $('h4').each(function(index, element) {
                const positionFromTop = $(element).offset().top;
                dayPositions.push({
                    day: $(element).text(),
                    position: positionFromTop - headerHeight
                });
            });

            dayPositions.forEach((dayPosition) => {
                const icon = $('<span class="location-day"><i class="bi bi-geo-alt-fill"></i></span>');

                // Set the top position for each icon based on the dayPositions
                icon.css({
                    backgroundColor: '#DDDDDD',
                    borderRadius: '50%',
                    padding: '5px 10px',
                    position: 'absolute',
                    top: `${dayPosition.position}px`,
                    left: '50%',
                    transform: 'translateX(-50%)',
                    color: 'white', // Use your preferred color
                    fontSize: '16px',
                    zIndex: 10,
                });

                // Append the icon to the left-side div
                $('.left-side').append(icon);
            });

            function setCarPosition(date, lms_id) {
                const currentDayPosition = dayPositions.find(dp => dp.day.trim() === date);

                if (!currentDayPosition) {
                    console.error('Day not found in dayPositions');
                    return;
                }

                const currentDayIndex = dayPositions.findIndex(dp => dp.day.trim() === date);
                const nextDayPosition = dayPositions[currentDayIndex + 1];

                const leftSideHeight = $('.left-side').outerHeight();
                const distanceToNextDay = nextDayPosition ?
                    nextDayPosition.position - currentDayPosition.position :
                    leftSideHeight - currentDayPosition.position;

                const sectionForDate = sections.find(section => {
                    return section.days.some(day => day.day.trim() === date);
                });

                if (!sectionForDate) {
                    console.error('Section not found for the given date');
                    return;
                }

                const dayData = sectionForDate.days.find(day => day.day.trim() === date);
                const itemIndex = dayData.items.findIndex(item => item.lms_id === lms_id);

                if (itemIndex === -1) {
                    console.error('LMS ID not found in sections');
                    return;
                }

                const totalItems = dayData.items.length;

                const offsetPercentage = itemIndex / totalItems;
                const itemOffsetWithinDay = distanceToNextDay * offsetPercentage;
                const carTopPosition = currentDayPosition.position + itemOffsetWithinDay;

                const carPosition = $('.car').offset().top;
                const distance = Math.abs(carTopPosition - carPosition);

                const speed = 1000;
                const animationDuration = distance / speed * 1000;

                // Tìm ngày cuối cùng trong tuần cuối cùng
                const lastWeek = sections[sections.length - 1]; // Tuần cuối cùng
                const lastDay = lastWeek.days[lastWeek.days.length - 1]; // Ngày cuối cùng của tuần cuối
                const lastItemIndex = lastDay.items.length - 1; // Chỉ số bài viết cuối cùng

                if (itemIndex === lastItemIndex && sectionForDate.days === lastWeek.days) {
                    $('.car').animate({
                        top: leftSideHeight - 10
                    }, animationDuration, function() {
                        Swal.fire({
                            title: 'Chúc mừng!',
                            text: 'Bạn đã hoàn thành khóa học.',
                            icon: 'success',
                            confirmButtonText: 'Đóng'
                        });
                    });
                    updateLocationDayColors(leftSideHeight - 10);
                } else {
                    $('.car').animate({
                        top: `${carTopPosition}px`
                    }, animationDuration, function() {

                    });
                    updateLocationDayColors(carTopPosition);
                }
            }


            // Example usage of the function
            setCarPosition('Thứ 3, 24/10', 'LMS053'); // Sửa lại ngày cho phù hợp với dữ liệu thực tế

            const finishGateWaytHeight = $('.finish').outerHeight(true);

            // Hàm để cập nhật màu sắc cho các biểu tượng .location-day dựa trên trạng thái của ngày
            function updateLocationDayColors(carTopPosition) {
                // Bắt đầu từ ngày đầu tiên trong danh sách dayPositions
                dayPositions.forEach((dayPosition, index) => {
                    const icon = $('.location-day').eq(index);

                    let backgroundColor;

                    // Kiểm tra nếu xe nằm giữa ngày hiện tại và ngày tiếp theo (nghĩa là vẫn đang học ngày hiện tại)
                    if (index < dayPositions.length - 1 &&
                        carTopPosition > dayPosition.position &&
                        carTopPosition < dayPositions[index + 1].position) {
                        backgroundColor = '#ffc107'; // Màu vàng cho ngày đang học
                    } else if (carTopPosition >= dayPosition.position) {
                        // Kiểm tra nếu là ngày cuối cùng
                        if (index === dayPositions.length - 1) {
                            // Nếu xe chưa đến cuối chiều cao của .left-side thì vẫn hiển thị màu vàng
                            const leftSideHeight = $('.left-side').outerHeight();
                            if (carTopPosition == (leftSideHeight - 10)) {
                                backgroundColor = '#198754'; // Màu vàng nếu chưa đến cuối
                            } else if (carTopPosition < (leftSideHeight - 10)) {
                                backgroundColor = '#ffc107'; // Màu vàng nếu chưa đến cuối
                            }
                        } else {
                            backgroundColor = 'green'; // Màu xanh lá cây cho ngày đã học qua
                        }
                    } else {
                        backgroundColor = 'gray'; // Màu xám cho ngày chưa học
                    }
                    // Cập nhật màu nền cho biểu tượng
                    icon.css('backgroundColor', backgroundColor);
                });
            }
        });
    </script>
@endsection