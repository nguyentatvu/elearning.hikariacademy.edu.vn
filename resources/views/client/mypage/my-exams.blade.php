@extends('client.shared.mypage')

@section('styles')
    <link href="{{ asset('css/pages/mypage.css') }}" rel="stylesheet">
@endsection

@section('mypage-content')
    <div class="container mt-4">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th class="text-center" scope="col" style="width: 5%;">STT</th>
                    <th class="text-center" scope="col" style="width: 30%;">KHÓA HỌC</th>
                    <th class="text-center" scope="col" style="width: 20%;">LỘ TRÌNH</th>
                    <th class="text-center" scope="col" style="width: 25%;">TRÌNH ĐỘ</th>
                    <th class="text-center" scope="col" style="width: 20%;"></th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 1; $i <= 6; $i++)
                    <tr>
                        <th scope="row" class="text-center align-middle">{{ $i }}</th>
                        <td>
                            <div class="d-flex align-items-center">
                                <img alt="Course image placeholder" class="rounded me-3" height="60" width="60"
                                    src="{{ asset('images/asset/courses/Course-N1.svg') }}" />
                                <div class="">
                                    <div class="fw-bold">Khoá N5</div>
                                    <div class="text-muted">Ngày mua: 06-04-2029</div>
                                    <div class="text-muted">Ngày hết hạn: 06-10-2029</div>
                                    <button class="btn btn-outline-primary btn-sm mt-2">Tổng quan</button>
                                </div>
                            </div>
                        </td>
                        <td class="text-center align-middle">Lộ trình ABC</td>
                        <td class="text-center align-middle">
                            <div>Hoàn thành: 60/296 bài học</div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-secondary-custom"
                                    role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                                    style="width: 35%">35%</div>
                            </div>
                        </td>
                        <td class="text-center align-middle">
                            <button class="btn btn-primary">Học ngay</button>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
@endsection
