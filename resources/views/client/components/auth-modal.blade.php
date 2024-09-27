<div class="modal auth-modal" id="modal_login_register">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="login-content auth-content animate__animated" id="login_content">
                <form class="login-form needs-validation" novalidate id="login_form">
                    <h1>Cùng<span> HIKARI </span> khám phá bài học mới mỗi ngày!</h1>
                    <p>“Chinh phục tiếng Nhật dễ dàng cùng <span> HIKARI </span> Hệ sinh thái Nhật ngữ hàng đầu tại Việt Nam!”</p>
                    <div>
                        <div class="mb-3">
                            <label class="form-label text-primary" for="email">
                                Email hoặc tên đăng nhập
                            </label>
                            <input class="form-control" name="email" id="email" placeholder="example@email.com" required/>
                            <div class="invalid-feedback">Vui lòng nhập vào đây</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-primary" for="password"> Mật khẩu </label>
                            <input class="form-control" name="password" id="password" placeholder="Mật khẩu" type="password" required/>
                            <div class="invalid-feedback">Vui lòng nhập vào đây</div>
                        </div>
                        <div class="login-failed text-danger d-none">Tên hoặc mật khẩu không đúng</div>
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
            <div class="register-content auth-content animate__animated d-none" id="register_content">
                <form class="register-form needs-validation" id="register_form" novalidate>
                    <h1>Cùng<span> HIKARI </span> khám phá bài học mới mỗi ngày!</h1>
                    <p>“Chinh phục tiếng Nhật dễ dàng cùng <span> HIKARI </span> Hệ sinh thái Nhật ngữ hàng đầu tại Việt Nam!”</p>
                    <div>
                        <div class="mb-3">
                            <label class="form-label text-primary" for="email">
                                Họ và tên
                            </label>
                            <input class="form-control" name="name" placeholder="Họ và tên" type="text" required/>
                            <div class="invalid-feedback">Vui lòng nhập vào đây</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-primary" for="email"> Email </label>
                            <input class="form-control" name="email" placeholder="example@email.com" type="email" required/>
                            <div class="invalid-feedback">Vui lòng nhập vào đây</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-primary" for="phone"> Số điện thoại </label>
                            <input class="form-control" name="phone" placeholder="Số điện thoại" type="text" required/>
                            <div class="invalid-feedback">Vui lòng nhập vào đây</div>
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
    </div>
</div>
@if (Session::has('popup_login'))
    <script type="text/javascript">
        $(function () {
            $('#btn_login').trigger('click');
        });
    </script>
@endif

@if (Session::has('popup_register'))
    <script type="text/javascript">
        $(function () {
            $('#btn_register').trigger('click');
        });
    </script>
@endif