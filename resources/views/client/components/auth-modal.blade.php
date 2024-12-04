<div class="modal auth-modal" id="modal_login_register">
    <div class="modal-dialog mx-auto">
        <div class="modal-content">
            <div class="login-content auth-content animate__animated" id="login_content">
                <form class="login-form needs-validation" novalidate id="login_form">
                    <h1>Cùng<span> HIKARI </span> khám phá bài học mới mỗi ngày!</h1>
                    <p>“Chinh phục tiếng Nhật dễ dàng cùng <span> HIKARI </span> Hệ sinh thái Nhật ngữ hàng đầu tại Việt
                        Nam!”</p>
                    <div>
                        <div class="mb-3">
                            <label class="form-label text-primary" for="email">
                                Email hoặc tên đăng nhập
                            </label>
                            <input class="form-control" name="email" id="email" placeholder="example@email.com"
                                required />
                            <div class="invalid-feedback">Vui lòng nhập vào đây</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-primary" for="password"> Mật khẩu </label>
                            <div class="password-field">
                                <input class="form-control" name="password" id="password" placeholder="Mật khẩu" type="password" required />
                                <span class="password-toggle-icon" id="password-icon" onclick="togglePasswordVisibility('password')">
                                    <i class="bi-eye-slash-fill"></i>
                                </span>
                            </div>
                            <div class="invalid-feedback">Vui lòng nhập vào đây</div>
                        </div>
                        <div class="mb-3 captcha-field">
                            {!! NoCaptcha::renderJs() !!}
                            {!! NoCaptcha::display() !!}
                        </div>
                        <div class="login-failed text-danger d-none">Tên hoặc mật khẩu không đúng</div>
                        <div class="captcha-failed text-danger d-none">Hãy đánh dấu vào ô kiểm tra robot!</div>
                        <div class="forgot-password">
                            <a href="javascript:void(0);" class="text-primary" data-tab="#forgot_password_content">
                                Quên mật khẩu </a>
                        </div>
                        <button class="btn btn-primary mt-2 submit-button" type="submit">Đăng nhập</button>
                        <div class="register">
                            Bạn chưa có tài khoản?
                            <a href="javascript:void(0);" class="text-primary" data-tab="#register_content">Đăng ký
                                ngay</a>
                        </div>
                    </div>
                </form>

                <div class="side-photo">
                    @if (isset($banners['login_banner']) && $banners['login_banner']->is_active == \App\Enums\BannerStatus::ACTIVE)
                        <img src="{{ asset($banners['login_banner']->image) }}"
                            alt="{{ $banners['login_banner']->title }}">
                    @endif
                </div>
            </div>
            <div class="register-content auth-content animate__animated d-none" id="register_content">
                <form class="register-form needs-validation" id="register_form" novalidate>
                    <h1>Cùng<span> HIKARI </span> khám phá bài học mới mỗi ngày!</h1>
                    <p>“Chinh phục tiếng Nhật dễ dàng cùng <span> HIKARI </span> Hệ sinh thái Nhật ngữ hàng đầu tại Việt
                        Nam!”</p>
                    <div>
                        <div class="mb-3">
                            <label class="form-label text-primary" for="email">
                                Họ và tên
                            </label>
                            <input class="form-control" name="name" placeholder="Họ và tên" type="text"
                                required />
                            <div class="invalid-feedback">Vui lòng nhập vào đây</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-primary" for="email"> Email </label>
                            <input class="form-control" name="email" placeholder="example@email.com" type="email"
                                required />
                            <div class="invalid-feedback">Vui lòng nhập vào đây</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-primary" for="phone"> Số điện thoại </label>
                            <input class="form-control" name="phone" placeholder="Số điện thoại" type="text"
                                required />
                            <div class="invalid-feedback">Vui lòng nhập vào đây</div>
                        </div>
                        <div class="mb-3 captcha-field">
                            {!! NoCaptcha::renderJs() !!}
                            {!! NoCaptcha::display() !!}
                        </div>
                        <button class="btn btn-primary mt-2 submit-button" type="submit">Đăng ký</button>
                        <div class="login">
                            Bạn đã có tài khoản?
                            <a href="javascript:void(0);" class="text-primary" data-tab="#login_content">Đăng nhập
                                ngay</a>
                        </div>
                    </div>
                </form>
                @if (isset($banners['register_banner']) && $banners['register_banner']->is_active == \App\Enums\BannerStatus::ACTIVE)
                    <div class="side-photo">
                        <img src="{{ asset($banners['register_banner']->image) }}"
                            alt="{{ $banners['register_banner']->title }}">
                    </div>
                @endif
            </div>

            <!-- Forgot password -->
            <div class="forgot-password-content auth-content animate__animated d-none" id="forgot_password_content">
                <form class="forgot-password-form needs-validation" novalidate id="forgot_password_form">
                    <h1>Đặt lại mật khẩu</h1>
                    <div>
                        <div class="mb-3">
                            <label class="form-label text-primary" for="email_forgot_password">
                                Email
                            </label>
                            <input class="form-control" type="email" name="email_forgot_password"
                                id="email_forgot_password" placeholder="example@email.com" required />
                            <div class="invalid-feedback">Vui lòng nhập vào đây</div>
                            <span class="email-failed text-danger d-none"></span>
                        </div>
                        <button id="forgot_password_btn" class="btn btn-primary mt-2 submit-button"
                            type="submit">Gửi</button>
                        <div class="register">
                            Bạn chưa có tài khoản?
                            <a href="javascript:void(0);" class="text-primary" data-tab="#register_content">Đăng
                                ký</a>
                        </div>
                        <div class="register">
                            Bạn đã có tài khoản?
                            <a href="javascript:void(0);" class="text-primary" data-tab="#login_content">Đăng
                                nhập</a>
                        </div>
                    </div>
                </form>
                <div class="side-photo">
                    @if (isset($banners['login_banner']) && $banners['login_banner']->is_active == \App\Enums\BannerStatus::ACTIVE)
                        <img src="{{ asset($banners['login_banner']->image) }}"
                            alt="{{ $banners['login_banner']->title }}">
                    @endif
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    </div>
</div>

@if (Session::has('popup_login'))
    <script type="text/javascript">
        $(function() {
            $('#btn_login').trigger('click');
        });
    </script>
@endif

@if (Session::has('popup_register'))
    <script type="text/javascript">
        $(function() {
            $('#btn_register').trigger('click');
        });
    </script>
@endif

<script>
    // Function to toggle password visibility and icon class
    function togglePasswordVisibility(inputId) {
        var inputField = document.getElementById(inputId);
        var icon = document.getElementById('password-icon').querySelector('i');

        if (inputField.type === 'password') {
            inputField.type = 'text'; // Show password
            icon.classList.remove('bi-eye-slash-fill');
            icon.classList.add('bi-eye-fill'); // Change icon to "eye-slash"
        } else {
            inputField.type = 'password'; // Hide password
            icon.classList.remove('bi-eye-fill');
            icon.classList.add('bi-eye-slash-fill'); // Change icon to "eye"
        }
    }
</script>
