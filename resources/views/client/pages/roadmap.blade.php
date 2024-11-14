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
            display: none;
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
            height: 200px;
            background-size: fit;
            border-radius: 8px;
            margin: 30px;
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

        .serie-content {
            position: relative;
            border-radius: 10px;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            min-height: 160px;
            margin: 0 auto;
            background-size: contain;
        }

        .serie-info {
            position: relative;
            z-index: 2;
        }

        .card-title {
            font-family: 'Nunito', sans-serif;
            color: var(--primary);
            margin-bottom: 15px;
            font-size: 1.5rem;
        }

        .roadmap-select {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px 10px;
            font-size: 14px;
            margin-left: 10px;
            width: 225px;
            display: inline-block;
        }

        .serie-info a {
            color: #0d6efd;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
        }

        .serie-info a:hover {
            text-decoration: underline;
        }

        /* Sun */
        .sun {
            position: absolute;
            top: 20px;
            right: 40px;
            width: 40px;
            height: 40px;
            background: radial-gradient(circle at center, #FFD700 60%, #FFA500);
            border-radius: 50%;
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.8);
        }

        /* Random bubbles */
        .bubble {
            position: absolute;
            background: radial-gradient(circle at 30% 30%, #FFE87C, #FFD700);
            border-radius: 50%;
            opacity: 0;
            pointer-events: none;
        }

        /* Horizontal river */
        .river {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 20px;
            background: linear-gradient(to right,
                    #88c1d0,
                    #6ab0bf 25%,
                    #88c1d0 50%,
                    #6ab0bf 75%,
                    #88c1d0 100%);
            background-size: 200% 100%;
            animation: flowRiver 8s linear infinite;
        }

        /* Keyframe Animations */
        @keyframes moveCloud {
            from {
                left: -70px;
            }

            to {
                left: calc(100% + 70px);
            }
        }

        @keyframes flowRiver {
            from {
                background-position: 0 0;
            }

            to {
                background-position: 200% 0;
            }
        }

        .serie-info strong {
            color: var(--primary);
            margin: 10px 0px;
        }

        @media (max-width: 1024px) {
            .serie-content {
                background-size: cover;
                height: 400px;
            }

            .serie-image {
                display: none;
            }

            .serie-info {
                display: flex;
                justify-content: center;
            }
        }

        .bubble {
            z-index: 99;
        }

        .news-item {
            position: relative;
            width: 100%;
            padding: 15px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Content lesson section */
        .content-lesson {
            flex-grow: 1;
            gap: 12px;
        }

        .content-lesson img {
            width: 24px;
            height: 24px;
            object-fit: contain;
        }

        .content-lesson p {
            margin: 0;
            flex-grow: 1;
        }

        /* Link icon styles */
        .fa-link {
            color: #6c757d;
            font-size: 16px;
            margin-left: 12px;
        }

        /* Finished state styles */
        .news-item.finished {
            border-left: 4px solid #4CAF50;
        }

        .news-item.finished::after {
            content: '✓';
            position: absolute;
            right: 40px;
            color: #4CAF50;
            font-weight: bold;
            font-size: 16px;
        }

        /* Hover effect */
        .news-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .news-item {
                padding: 12px;
            }

            .content-lesson img {
                width: 20px;
                height: 20px;
            }
        }
    </style>
@endsection

@section('content')
    <div id="serie_card" class="serie-card row">
        <div class="serie-content col-12 col-sm-10"
            style="background-image: url({{ asset('images/background/background-roadmap.png') }})">
            <!-- Background elements -->
            <div class="sun"></div>
            <div class="mountains">
                <div class="mountain mountain-1"></div>
                <div class="mountain mountain-2"></div>
            </div>

            <!-- Course information -->
            <div class="serie-info">
                <div class="d-flex align-items-center mb-3">
                    <div class="serie-image">
                        @if ($serie_combo->image)
                            <img src="{{ asset('/public/' . config('constant.series_combo.upload_path') . $serie_combo->image) }}"
                                alt="" srcset="">
                        @endif
                    </div>
                    <div>
                        <div>
                            <h4 class="card-title">✨ {{ $serie->title }}</h4>
                            <label for="roadmap_select"><strong>🛣 Lộ trình:</strong></label>
                            @if ($road_map->isEmpty())
                                <span>Lộ trình cho khoá học đang được chuẩn bị! Sẽ sớm có thôi, bạn hãy ghé lại sau nhé!
                                    🌟</span>
                            @else
                                <select id="roadmap_select" class="form-select roadmap-select"
                                    aria-label="Default select example">
                                    <option value="" selected>Chọn lộ trình bạn muốn xem</option>
                                    @foreach ($road_map as $item)
                                        @php
                                            $roadmapContents = json_decode($item->contents);
                                            $lastDayNum = end($roadmapContents)->day_number;
                                        @endphp
                                        <option value="{{ $item->duration_months }}">{{ $lastDayNum }} ngày
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div>
                            @if (isset($last_view))
                                <div class="mt-1">
                                    <strong>📍 Bài học gần đây:</strong>
                                    <a href="#car" rel="noopener noreferrer">{{ $last_view->bai }} (Click để xem chi
                                        tiết)</a>
                                </div>
                            @endif
                            <div id="information-serie"></div>
                        </div>
                    </div>
                </div>
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

            const styles = `
                .bubble {
                    position: absolute;
                    background: radial-gradient(
                        circle at 30% 30%,
                        rgba(255, 223, 0, 0.4) 70%,
                        rgba(255, 165, 0, 0.4) 30%
                    );
                    border-radius: 50%;
                    pointer-events: none;
                }

                .cloud {
                    position: absolute;
                    width: 100px;
                    height: 40px;
                    background: rgba(255, 255, 255, 0.8);
                    border-radius: 20px;
                    animation: moveCloud linear infinite;
                    opacity: 0.7;
                }

                .cloud::before,
                .cloud::after {
                    content: '';
                    position: absolute;
                    background: rgba(255, 255, 255, 0.8);
                    border-radius: 50%;
                }

                .cloud::before {
                    width: 50px;
                    height: 50px;
                    top: -20px;
                    left: 15px;
                }

                .cloud::after {
                    width: 30px;
                    height: 30px;
                    top: -10px;
                    left: 50px;
                }

                @keyframes moveCloud {
                    from {
                        transform: translateX(-120px);
                    }
                    to {
                        transform: translateX(calc(100vw + 120px));
                    }
                }

                @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }

                @keyframes fadeOut {
                    from { opacity: 0.4; }
                    to { opacity: 0; }
                }

                @keyframes float {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-15px); }
                }
            `;

            // Add styles to head
            $('<style>').text(styles).appendTo('head');

            let bubbleInterval;
            let cloudInterval;

            // Function to create a cloud
            function createCloud() {
                const $card = $('.serie-content');

                if ($card.length === 0) return;

                const $cloud = $('<div class="cloud"></div>');

                // Random vertical position
                const maxY = $card.height() - 40; // 40 is cloud height
                const y = Math.random() * (maxY * 0.7); // Keep clouds in top 70% of container

                // Random size (0.5 to 1.5 times original size)
                const scale = 0.5 + Math.random();

                // Random speed (15-25 seconds to cross screen)
                const duration = 15000 + Math.random() * 10000;

                $cloud.css({
                    top: `${y}px`,
                    transform: `scale(${scale})`,
                    animation: `moveCloud ${duration}ms linear`
                });

                $card.append($cloud);

                // Remove cloud after animation completes
                setTimeout(() => {
                    $cloud.remove();
                }, duration);
            }

            // Function to create a bubble
            function createBubble() {
                const $card = $('.serie-content');
                if ($card.length === 0) return;

                const $bubble = $('<div class="bubble"></div>');

                const size = Math.random() * 12 + 8;
                const maxX = $card.width() - size;
                const maxY = $card.height() - size;
                const x = Math.random() * maxX;
                const y = Math.random() * (maxY - 40);

                const floatDuration = Math.random() * 3 + 2;

                $bubble.css({
                    width: `${size}px`,
                    height: `${size}px`,
                    left: `${x}px`,
                    top: `${y}px`,
                    animation: `
                        fadeIn 0.5s ease-in forwards,
                        float ${floatDuration}s ease-in-out infinite,
                        fadeOut 0.5s ease-out ${floatDuration}s forwards
                    `
                });

                $card.append($bubble);

                setTimeout(() => {
                    $bubble.remove();
                }, (floatDuration + 0.5) * 1000);
            }

            // Function to start animations
            function startAnimations() {
                stopAnimations();
                bubbleInterval = setInterval(createBubble, 1500);
                cloudInterval = setInterval(createCloud, 8000); // Create new cloud every 8 seconds
                createBubble();
                createCloud();
            }

            // Function to stop animations
            function stopAnimations() {
                if (bubbleInterval) {
                    clearInterval(bubbleInterval);
                    bubbleInterval = null;
                }
                if (cloudInterval) {
                    clearInterval(cloudInterval);
                    cloudInterval = null;
                }
            }

            // Handle visibility changes
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    stopAnimations();
                } else {
                    startAnimations();
                }
            });

            // Start animations when page loads
            startAnimations();

            // load roadmap
            loadRoadMapByURL();

            function getRandomPastelGradient() {
                const colors = [];
                for (let i = 0; i < 2; i++) {
                    const hue = Math.random() * 360;
                    const saturation = Math.random() * 30 + 50;
                    const lightness = Math.random() * 20 + 70;
                    colors.push(`hsl(${hue}, ${saturation}%, ${lightness}%)`);
                }
                return `linear-gradient(to right, ${colors.join(', ')})`;
            }

            const container = document.getElementById("news-sections");
            let dayPositions = [];

            function loadDataRoadMap(data) {
                // Clear existing content
                container.innerHTML = '';

                // Process only numeric keys (weeks) from the data object
                Object.keys(data).forEach(key => {
                    // Skip if not a week object (e.g., skip finish_course)
                    if (key === 'finish_course' || !data[key].week) {
                        return;
                    }

                    const section = data[key];

                    // Create header section
                    const header = document.createElement('div');
                    header.classList.add('header', 'd-flex', 'align-items-center', 'header-week');
                    header.style.background = getRandomPastelGradient();
                    header.innerHTML = `
                        <h1 class="me-auto">${section.week}</h1>
                        <p>${section.message}</p>
                        <img class="ms-2" alt="Daily icon" src="/images/icons/schedule-icon.png" />
                    `;
                    container.appendChild(header);

                    // Load the days
                    if (Array.isArray(section.days)) {
                        section.days.forEach(dayData => {
                            // Create day header
                            const dayHeader = document.createElement('h4');
                            dayHeader.textContent = `Ngày ${dayData.day_number}`;
                            dayHeader.setAttribute('data-day', dayData.day_number);
                            dayHeader.setAttribute('data-finished', dayData.finish_day);
                            container.appendChild(dayHeader);

                            // Create row for lessons
                            const row = document.createElement('div');
                            row.classList.add('row');

                            // Add lessons
                            if (Array.isArray(dayData.lesson_list)) {
                                dayData.lesson_list.forEach(item => {
                                    const col = document.createElement('div');
                                    const isFinished = item.finish;
                                    col.classList.add('col-12', 'col-md-6', 'col-lg-4',
                                        'd-flex');
                                    col.innerHTML = `
                                        <div class="news-item ${isFinished ? 'finished' : ''}">
                                            <div class="content-lesson d-flex align-items-center">
                                                <img src="${checkType(item.type)}" alt="${item.name}" />
                                                <p>${item.name}</p>
                                            </div>
                                            <i class="fas fa-link"></i>
                                        </div>
                                    `;
                                    row.appendChild(col);
                                });
                            }

                            container.appendChild(row);
                        });
                    }
                });
            }

            function checkType(type) {
                const icons = {
                    video: "{{ asset('images/icons/lesson.png') }}",
                    exercise: "{{ asset('images/icons/exercise.png') }}",
                    audit: "{{ asset('images/icons/score.png') }}",
                    flashcard: "{{ asset('images/icons/flashcard.svg') }}",
                    title: "{{ asset('images/icons/tittle.svg') }}",
                    handwriting: "{{ asset('images/icons/handwriting.svg') }}",
                    rest: "{{ asset('images/icons/Paradise-icon.svg') }}",
                };
                return icons[type] || "";
            }

            function locationIcon() {
                let roadMapHeight = $('.roadmap').outerHeight();
                let headerHeight = $('#header').outerHeight();
                let seriecCardHeight = $('#serie_card').outerHeight();

                $('.left-side').css('height', roadMapHeight);
                dayPositions = [];

                $('h4').each(function(index, element) {
                    const positionFromTop = $(element).offset().top;
                    dayPositions.push({
                        day: $(element).data('day'),
                        position: positionFromTop - headerHeight - seriecCardHeight,
                        isFinished: $(element).data('finished')
                    });
                });

                dayPositions.forEach((dayPosition) => {
                    const icon = $('<span class="location-day"><i class="bi bi-geo-alt-fill"></i></span>');

                    // Set initial color based on finish status
                    const backgroundColor = dayPosition.isFinished ? '#198754' : '#DDDDDD';

                    icon.css({
                        backgroundColor: backgroundColor,
                        borderRadius: '50%',
                        padding: '5px 10px',
                        position: 'absolute',
                        top: `${dayPosition.position}px`,
                        left: '50%',
                        transform: 'translateX(-50%)',
                        color: 'white',
                        fontSize: '16px',
                        zIndex: 10,
                    });

                    $('.location-days').append(icon);
                });
            }

            function setCarPosition(date, lms_id) {
                if (!date || !lms_id) return;

                const currentDayPosition = dayPositions.find(dp => dp.day == date);
                if (!currentDayPosition) {
                    console.error('Day not found in dayPositions');
                    return;
                }

                // Convert object to array and then use flatMap
                const sectionsArray = Object.values(sections).filter(item => Array.isArray(item?.days));
                const currentDayData = sectionsArray.flatMap(s => s.days).find(d => d.day_number == date);

                if (!currentDayData) {
                    console.error('Day data not found');
                    return;
                }

                const itemIndex = currentDayData.lesson_list.findIndex(item => item.id == lms_id);
                if (itemIndex == -1) {
                    console.error('LMS ID not found in sections');
                    return;
                }

                // Check if this is the last day and course is finished
                const lastSection = sectionsArray[sectionsArray.length - 1];
                const isLastDay = lastSection?.days[lastSection.days.length - 1]?.day_number === date;
                const isCourseFinished = sections.finish_course;

                // Determine the target position for the car
                let carTopPosition;
                if (isLastDay && isCourseFinished) {
                    // If course is finished, get position of the finish element
                    const $finishElement = $('.finish');
                    if ($finishElement.length) {
                        const headerHeight = $('#header').outerHeight();
                        const serieCardHeight = $('#serie_card').outerHeight();
                        carTopPosition = $finishElement.offset().top - headerHeight - serieCardHeight;
                    } else {
                        carTopPosition = currentDayPosition.position;
                    }
                } else {
                    carTopPosition = currentDayPosition.position;
                }

                const carPosition = $('.car').offset().top;
                const distance = Math.abs(carTopPosition - carPosition);
                const animationDuration = distance / 1000 * 1000;

                $('.car').animate({
                    top: `${carTopPosition}px`
                }, animationDuration, function() {
                    if (isLastDay && isCourseFinished) {

                    }
                });

                updateLocationDayColors(date);
            }

            // Function to update the car position when course is completed
            function updateCarPositionOnCompletion() {
                if (sections.finish_course) {
                    const $finishElement = $('.finish');
                    if ($finishElement.length) {
                        const headerHeight = $('#header').outerHeight();
                        const serieCardHeight = $('#serie_card').outerHeight();
                        const finishPosition = $finishElement.offset().top - headerHeight - serieCardHeight;
                        $('.car').animate({
                            top: `${finishPosition}px`
                        }, 1000, function() {
                            Swal.fire({
                                title: 'Tuyệt vời lắm!',
                                text: 'Bạn đã xuất sắc hoàn thành khóa học rồi! Tiếp tục cố gắng nhé!',
                                icon: 'success',
                                confirmButtonText: 'Đóng'
                            });

                        });
                    }
                }
            }

            function updateLocationDayColors(currentDay) {
                // Convert object to array and filter out non-week items
                const sectionsArray = Object.values(sections).filter(item => Array.isArray(item?.days));

                dayPositions.forEach((dayPosition, index) => {
                    const icon = $('.location-day').eq(index);
                    const dayData = sectionsArray.flatMap(s => s.days).find(d => d.day_number == dayPosition
                        .day);

                    let backgroundColor;
                    if (dayData) {
                        if (dayData.finish_day) {
                            backgroundColor = '#198754'; // Green for completed days
                        } else if (dayPosition.day == currentDay) {
                            backgroundColor = '#ffc107'; // Yellow for current day
                        } else {
                            backgroundColor = '#DDDDDD'; // Gray for incomplete days
                        }
                    } else {
                        backgroundColor = '#DDDDDD';
                    }

                    icon.css('background-color', backgroundColor);
                });
            }

            $('.roadmap-select').on('change', function() {
                const selectedValue = $(this).val();
                const selectedMonth = parseInt(selectedValue);

                if ($('.main-content').scrollTop() === 0) {
                    // If already at the top, call loadRoadMap immediately
                    loadRoadMap(selectedMonth, '{{ $serie_combo->slug }}', '{{ $serie->slug }}');
                } else {
                    // Otherwise, scroll to the top and set a timeout before calling loadRoadMap
                    $('.main-content').animate({
                        scrollTop: 0
                    }, 'smooth');

                    setTimeout(function() {
                        loadRoadMap(selectedMonth, '{{ $serie_combo->slug }}',
                            '{{ $serie->slug }}');
                    }, 500);
                }
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
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $("#news-sections").html('');
                        $('.location-days').html('');
                        sections = response.road_map;
                        dayPositions = [];
                        $('.left-side').show();
                        $('#information-serie').html(`
                    <div>
                        <strong>🏁 Lộ trình ${response.last_roadmap_day} ngày:</strong>
                        Hoàn thành mục tiêu trong ${response.last_roadmap_day} ngày chỉ với ${response.day_count} buổi học!
                    </div>
                `);
                        loadDataRoadMap(response.road_map);
                        locationIcon();
                        setCarPosition(response.day_last_view, response.last_view);
                        if (response.road_map.finish_course == true) {
                            updateCarPositionOnCompletion();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Lỗi không tải dữ liệu:', error);
                    }
                });
            }

            function loadRoadMapByURL() {
                const urlParams = new URLSearchParams(window.location.search);
                let month = urlParams.get('month');

                if (month !== null && month !== undefined) {
                    $('.roadmap-select').val(month);
                    loadRoadMap(month, '{{ $serie_combo->slug }}', '{{ $serie->slug }}');
                }
            }
        });
    </script>
@endsection
