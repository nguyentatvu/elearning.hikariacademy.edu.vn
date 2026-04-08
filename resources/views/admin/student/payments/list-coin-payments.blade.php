@extends('admin.layouts.sitelayout')
@section('header_scripts')
    <link href="{{ admin_asset('css/file/application.css') }}" rel="stylesheet">
    <style>
        .main-container {
            background-color: white;
            width: 100%;
        }

        .payment-list {
            padding: 0 60px;
            display: flex;
            gap: 20px;
        }

        .payment-list .payment-method {
            width: 100px;
            height: 100px;
            border-radius: 6px;
            cursor: pointer;
        }

        .payment-list .payment-method.selected{
            border: 3px solid #166AC9;
        }

        .payment-list .payment-method img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .payment-transactions {
            display: flex;
            padding: 0 60px;
            width: 100%;
        }

        .coin-payment-selections {
            width: 100%;
        }

        .coin-payment-selections table {
            width: 100%;
            border-collapse: collapse;
        }

        .coin-payment-selections th {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .coin-payment-selections td {
            padding: 14px;
            border-bottom: 1px solid #ddd;
        }

        .coin-payment-selections tr {
            cursor: pointer;
        }

        .coin-payment-selections tr:hover {
            background-color: #fafafa;
        }

        .coin-payment-selections td label {
            margin-bottom: 0px;
        }

        .coin-payment-selections .coin,
        .payment-transaction-detail .coin {
            position: relative;
            top: -2px;
        }

        .coin-payment-selections input[type="radio"] {
            margin-right: 10px;
        }

        .payment-transaction-detail {
            width: 50%;
            padding: 0 60px;
        }

        .payment-transaction-detail .transaction-details {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .payment-transaction-detail h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .payment-transaction-detail .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .payment-transaction-detail .detail-row:last-child {
            border-bottom: none;
        }

        .payment-transaction-detail .transaction-instructions {
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .payment-transaction-detail .transaction-instructions ul li{
            color: black !important;
        }

        .payment-transaction-detail .warning {
            font-size: 14px;
            margin-top: 20px;
            font-weight: 700;
        }

        .payment-transaction-detail .submit-button {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            text-align: center;
        }

        .payment-transaction-detail .submit-button:hover {
            background-color: #0065d2;
        }

        .icon-park-outline--bank-card {
            display: inline-block;
            width: 100%;
            height: 100%;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 48 48'%3E%3Cg fill='none' stroke='%23000' stroke-linejoin='round' stroke-width='4'%3E%3Cpath d='M4 10a2 2 0 0 1 2-2h36a2 2 0 0 1 2 2v28a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z'/%3E%3Cpath stroke-linecap='square' d='M4 16h40'/%3E%3Cpath stroke-linecap='round' d='M27 32h9m8-22v16M4 10v16'/%3E%3C/g%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }

        .transaction-container {
            display: flex;
            padding: 60px;
        }

        .payment-list_coin-packages {
            width: 50%;
            display: inline;
            display: flex;
            flex-direction: column;
            gap: 40px;
        }
    </style>
@stop
@section('content')
    <div class="main-container">
        <div class="transaction-container">
            <div class="payment-list_coin-packages">
                <div class="payment-list">
                    <div class="payment-method selected" data-name="Momo" data-instruction="instruction_momo" data-submit="submit_momo">
                        <img src="{{ asset('/public/assets/images/brands/momo.png') }}" alt="momo logo">
                    </div>
                    <div class="payment-method" data-name="VNPAY" data-instruction="instruction_vnpay" data-submit="submit_vnpay">
                        <img src="{{ asset('/public/assets/images/brands/vnpay.png') }}" alt="momo logo">
                    </div>
                    <div class="payment-method" data-name="Chuyển khoản ngân hàng" data-instruction="instruction_bank_transfer" data-submit="submit_bank_transfer">
                        <span class="icon-park-outline--bank-card"></span>
                    </div>
                </div>
                <div class="payment-transactions">
                    <div class="coin-payment-selections">
                        <table>
                            <thead>
                                <tr>
                                    <th>Giá</th>
                                    <th>Điểm</th>
                                </tr>
                            </thead>
                            <tbody class="coin-table">
                                @foreach ($ative_coin_packages as $coin_package)
                                    <tr data-coin="{{ $coin_package->totalCoin }}" data-price="{{ $coin_package->price }}" data-formatted-price="{{ $coin_package->formattedPrice }}">
                                        <td>
                                            <input type="radio" name="price">
                                            <label>{{ $coin_package->formattedPrice }}</label>
                                        </td>
                                        <td>
                                            <span class="coin">
                                                <img width="20" alt="hi-coin" src="{{ asset('/public/assets/images/icons/hi-coin.png') }}">
                                            </span>
                                            Coin x {{ $coin_package->totalCoin }}&#32;
                                            @if ($coin_package->bonus_percentage > 0)
                                                <small><strong>(+{{ $coin_package->bonus_percentage }}%)</strong></small>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="payment-transaction-detail">
                <div class="transaction-details d-none">
                    <h1>Chi tiết giao dịch</h1>
                    <div class="detail-row">
                        <span>Sản phẩm được chọn</span>
                        <span>
                            <span class="coin"><img width="20" alt="hi-coin" src="{{ asset('/public/assets/images/icons/hi-coin.png') }}"></span>
                            Coin x <span class="coin-cost"></span>
                        </span>
                    </div>
                    <div class="detail-row">
                        <span>Giá</span>
                        <span class="transaction-amount"></span>
                    </div>
                    <div class="detail-row">
                        <span>Phương thức thanh toán</span>
                        <span class="selected-payment-method"></span>
                    </div>
                    <div class="detail-row">
                        <span>Tài khoản</span>
                        <span>Nguyen Thi Anh Vinh</span>
                    </div>
                    <div class="transaction-instructions">
                        <h4 class="font-weight-bold">Hướng dẫn thanh toán</h4>
                        <div id="instruction_momo" class="transaction-instruction d-none mt-4 ml-3">
                            <div class="col-12">
                                <ul class="list-unstyled widget-spec mb-0">
                                    <li class="">
                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 1: Mở Ví MoMo, chọn “Quét Mã”
                                    </li>
                                    <li class="">
                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 2: Quét mã QR. Di chuyển Camera để thấy và quét mã QR
                                    </li>
                                    <li class="">
                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 3: Kiểm tra & Bấm “Xác nhận”
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div id="instruction_vnpay" class="transaction-instruction d-none mt-4 ml-3">
                            <ul class="list-unstyled widget-spec mb-0">
                                <li>
                                    <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 1: Từ màn hình thanh toán VNPAY, chọn phương thức thanh toán. Các phương thức thanh toán hiện có:
                                    <br>* Thanh toán quét mã VNPAY
                                    <br>* Thẻ ATM và tài khỏan ngân hàng
                                    <br>* Thẻ thanh toán quốc tế
                                    <br>* Ví điện tử VNPAY
                                </li>
                                <li>
                                    <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 2: Nhập các thông tin yêu cầu, nhấn Tiếp tục.
                                </li>
                                <li>
                                    <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 3: Kiểm tra thông tin giao dịch
                                </li>
                                <li>
                                    <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 4: Nhấn Xác nhận.
                                </li>
                                <li>
                                    <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 5: Nhập mã OTP xác thực giao dịch.
                                </li>
                            </ul>
                        </div>
                        <div id="instruction_bank_transfer" class="transaction-instruction d-none mt-4 ml-3">
                            <div class="col-12">
                                <ul class="list-unstyled widget-spec mb-0">
                                    <li class="">
                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 1:
                                        Click vào Tạo đơn hàng
                                    </li>
                                    <li class="">
                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>
                                        <span>
                                            Bước 2: Chuyển khoản với nội dung <strong class="text-primary">"{{ Auth::user()->username }} NAP-COIN"</strong> đến tài khoản ngân hàng của trung tâm.
                                        </span>
                                    </li>
                                    <li class="">
                                        <i class="fa fa-caret-right text-success" aria-hidden="true"></i>Bước 3:
                                        Sau khi nhận được thanh toán, trung tâm sẽ kích hoạt khóa học ngay cho bạn.
                                    </li>
                                </ul>
                                <h6 class="font-weight-semibold mt-5">Thông tin ngân hàng</h6>
                                <ul class="list-group">
                                    <li class="listunorder">
                                        Ngân hàng: <strong>{{ config('constant.bank.name') }}</strong>
                                    </li>
                                    <li class="listunorder">Số tài khoản: <strong>{{ config('constant.bank.account_number') }}</strong></li>
                                    <li class="listunorder">
                                        Tên thụ hưởng: <strong>{{ config('constant.bank.account_holder') }}</strong>
                                    </li>
                                </ul>
                                <span class="mt-4 d-block"><strong>Ghi chú:</strong> Tùy thuộc vào ngân hàng, hình thức chuyển tiền hay thời điểm thanh toán (ngoài giờ làm việc hay bị trùng ngày
                                nghỉ/ngày lễ) mà việc xác nhận thanh toán có thể dao động từ 2 đến 72 tiếng.</span>
                            </div>
                        </div>
                    </div>
                    <p class="warning">Vui lòng kiểm tra kỹ thông tin trước khi tiến hành thanh toán. Giao dịch không thể hoàn lại sau khi hoàn tất.</p>
                    <div class="transaction-submit d-none" id="submit_momo">
                        <form action="#" method="get">
                            <input type="text" name="price" hidden>
                            <button type="submit" class="submit-button">Nạp điểm</button>
                        </form>
                    </div>
                    <div class="transaction-submit d-none" id="submit_vnpay">
                        <form action="{{ route('payments.coin.vnpay') }}" method="get">
                            <input type="text" name="price" hidden>
                            <button type="submit" class="submit-button">Nạp điểm</button>
                        </form>
                    </div>
                    <div class="transaction-submit d-none" id="submit_bank_transfer">
                        <button type="submit" class="submit-button">Tạo đơn hàng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('footer_scripts')
    <script>
        $(document).ready(function(){
            selectCoinPackage();
            selectPaymentMethod();
            askToCreateTransferOrder();
        });

        const selectCoinPackage = () => {
            $('.coin-payment-selections tr td').on('click', function () {
                const coinCheckBox = $(this).closest('tr').find('input');
                coinCheckBox.prop('checked', true);
                changeTransactionDetail();
                changeTransactionInstruction();
                changeTransactionSubmit();
            });
        }

        const selectPaymentMethod = () => {
            $('.payment-method').on('click', function () {
                $('.payment-method').removeClass('selected');
                $(this).addClass('selected');
                changeTransactionDetail();
                changeTransactionInstruction();
                changeTransactionSubmit();
            });
        }

        const changeTransactionDetail = () => {
            const checkedCoinInput = $('.coin-payment-selections input:checked');
            if (!checkedCoinInput.length) return;

            const coinPackage = checkedCoinInput.closest('tr');
            const selectedPaymentMethod = $('.payment-method.selected').data('name');
            const formattedTransactionAmount = coinPackage.data('formatted-price');
            const transactionAmount = coinPackage.data('price');
            const coinCost = coinPackage.data('coin');

            $('.transaction-details').removeClass('d-none');
            $('.selected-payment-method').text(selectedPaymentMethod);
            $('.coin-cost').text(coinCost);
            $('.transaction-amount').text(formattedTransactionAmount);
            $('input[name="price"]').val(transactionAmount);
        }

        const changeTransactionInstruction = () => {
            const selectedPaymentMethod = $('.payment-method.selected');
            const instructionId = selectedPaymentMethod.data('instruction');
            $('.transaction-instruction').addClass('d-none');
            $(`#${instructionId}`).removeClass('d-none');
        }

        const changeTransactionSubmit = () => {
            const selectedPaymentMethod = $('.payment-method.selected');
            const selectedSubmitBtn = $(`#${selectedPaymentMethod.data('submit')}`);

            $('.transaction-submit').addClass('d-none');
            selectedSubmitBtn.removeClass('d-none');
        }

        const askToCreateTransferOrder = () => {
            $('#submit_bank_transfer>button').on('click', function () {
                swal({
                    title: "Xác nhận tạo đơn hàng",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#8CD4F5',
                    confirmButtonText: "Đồng ý",
                    cancelButtonText: "Hủy bỏ",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }, function (isConfirm) {
                    if(!isConfirm) {
                        swal("Hủy bỏ", "Đơn hàng của bạn đã bị hủy bỏ", "error");
                    }
                    else {
                        submitCreateTransferOrder();
                    }
                });
            });
        }

        const submitCreateTransferOrder = () => {
            const url = '{{ route('payments.coin.transfer') }}';

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN':'{{ csrf_token() }}'
                },
                url: url,
                type: 'POST',
                data: {
                    price: $('input[name="price"]').val()
                },
                dataType: 'json',
                success: function (data) {
                    swal({
                        title: 'Thông báo',
                        text: data.messages,
                        type: 'success',
                        showConfirmButton: false,
                        showCancelButton: false,
                        timer: 2000,
                    });
                },
                error: function (data) {
                    if (data.responseJSON) {
                        data = data.responseJSON;
                    }

                    swal({
                        title: 'Thông báo',
                        text: data.messages,
                        type: 'error',
                        showConfirmButton: false,
                        showCancelButton: false,
                        timer: 2000,
                    });
                }
            });
        }
    </script>
@stop