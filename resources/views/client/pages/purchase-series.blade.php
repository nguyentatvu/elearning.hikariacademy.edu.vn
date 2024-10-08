@extends('client.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/mypage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/purchase-series.css') }}">
@endsection

@section('content')
    <div class="series-payment">
        <div class="d-flex title-payment">
            <div class="default">Phương thức thanh toán</div>
            @if ($is_redeemed)
                <div class="is-redeemed">
                    <span>Với HICOIN</span>
                    <img width="20" alt="hi-coin" src="{{ asset('images/icons/coin.svg') }}">
                </div>
            @endif
        </div>
        <div class="content">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-vnpay-tab" data-bs-toggle="pill" data-bs-target="#pills-vnpay"
                        type="button" role="tab" aria-controls="pills-vnpay" aria-selected="true">
                        <i class="bi bi-currency-dollar"></i>
                        <span class="ms-1">Thanh toán qua VNPAY</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-momo-tab" data-bs-toggle="pill" data-bs-target="#pills-momo" type="button"
                        role="tab" aria-controls="pills-momo" aria-selected="false">
                        <i class="bi bi-cash"></i>
                        <span class="ms-1">Ví MoMo (Mã QR)</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-transfer-tab" data-bs-toggle="pill" data-bs-target="#pills-transfer"
                        type="button" role="tab" aria-controls="pills-transfer" aria-selected="false">
                        <i class="bi bi-bank"></i>
                        <span class="ms-1">Chuyển khoản ngân hàng</span>
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="">
                    <span class="payment-title">Thông tin thanh toán:</span>
                    <div class="fs-4 text-primary">
                        <i class="bi bi-cart-fill"></i>
                        <span>
                            <span>{{ $series_combo->title }}</span>
                            <span> (#{{ $series_combo->code }}) - </span>
                            @if ($is_redeemed)
                                <span class="fw-bold fs-3">
                                    {{ formatCurrencyVND($remaining_series_cost) }}
                                </span>
                                <span style="text-decoration: line-through" class="text-muted fs-5">
                                    {{ formatCurrencyVND($series_combo->cost) }}
                                </span>
                            @else
                                <span>{{ formatCurrencyVND($series_combo->cost) }}</span>
                            @endif
                        </span>
                    </div>
                    @if ($is_redeemed)
                        <div class="redeem-point mt-1 align-items-baseline">
                            <span class="fs-5" style="color: #4b5d73;">Giá: </span>
                            <div class="fw-bold fs-3 text-primary">{{ $series_combo->redeem_point }}</div>
                            <img width="20" alt="hi-coin" class="position-relative" style="top:" src="{{ asset('images/icons/coin.svg') }}">
                        </div>
                    @endif
                </div>
                <div class="tab-pane show active" id="pills-vnpay" role="tabpanel" aria-labelledby="pills-vnpay-tab">
                    <div id="instruction_vnpay" class="transaction-instruction mt-3 ml-3">
                        <ul class="list-unstyled mb-0 recharge-coin__instruction-list">
                            <li>
                                <i class="bi bi-caret-right-fill me-1 text-primary" aria-hidden="true"></i>Bước 1: Từ màn
                                hình thanh toán VNPAY, chọn phương thức thanh toán. Các phương thức thanh toán hiện có:
                                <br>* Thanh toán quét mã VNPAY
                                <br>* Thẻ ATM và tài khoản ngân hàng
                                <br>* Thẻ thanh toán quốc tế
                                <br>* Ví điện tử VNPAY
                            </li>
                            <li>
                                <i class="bi bi-caret-right-fill me-1 text-primary" aria-hidden="true"></i>Bước 2: Nhập các
                                thông tin yêu cầu, nhấn Tiếp tục.
                            </li>
                            <li>
                                <i class="bi bi-caret-right-fill me-1 text-primary" aria-hidden="true"></i>Bước 3: Kiểm tra
                                thông tin giao dịch
                            </li>
                            <li>
                                <i class="bi bi-caret-right-fill me-1 text-primary" aria-hidden="true"></i>Bước 4: Nhấn Xác
                                nhận.
                            </li>
                            <li>
                                <i class="bi bi-caret-right-fill me-1 text-primary" aria-hidden="true"></i>Bước 5: Nhập mã
                                OTP xác thực giao dịch.
                            </li>
                        </ul>
                    </div>
                    <div class="series-submit" id="vnpay_submit">
                        <form action="/payments/vnpay/{{ $series_combo->slug }}" method="get">
                            <input type="hidden" name="is_redeemed" value="{{ $is_redeemed ? '1' : '0' }}">
                            <button class="btn btn-primary">
                                Click vào đây để thanh toán
                            </button>
                        </form>
                    </div>
                </div>
                <div class="tab-pane" id="pills-momo" role="tabpanel" aria-labelledby="pills-momo-tab">
                    <div id="instruction_momo" class="transaction-instruction mt-4 ml-3">
                        <div class="col-12">
                            <ul class="list-unstyled mb-0 recharge-coin__instruction-list">
                                <li class="">
                                    <i class="bi bi-caret-right-fill me-1 text-primary"></i>Bước 1: Mở Ví MoMo, chọn “Quét
                                    Mã”
                                </li>
                                <li class="">
                                    <i class="bi bi-caret-right-fill me-1 text-primary"></i>Bước 2: Quét mã QR. Di chuyển
                                    Camera để thấy và quét mã QR
                                </li>
                                <li class="">
                                    <i class="bi bi-caret-right-fill me-1 text-primary"></i>Bước 3: Kiểm tra & Bấm “Xác
                                    nhận”
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="series-submit" id="momo_submit">
                        <form action="#" method="get">
                            <button class="btn btn-primary">
                                Click vào đây để thanh toán
                            </button>
                        </form>
                    </div>
                </div>
                <div class="tab-pane" id="pills-transfer" role="tabpanel" aria-labelledby="pills-transfer-tab">
                    <div id="instruction_bank_transfer" class="transaction-instruction mt-3 ml-3">
                        <div class="col-12">
                            <ul class="list-unstyled mb-0 recharge-coin__instruction-list">
                                <li class="">
                                    <i class="bi bi-caret-right-fill me-1 text-primary" aria-hidden="true"></i>Bước 1: Click
                                    vào Tạo đơn hàng
                                </li>
                                <li class="">
                                    <i class="bi bi-caret-right-fill me-1 text-primary" aria-hidden="true"></i>
                                    <span>
                                        Bước 2: Chuyển khoản với nội dung&#32;
                                        <strong class="text-primary">"HID-349343 NAP-COIN"</strong>
                                        &#32;đến tài khoản ngân hàng của trung tâm.
                                    </span>
                                </li>
                                <li class="">
                                    <i class="bi bi-caret-right-fill me-1 text-primary" aria-hidden="true"></i>Bước 3:
                                    Sau khi nhận được thanh toán, trung tâm sẽ kích hoạt khóa học ngay cho bạn.
                                </li>
                            </ul>
                            <h6 class="font-weight-semibold mt-4">Thông tin ngân hàng</h6>
                            <ul class="list-group">
                                <li class="listunorder">
                                    Ngân hàng: <strong>Vietcombank - Chi nhánh Tân Bình</strong>
                                </li>
                                <li class="listunorder">Số tài khoản: <strong>0441000688321</strong></li>
                                <li class="listunorder">
                                    Tên thụ hưởng: <strong>TRUNG TAM NHAT NGU QUANG VIET</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="series-submit" id="transfer_submit">
                        <button class="btn btn-primary" onclick="showTransferPayment({{ $is_redeemed ? 'true' : 'false' }})">
                            Tạo đơn hàng
                        </button>
                        <span class="mt-4 d-block"><strong>Ghi chú:</strong> Tùy thuộc vào ngân hàng, hình thức chuyển
                            tiền hay thời điểm thanh toán (ngoài giờ làm việc hay bị trùng ngày
                            nghỉ / ngày lễ) mà việc xác nhận thanh toán có thể dao động từ 2 đến 72 tiếng.
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function showTransferPayment(isRedeemed = false) {
            Swal.fire({
                title: "Xác nhận tạo đơn hàng",
                text: "",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#8CD4F5',
                confirmButtonText: "Đồng ý",
                cancelButtonText: "Hủy bỏ",
                reverseButtons: true,
                allowOutsideClick: false
            })
            .then((result) => {
                if (result.isConfirmed) {
                    let route = '{{url('payments/transfer')}}';
                    let token = '{{csrf_token()}}';
                    let slug  = '{{$series_combo->slug}}';

                    $.ajax({
                        url: route,
                        type: 'post',
                        dataType: "json",
                        data: {
                            _method: 'post',
                            _token: token,
                            slug: slug,
                            isRedeemed: isRedeemed
                        },
                        beforeSend: function() {
                            toggleLoadingOverlay();
                        },
                        success: function(data) {
                            Swal.fire({
                                title: 'Thông báo',
                                text: data.message,
                                icon: data.error == 1 ? 'success' : 'error',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        },
                        complete: function() {
                            // Update display HICOIN
                            const remainRewardPoint = {{ $total_reward_point - $required_redeem_point }};
                            $('.header-my-coin .owned-point').text(remainRewardPoint);
                            toggleLoadingOverlay();
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire("Hủy bỏ", "Đơn hàng của bạn đã bị hủy bỏ", "error");
                }
            });
        }
    </script>
@endsection