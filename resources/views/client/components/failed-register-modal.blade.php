<div class="modal-content">
    <div class="login-content auth-content animate__animated d-none" id="login_content">
        <form class="login-form needs-validation" novalidate id="login_form">
            <h1>Cùng<span> HIKARI </span> khám phá bài học mới mỗi ngày!</h1>
            <p>“Chinh phục tiếng Nhật dễ dàng cùng <span> HIKARI </span> Hệ sinh thái Nhật ngữ hàng đầu tại Việt Nam!”
            </p>
            <div>
                <div class="mb-3">
                    <label class="form-label text-primary" for="email">
                        Email hoặc tên đăng nhập
                    </label>
                    <input class="form-control" id="email" placeholder="example@email.com" required />
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label text-primary" for="password"> Mật khẩu </label>
                    <input class="form-control" id="password" placeholder="Mật khẩu" type="password" required />
                    <div class="invalid-feedback"></div>
                </div>
                <div class="forgot-password">
                    <a href="#"> Quên mật khẩu </a>
                </div>
                <button class="btn btn-primary mt-2 submit-button" type="submit">Đăng nhập</button>
                <div class="register">
                    Bạn chưa có tài khoản?
                    <a href="javascript:void(0);" class="text-secondary" data-tab="#register_content">Đăng ký ngay</a>
                </div>
            </div>
        </form>
        <div class="side-photo">
            <img src="{{ asset('images/no-image.png') }}" alt="no image">
        </div>
    </div>
    <div class="register-content auth-content animate__animated" id="register_content">
        <form class="register-form needs-validation" id="register_form" novalidate>
            <h1>Cùng<span> HIKARI </span> khám phá bài học mới mỗi ngày!</h1>
            <p>“Chinh phục tiếng Nhật dễ dàng cùng <span> HIKARI </span> Hệ sinh thái Nhật ngữ hàng đầu tại Việt Nam!”
            </p>
            <div>
                <div class="mb-3">
                    <label class="form-label text-primary" for="email">
                        Họ và tên
                    </label>
                    <input value="{{ $data['name'] }}" class="form-control {{ isset($errors['name']) ? 'is-invalid' : '' }}" name="name" placeholder="Họ và tên" type="text" required />
                    <div class="invalid-feedback">{{ isset($errors['name']) ? $errors['name'][0] : '' }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label text-primary" for="email"> Email </label>
                    <input value="{{ $data['email'] }}" class="form-control {{ isset($errors['email']) ? 'is-invalid' : '' }}" name="email" placeholder="example@email.com" type="email" required />
                    <div class="invalid-feedback">{{ isset($errors['email']) ? $errors['email'][0] : '' }}</div>
                </div>
                <div class="mb-3">
                    <label class="form-label text-primary" for="phone"> Số điện thoại </label>
                    <input value="{{ $data['phone'] }}" class="form-control {{ isset($errors['phone']) ? 'is-invalid' : '' }}" name="phone" placeholder="Số điện thoại" type="text" required />
                    <div class="invalid-feedback">{{ isset($errors['phone']) ? $errors['phone'][0] : '' }}</div>
                </div>
                <button class="btn btn-primary mt-2 submit-button" type="submit">Đăng ký</button>
                <div class="login">
                    Bạn đã có tài khoản?
                    <a href="javascript:void(0);" class="text-secondary" data-tab="#login_content">Đăng nhập ngay</a>
                </div>
            </div>
        </form>
        <div class="side-photo">
            <img src="{{ asset('images/no-image.png') }}" alt="no iamge">
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>