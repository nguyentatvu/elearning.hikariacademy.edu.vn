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
                <span class="bank-card-icon"></span>
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
                        <tr data-coin="10" data-price="10000" data-formatted-price="10 000VND">
                            <td>
                                <input type="radio" name="price">
                                <label>10 000VND</label>
                            </td>
                            <td>
                                <span class="coin">
                                    <img src="{{ asset('images/coin.jpg') }}" class="rounded-circle coin-size">
                                </span>
                                Coin x 10&#32;
                            </td>
                        </tr>
                        <tr data-coin="55" data-price="50000" data-formatted-price="50 000VND">
                            <td>
                                <input type="radio" name="price">
                                <label>50 000VND</label>
                            </td>
                            <td>
                                <span class="coin">
                                    <img src="{{ asset('images/coin.jpg') }}" class="rounded-circle coin-size">
                                </span>
                                Coin x 55&#32;
                                <small><span class="fw-bold">(+10%)</span></small>
                            </td>
                        </tr>
                        <tr data-coin="120" data-price="100000" data-formatted-price="100 000VND">
                            <td>
                                <input type="radio" name="price">
                                <label>100 000VND</label>
                            </td>
                            <td>
                                <span class="coin">
                                    <img src="{{ asset('images/coin.jpg') }}" class="rounded-circle coin-size">
                                </span>
                                Coin x 120&#32;
                                <small><span class="fw-bold">(+20%)</span></small>
                            </td>
                        </tr>
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
                    <img src="{{ asset('images/coin.jpg') }}" class="rounded-circle coin-size">
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
                <span>Nguyen Thi Anh Duong</span>
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
                                    &#32;đến tài khoản ngân hàng của trungtâm.
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
                <form action="#" method="get">
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

@section('scripts')
    <script src="{{ asset('js/client/mypage/recharge-point.js') }}"></script>
    <script>
        const TRANSFER_ORDER_URL = '#';
    </script>
@endsection