// Init variables
let currentCourseId = null;
let currentRoadmapId = null;
let currentDayElement = null;
let currentDurationMonths = -1;
let currentChosedContentIds = {};
let isShowingCourseRoadmapsModal = false;

function showCourseRoadmaps(courseId) {
    currentCourseId = courseId;

    const course = courses.find(c => c.id === courseId);
    const roadmaps = course.roadmaps;
    const roadmapList = $('#roadmapList');
    const roadmapDurations =  $("#newRoadmapDuration");
    roadmapList.empty();
    roadmapDurations.empty();

    course.roadmaps.forEach(roadmap => {
        const li = $('<li>')
            .addClass('list-group-item clearfix')
            .attr('role', 'button')
            .attr('data-month-duration', roadmap.duration_months)
            .text(`Lộ trình (${roadmap.duration_months} tháng)`);
        const viewBtn = $("<button>")
            .addClass("btn btn-primary btn-xs pull-right view-roadmap-btn")
            .text("Xem")
            .on("click", () => {
                $("#courseRoadmapsModal").modal("hide");
                showRoadmapDetails(course, roadmap.id);
                makeRoadmapDetailDraggable();

                const roadmapInfo = $("#roadmap_info");
                if (roadmapInfo.length) {
                    $("html, body").animate({
                        scrollTop: roadmapInfo.offset().top - 100
                    }, 500);
                }
          });
        li.append(viewBtn);
        roadmapList.append(li);
    });

    if (roadmapList.children().length == 0) {
        roadmapList.append(
            $('<p>')
                .css('font-size', '16px')
                .addClass('text-center text-error text-capitalize')
                .text('Khoá học chưa có lộ trình!')
        )
    }

    const maxDurationMonths = 6;
    for (let i = 0; i < maxDurationMonths; i++) {
        if (roadmaps.find(r => r.duration_months == i + 1)) {
            continue;
        }
        roadmapDurations.append(
            $('<option>').val(i + 1).text(`${i + 1} tháng`)
        );
    }

    if (isShowingCourseRoadmapsModal) {
        $('#courseRoadmapsModal').modal('show');
    }
}

function showRoadmapDetails(course, roadmapId, selectedNewDurationMonths = -1) {
    // set active course item
    $(".list-group-item").removeClass("active");
    $(`.list-group-item[data-course-id="${course.id}"]`).addClass("active");

    // show roadmap detail container
    $("#roadmapDetailsWrapper").removeClass('d-none');

    // set current roadmap id
    currentRoadmapId = roadmapId;

    // Hide deleting roadmap button by default and show it when showing saved roadmap details
    const deleteRoadmapBtn = $('#deleteRoadmapBtn');
    deleteRoadmapBtn.addClass('d-none').off('click');
    if (selectedNewDurationMonths == -1) {
        deleteRoadmapBtn.removeClass('d-none');
        deleteRoadmapBtn.on('click', () => deleteRoadmap(roadmapId));
    }

    // empty roadmap details and current chosed id list
    const roadmapDetails = $('#roadmapDetails');
    roadmapDetails.empty();

    // set course and roadmap details
    const roadMapDetail = roadMapDetailsList.find(r => r.id === roadmapId);
    currentDurationMonths = roadMapDetail?.duration_months ?? selectedNewDurationMonths;

    // get roadmap and set max day number
    let durationDays = 0;
    let maxDayNum = currentDurationMonths * 30;
    const roadmap = roadMapDetailsList.find(r => r.id == roadmapId);
    if (roadmap) {
        currentChosedContentIds = getChosedContentIds(roadmapId);
        maxDayNum = roadmap.contents.reduce((max, current) =>
            (current.day_number > max.day_number) ? current : max
        ).day_number;
    } else {
        currentChosedContentIds = {};
    }
    durationDays = Math.max(maxDayNum, currentDurationMonths * 30);

    // show roadmap title
    $("#roadmapTitle").text(`Lộ trình ${currentDurationMonths} tháng - ${course.title}`);
    $("#roadmapDayCount").text(durationDays);
    $("#roadmapLessonDayCount").text(roadmap?.contents?.length ?? 0);

    // show roadmap details
    const monthDiv = $("<div>").addClass("roadmap-month");
    const monthRoadMapWrapper = $("<div>").addClass("month-roadmap-wrapper");

    const table = $("<table>").addClass("table table-calendar");
    const tbody = $("<tbody>");

    for (let day = 1; day <= durationDays; day++) {
        if ((day - 1) % 7 === 0) {
            tbody.append(
              $("<tr>").append(`
                    <td class="week-table-day">
                        <p><strong>Tuần ${Math.floor(day / 7) + 1}</strong></p>
                    </td>
                `)
            );
        }

        let td = null;
        let dayDiv = null;
        if (day <= currentDurationMonths * 30) {
            td = $("<td>").addClass("table-day");
            dayDiv = $("<div>")
                .addClass("roadmap-day")
                .attr("data-day", day)
                .append($("<strong>").text(`Ngày ${day}`))
                .on("click", function () {
                    currentDayElement = $(this);
                    showAddLessonModal(course.id, day);
                });
        } else {
            td = $('<td>').addClass('table-day removable');
            dayDiv = $("<div>")
                .addClass("roadmap-day")
                .attr("data-day", day)
                .append($("<strong>").text(`Ngày ${day}`))
                .append(
                    `<span aria-hidden="true" class="text-danger close-table-day" onclick="closeTableDay(this)">
                        &times;
                    </span>`)
                .on("click", function () {
                    currentDayElement = $(this);
                    showAddLessonModal(course.id, day);
                });
        }
        const dayContent = roadMapDetail?.contents?.find(
            (c) => c.day_number === day
        );
        if (dayContent) {
            dayContent.lesson_list.forEach((lesson) => {
                showLessonToDayForSavedRoadmap(lesson, dayDiv);
            });
        }

        td.append(dayDiv);
        tbody.find("tr:last").append(td);
    }

    table.append(tbody);

    monthDiv.append(monthRoadMapWrapper);
    monthRoadMapWrapper.append(table);
    roadmapDetails.append(monthDiv);

    const addDayButton = $("<button>")
      .addClass("btn btn-default btn-primary")
      .text("+ Thêm ngày")
      .on("click", () => addDayToRoadmap(course.id, roadmapId));
    roadmapDetails.append(addDayButton);
    roadmapDetails.append(`
        <h4 style="margin-top: 24px;">Mô tả lộ trình</h4>
        <textarea id="roadmap_description" class="form-control" rows="6"></textarea>
    `);

    $('#roadmap_description').text(roadMapDetail?.description ?? '');
    const roadmapInfo = $("#roadmap_info");
    if (roadmapInfo.length) {
        console.log(roadmapInfo);
        $("html, body").animate({
            scrollTop: roadmapInfo.offset().top - 100
        }, 500);
    }
}

function showAddLessonModal(courseId, dayNumber) {
    // clear remaining
    const lessonSelect = $('#lessonSelect');
    lessonSelect.empty();

    // filter lesson and render course selection by course id
    // const courseLessons = lessons.filter(lesson => lesson.courseId == courseId);
    const courseLessons = lessons[courseId];
    let firstSelectedItem = null;

    courseLessons.forEach(lesson => {
        const chosableClass =
            lesson.num_type != contentChapterType && lesson.num_type != contentTopicType
            ? "chosable"
            : "";
        if (!Object.keys(currentChosedContentIds).filter(chosedId => chosedId == lesson.id).length)
        {
            lessonSelect.append(`
                <div class="lesson-select-item ${chosableClass}" data-id="${lesson.id}" data-lesson-type="${lesson.num_type}">
                    ${lesson.name}
                </div>
            `);
        } else if (currentChosedContentIds[lesson.id] == dayNumber) {
            lessonSelect.append(`
                <div class="lesson-select-item selected ${chosableClass}" data-id="${lesson.id}" data-lesson-type="${lesson.num_type}">
                    ${lesson.name}
                </div>
            `);

            if (!firstSelectedItem) {
                firstSelectedItem = $(`.lesson-select-item[data-id="${lesson.id}"]`)[0];
            }
        } else {
            lessonSelect.append(`
                <div class="lesson-select-item disabled ${chosableClass}" data-id="${lesson.id}" data-lesson-type="${lesson.num_type}">
                    ${lesson.name}
                </div>
            `);
        }
    });

    // Unbind events
    lessonSelect.find('.lesson-select-item').off('click');
    $('#saveLessonToDay').off('click');

    // Bind events
    lessonSelect.find('.lesson-select-item.chosable').not('.disabled').on('click', function() {
        $(this).toggleClass('selected');
    });

    $("#saveLessonToDay").on("click", () => {
        Object.keys(currentChosedContentIds).forEach((lessonId) => {
            if (currentChosedContentIds[lessonId] == dayNumber) {
                delete currentChosedContentIds[lessonId];
            }
        });
        currentDayElement.find(".lesson-item").remove();

        const selectedIds = $(".lesson-select-item.selected")
            .not(".disabled")
            .map(function () {
                return {
                    id: $(this).data("id"),
                    num_type: $(this).data("lesson-type"),
                };
            })
            .get();

        if (selectedIds.length > 0) {
            let totalRoadmapLessonDayCount = parseInt($('#roadmapLessonDayCount').text())
            $('#roadmapLessonDayCount').text(totalRoadmapLessonDayCount + 1);
        }
        selectedIds.forEach((info) => {
            addLessonToDay(info.id, info.num_type);
            currentChosedContentIds[info.id] = dayNumber;
        });

        $("#addLessonModal").modal("hide");
    });

    $('#addLessonModal').modal('show');
    if (firstSelectedItem) {
        setTimeout(() => {
            scrollToItem(lessonSelect[0], firstSelectedItem, 30);
        }, 200);
    }
    // else if ($('.roadmap-day[data-day="' + dayNumber + '"] .lesson-item').length == 0) {
    //     setTimeout(() => {
    //         scrollToItem(lessonSelect[0], $('.lesson-select-item.chosable').not('.disabled')[0], 30);
    //     }, 200);
    // }
}

function scrollToItem(container, targetItem, offset = 0) {
    const containerRect = container.getBoundingClientRect();
    const targetRect = targetItem.getBoundingClientRect();

    const scrollTop = targetRect.top - containerRect.top + container.scrollTop - offset;

    container.scrollTo({
        top: scrollTop,
        behavior: "smooth",
    });
}

function addLessonToDay(lessonId, lessonNumType) {
    if (currentDayElement) {
        const lesson = lessons[currentCourseId].find(l => l.id === parseInt(lessonId));
        const lessonType = getTextTypeOfLesson(lessonNumType);
        const lessonElement = $('<div>')
            .addClass('lesson-item')
            .attr('data-lesson-id', lessonId)
            .attr('data-lesson-type', lessonType)
            .text(lesson.name);
        currentDayElement.append(lessonElement);
    }
}

function showLessonToDayForSavedRoadmap(lesson, dayElement) {
    const lessonElement = $('<div>')
        .addClass('lesson-item')
        .attr('data-lesson-id', lesson.id)
        .attr('data-lesson-type', lesson.type)
        .text(lesson.name);
    dayElement.append(lessonElement);
}

function addDayToRoadmap(courseId) {
    const roadmapDetails = $('#roadmapDetails');
    const lastMonth = roadmapDetails.find('.roadmap-month').last();
    const lastTable = lastMonth.find('table').last();

    const dayCount = $('.roadmap-day').length;
    const newDay = dayCount + 1;

    let totalRoadmapDayCount = parseInt($('#roadmapDayCount').text())
    $('#roadmapDayCount').text(totalRoadmapDayCount + 1);

    if (dayCount % 7 === 0) {
        const weekNum = lastTable.find('tr').length;
        lastTable.append(
          $("<tr>").append(`
                <td class="week-table-day">
                    <p><strong>Tuần ${weekNum + 1}</strong></p>
                </td>
            `)
        );
    }

    const td = $('<td>').addClass('table-day removable');
    const dayDiv = $("<div>")
        .addClass("roadmap-day")
        .attr("data-day", newDay)
        .append($("<strong>").text(`Ngày ${newDay}`))
        .append(
            `<span aria-hidden="true" class="text-danger close-table-day" onclick="closeTableDay(this)">
                &times;
            </span>`)
        .on("click", function () {
            currentDayElement = $(this);
            showAddLessonModal(courseId, newDay);
        });

    td.append(dayDiv);
    lastTable.find('tr:last').append(td);
}

function closeTableDay(element) {
    const dayNumber = $(element).closest(".roadmap-day").data("day");
    Object.keys(currentChosedContentIds).forEach((lessonId) => {
        if (currentChosedContentIds[lessonId] == dayNumber) {
            delete currentChosedContentIds[lessonId];
        }
    });

    if ($(element).closest("tr").children().length == 2) {
        $(element).closest("tr").remove();
    } else {
        $(element).closest('.table-day.removable').remove();
    }

    let totalRoadmapDayCount = parseInt($('#roadmapDayCount').text())
    $('#roadmapDayCount').text(totalRoadmapDayCount - 1);
}

function adjustRoadmapDayHeights() {
    const rows = document.querySelectorAll('tr');

    rows.forEach(row => {
        const roadmapDays = row.querySelectorAll('.roadmap-day');
        let maxHeight = 0;

        roadmapDays.forEach(day => {
            day.style.height = 'auto';
            const height = day.offsetHeight;
            if (height > maxHeight) {
                maxHeight = height;
            }
        });

        roadmapDays.forEach(day => {
            day.style.height = `${maxHeight}px`;
        });
    });
}

function generateRandomString(length = 6) {
    const characters =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    let result = "";
    const charactersLength = characters.length;
    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function getChosedContentIds(roadmapId) {
    const data = roadMapDetailsList.find(r => r.id == roadmapId).contents;
    const result = {};

    data.forEach(day => {
        day.lesson_list.forEach(lesson => {
            result[lesson.id] = day.day_number;
        });
    });

    return result;
}

function saveRoadmap() {
    const roadmapContents = [];

    if ($('.roadmap-day .lesson-item').length == 0) {
        Swal.fire({
            title: "Lưu lộ trình thất bại",
            text: "Một lộ trình phải được lưu với ít nhất 1 bài học!",
            icon: "error"
        });
        return;
    }

    $('.roadmap-day').each(function(dayIndex, roadmapDay) {
        const dayLessons = [];

        $(roadmapDay).find('.lesson-item').each(function(lessonIndex, dayLesson) {
            dayLessons.push({
                id: $(dayLesson).data('lesson-id'),
                type: $(dayLesson).data('lesson-type'),
                name: $(dayLesson).text().trim()
            });
        });

        if (dayLessons.length == 0) return;

        roadmapContents.push({
            day_number: $(roadmapDay).data('day'),
            lesson_list: dayLessons
        });
    });

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: SAVE_ROADMAP_URL,
        type: "post",
        data: {
            contents: JSON.stringify(roadmapContents),
            course_id: currentCourseId,
            duration_months: currentDurationMonths,
            description: $('#roadmap_description').val()
        },
        success: function() {
            Swal.fire({
                title: "Thông báo",
                text: "Lưu lộ trình thành công",
                icon: "success",
                timer: 2000,
                showConfirmButton: false,
            }).then(() => {
                window.location.href = window.location.origin + window.location.pathname + `?lmsseries_id=${currentCourseId}&duration_months=${currentDurationMonths}`;
            });
        }
    });
}

const getTextTypeOfLesson = (numType) => {
    for (const [key, value] of Object.entries(lessonTypeMap)) {
        if (value.includes(numType)) return key;
    }

    return null;
}

const openRoadmapFromParams = () => {
    const urlParams = new URLSearchParams(window.location.search);
    const courseId = urlParams.get('lmsseries_id');
    const durationMonths = urlParams.get('duration_months');

    if (!(courseId && durationMonths)) return;

    const course = courses.find((c) => c.id == courseId);

    if (course && course?.roadmaps?.find(r => r?.duration_months == durationMonths)) {
        $(`.list-group-item[data-course-id="${courseId}"]`).trigger('click');
        setTimeout(() => {
            $(`.list-group-item[data-month-duration="${durationMonths}"] button`).trigger('click');
        }, 50);
    }
}

const deleteRoadmap = (roadmapId) => {
    Swal.fire({
        title: "Xác nhận",
        text: "Bạn có chắc chắn muốn xoá lộ trình học này?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Xoá"
    }).then((result) => {
        if (!result.isConfirmed) return;

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: DELETE_ROADMAP_URL,
            type: "post",
            data: {
                id: roadmapId,
                _method: 'delete'
            },
            success: function() {
                Swal.fire({
                    title: "Thông báo",
                    text: "Xoá lộ trình thành công",
                    icon: "success",
                    timer: 2000,
                    showConfirmButton: false,
                }).then(() => {
                    window.location.href = window.location.origin + window.location.pathname;
                });
            }
        });
    });
}

const makeRoadmapDetailDraggable = () => {
    const container = $('.month-roadmap-wrapper')[0];
    let isMouseDown = false;
    let startX, startY, scrollLeft, scrollTop;

    container.style.userSelect = 'none';

    container.addEventListener('mousedown', (e) => {
        isMouseDown = true;
        container.style.cursor = 'grabbing';
        startX = e.pageX - container.offsetLeft;
        startY = e.pageY - container.offsetTop;
        scrollLeft = container.scrollLeft;
        scrollTop = container.scrollTop;
    });

    container.addEventListener('mouseleave', () => {
        isMouseDown = false;
        container.style.cursor = 'grab';
    });

    container.addEventListener('mouseup', () => {
        isMouseDown = false;
        container.style.cursor = 'grab';
    });

    container.addEventListener('mousemove', (e) => {
        if (!isMouseDown) return;
        e.preventDefault();
        const x = e.pageX - container.offsetLeft;
        const y = e.pageY - container.offsetTop;
        const walkX = (x - startX) * 1.5; // Tốc độ cuộn
        const walkY = (y - startY) * 1.5;
        container.scrollLeft = scrollLeft - walkX;
        container.scrollTop = scrollTop - walkY;
    });

    // Xử lý cho thiết bị cảm ứng
    container.addEventListener('touchstart', (e) => {
        const touch = e.touches[0];
        startX = touch.pageX - container.offsetLeft;
        startY = touch.pageY - container.offsetTop;
        scrollLeft = container.scrollLeft;
        scrollTop = container.scrollTop;
    });

    container.addEventListener('touchmove', (e) => {
        e.preventDefault();
        const touch = e.touches[0];
        const x = touch.pageX - container.offsetLeft;
        const y = touch.pageY - container.offsetTop;
        const walkX = (x - startX) * 1.5;
        const walkY = (y - startY) * 1.5;
        container.scrollLeft = scrollLeft - walkX;
        container.scrollTop = scrollTop - walkY;
    });
}

$(() => {
    $("#addNewRoadmap").on("click", () => {
        const duration = $("#newRoadmapDuration").val();
        currentDurationMonths = duration;
        const course = courses.find((c) => c.id === currentCourseId);
        const newRoadmapId = generateRandomString();
        $("#courseRoadmapsModal").modal("hide");
        showRoadmapDetails(course, newRoadmapId, duration);
        makeRoadmapDetailDraggable();
    });

    openRoadmapFromParams();

    // Allow showing course roadmap detail modal after amount of time
    setTimeout(() => {
        isShowingCourseRoadmapsModal = true;
    }, 500);
});

// Resize the roadmap day heights on load and on resize
window.addEventListener('load', adjustRoadmapDayHeights);
window.addEventListener('resize', adjustRoadmapDayHeights);

const observer = new MutationObserver(adjustRoadmapDayHeights);
observer.observe(document.body, { childList: true, subtree: true });