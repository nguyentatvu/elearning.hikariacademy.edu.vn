<style>
    .description {
        text-align: left;
        color: #666;
        margin-bottom: 40px;
        line-height: 1.6;
    }

    .section-title {
        font-size: 20px;
        color: #444;
        margin: 30px 0 20px;
        text-align: center;
        font-weight: bold;
    }

    .partner-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .partner-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .partner-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .partner-logo {
        max-width: 120px;
        height: auto;
    }

    .payment-methods {
        background: #f8f8f8;
        padding: 30px;
        border-radius: 12px;
        margin-top: 40px;
    }

    .payment-option {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px;
        border: 1px solid #e0e0e0;
        margin-bottom: 15px;
        background: white;
        border-radius: 8px;
    }

    .payment-option-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .payment-icon {
        width: 40px;
        height: 40px;
    }

    .steps {
        margin-top: 30px;
    }

    .step {
        margin-bottom: 20px;
        padding: 15px;
        background: white;
        border-radius: 8px;
        border-left: 4px solid #1a73e8;
    }

    .step-title {
        font-weight: bold;
        color: #1a73e8;
        margin-bottom: 10px;
    }

    .note {
        font-style: italic;
        color: #666;
        padding: 10px;
        background: #fff3e0;
        border-radius: 4px;
        margin: 10px 0;
    }

    .qr-steps {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        margin: 30px 0;
    }

    .qr-step {
        flex: 1;
        text-align: center;
    }

    .qr-step-image {
        border: 1px solid #e0e0e0;
        border-radius: 20px;
        padding: 10px;
        margin-bottom: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .qr-step-number {
        background: #1a73e8;
        color: white;
        padding: 5px 15px;
        border-radius: 4px;
        margin-bottom: 10px;
        display: inline-block;
    }

    .qr-step-desc {
        font-size: 14px;
        color: #555;
        line-height: 1.4;
    }

    .method-section {
        margin: 40px 0;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .method-title {
        font-size: 18px;
        color: #1a73e8;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .support-channels {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-top: 40px;
    }

    .support-title {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 15px;
        color: #333;
    }

    .support-item {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
        color: #555;
        word-wrap: break-word;
    }

    .support-item-title {
        white-space: nowrap;

    }

    .support-item a {
        color: #1a73e8;
        text-decoration: none;
        overflow-wrap: break-word;
        word-break: break-all
    }

    .support-item a:hover {
        text-decoration: underline;
    }

    .interface-preview {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        margin: 20px 0;
    }

    .payment-instructions-img {
        width: 100%;
    }

    .w-50 {
        width: 50%;
    }

    .step  h4 {
        font-weight: bold !important;
    }
</style>

<div class="container">
    <h2 class="text-center">HƯỚNG DẪN THANH TOÁN VNPAY TRÊN WEBSITE</h2>

    <div class="description">
        Cổng thanh toán VNPAY là giải pháp thanh toán do Công ty Cổ phần Giải pháp Thanh toán Việt Nam (VNPAY) phát
        triển. Khách hàng sử dụng thẻ/tài khoản ngân hàng, tính năng QR Pay/VNPAY-QR được tích hợp sẵn trên ứng dụng
        Mobile Banking của các ngân hàng hoặc Ví điện tử liên kết để thanh toán các giao dịch và nhập mã giảm giá
        (nếu có)
    </div>

    <div class="section-title">
        Quét mã VNPAY-QR trên 35+ Ứng dụng Mobile Banking và 15+ Ví điện tử liên kết
    </div>

    <img class="payment-instructions-img" src="{{ asset('images/upload/payment1.png') }}" alt="" srcset="">

    <div class="section-title">
        40+ Thẻ ATM/nội địa/tài khoản ngân hàng
    </div>

    <img class="payment-instructions-img" src="{{ asset('images/upload/payment2.png') }}" alt="" srcset="">

    <div class="section-title">
        4 Thẻ thanh toán quốc tế
    </div>

    <img class="payment-instructions-img" src="{{ asset('images/upload/payment3.png') }}" alt="" srcset="">

    <div class="payment-methods">
        <h2 class="text-center">Các phương thức thanh toán qua VNPAY</h2>
        <div class="d-flex justify-content-center">
            <img class="payment-instructions-img w-50" src="{{ asset('images/upload/payment4.png') }}" alt="" srcset="">
        </div>

        <div class="steps">
            <h4>1. Phương thức thanh toán qua "Ứng dụng thanh toán hỗ trợ VNPAY-QR"</h4>

            <div class="step">
                <div class="step-title">Bước 1:</div>
                <div>Quý khách lựa chọn sản phẩm, dịch vụ và chọn Thanh toán ngay hoặc Đặt hàng. Tại trang thanh
                    toán, vui lòng kiểm tra lại sản phẩm đã đặt, điền đầy đủ thông tin người nhận hàng, chọn phương
                    thức thanh toán VNPAY và nhấn nút "Đặt hàng ngay".</div>
            </div>

            <div class="step">
                <div class="step-title">Bước 2:</div>
                <div>Màn hình thanh toán chuyển sang giao diện cổng thanh toán VNPAY. Chọn phương thức "Ứng dụng
                    thanh toán hỗ trợ VNPAY-QR"</div>
            </div>

            <div class="step">
                <div class="step-title">Bước 3:</div>
                <div>Hệ thống hiển thị mã QR cùng với số tiền cần thanh toán, Quý khách kiểm tra lại số tiền này. Sử
                    dụng ứng dụng ngân hàng (theo danh sách liệt kê), chọn "Quét Mã" và tiến hành quét mã QR trên
                    màn hình thanh toán website</div>
                <div class="note">*Lưu ý: Mã QR có hiệu lực trong 15 phút</div>
                <div class="note">Để quá trình thanh toán thành công, khách hàng vui lòng tham khảo trước các điều
                    kiện và thao tác quét mã trên điện thoại để sẵn sàng, tránh sự cố hết thời gian ảnh hưởng đến
                    thanh toán và mã khuyến mại của quý khách.</div>
            </div>

            <div class="step">
                <div class="step-title">Bước 4:</div>
                <div>Kiểm tra thông tin, nhập mã giảm giá (nếu có) và hoàn tất thanh toán. Khi thực hiện thanh toán
                    hoàn tất Quý khách sẽ nhận được thông báo xác nhận đơn hàng đặt hàng thành công tại website
                </div>
            </div>
            <img class="payment-instructions-img" src="{{ asset('images/upload/payment5.png') }}" alt="" srcset="">
        </div>
        <div class="steps">
            <h4>2. Phương thức thanh toán qua “Thẻ nội địa và tài khoản ngân hàng”</h4>

            <div class="step">
                <div class="step-title">Bước 1:</div>
                <div>Quý khách lựa chọn sản phẩm, dịch vụ và chọn Thanh toán ngay hoặc Đặt hàng
                    Tại trang thanh toán, vui lòng kiểm tra lại sản phẩm đã đặt, điền đầy đủ thông tin người nhận hàng, chọn phương thức thanh toán VNPAY và nhấn nút “Đặt hàng ngay”.</div>
            </div>

            <div class="step">
                <div class="step-title">Bước 2:</div>
                <div>Màn hình thanh toán chuyển sang giao diện cổng thanh toán VNPAY. Chọn phương thức  “Thẻ nội địa và tài khoản ngân hàng” và chọn ngân hàng muốn thanh toán thẻ trong danh sách</div>
            </div>

            <div class="step">
                <div class="step-title">Bước 3:</div>
                <div>Quý khách vui lòng thực hiện nhập các thông tin thẻ/tài khoản theo yêu cầu và chọn “Tiếp tục”. Mã OTP sẽ được gửi về điện thoại đăng ký, nhập mã OTP để hoàn tất giao dịch </div>
                <div class="note">*Lưu ý: Mã QR có hiệu lực trong 15 phút</div>
            </div>

            <div class="step">
                <div class="step-title">Bước 4:</div>
                <div>Khi thực hiện thanh toán hoàn tất Quý khách sẽ nhận được thông báo xác nhận đơn hàng đặt hàng thành công tại website</div>
            </div>
            <img class="payment-instructions-img" src="{{ asset('images/upload/payment6.png') }}" alt="" srcset="">
        </div>
        <div class="steps">
            <h4>3. Phương thức thanh toán qua “Thẻ thanh toán quốc tế (Visa, MasterCard, JCB, UnionPay)”</h4>
            <div>
                Tương tự như phương thức thanh toán “Thẻ nội địa và tài khoản ngân hàng”
            </div>
        </div>
        <div class="steps">
            <h4>4. Phương thức thanh toán qua “Ví điện tử VNPAY”</h4>
            <div>
                Tương tự như phương thức thanh toán “Ứng dụng thanh toán hỗ trợ VNPAY-QR
            </div>
        </div>


        <div class="support-channels">
            <div class="support-title">KÊNH HỖ TRỢ VNPAY</div>
            <div class="support-item">
                <span class="support-item-title">- Tổng đài: </span>
                <a href="tel:+1900555577">*3388 hoặc 1900 55 55 77</a>
            </div>
            <div class="support-item">
                <span class="support-item-title">- Zalo OA: </span>
                <a href="https://zalo.me/4134983655549474109"
                    target="_blank">zalo.me/4134983655549474109</a>
            </div>
            <div class="support-item">
                <span class="support-item-title">- Email: </span>
                <a href="mailto:hotro@vnpay.vn">hotro@vnpay.vn</a>
            </div>
            <div class="support-item">
                <span class="support-item-title">- Fanpage: </span>
                <a href="https://facebook.com/VNPAYQR.vn" target="_blank">facebook.com/VNPAYQR.vn</a>
            </div>
        </div>
    </div>
</div>