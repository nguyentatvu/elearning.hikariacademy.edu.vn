@extends('admin.layouts.owner.ownerlayout')

@section('header_scripts')
<link href="{{ admin_asset('css/sweetalert2.css') }}" rel="stylesheet">
<style>
    .panel-title {
        font-weight: bold;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .table th {
        background-color: #f5f5f5;
    }

    .vertical-middle {
        vertical-align: middle !important;
    }

    .submit-button {
        margin-bottom: 20px !important;
        margin-left: 18px !important;
    }

    .daily-login-points td{
        vertical-align: middle !important;
    }

    .remove-streak-btn {
        padding: 8px 22px !important;
    }

</style>
@endsection

@section('content')
<div id="page-wrapper">
    <div class="container">
        <h1 class="page-header">Quản lý Điểm Thưởng</h1>
        <form action="#" id="point_rules_form">
            <!-- Register point -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Điểm Đăng Ký Tài Khoản</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="registration_points">Điểm thưởng khi đăng ký mới:</label>
                        <input type="number" class="form-control" id="registration_points" value="{{ $rules['registration']['points'] }}">
                    </div>
                </div>
            </div>

            <!-- Streak login point -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Điểm Đăng Nhập Hàng Ngày</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Mốc ngày</th>
                                <th>Điểm thưởng</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="daily_login_points" class="daily-login-points">
                            @foreach ($rules['daily_login']['milestones'] as $streak)
                                <tr>
                                    <td><input type="number" class="daily-days form-control" value="{{ $streak['days'] }}"></td>
                                    <td><input type="number" class="daily-points form-control" value="{{ $streak['points'] }}"></td>
                                    <td>
                                        <div class="text-center">
                                            <button class="remove-streak-btn btn btn-danger btn-sm" onclick="removeLoginStreakItem()">
                                                Xóa
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button class="btn btn-primary" onclick="addDailyLoginRow()">Thêm mốc ngày</button>
                </div>
            </div>

            <!-- Learning point -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Điểm Học Tập</h3>
                </div>
                <div class="panel-body">
                    <h4>Video</h4>
                    <div class="form-group">
                        <label for="video_points">Điểm thưởng khi hoàn thành video:</label>
                        <input type="number" class="form-control" id="video_points" value="{{ $rules['learning']['video']['completion_points'] }}">
                    </div>

                    <h4>Bài tập</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Mức độ hoàn thành</th>
                                <th>Điểm thưởng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rules['learning']['exercise']['thresholds'] as $threshold)
                                <tr>
                                    <td class="vertical-middle">
                                        {{ $threshold['percentage'] == 100 ? '100%' : "Trên {$threshold['percentage']}%" }}
                                    </td>
                                    <td>
                                        <input type="number" class="exercise-points form-control" value="{{ $threshold['points'] }}"
                                            data-percentage="{{ $threshold['percentage'] }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h4>Bài kiểm tra</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Mức độ hoàn thành</th>
                                <th>Điểm thưởng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rules['learning']['test']['thresholds'] as $threshold)
                                <tr>
                                    <td class="vertical-middle">
                                        {{ $threshold['percentage'] == 100 ? '100%' : "Trên {$threshold['percentage']}%" }}
                                    </td>
                                    <td>
                                        <input type="number" class="test-points form-control" value="{{ $threshold['points'] }}"
                                            data-percentage="{{ $threshold['percentage'] }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button class="btn btn-success btn-lg submit-button">Lưu Thay Đổi</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('footer_scripts')
<script>
    const addDailyLoginRow = () => {
        event.preventDefault();
        const row = `
            <tr>
                <td><input type="number" class="daily-days form-control" value="0"></td>
                <td><input type="number" class="daily-points form-control" value="0"></td>
                <td class="streak-item-action">
                    <div class="text-center">
                        <button class="remove-streak-btn btn btn-danger btn-sm" onclick="removeLoginStreakItem()">Xóa</button>
                    </div>
                </td>
            </tr>
        `;
        $('#daily_login_points').append(row);
    }

    const removeLoginStreakItem = () => {
        $(event.target).closest('tr').remove();
    }

    const checkPositiveArray = (array) => {
        if (array.length == 0) {
            return false;
        }
        return array.every((item) => item >= 0);
    }

    const collectFormData = () => {
        const rules = {
            registration: {
                points: parseInt($('#registration_points').val()) || 0
            },
            daily_login: {
                milestones: []
            },
            learning: {
                video: {
                    completion_points: parseInt($('#video_points').val()) || 0
                },
                exercise: {
                    thresholds: []
                },
                test: {
                    thresholds: []
                }
            }
        };

        // Collect daily login points
        let milestones = [];
        $('#daily_login_points tr').each(function() {
            const days = parseInt($(this).find('.daily-days').val()) || 0;
            const points = parseInt($(this).find('.daily-points').val()) || 0;
            if (days > 0 && !milestones.some(item => item.days == days)) {
                milestones.push({
                    days: Math.abs(days),
                    points: Math.abs(points)
                });
            }
        });
        rules.daily_login.milestones = milestones;

        // Sort milestones by days
        rules.daily_login.milestones.sort((a, b) => a.days - b.days);

        // Collect exercise points
        $('.exercise-points').each(function() {
            const percentage = parseInt($(this).data('percentage')) || 0;
            const points = parseInt($(this).val()) || 0;
            rules.learning.exercise.thresholds.push({
                percentage: Math.abs(percentage),
                points: Math.abs(points)
            });
        });

        // Collect test points
        $('.test-points').each(function() {
            const percentage = parseInt($(this).data('percentage')) || 0;
            const points = parseInt($(this).val()) || 0;
            rules.learning.test.thresholds.push({
                percentage: Math.abs(percentage),
                points: Math.abs(points)
            });
        });

        return rules;
    }

    $('#point_rules_form').on('submit', function(e) {
        e.preventDefault();

        const rules = collectFormData();
        if (!rules) {
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            },
            url: '{{ route('point-management.save-rules') }}',
            type: 'POST',
            data: {
                rules: JSON.stringify(rules)
            },
            success: function(response) {
                showSucessAlert(
                    'Lưu danh sách điểm thưởng thành công!',
                    function () {
                        location.reload();
                    }
                );
            },
            error: function(xhr, status, error) {
                showErrorAlert('Có lỗi xảy ra!', null, 1000);
            }
        });
    });
</script>
<script src="{{ admin_asset('js/sweetalert2.js') }}"></script>
@endsection