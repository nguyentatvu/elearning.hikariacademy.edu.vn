<style>
    /* Reset margin and padding */
    .momo-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 15px;
    }

    .momo-title {
        font-size: 24px;
        color: #d81b60;
        text-align: center;
        margin-bottom: 20px;
    }

    .momo-step-title {
        font-size: 20px;
        color: #333;
        margin-top: 20px;
    }

    .momo-text {
        font-size: 16px;
        color: #555;
        line-height: 1.8;
    }

    .momo-note {
        font-size: 14px;
        color: #ff5722;
        margin-top: 10px;
        font-style: italic;
    }

    .momo-list {
        margin: 10px 0;
        padding-left: 20px;
    }

    .momo-list-item {
        margin-bottom: 5px;
    }

    .momo-success-message {
        background-color: #e8f5e9;
        padding: 15px;
        border: 1px solid #c8e6c9;
        border-radius: 8px;
        text-align: center;
        color: #388e3c;
        margin-top: 20px;
    }

    .momo-step li {
        list-style-type: circle;
    }

    .momo-img {
        width: 100%;
        height: auto;
        max-width: 100%;
        margin-top: 10px;
    }

    /* Media Queries for Responsiveness */
    @media (min-width: 768px) {
        .momo-img {
            width: 70%;
            margin: 10px auto;
            display: block;
        }
    }

    @media (max-width: 768px) {
        .momo-step-title {
            font-size: 18px;
        }

        .momo-text {
            font-size: 14px;
        }

        .momo-note {
            font-size: 12px;
        }

        .momo-list {
            padding-left: 15px;
        }

        .momo-img {
            width: 100%; /* 100% width for smaller screens */
        }
    }

    @media (max-width: 480px) {
        .momo-step-title {
            font-size: 16px;
        }

        .momo-text {
            font-size: 13px;
        }

        .momo-list {
            padding-left: 10px;
        }

        .momo-list-item {
            font-size: 12px;
        }

        .momo-img {
            width: 100%; /* 100% width for mobile screens */
        }
    }
</style>

<div class="momo-container">
    <p class="momo-text">Dưới đây là các bước chi tiết để thực hiện thanh toán khóa học trên Hikari E-learning bằng
        phương thức quét mã QR:</p>

    <div class="momo-step">
        <h2 class="momo-step-title">Bước 1: Chọn Phương Thức Thanh Toán Bằng Ví MoMo</h2>
        <p class="momo-text">Chọn mua khóa học và chuyển đến trang thanh toán trên website của Hikari E-learning. Sau đó,
            chọn phương thức thanh toán bằng Ví MoMo.</p>
        <p class="momo-text">Hệ thống sẽ hiển thị mã QR chứa thông tin thanh toán, bao gồm:</p>
        <ul class="momo-list">
            <li class="momo-list-item">Tên nhà cung cấp: Công ty TNHH Tư Vấn - Dịch Vụ Quang Việt</li>
            <li class="momo-list-item">Mã đơn hàng</li>
            <li class="momo-list-item">Mô tả</li>
            <li class="momo-list-item">Số tiền cần thanh toán</li>
        </ul>
        <img class="momo-img" src="{{ asset('images/upload/momo/payment-momo-qr.png') }}" alt="">
    </div>

    <div class="momo-step">
        <h2 class="momo-step-title">Bước 2: Mở Ứng Dụng Ví MoMo</h2>
        <p class="momo-text">Truy cập ứng dụng Ví MoMo trên điện thoại và đăng nhập vào tài khoản.</p>
        <p class="momo-text">Tại màn hình chính của ứng dụng, chọn mục "Quét Mã".</p>
        <img class="momo-img" src="{{ asset('images/upload/momo/payment-momo-qr.png') }}" alt="">
    </div>

    <div class="momo-step">
        <h2 class="momo-step-title">Bước 3: Quét Mã QR</h2>
        <p class="momo-text">Sử dụng camera của ứng dụng MoMo để quét mã QR hiển thị trên màn hình thanh toán của
            website.</p>
        <p class="momo-note">Lưu ý: Mã QR có hiệu lực trong vòng 10 phút.</p>
    </div>

    <div class="momo-step">
        <h2 class="momo-step-title">Bước 4: Kiểm Tra Thông Tin Giao Dịch</h2>
        <p class="momo-text">Trước khi xác nhận, kiểm tra các thông tin hiển thị trong ứng dụng MoMo, bao gồm:</p>
        <ul class="momo-list">
            <li class="momo-list-item">Tên nhà cung cấp</li>
            <li class="momo-list-item">Số tiền</li>
            <li class="momo-list-item">Mô tả sản phẩm (tên khóa học)</li>
        </ul>
    </div>

    <div class="momo-step">
        <h2 class="momo-step-title">Bước 5: Xác Thực Giao Dịch</h2>
        <p class="momo-text">Sau khi kiểm tra thông tin, nhấn nút "Xác Nhận" trong ứng dụng MoMo.</p>
        <p class="momo-text">Hoàn tất xác thực giao dịch bằng cách nhập mã PIN hoặc sử dụng các phương thức xác thực
            sinh trắc học (vân tay hoặc nhận diện khuôn mặt, nếu đã thiết lập).</p>
        <p class="momo-text">Hệ thống sẽ xử lý và hiển thị thông báo "Thanh Toán Thành Công".</p>
        <img class="momo-img" src="{{ asset('images/upload/momo/payment-momo-qr.png') }}" alt="">
    </div>

    <div class="momo-step">
        <h2 class="momo-step-title">Lưu Ý:</h2>
        <ul class="momo-list">
            <li class="momo-list-item">Hệ thống sẽ tự động quay lại website Hikari E-learning sau khi thanh toán thành
                công.</li>
            <li class="momo-list-item">Nếu gặp sự cố, vui lòng liên hệ bộ phận hỗ trợ của Hikari E-learning.</li>
        </ul>
    </div>
</div>
