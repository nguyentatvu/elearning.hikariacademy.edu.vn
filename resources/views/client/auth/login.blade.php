@extends('client.shared.auth')

@section('auth-content')
    <div class="login-page">
        <div class="login-form">
            <h1>
                Cùng<span> HIKARI </span> khám phá bài học mới mỗi ngày!
            </h1>
            <p>
                “Chinh phục tiếng Nhật dễ dàng cùng <span> HIKARI </span> Hệ sinh thái Nhật ngữ hàng đầu tại Việt Nam!”
            </p>
            <form>
                <div class="mb-3">
                    <label class="form-label text-primary" for="email">
                        Email hoặc tên đăng nhập
                    </label>
                    <input class="form-control" id="email" placeholder="Example@email.com" type="email" />
                </div>
                <div class="mb-3">
                    <label class="form-label text-primary" for="password"> Mật khẩu </label>
                    <input class="form-control" id="password" placeholder="Mật khẩu" type="password" />
                </div>
                <div class="forgot-password">
                    <a href="#"> Quên mật khẩu </a>
                </div>
                <button class="btn btn-primary mt-2 submit-button" type="submit">Đăng nhập</button>
                <div class="register">
                    Bạn chưa có tài khoản?
                    <a href="{{ route('register') }}" class="text-secondary">Đăng ký ngay</a>
                </div>
            </form>
        </div>
        @if (isset($banners['login_banner']) && $banners['login_banner']->is_active == \App\Enums\BannerStatus::ACTIVE)
            <div class="side-photo">
                <img src="{{ asset($banners['login_banner']->image_url) }}" alt="{{ $banners['login_banner']->title }}">
            </div>
        @endif
    </div>
@endsection
