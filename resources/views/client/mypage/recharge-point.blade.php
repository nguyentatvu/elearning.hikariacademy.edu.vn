@extends('client.shared.mypage')

@section('mypage-content')
<div class="recharge-coin">
    <div class="recharge-coin__list">
        <div class="payment-list">
            <div class="payment-method selected" data-name="Momo" data-instruction="instruction_momo"
                data-submit="submit_momo">
                <img src="{{ asset('images/mypage/momo.png') }}" alt="momo logo">
            </div>
            <div class="payment-method" data-name="VNPAY" data-instruction="instruction_vnpay"
                data-submit="submit_vnpay">
                <img src="{{ asset('images/mypage/vnpay.png') }}" alt="vnpay logo">
            </div>
            <div class="payment-method" data-name="Chuyển khoản ngân hàng" data-instruction="instruction_bank_transfer"
            data-submit="submit_bank_transfer">
                <img src="{{ asset('images/icons/bank.svg') }}" alt="bank logo">
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
                        @foreach ($active_coin_packages as $coin_package)
                            <tr data-coin="{{ $coin_package->totalCoin }}" data-price="{{ $coin_package->price }}" data-formatted-price="{{ $coin_package->formattedPrice }}">
                                <td>
                                    <input type="radio" name="price">
                                    <label>{{ $coin_package->formattedPrice }}</label>
                                </td>
                                <td>
                                    <span class="coin">
                                        <img width="20" alt="hi-coin" src="{{ asset('images/icons/coin.svg') }}">
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
                    <img src="{{ asset('images/icons/coin.svg') }}" class="rounded-circle coin-size">
                    Coin x <span class="coin-cost">10 000VND</span>
                </span>
            </div>
            <div class="detail-row">
                <span>Giá</span>
                <span class="transaction-amount">10 000VND</span>
            </div>
            <div class="detail-row">
                <span>Phương thức thanh toán</span>
                <span class="selected-payment-method">MOMO</span>
            </div>
            <div class="detail-row">
                <span>Tài khoản</span>
                <span>{{ Auth::user()->name }}</span>
            </div>
            <div class="transaction-instructions">
                <h4 class="font-weight-bold">Hướng dẫn thanh toán</h4>
                <div id="instruction_momo" class="transaction-instruction mt-4 ml-3 d-none">
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
                <div id="instruction_vnpay" class="transaction-instruction mt-3 ml-3 d-none">
                    <ul class="list-unstyled mb-0 recharge-coin__instruction-list">
                        <li>
                            <i class="bi bi-caret-right-fill me-1 text-primary" aria-hidden="true"></i>Bước 1: Từ màn
                            hình thanh toán VNPAY, chọn phương thức thanh toán. Các phương thức thanh toán hiện có:
                            <br>* Thanh toán quét mã VNPAY
                            <br>* Thẻ ATM và tài khỏan ngân hàng
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
                <div id="instruction_bank_transfer" class="transaction-instruction mt-3 ml-3 d-none">
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
                        <span class="mt-4 d-block"><strong>Ghi chú:</strong> Tùy thuộc vào ngân hàng, hình thức chuyển
                            tiền hay thời điểm thanh toán (ngoài giờ làm việc hay bị trùng ngày
                            nghỉ / ngày lễ) mà việc xác nhận thanh toán có thể dao động từ 2 đến 72 tiếng.</span>
                    </div>
                </div>
            </div>
            <p class="warning">Vui lòng kiểm tra kỹ thông tin trước khi tiến hành thanh toán. Giao dịch không thể hoàn
                lại sau khi hoàn tất.</p>
            <div class="transaction-submit" id="submit_momo">
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
@endsection

@section('mypage-scripts')
    <script src="{{ asset('js/client/mypage/recharge-point.js') }}"></script>
    <script>
        const TRANSFER_ORDER_URL = '{{ route('payments.coin.transfer') }}';
    </script>
@endsection