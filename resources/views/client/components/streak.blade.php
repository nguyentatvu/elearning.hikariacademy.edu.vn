    <style>
        /* Styling the calendar container and header */
        .streak-calendar-container {
            width: 100%;
            padding: 30px;
        }

        .streak-header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .streak-logo {
            font-size: 28px;
            color: #166AC9;
            margin-right: 5px;
        }

        .streak-info {
            text-align: center;
            position: relative;
        }

        .streak-count {
            color: #166AC9;
            margin: 0;
        }

        .streak-info img {
            height: 100px;
        }

        .streak-label {
            color: #6AC4F3;
            margin: 0;
        }

        .calendar {
            border-radius: 8px;
            overflow: hidden;
        }

        /* Styling the calendar header buttons and the table structure */
        .calendar-header {
            padding: 5px;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .calendar-header button {
            background-color: transparent;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #166AC9;
        }

        .table-calendar {
            width: 100%;
            border-collapse: collapse;
        }

        .table-calendar th {
            padding: 5px;
            text-align: center;
            font-size: 14px;
        }

        .table-calendar td {
            padding: 10px;
            text-align: center;
            cursor: pointer;
            font-size: 12px;
        }

        /* Styling for days and different types of statuses */
        .selected-range span {
            background-color: #6AC4F3;
            color: white;
            width: 30px;
            height: 30px;
            display: inline-block;
            text-align: center;
            line-height: 30px;
            border-radius: 50%;
        }

        .studied {
            background-color: #6AC4F3;
            color: white;
        }

        .not-studied span {
            background-color: #fff1f2;
            color: #fd5673;
            width: 30px;
            height: 30px;
            display: inline-block;
            text-align: center;
            line-height: 30px;
            border-radius: 50%;
        }

        .selected-range:not(.end-range, .one-day) {
            background-color: #FEF2DD;
        }

        .future {
            background-color: white;
            color: black;
        }

        .modal-header {
            border: none;
        }

        @keyframes gradientRiseAndColorChange {
            0% {
                background-position: 0 100%;
                color: #fd5673;
                background: #fff1f2;
            }

            100% {
                background-position: 0 0;
                color: white;
                background: #166AC9;
            }
        }

        .today span {
            width: 30px;
            height: 30px;
            display: inline-block;
            text-align: center;
            line-height: 30px;
            border-radius: 50%;
            background-size: 0 100%;
            color: #fd5673;
            background: #fff1f2;
            animation: gradientRiseAndColorChange 1s forwards;
            animation-delay: 0.5s;
        }

        .other-month {
            color: #cccccc;
        }

        /* Legend for the calendar */
        .legend {
            margin-top: 15px;
            display: flex;
            justify-content: space-around;
            font-size: 12px;
        }

        .legend-item {
            display: flex;
            align-items: center;
        }

        .legend-color {
            width: 15px;
            height: 15px;
            margin-right: 5px;
        }

        .studied-legend {
            background-color: #6AC4F3;
        }

        .not-studied-legend {
            background-color: #f8d7da;
        }

        .today-legend {
            background-color: #166AC9;
        }

        /* Styling for the input section */
        .date-inputs {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }

        .date-inputs input {
            margin: 0 5px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
        }

        .submit-btn {
            padding: 5px 10px;
            background-color: #166AC9;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        /* Flicker animation for the logo */
        .streak-logo img {
            width: 50px;
            /* Adjust size as necessary */
            animation: flicker 1.5s infinite alternate;
        }

        .start-range {
            border-radius: 30px 0px 0px 30px;
        }

        .end-range {
            border-radius: 0px 30px 30px 0px;
            position: relative;
            overflow: hidden;
            background-image: linear-gradient(to right, #FEF2DD 0%, #FEF2DD 100%);
            background-size: 0% 100%;
            background-repeat: no-repeat;
            background-position: left;
            animation: backgroundFill 1s forwards;
        }

        .one-day {
            position: relative;
            overflow: hidden;
            background-image: linear-gradient(to right, #FEF2DD 0%, #FEF2DD 100%);
            background-size: 0% 100%;
            background-repeat: no-repeat;
            background-position: left;
            animation: backgroundFill 1s forwards;
        }

        .one-day-not-log-in {
            position: relative;
            overflow: hidden;
            background-size: 0% 100%;
            background-repeat: no-repeat;
            background-position: left;
            animation: backgroundFill 1s forwards;
        }

        /* Keyframes for flicker effect */
        @keyframes flicker {
            0% {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }

            50% {
                transform: scale(1.1) rotate(-2deg);
                opacity: 0.85;
            }

            100% {
                transform: scale(1) rotate(2deg);
                opacity: 1;
            }
        }

        /* Fade-in animation for the streak count */
        @-webkit-keyframes fadeInUp {
            from {
                opacity: 0;
                -webkit-transform: translate3d(0, 30%, 0);
                transform: translate3d(0, 30%, 0);
            }

            to {
                opacity: 1;
                -webkit-transform: translate3d(0, 0, 0);
                transform: translate3d(0, 0, 0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                -webkit-transform: translate3d(0, 30%, 0);
                transform: translate3d(0, 30%, 0);
            }

            to {
                opacity: 1;
                -webkit-transform: translate3d(0, 0, 0);
                transform: translate3d(0, 0, 0);
            }
        }

        @keyframes backgroundFill {
            to {
                background-size: 100% 100%;
            }
        }

        .selected-range.today.end-range {
            position: relative;
            overflow: visible;
        }

        .selected-range.today.end-range::before,
        .selected-range.today.end-range::after {
            content: "★";
            position: absolute;
            pointer-events: none;
            font-size: 1rem;
            opacity: 0;
            z-index: 1;
            color: #FFD700;
            font-weight: bold;
        }

        .selected-range.today.end-range::before {
            animation: starBurstUp 2s ease-out 1;
            animation-delay: 1s;
        }

        .selected-range.today.end-range::after {
            animation: starBurstUp 2s ease-out 1;
            animation-delay: 1.5s;
        }

        .selected-range.today.one-day::before,
        .selected-range.today.one-day::after {
            content: "★";
            position: absolute;
            pointer-events: none;
            font-size: 1rem;
            opacity: 0;
            z-index: 1;
            color: #FFD700;
            font-weight: bold;
        }

        .selected-range.today.one-day::before {
            animation: starBurstUp 2s ease-out 1;
            animation-delay: 1s;
        }

        .selected-range.today.one-day::after {
            animation: starBurstUp 2s ease-out 1;
            animation-delay: 1.5s;
        }

        @keyframes starBurstUp {
            0% {
                top: 50%;
                left: 50%;
                opacity: 1;
                transform: scale(0.5) rotate(0deg);
            }

            50% {
                opacity: 1;
            }

            100% {
                top: -100%;
                left: calc(50% + (100% - 50%) * (Math.random() - 0.5));
                opacity: 0;
                transform: scale(1.5) rotate(360deg);
            }
        }

        .streak-container {
            width: 100%;
            margin-bottom: 20px;
        }

        .streak-bar {
            position: relative;
            background-color: #ddd;
            height: 15px;
            border-radius: 15px;
            overflow: hidden;
        }

        .streak-fill {
            background: linear-gradient(90deg, #ff7e00, #ff4500);
            height: 100%;
            width: 0%;
            transition: width 0.5s ease;
            border-radius: 15px;
        }

        .fire-effect {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            opacity: 0.7;
            pointer-events: none;
            mix-blend-mode: screen;
        }

        .streak-dates {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            margin-bottom: 10px;
            position: relative;
            width: 100%;
        }

        .streak-dates span {
            font-weight: bold;
            color: #555;
        }

        .btn-close {
            position: absolute;
            right: 10px;
            top: 10px;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #adb5bd;
            padding: 0;
        }

        .flame-icon {
            color: #ff922b;
            font-size: 2.5rem;
            margin-right: 5px;
        }

        .streak-number {
            color: #4dabf7;
            font-size: 2.5rem;
            font-weight: bold;
        }

        .day-boxes {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            background-color: #e9ecef;
            border-radius: 15px;
            padding: 15px;
        }

        .day-column {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .day-label {
            font-size: 0.8rem;
            margin-bottom: 5px;
            color: #495057;
        }

        .checkmark {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }

        .checkmark.active {
            background-color: #4dabf7;
            color: white;
        }

        .checkmark.inactive {
            background-color: #ced4da;
            color: #868e96;
        }

        .checkmark.unactive {
            background-color: #fff1f2;
            color: #fd5673;
        }

        .milestone {
            position: absolute;
        }

        .checkmark i {
            margin: 0;
        }
    </style>
    <div class="modal fade" id="modalLoginStreak" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close btn-close-streak" aria-label="Close"><i
                            class="bi bi-x"></i></button>
                </div>
                <div class="modal-body text-center">
                    <div class="streak-header">
                        <div class="streak-logo">
                            <img src="{{ asset('images/icons/fire.svg') }}" alt="" srcset="">
                        </div>
                        <div class="streak-info">
                            <h1 class="streak-count"></h1>
                            {{-- <img src="{{ asset('images/icons/book.svg') }}" alt="" srcset=""> --}}
                            <h3 class="streak-label">Ngày streak!</h3>
                        </div>
                    </div>
                    <div class="day-boxes">
                        <div class="day-column">
                            <span class="day-label">T2</span>
                            <span class="checkmark"><i class="bi bi-check"></i></span>
                        </div>
                        <div class="day-column">
                            <span class="day-label">T3</span>
                            <span class="checkmark"><i class="bi bi-check"></i></span>
                        </div>
                        <div class="day-column">
                            <span class="day-label">T4</span>
                            <span class="checkmark"><i class="bi bi-check"></i></span>
                        </div>
                        <div class="day-column">
                            <span class="day-label">T5</span>
                            <span class="checkmark"><i class="bi bi-check"></i></span>
                        </div>
                        <div class="day-column">
                            <span class="day-label">T6</span>
                            <span class="checkmark"><i class="bi bi-check"></i></span>
                        </div>
                        <div class="day-column">
                            <span class="day-label">T7</span>
                            <span class="checkmark"><i class="bi bi-check"></i></span>
                        </div>
                        <div class="day-column">
                            <span class="day-label">CN</span>
                            <span class="checkmark"><i class="bi bi-check"></i></span>
                        </div>
                    </div>
                    <p id="text_login_streak">Siêu quá! Bạn đã duy trì thành công chuỗi đăng nhập rồi đấy! Hãy vào học
                        để nhận điểm thưởng nhé!</p>
                    <button type="button" id="btnViewDetail" class="btn btn-primary">Xem chi tiết</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalLoginStreakDetail" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close btn-close-streak" aria-label="Close"><i
                            class="bi bi-x"></i></button>

                    <button type="button" class="btn btn-link" id="btnBackToStreak">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                </div>
                <div class="streak-calendar-container">
                    <div class="streak-header">
                        <div class="streak-logo">
                            <img src="{{ asset('images/icons/fire.svg') }}" alt="" srcset="">
                        </div>
                        <div class="streak-info">
                            <h1 class="streak-count"></h1>
                            <h3 class="streak-label">Ngày streak!</h3>
                        </div>
                    </div>
                    <div class="streak-container">
                        <div class="streak-bar">
                            <div class="streak-fill"></div>
                            <div class="fire-effect"></div>
                        </div>
                        <div class="streak-dates">
                        </div>
                    </div>
                    <div class="calendar">
                        <div class="calendar-header">
                            <!-- Buttons to navigate between months -->
                            <button id="prevMonth"><i class="bi bi-chevron-left"></i></button>
                            <span id="currentMonth"></span>
                            <button id="nextMonth"><i class="bi bi-chevron-right"></i></button>
                        </div>
                        <table class="table-calendar">
                            <thead>
                                <tr>
                                    <!-- Calendar headers (Days of the week) -->
                                    <th>T2</th>
                                    <th>T3</th>
                                    <th>T4</th>
                                    <th>T5</th>
                                    <th>T6</th>
                                    <th>T7</th>
                                    <th>CN</th>
                                </tr>
                            </thead>
                            <tbody id="calendarBody">
                                <!-- Calendar days will be dynamically inserted here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Legend for what each color in the calendar represents -->
                    <div class="legend">
                        <div class="legend-item">
                            <div class="legend-color studied-legend"></div>
                            <span>Đã học</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color not-studied-legend"></div>
                            <span>Chưa học</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color today-legend"></div>
                            <span>Hôm nay</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let LOGINGTEXT = '';
        @if (Auth::check() && Auth::user()->has_logged_in == false)
            LOGINGTEXT = 'Siêu quá! Bạn đã duy trì thành công chuỗi đăng nhập rồi đấy! Hãy vào học để nhận điểm thưởng nhé!';
        @else
            LOGINGTEXT = 'Tuyệt vời! Bạn đã nhận được điểm thưởng cho chuỗi đăng nhập và học hôm nay. Hãy tiếp tục phát huy nhé!';
        @endif
        $(document).ready(function() {
            // State management for async data
            let state = {
                streakCurrent: 0,
                lastLoginDate: '',
                currentDate: new Date(),
                currentDay: new Date().getDay(),
                streakMilestone: '',
                initialized: false
            };


            $('#text_login_streak').text(LOGINGTEXT);

            // Initialize data function
            async function initializeData() {
                try {
                    const response = await $.ajax({
                        url: '{{ route('daily_streak') }}',
                        method: 'GET'
                    });

                    state = {
                        ...state,
                        streakCurrent: response.streakCurrent,
                        lastLoginDate: response.lastLoginDate,
                        streakMilestone: response.streakMilestones,
                        initialized: true
                    };

                    // Update all UI elements with new data
                    updateAllUI();

                    return state;
                } catch (error) {
                    console.error('Error fetching data:', error);
                    throw error;
                }
            }

            // Function to update all UI elements
            function updateAllUI() {
                const previousStreak = parseInt(state.streakCurrent - 1);

                // Update streak displays
                updateStreakDisplay(previousStreak);

                // Update calendar
                renderCalendar();

                // Update streak progress
                updateStreakProgress();

                // Update active days
                setActiveDays(state.streakCurrent, state.currentDate);

                var old_streak = $('#owned_login_streak_mobile a');
                if (old_streak.length > 0) {
                    old_streak.text(state.streakCurrent);
                }

            }

            function updateStreakDisplay(previousStreak) {
                // Set initial value of "streak-count" to the previous streak
                $('.streak-count').text(previousStreak);

                // Check if last login is today
                if (state.lastLoginDate === state.currentDate.toISOString().split('T')[0]) {
                    setTimeout(function() {
                        $('.streak-count').text(state.streakCurrent);
                        $('.streak-count').addClass('animate__animated animate__fadeInUp');
                        $('.streak-number').text(state.streakCurrent);
                        $('.streak-number').addClass('animate__animated animate__fadeInUp');
                    }, 500);
                } else {
                    $('.streak-count').text(state.streakCurrent);
                    $('.streak-number').text(state.streakCurrent);
                }
            }

            // Your existing calendar code
            let currentMonth = new Date();
            const today = new Date();

            function renderCalendar() {
                const month = currentMonth.getMonth();
                const year = currentMonth.getFullYear();
                $('#currentMonth').text(`Tháng ${month + 1}/${year}`);

                const firstDay = (new Date(year, month, 1).getDay() + 6) % 7;
                const lastDate = new Date(year, month + 1, 0).getDate();
                const lastDatePrevMonth = new Date(year, month, 0).getDate();
                let nextMonthDay = 1;
                let calendarHTML = '';
                let day = 1;

                if (firstDay > 0) {
                    calendarHTML += '<tr>';
                    for (let i = 0; i < firstDay; i++) {
                        const dayPrevMonth = lastDatePrevMonth - firstDay + i + 1;
                        const datePrevMonth = new Date(year, month - 1, dayPrevMonth);
                        calendarHTML += `<td class="other-month" data-day="${dayPrevMonth}" data-month="${datePrevMonth.getMonth()}" data-year="${datePrevMonth.getFullYear()}">
                    ${dayPrevMonth}
                </td>`;
                    }
                }

                let filledCells = firstDay;
                while (day <= lastDate) {
                    if (filledCells % 7 === 0) {
                        calendarHTML += '<tr>';
                    }
                    const date = new Date(year, month, day);
                    const isToday = date.toDateString() === today.toDateString();
                    const isFuture = date > today;

                    calendarHTML += `<td class="${isToday ? 'today' : (isFuture ? 'future' : 'not-studied')}" data-day="${day}" data-month="${month}" data-year="${year}">
                <span>${day}</span>
            </td>`;
                    day++;
                    filledCells++;
                    if (filledCells % 7 === 0) {
                        calendarHTML += '</tr>';
                    }
                }

                while (filledCells % 7 !== 0) {
                    const dateNextMonth = new Date(year, month + 1, nextMonthDay);
                    calendarHTML += `<td class="other-month" data-day="${nextMonthDay}" data-month="${dateNextMonth.getMonth()}" data-year="${dateNextMonth.getFullYear()}">
                ${nextMonthDay}
            </td>`;
                    nextMonthDay++;
                    filledCells++;
                    if (filledCells % 7 === 0) {
                        calendarHTML += '</tr>';
                    }
                }

                $('#calendarBody').html(calendarHTML);
                setDailystreak();
            }

            function setDailystreak() {
                const today = new Date();
                const previousStreak = parseInt(state.streakCurrent - 1);
                const startDate = new Date(today);
                startDate.setDate(today.getDate() - previousStreak);

                const endDate = today;

                startDate.setHours(0, 0, 0, 0);
                endDate.setHours(0, 0, 0, 0);
                today.setHours(0, 0, 0, 0);

                $('#calendarBody td').each(function() {
                    const $td = $(this);

                    if ($td.hasClass('other-month')) {
                        return;
                    }

                    const day = parseInt($td.attr('data-day'));
                    const month = parseInt($td.attr('data-month'));
                    const year = parseInt($td.attr('data-year'));
                    const date = new Date(year, month, day);

                    if (state.streakCurrent > 1) {
                        if (date >= startDate && date <= endDate && date <= today) {
                            $td.removeClass().addClass('selected-range');
                        } else {
                            $td.removeClass('selected-range');
                        }

                        if (date.getTime() === startDate.getTime() && date.getTime() <= today.getTime()) {
                            $td.addClass('start-range');
                        }

                        if (date.getTime() === endDate.getTime() && date.getTime() <= today.getTime()) {
                            $td.addClass('end-range');
                        }
                    } else {
                        if (date.getTime() === today.getTime() && state.streakCurrent == 0) {
                            $td.addClass('one-day-not-log-in');
                        } else if (date.getTime() === today.getTime() && state.streakCurrent == 1) {
                            $td.addClass('one-day');
                        }
                    }
                    if (date.getTime() === today.getTime()) {
                        $td.addClass('today');
                    }
                });
            }

            function updateStreakProgress() {
                let totalDays = state.streakMilestone[state.streakMilestone.length - 1];
                createMilestones(state.streakMilestone, totalDays);

                if (state.streakCurrent <= totalDays) {
                    let progressWidth = (state.streakCurrent / totalDays) * 100;
                    $(".streak-fill").css("width", progressWidth + "%");
                }
            }

            function createMilestones(milestones, totalDays) {
                $(".streak-dates").empty();

                milestones.forEach(function(day, index) {
                    let leftPosition = (day / totalDays) * 100;
                    let transformStyle = 'translateX(-10px)';
                    let displayStyle = '';

                    if (index === milestones.length - 1) {
                        transformStyle = 'translateX(-30px)';
                    }

                    if (index === 0) {
                        displayStyle = 'display: none;';
                    }

                    $(".streak-dates").append('<span class="milestone" style="left:' + leftPosition +
                        '%; transform: ' + transformStyle + '; ' + displayStyle + '">' + day + '</span>'
                    );
                });
            }

            function setActiveDays(streakCurrent, lastLoginDateStr) {
                // Parse the last login date and get the current date
                const lastLoginDate = new Date(
                    lastLoginDateStr); // Remove .date since lastLoginDateStr is already a date string
                const currentDate = new Date();

                // Check if lastLoginDate is the same as currentDate
                const isToday = lastLoginDate.toDateString() === currentDate.toDateString();

                // Get the current day of the week (0-6, where 0 is Sunday)
                const currentDayOfWeek = currentDate.getDay();

                // Adjust to match your week format (if week starts on Monday)
                let dayOfWeek = currentDayOfWeek === 0 ? 6 : currentDayOfWeek - 1;

                // Parse streakCurrent into an integer
                const streak = parseInt(streakCurrent);

                $('.day-column').each(function(index) {
                    const $this = $(this);
                    const $checkmark = $this.find('.checkmark');
                    const $icon = $checkmark.find('i');

                    // Reset all classes first
                    $this.removeClass('inactive active unactive');
                    $checkmark.removeClass('inactive active unactive');
                    $icon.removeClass('bi-check bi-ban bi-dash-circle');

                    if (streak === 0) {
                        // Case 3: If streak is 0, all days are inactive
                        $this.addClass('inactive');
                        $checkmark.addClass('inactive');
                        $icon.addClass('bi-ban');
                    } else {
                        if (index > dayOfWeek) {
                            // Future days are always inactive
                            $this.addClass('inactive');
                            $checkmark.addClass('inactive');
                            $icon.addClass('bi-ban');
                        } else if (isToday) {
                            // Case 1: If last login is today
                            if (index <= dayOfWeek && index >= (dayOfWeek - streak + 1)) {
                                // Active current day and days within streak range counting from current day
                                $this.addClass('active');
                                $checkmark.addClass('active');
                                $icon.addClass('bi-check');
                            } else if (index < (dayOfWeek - streak + 1)) {
                                // Days before streak range
                                $this.addClass('unactive');
                                $checkmark.addClass('unactive');
                                $icon.addClass('bi-dash-circle');
                            }
                        } else {
                            // Case 2: If last login is not today
                            if (index < dayOfWeek && index >= (dayOfWeek - streak)) {
                                // Active days within streak range before current day
                                $this.addClass('active');
                                $checkmark.addClass('active');
                                $icon.addClass('bi-check');
                            } else if (index < (dayOfWeek - streak)) {
                                // Days before streak range
                                $this.addClass('unactive');
                                $checkmark.addClass('unactive');
                                $icon.addClass('bi-dash-circle');
                            } else {
                                // Current day is inactive when last login is not today
                                $this.addClass('inactive');
                                $checkmark.addClass('inactive');
                                $icon.addClass('bi-ban');
                            }
                        }
                    }
                });
            }

            // Event handlers
            $('#modalLoginStreak').on('show.bs.modal', async function() {
                try {
                    await initializeData();
                } catch (error) {
                    console.error('Error initializing modal data:', error);
                }
            });

            $('.btn-close-streak').on('click', function() {
                $('#modalLoginStreak').modal('hide');
                $('#modalLoginStreakDetail').modal('hide');
            });

            $('#prevMonth').click(function() {
                currentMonth.setMonth(currentMonth.getMonth() - 1);
                renderCalendar();
            });

            $('#nextMonth').click(function() {
                currentMonth.setMonth(currentMonth.getMonth() + 1);
                renderCalendar();
            });

            $('#btnViewDetail').click(function() {
                $('#modalLoginStreak').modal('hide');
                $('#modalLoginStreakDetail').modal('show');
            });

            $('#btnBackToStreak').click(function() {
                $('#modalLoginStreakDetail').modal('hide');
                $('#modalLoginStreak').modal('show');
            });

            // Initial render
            initializeData().then(() => {
                renderCalendar();
            }).catch(error => {
                console.error('Error during initialization:', error);
            });
        });
    </script>
