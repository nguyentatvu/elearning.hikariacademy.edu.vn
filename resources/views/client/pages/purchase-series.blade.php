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
                    <button class="nav-link active" id="pills-transfer-tab" data-bs-toggle="pill" data-bs-target="#pills-transfer"
                        type="button" role="tab" aria-controls="pills-transfer" aria-selected="false">
                        <i class="bi bi-bank"></i>
                        <span class="ms-1">Chuyển khoản ngân hàng</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-momo-tab" data-bs-toggle="pill" data-bs-target="#pills-momo" type="button"
                        role="tab" aria-controls="pills-momo" aria-selected="false">
                        <img src="{{ asset('images/icons/momo_square_pinkbg.svg') }}" alt="" style="width: 20px;">
                    </button>
                </li>
                @if (env('ENABLE_THIRDPARTY_PAYMENT', false))
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-vnpay-tab" data-bs-toggle="pill" data-bs-target="#pills-vnpay"
                            type="button" role="tab" aria-controls="pills-vnpay" aria-selected="true">
                            <img src="{{ asset('images/icons/vnpay_logo_horizontal.svg') }}" alt="" style="width: 60px;">
                        </button>
                    </li>
                @else
                    <li class="nav-item" role="presentation" onclick="showMaintainancePaymentAlert()">
                        <button class="nav-link disabled"
                            type="button" role="tab" aria-controls="pills-vnpay" aria-selected="true">
                            <img src="{{ asset('images/icons/vnpay_logo_horizontal.svg') }}" alt="" style="width: 60px; filter: grayscale(70%); opacity: 0.4;">
                        </button>
                    </li>
                @endif
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="">
                    <div class="d-flex justify-content-sm-between align-items-center flex-sm-row flex-column">
                        <span class="payment-title">Thông tin thanh toán:</span>
                        <div style="color: #f16a43">
                        </div>
                    </div>
                    <div class="fs-4 text-primary mt-3 text-center text-sm-start">
                        <i class="bi bi-cart-fill"></i>
                        <span>
                            <span>{{ $series_combo->title }}</span>
                            <span> (#{{ $series_combo->code }}) - </span>
                            @if ($is_redeemed)
                                <span class="fw-bold fs-3">
                                    {{ formatCurrencyVND($remaining_series_cost) }}
                                </span>
                                <span style="text-decoration: line-through" class="text-muted fs-5">
                                    {{ formatCurrencyVND($series_combo->actualCost) }}
                                </span>
                            @else
                                <span>{{ formatCurrencyVND($series_combo->actualCost) }}</span>
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
                @if (env('ENABLE_THIRDPARTY_PAYMENT', false))
                    <div class="tab-pane" id="pills-vnpay" role="tabpanel" aria-labelledby="pills-vnpay-tab">
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
                            <div>
                                Hướng dẫn thanh toán VNPAY

                                <a class="text-primary text-decoration-line" href="{{ route('site_pages', 'payment-instructions-vnpay') }}">
                                    Xem hướng dẫn chi tiết tại đây
                                </a>
                            </div>
                        </div>
                        <div class="series-submit" id="vnpay_submit">
                            <form action="/payments/vnpay/{{ $series_combo->slug }}" method="get">
                                <input type="hidden" name="is_redeemed" value="{{ $is_redeemed ? '1' : '0' }}">
                                <button class="btn btn-primary" {{ Request::query('is_redeemed') ? 'onclick=submitVnpayDiscount()' : '' }}>
                                    Click vào đây để thanh toán
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
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
                            <div>
                                Hướng dẫn thanh toán MoMo

                                <a class="text-primary text-decoration-line" href="{{ route('site_pages', 'payment-instructions-momo') }}">
                                    Xem hướng dẫn chi tiết tại đây
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="series-submit" id="momo_submit">
                        <form action="/payments/momoqr/{{ $series_combo->slug }}" method="get">
                            <input type="hidden" name="is_redeemed" value="{{ $is_redeemed ? '1' : '0' }}">
                            <button class="btn btn-primary" {{ Request::query('is_redeemed') ? 'onclick=submitMomoDiscount()' : '' }}>
                                Click vào đây để thanh toán
                            </button>
                        </form>
                    </div>
                </div>
                <div class="tab-pane show active" id="pills-transfer" role="tabpanel" aria-labelledby="pills-transfer-tab">
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
        const isRedeemed = {{ $is_redeemed ? 'true' : 'false' }};

        const showTransferPayment = async (isRedeemed = false) => {
            let isConfirmed = true;
            if (isRedeemed) {
                isConfirmed = await showDiscountTransferWarning('tạo đơn hàng');
            }

            if (isConfirmed) {
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

                        // Update display HICOIN
                        if (data.error == 1) {
                            const remainRewardPoint = {{ $total_reward_point - $required_redeem_point }};
                            $('.header-my-coin .owned-point').text(remainRewardPoint);
                        }
                    },
                    complete: function() {
                        toggleLoadingOverlay();
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire("Hủy bỏ", "Đơn hàng của bạn đã bị hủy bỏ", "error");
            }
        }

        const showDiscountTransferWarning = async (payText = 'thanh toán') => {
            const warningContent = `
                <div class="text-center">
                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            <div class="card border-primary border-2">
                                <div class="card-body">
                                    <h6 class="card-title fs-5">
                                        <i class="bi bi-lock me-2"></i>
                                        Khóa tạm thời
                                    </h6>
                                    <p class="card-text small mb-0">
                                        <strong class="fs-5">{{ $series_combo->redeem_point }} coin</strong>
                                        bạn chọn sẽ tạm thời bị khóa trong quá trình thanh toán
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="card border-success h-100 border-2">
                                <div class="card-body">
                                    <h6 class="card-title text-success fs-5">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Thanh toán thành công
                                    </h6>
                                    <p class="card-text small mb-0">
                                        Coin sẽ được trừ tự động
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="card border-danger h-100 border-2">
                                <div class="card-body">
                                    <h6 class="card-title text-danger fs-5">
                                        <i class="bi bi-x-circle me-2"></i>
                                        Thanh toán thất bại/hủy
                                    </h6>
                                    <p class="card-text small mb-0">
                                        Coin sẽ được hoàn lại ngay lập tức
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="small text-muted mb-0">
                        <i class="bi bi-shield-check me-1"></i>
                        Quy trình này đảm bảo tính công bằng và an toàn cho giao dịch của bạn
                    </p>
                </div>
            `;

            let confirmed;
            await Swal.fire({
                title: `Xác nhận trước khi ${payText}`,
                icon: "warning",
                html: warningContent,
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonColor: '#8CD4F5',
                confirmButtonText: "Xác nhận",
                cancelButtonText: "Hủy bỏ",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    confirmed = true;
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    confirmed = false;
                } else {
                    confirmed = false;
                }
            });

            return confirmed;
        }

        const submitVnpayDiscount = async () => {
            event.preventDefault();
            const vnpayForm = $(event.target).closest('form');

            let isConfirmed = true;
            if (isRedeemed) {
                isConfirmed = await showDiscountTransferWarning();
            }
            if (isConfirmed) {
                vnpayForm.submit();
            }
        }

        const submitMomoDiscount = async () => {
            event.preventDefault();
            const momoForm = $(event.target).closest('form');

            let isConfirmed = true;
            if (isRedeemed) {
                isConfirmed = await showDiscountTransferWarning();
            }
            if (isConfirmed) {
                momoForm.submit();
            }
        }

        const showMaintainancePaymentAlert = () => {
            Swal.fire({
                title: `Thông báo`,
                icon: "warning",
                html: 'Cổng thanh toán <strong>VNPAY</strong> đang được bảo trì. Sẽ quay lại trong khoảng thời gian sớm nhất!'
            });
        }
    </script>
@endsection