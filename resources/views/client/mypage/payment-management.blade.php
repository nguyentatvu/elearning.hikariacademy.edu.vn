@extends('client.shared.mypage')

@section('styles')
    <link href="{{ asset('css/pages/mypage.css') }}" rel="stylesheet">
@endsection

@section('mypage-content')
    <div class="manged-ad table-responsive border-top userprof-tab">

        <table class="table table-bordered table-hover mb-0 text-nowrap">
            <thead>
                <tr>
                    <th class="text-center align-middle" style="width: 5%">STT</th>
                    <th>Quản lý thanh toán của bạn</th>
                    <th class="text-center align-middle">Giá</th>
                    <th class="text-center align-middle">Phương thức</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td class="text-center align-middle">1</td>
                    <td>
                        <div class="d-flex align-items-start">
                            <div class="card-aside-img">
                                <a href="#">
                                    <img style="height: auto;" src="{{ asset('images/asset/courses/Course-N1.svg') }}" alt="Khóa học N5">
                                </a>
                            </div>
                            <div class="ms-3">
                                <a href="#" class="text-dark">
                                    <h4 class="fw-semibold">Khóa học N5 (6 tháng)</h4>
                                </a>
                                <p>Ngày mua: 20-09-2024</p>
                                <p>Ngày hết hạn: 19-03-2025</p>
                            </div>
                        </div>
                    </td>
                    <td class="fw-semibold fs-16 align-middle">399.000đ</td>
                    <td class="text-center align-middle">
                        <a href="#" class="text-uppercase">transfer</a>
                    </td>
                    <td class="text-center align-middle">
                        <span class="text-danger">Đang xử lý</span><br>
                        <a href="javascript:void(0)" onclick="canpayment(4)" class="btn btn-sm btn-primary">Hủy đơn hàng</a>
                    </td>
                </tr>

                <tr>
                    <td class="text-center align-middle">2</td>
                    <td>
                        <div class="d-flex align-items-start">
                            <div class="card-aside-img">
                                <a href="#">
                                    <img style="height: auto;" src="{{ asset('images/asset/courses/Course-N1.svg') }}" alt="Khóa học N5">
                                </a>
                            </div>
                            <div class="ms-3">
                                <a href="#" class="text-dark">
                                    <h4 class="fw-semibold">Khóa học N5 (6 tháng)</h4>
                                </a>
                                <p>Ngày mua: 20-09-2024</p>
                                <p>Ngày hết hạn: 19-03-2025</p>
                            </div>
                        </div>
                    </td>
                    <td class="fw-semibold fs-16 align-middle">399.000đ</td>
                    <td class="text-center align-middle">
                        <a href="#" class="text-uppercase">transfer</a>
                    </td>
                    <td class="text-center align-middle">
                        <span class="text-success">Thành công</span>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
@endsection
