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
            width: 100%;
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
            z-index: 11;
            width: 100%;
        }

        .left-side .start-line {
            position: absolute;
            width: 100%;
            left: 50%;
            transform: translateX(-50%);
            top: 30px;
            width: 100%;
        }

        .star {
            position: absolute;
            font-size: 24px;
            color: #FFD700;
            transition: transform 0.5s ease, opacity 0.5s ease;
        }

        .serie-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 16px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .serie-image {
            height: 100%;
            background-size: fit;
            border-radius: 8px;
        }

        .serie-content {
            flex: 1;
            padding-left: 16px;
        }

        .serie-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .serie-info {
            margin-top: 8px;
            font-size: 16px;
            color: #555;
        }

        .serie-info p {
            margin: 0;
        }

        .serie-info p+p {
            margin-top: 4px;
        }

        .serie-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 20px;
        }

        .roadmap-select {
            width: 50%;
            margin-left: 5px;
        }
    </style>
@endsection

@section('content')
    <div id="serie_card" class="serie-card row">
        <div class="serie-image col-12 col-sm-2">
            <img src="{{ asset('uploads/exams/series/site/n5.png') }}" alt="" srcset="">
        </div>
        <div class="serie-content col-12 col-sm-10">
            <div class="serie-info">
                <h4 class="card-title">{{ $serie->title }}</h4>
                <div class="d-flex align-items-center">
                    <label for="roadmap_select"><strong>Lộ trình:</strong></label>
                    <select id="roadmap_select" class="form-select roadmap-select" aria-label="Default select example">
                        <option value="" selected>Vui lòng chọn lộ trình</option>
                        @foreach ($road_map as $item)
                            <option value="{{ $item->duration_months }}">{{ $item->duration_months }} tháng</option>
                        @endforeach
                    </select>
                </div>
                @if (isset($last_view))
                    <div><strong>Bài học gần đây:</strong> <a href="#car" rel="noopener noreferrer">{{ $last_view->bai }}</a></div>
                @endif
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Left side (1 column) -->
            <div class="col-2 col-sm-1 left-side">
                <div class="row"></div>
                <img class="start-line" src="{{ asset('images/icons/start-line.svg') }}" alt="">
                <img id="car" class="car" src="{{ asset('images/icons/car.svg') }}" alt="">
                <img class="start-gate" src="{{ asset('images/icons/start-gate.svg') }}" alt="">
                <img class="finish" src="{{ asset('images/icons/finish.png') }}" alt="">

                <div class="location-days"></div>
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
        let sections = [];

        // jQuery function
        $(document).ready(function() {
            // Function to create a random pastel gradient
            function getRandomPastelGradient() {
                const colors = [];
                for (let i = 0; i < 2; i++) {
                    const hue = Math.random() * 360; // H hue
                    const saturation = Math.random() * 30 + 50; // S saturation from 50% to 80%
                    const lightness = Math.random() * 20 + 70; // L lightness from 70% to 90%
                    colors.push(`hsl(${hue}, ${saturation}%, ${lightness}%)`);
                }
                return `linear-gradient(to right, ${colors.join(', ')})`;
            }

            $('.left-side').hide();

            const container = document.getElementById("news-sections");

            // Current date as reference
            const today = new Date();

            function loadDataRoadMap(sections) {
                sections.forEach((section, sectionIndex) => {
                    // Create header section
                    const header = document.createElement('div');
                    header.classList.add('header', 'd-flex', 'align-items-center', 'header-week');
                    header.style.background = getRandomPastelGradient();
                    header.innerHTML = `
                        <h1 class="me-auto">${section.week}</h1>
                        <p>${section.message}</p>
                        <img class="ms-2" alt="Daily icon" src="{{ asset('images/icons/schedule-icon.png') }}" />
                    `;
                    container.appendChild(header);

                    // Load the days from section.days
                    section.days.forEach(dayData => {
                        const dayHeader = document.createElement('h4');
                        dayHeader.textContent =
                            `Ngày ${dayData.day_number}`; // Set value for h4 tag
                        dayHeader.setAttribute('data-day', dayData
                            .day_number); // Add data-day attribute
                        container.appendChild(dayHeader); // Add h4 tag to container

                        // Create a row for each day's news items
                        const row = document.createElement('div');
                        row.classList.add('row');

                        dayData.lesson_list.forEach(item => {
                            const col = document.createElement('div');
                            col.classList.add('col-12', 'col-md-6', 'col-lg-4', 'd-flex');
                            col.innerHTML = `
                                <div class="news-item">
                                    <div class="content-lesson d-flex align-items-center">
                                        <img src="${checkType(item.type)}" alt="${item.name}" />
                                        <p>${item.name}</p>
                                    </div>
                                    <i class="fas fa-link"></i>
                                </div>
                            `;
                            row.appendChild(col);
                        });

                        container.appendChild(row);
                    });
                });
            }

            function checkType(type) {
                let linkIcon = '';
                if (type == 'video') {
                    linkIcon = "{{ asset('images/icons/lesson.png') }}";
                }
                if (type == 'exercise') {
                    linkIcon = "{{ asset('images/icons/exercise.png') }}";
                }
                if (type == 'audit') {
                    linkIcon = "{{ asset('images/icons/score.png') }}";
                }
                if (type == 'flashcard') {
                    linkIcon = "{{ asset('images/icons/flashcard.svg') }}";
                }
                if (type == 'title') {
                    linkIcon = "{{ asset('images/icons/tittle.svg') }}";
                }
                if (type == 'handwriting') {
                    linkIcon = "{{ asset('images/icons/handwriting.svg') }}";
                }
                return linkIcon;
            }

            let dayPositions = [];

            function locationIcon() {
                let roadMapHeight = $('.roadmap').outerHeight();
                let headerHeight = $('#header').outerHeight();
                let seriecCardHeight = $('#serie_card').outerHeight();

                $('.left-side').css('height', roadMapHeight);

                $('h4').each(function(index, element) {
                    const positionFromTop = $(element).offset().top;
                    dayPositions.push({
                        day: $(element).data('day'),
                        position: positionFromTop - headerHeight - seriecCardHeight
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
                    $('.location-days').append(icon);
                });
            }

            function setCarPosition(date, lms_id) {
                const currentDayPosition = dayPositions.find(dp => dp.day == date);
                if (!currentDayPosition) {
                    console.error('Day not found in dayPositions');
                    return;
                }

                const currentDayIndex = dayPositions.findIndex(dp => dp.day == date);
                const nextDayPosition = dayPositions[currentDayIndex + 1];

                const leftSideHeight = $('.left-side').outerHeight();
                const distanceToNextDay = nextDayPosition ?
                    nextDayPosition.position - currentDayPosition.position :
                    leftSideHeight - currentDayPosition.position;

                const sectionForDate = sections.find(section => {
                    return section.days.some(day => (day.day_number) == date);
                });

                if (!sectionForDate) {
                    console.error('Section not found for the given date');
                    return;
                }

                const dayData = sectionForDate.days.find(day => day.day_number == date);
                const itemIndex = dayData.lesson_list.findIndex(item => item.id == lms_id);

                if (itemIndex == -1) {
                    console.error('LMS ID not found in sections');
                    return;
                }

                const totalItems = dayData.lesson_list.length;

                const offsetPercentage = itemIndex / totalItems;
                const itemOffsetWithinDay = distanceToNextDay * offsetPercentage;
                const carTopPosition = currentDayPosition.position + itemOffsetWithinDay;

                const carPosition = $('.car').offset().top;
                const distance = Math.abs(carTopPosition - carPosition);

                const speed = 1000;
                const animationDuration = distance / speed * 1000;
                // Find the last day in the last week
                const lastWeek = sections[sections.length - 1]; // Last week
                const lastDay = lastWeek.days[lastWeek.days.length - 1]; // Last day of the last week
                const lastItemIndex = lastDay.lesson_list.length - 1; // Last item index

                if (itemIndex == lastItemIndex && sectionForDate.days == lastWeek.days) {
                    $('.car').animate({
                        top: leftSideHeight - 10
                    }, animationDuration, function() {
                        Swal.fire({
                            title: 'Congratulations!',
                            text: 'You have completed the course.',
                            icon: 'success',
                            confirmButtonText: 'Close'
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

            const finishGateWaytHeight = $('.finish').outerHeight(true);

            // Function to update the color of .location-day icons based on the current day's status
            function updateLocationDayColors(carTopPosition) {
                // Start from the first day in the dayPositions list
                dayPositions.forEach((dayPosition, index) => {
                    const icon = $('.location-day').eq(index);

                    let backgroundColor;

                    // Check if the car is between the current day and the next day (still studying the current day)
                    if (index < dayPositions.length - 1 &&
                        carTopPosition > dayPosition.position &&
                        carTopPosition < dayPositions[index + 1].position) {
                        backgroundColor = '#ffc107'; // Yellow for the current day being studied
                    } else if (carTopPosition >= dayPosition.position) {
                        // Check if it's the last day
                        if (index == dayPositions.length - 1) {
                            // If the car hasn't reached the bottom of the .left-side, still show yellow
                            const leftSideHeight = $('.left-side').outerHeight();
                            if (carTopPosition == (leftSideHeight - 10)) {
                                backgroundColor = '#198754'; // Green if reached the end
                            } else if (carTopPosition < (leftSideHeight - 10)) {
                                backgroundColor = '#ffc107'; // Yellow if not at the end yet
                            }
                        } else {
                            backgroundColor = 'green'; // Green for days already studied
                        }
                    } else {
                        backgroundColor = 'gray'; // Gray for days not yet studied
                    }
                    // Update the background color of the icon
                    icon.css('background-color', backgroundColor);
                });
            }
            $('.roadmap-select').on('change', function() {
                const selectedValue = $(this).val();
                const selectedMonth = parseInt(selectedValue);
                loadRoadMap(selectedMonth, 'khoa-hoc-n5-4fd338484641a026d2c76a6cc7dddaa23a50e59e-2',
                    'khoa-hoc-n5-8caf4720ced649c1602c132ec4a3eaf09189d0f9-2');
            });

            function loadRoadMap(month, comboSlug, slug) {
                let url = '{{ route('home.load-roadmap', ['comboSlug' => ':comboSlug', 'slug' => ':slug']) }}';
                url = url.replace(':comboSlug', comboSlug).replace(':slug', slug);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        month: month,
                        combo_slug: comboSlug,
                        slug: slug,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content')
                    },
                    success: function(response) {
                        $("#news-sections").html('');
                        $('.location-days').html('');
                        sections = response.road_map;
                        dayPositions = [];
                        $('.left-side').show();
                        loadDataRoadMap(response.road_map)
                        locationIcon();
                        setCarPosition(response.day_last_view, response.last_view);
                    },
                    error: function(xhr, status, error) {
                        console.error('Lỗi không tải dữ liệu:');
                    }
                });
            }

        });
    </script>
@endsection
