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
        }

        .streak-count {
            color: #166AC9;
            margin: 0;
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
                    <button type="button" class="btn-close btn-close-streak" aria-label="Close"><i class="bi bi-x"></i></button>
                </div>
                <div class="modal-body text-center">
                    <div class="streak-header">
                        <div class="streak-logo">
                            <img src="{{ asset('images/icons/fire.svg') }}" alt="" srcset="">
                        </div>
                        <div class="streak-info">
                            <h1 class="streak-count"></h1>
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
                    <p>Chúc mừng! bạn đã duy trì Day streak thành công!</p>
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
                    <button type="button" class="btn-close btn-close-streak" aria-label="Close"><i class="bi bi-x"></i></button>

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
                            <span id="currentMonth">Tháng 10/2024</span>
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
        const streakCurrent = '{{ Auth::user()->login_streak }}'; // Current streak value
        const currentDate = new Date(); // Ensure this is defined
        const currentDay = currentDate.getDay();
        $(document).ready(function() {
            // Animation for "streak-count" on page load
            const previousStreak = parseInt(streakCurrent - 1);

            // Set initial value of "streak-count" to the previous streak
            $('.streak-count').text(previousStreak);

            $('.btn-close-streak').on('click', function () {
                $('#modalLoginStreak').modal('hide');
                $('#modalLoginStreakDetail').modal('hide');
            });
            // After a short delay, update the value and add the animation
            setTimeout(function() {
                $('.streak-count').text(streakCurrent);
                $('.streak-count').addClass('animate__animated animate__fadeInUp');
                $('.streak-number').text(streakCurrent);
                $('.streak-number').addClass('animate__animated animate__fadeInUp');
            }, 2000); // Adjust timing if necessary

            let currentMonth = new Date(2024, 9); // Start from October 2024
            const today = new Date(); // Current date

            function renderCalendar() {
                // Get the current month and year
                const month = currentMonth.getMonth();
                const year = currentMonth.getFullYear();
                $('#currentMonth').text(`Tháng ${month + 1}/${year}`);

                // Get first day of the month and last date
                const firstDay = (new Date(year, month, 1).getDay() + 6) % 7; // Adjust to start from Monday
                const lastDate = new Date(year, month + 1, 0).getDate();
                const lastDatePrevMonth = new Date(year, month, 0)
                    .getDate(); // Number of days in the previous month
                let nextMonthDay = 1; // Starting day of the next month
                let calendarHTML = '';
                let day = 1;

                // Fill in days from the previous month if needed
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

                // Fill in the days of the current month
                let filledCells = firstDay;
                while (day <= lastDate) {
                    if (filledCells % 7 === 0) {
                        calendarHTML += '<tr>';
                    }
                    const date = new Date(year, month, day);
                    const isToday = date.toDateString() === today.toDateString();
                    const isFuture = date > today;

                    // Assign classes for today's date, future dates, or not studied dates
                    calendarHTML += `<td class="${isToday ? 'today' : (isFuture ? 'future' : 'not-studied')}" data-day="${day}" data-month="${month}" data-year="${year}">
                <span>${day}</span>
            </td>`;
                    day++;
                    filledCells++;
                    if (filledCells % 7 === 0) {
                        calendarHTML += '</tr>';
                    }
                }

                // Fill in days of the next month if needed
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

                // Call setDailystreak after the calendar is rendered
                setDailystreak();
            }

            // Function to highlight the date range
            function setDailystreak() {
                const today = new Date(); // Define "today" to compare
                // Set startDate to today minus the number of days equal to the streak
                const startDate = new Date(today);
                startDate.setDate(today.getDate() - previousStreak);
                console.log(startDate);

                const endDate = today; // endDate is today


                // Set hours for startDate and endDate to 00:00:00
                startDate.setHours(0, 0, 0, 0);
                endDate.setHours(0, 0, 0, 0);
                today.setHours(0, 0, 0, 0);

                // Highlight the selected date range and only before today
                $(document).ready(function() {
                    $('#calendarBody td').each(function() {
                        const $td = $(this);

                        // Skip the iteration if the td has the class 'other-month'
                        if ($td.hasClass('other-month')) {
                            return; // Continue to the next td
                        }

                        // Get the day, month, and year from the data attributes
                        const day = parseInt($td.attr('data-day'));
                        const month = parseInt($td.attr('data-month'));
                        const year = parseInt($td.attr('data-year'));
                        const date = new Date(year, month, day);

                        // Check if the date is today and add the 'today' class
                        if (date.getTime() === today.getTime()) {
                            $td.addClass('today');
                        }

                        // Handle streak logic for streaks greater than 1
                        if (streakCurrent > 1) {
                            if (date >= startDate && date <= endDate && date <= today) {
                                $td.removeClass().addClass(
                                    'selected-range'); // Reset classes and add 'selected-range'
                            } else {
                                $td.removeClass(
                                    'selected-range'
                                ); // Remove 'selected-range' if conditions are not met
                            }

                            // Add 'start-range' class if the date matches the start date
                            if (date.getTime() === startDate.getTime() && date.getTime() <= today
                                .getTime()) {
                                $td.addClass('start-range');
                            }

                            // Add 'end-range' class if the date matches the end date
                            if (date.getTime() === endDate.getTime() && date.getTime() <= today
                                .getTime()) {
                                $td.addClass('end-range');
                            }
                        } else { // Handle the case where streak is 1 or less
                            if (date.getTime() === today.getTime()) {
                                $td.addClass('one-day'); // Mark today as 'one-day' for streak of 1
                            }
                        }
                    });
                });

            }

            // Event handlers for changing months
            $('#prevMonth').click(function() {
                currentMonth.setMonth(currentMonth.getMonth() - 1);
                renderCalendar();
            });

            $('#nextMonth').click(function() {
                currentMonth.setMonth(currentMonth.getMonth() + 1);
                renderCalendar();
            });

            renderCalendar(); // Initial render of the calendar

            let totalDays = 100;
            let milestones = [0, 5, 15, 30, 45, 100];

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

            // Gọi hàm tạo milestones
            createMilestones(milestones, totalDays);

            if (streakCurrent <= totalDays) {
                let progressWidth = (streakCurrent / totalDays) * 100;
                $(".streak-fill").css("width", progressWidth + "%");
            }



            $('#btnViewDetail').click(function() {
                $('#modalLoginStreak').modal('hide');
                $('#modalLoginStreakDetail').modal('show');
            });

            $('#btnBackToStreak').click(function() {
                $('#modalLoginStreakDetail').modal('hide');

                $('#modalLoginStreak').modal('show');
            });

            function setActiveDays(streakCurrent) {
                // Get the current day (0: Sunday, 1: Monday, ..., 6: Saturday)
                const currentDay = new Date().getDay();

                // Convert streak to an integer
                const activeDays = parseInt(streakCurrent);

                // Loop through each day column
                $('.day-column').each(function(index) {
                    const $this = $(this); // Cache the current element
                    const $checkmark = $this.find('.checkmark'); // Find the associated checkmark

                    // If the index is less than the number of active days and the index is less than or equal to the current day, activate it
                    if (index < activeDays && index <= currentDay) {
                        $this.removeClass('inactive').addClass('active'); // Activate the day
                        $checkmark.removeClass('inactive').addClass('active'); // Activate the checkmark
                    } else {
                        $this.removeClass('active').addClass('inactive'); // Deactivate the day
                        $checkmark.removeClass('active').addClass('inactive'); // Deactivate the checkmark
                    }
                });
            }

            setActiveDays(streakCurrent);
        });
    </script>
