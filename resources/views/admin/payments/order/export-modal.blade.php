<style>
    .export-modal .chart-box {
        display: flex;
        justify-content: center;
    }

    .export-modal .modal-custom {
        width: 1200px;
        overflow-x: auto;
    }

    @media not all and (min-width: 1280px) {
        .export-modal .modal-custom {
            width: 90% !important;
            margin-left: auto;
            margin-right: auto;
        }
    }

    .export-modal .modal-body {
        height: 450px;
        overflow-y: auto;
        width: 1200px;
    }

    #chart1,
    #chart2 {
        border: 1px solid black;
        float: left;
        width: 575px;
        height: 400px;
        padding: 5px;
    }

    #chart3,
    #chart4 {
        border: 1px solid black;
        float: left;
        width: 575px;
        height: 500px;
        padding: 5px;
    }

    .export-modal .modal-content {
        min-width: 1200px;
    }

    @media (min-width: 768px) {
        .modal-dialog {
            max-width: 1200px;
            width: 1200px !important;
        }
    }

    .export-modal .input-daterange {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 250px;
        max-width: 250px;
    }

    .export-modal .input-daterange input {
        min-height: 35px;
        margin-left: 5px;
        margin-right: 5px;
        border-color: black;
    }

    .export-modal .date-button-group {
        display: flex;
        justify-content: start;
        align-content: center;
        gap: 8px;
    }

    .chart-group {
        position: absolute;
        top: 60px;
        left: 10px;
    }

    .date-error-message {
        color: red;
    }
</style>

<div class="modal fade export-modal" id="export_modal" tabindex="-1" role="dialog" aria-labelledby="export_modal"
    aria-hidden="true">
    <div class="modal-dialog modal-custom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Export PDF</h3>
            </div>

            <div class="modal-body">
                <div class="date-button-group">
                    <div class="input-group input-daterange">
                        <input type="text" id="from_date" class="form-control" value="">
                        <div class=""> ~ </div>
                        <input type="text" id="to_date" class="form-control" value="">
                    </div>
                    <div class="date-error-message"></div>
                </div>
                <div class="chart-group">
                    <div class="chart-box">
                        <div id="chart1"></div>
                        <div id="chart2"></div>
                    </div>
                    <div class="chart-box">
                        <div id="chart3"></div>
                        <div id="chart4"></div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Huỷ</button>
                <button type="button" id="export_pdf" class="btn btn-primary" onclick="exportChartToPDF()"
                    disabled>Export</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ admin_asset('js/echarts.min.js') }}"></script>
<script src="{{ admin_asset('js/jspdf.umd.min.js') }}"></script>
<script src="{{ admin_asset('js/html2canvas.min.js') }}"></script>
<script src="{{ admin_asset('js/jquery.min.js') }}"></script>
<script src="{{ admin_asset('js/moment.min.js') }}"></script>
<script src="{{ admin_asset('js/bootstrap-datepicker.min.js') }}" defer></script>
<script>
    const now = new Date();
    var fromDate = formatDate(now);
    var toDate = formatDate(now);
    var errorMessageDiv = document.querySelector('.date-error-message');
    $(document).ready(function() {
        $('#export').prop('disabled', false);
        if (fromDate && toDate && isValidDate(fromDate) && isValidDate(toDate)) {
            $('#from_date').val(fromDate);
            $('#to_date').val(toDate);
            getNumberOfStudentsChart(fromDate, toDate);
        }

        $("#export_modal").on("show.bs.modal", function(event) {
            getChartData();
        });

        $(".input-daterange").datepicker({
            format: "dd-mm-yyyy",
            todayHighlight: true,
        }).on("changeDate", function() {
            fromDate = $("#from_date").val();
            toDate = $("#to_date").val();
            errorMessageDiv.textContent = '';
            if (fromDate && toDate && isValidDate(fromDate) && isValidDate(toDate)) {
                getNumberOfStudentsChart(fromDate, toDate);
            } else {
                errorMessageDiv.textContent = "Ngày không đúng định dạng dd-mm-yyyy";
            }
        });

        $(".modal-body").on("scroll", function() {
            $(".input-daterange input").datepicker("hide");
        });
    });

    function isValidDate(dateString) {
        if (!moment(dateString, 'DD-MM-YYYY', true).isValid()) return false;

        return true;
    }

    function formatDate(date) {
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}-${month}-${year}`;
    }

    function getNumberOfStudentsChart(startTime, endTime) {
        $.ajax({
            url: "{{ URL_GET_STUDENT_CHART_DATA }}",
            type: "GET",
            data: {
                start_date: startTime,
                end_date: endTime
            },
            success: function(response) {
                createNumberOfStudentsChart(response);
            },
            error: function(xhr) {
                alert("Có lỗi xảy ra");
            },
        });
    }

    /**
     * Get chart data and export
     */
    function getChartData() {
        $.ajax({
            url: "{{ URL_GET_CHART_DATA }}",
            type: "GET",
            success: function(response) {
                createChart(response.data);
                $('#export_pdf').prop('disabled', false);

            },
            error: function(xhr) {
                alert("Có lỗi xảy ra");
                $('#export_pdf').prop('disabled', true);
            },
        });
    }

    /**
     * Create chart
     */
    function createChart(data) {
        if (data.numberOfStudentsTakingTheExam && data.courseStatus && data.examStatus) {
            createNumberOfStudentsTakingTheExamChart(data.numberOfStudentsTakingTheExam);
            createCourseStatusChart(data.courseStatus, "chart3");
            createCourseStatusChart(data.examStatus, "chart4");
        }
    }

    /**
     * Create number of students chart
     */
    function createNumberOfStudentsChart(numberOfStudents = []) {
        const chart1 = echarts.init(document.getElementById("chart1"));
        const data = numberOfStudents.data;

        chart1.setOption({
            backgroundColor: "#fff",
            title: {
                text: numberOfStudents.title + ` (${fromDate} ~ ${toDate})`,
                subtext: "Tổng: " + data.list_count_user.reduce(function(all, val) {
                    return all + val;
                }, 0),
                left: "center",
                textStyle: {
                    fontSize: 16,
                    color: "#333",
                    fontFamily: "Helvetica, Arial, sans-serif"
                },
                subtextStyle: {
                    fontSize: 14,
                    color: "#666",
                    fontFamily: "Helvetica, Arial, sans-serif"
                }
            },
            xAxis: {
                type: "category",
                data: data.list_date_count_user,
                axisLabel: {
                    rotate: 60,
                }
            },
            yAxis: {
                type: "value",
                axisLabel: {
                    formatter: function(value) {
                        return Math.round(value);
                    }
                },
                minInterval: 100,
            },
            series: [{
                data: data.list_count_user,
                type: "line",
                label: {
                    show: true,
                    position: "top",
                    formatter: "{c}"
                }
            }]
        });
    }

    /**
     * Create number of students taking the exam chart
     */
    function createNumberOfStudentsTakingTheExamChart(numberOfStudentsTakingTheExam) {
        const chart1 = echarts.init(document.getElementById("chart2"));
        const data = numberOfStudentsTakingTheExam.data;

        chart1.setOption({
            backgroundColor: "#fff",
            title: {
                text: numberOfStudentsTakingTheExam.title,
                subtext: "Tổng: " + data.reduce(function(all, obj) {
                    return all + obj["value"];
                }, 0),
                left: "center",
                textStyle: {
                    fontSize: 16,
                    color: "#333",
                    fontFamily: "Helvetica, Arial, sans-serif"
                },
                subtextStyle: {
                    fontSize: 14,
                    color: "#666",
                    fontFamily: "Helvetica, Arial, sans-serif"
                }
            },
            tooltip: {
                trigger: "item",
            },
            legend: {
                orient: "horizontal",
                top: "15%",
                middle: "middle",
                textStyle: {
                    fontSize: 12,
                    color: "#333",
                    fontFamily: "Helvetica, Arial, sans-serif"
                },
            },
            series: [{
                top: "10%",
                type: "pie",
                radius: "50%",
                data: data,
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: "rgba(0, 0, 0, 0.5)",
                    },
                },
                label: {
                    show: true,
                    position: "inside",
                    formatter: "{c}: ({d}%)",
                    fontSize: 11,
                    fontFamily: "Helvetica, Arial, sans-serif"
                },
            }, ],
        });
    }

    function createCourseStatusChart(courseStatus, chartName) {
        const chart = echarts.init(document.getElementById(chartName));
        const courseStatusData = courseStatus.data;
        var option;
        var sum = 0;
        const rawData = courseStatusData.data;
        const course = courseStatusData.course;
        const status = courseStatusData.status;
        var data = [];

        const totalData = [];
        for (var i = 0; i < rawData[0].length; i++) {
            for (var j = 0; j < rawData.length; j++) {
                sum += rawData[j][i];
            }
            totalData.push(sum);
            sum = 0;
        }

        function createBarConfig(name, data) {
            return {
                type: "bar",
                stack: "total",
                emphasis: {
                    focus: "series"
                },
                name,
                data: data.map(value => ({
                    value,
                    label: {
                        show: value !== 0
                    }
                }))
            };
        }

        for (var i = 0; i < status.length; i++) {
            data.push(createBarConfig(status[i], rawData[i]));
        }

        data.push({
            type: "bar",
            tooltip: {
                show: false
            },
            stack: "",
            color: "#FFFFFF",
            label: {
                normal: {
                    show: true,
                    position: "top"
                }
            },
            data: totalData,
            z: -1,
            barGap: "-100%"
        });

        const grid = {
            left: 100,
            right: 100,
            top: 75,
            bottom: 125
        };
        const series = data;
        option = {
            title: {
                text: courseStatus.title,
                left: "center",
                textStyle: {
                    fontSize: 16,
                    color: "#333",
                    fontFamily: "Helvetica, Arial, sans-serif"
                },
                subtextStyle: {
                    fontSize: 14,
                    color: "#666",
                    fontFamily: "Helvetica, Arial, sans-serif"
                }
            },
            backgroundColor: "#fff",
            legend: {
                selectedMode: false,
                top: "8%",
                textStyle: {
                    fontSize: 12,
                    color: "#333",
                    fontFamily: "Helvetica, Arial, sans-serif"
                }
            },
            grid,
            yAxis: {
                type: "value",
                name: "Số lượng học viên",
                nameLocation: "middle",
                nameGap: 30,
                nameTextStyle: {
                    fontSize: 14,
                    fontWeight: 'bold',
                    fontFamily: "Helvetica, Arial, sans-serif"
                }
            },
            xAxis: {
                type: "category",
                data: course,
                axisLabel: {
                    rotate: 60,
                    fontSize: 10,
                    fontFamily: "Helvetica, Arial, sans-serif"
                }
            },
            series
        };

        chart.on("legendselectchanged", function(params) {
            totalData.length = 0;
            for (let z = 0; z < data[0].data.length; z++) {
                for (let i = 0; i < data.length; i++) {
                    if (params.selected[data[i].name]) sum += data[i].data[z];
                }
                totalData.push(sum);
                sum = 0;
            }
            chart.setOption(option);
        });

        option && chart.setOption(option);
    }

    async function exportChartToPDF() {
        showLoadingSpinner();
        $("#export_modal").modal("hide");

        try {
            const doc = new jspdf.jsPDF({
                unit: "px",
                orientation: "l",
                format: [1200, 1100],
                hotfixes: ["px_scaling"],
            });

            const pageWidth = doc.internal.pageSize.getWidth();
            const pageHeight = doc.internal.pageSize.getHeight();
            const chartElement = document.querySelector(".chart-group");
            const chartWidth = chartElement.offsetWidth;
            const chartHeight = chartElement.offsetHeight;
            const xOffset = (pageWidth - chartWidth) / 2;
            const yOffset = (pageHeight - chartHeight) / 2;
            const canvas = await html2canvas(chartElement, {
                allowTaint: true,
                backgroundColor: "transparent"
            });

            const imgData = canvas.toDataURL("image/png");
            doc.addImage(imgData, "PNG", xOffset, yOffset, chartWidth, chartHeight);

            await doc.save("report.pdf");

        } catch (e) {
            console.error("failed to export", e);
        } finally {
            closeLoadingSpinner();
        }
    }
</script>
