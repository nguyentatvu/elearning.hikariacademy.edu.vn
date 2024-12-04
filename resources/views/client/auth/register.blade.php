@extends('client.shared.auth')

@section('auth-content')
<div class="register-page">
    <div class="register-form">
        <h1>
            Cùng<span> HIKARI </span> khám phá bài học mới mỗi ngày!
        </h1>
        <p>
            “Chinh phục tiếng Nhật dễ dàng cùng <span> HIKARI </span> Hệ sinh thái Nhật ngữ hàng đầu tại Việt Nam!”
        </p>
        <form>
            <div class="mb-3">
                <label class="form-label text-primary" for="email">
                    Họ và tên
                </label>
                <input class="form-control" name="name" placeholder="Họ và tên" type="text" />
            </div>
            <div class="mb-3">
                <label class="form-label text-primary" for="email"> Email </label>
                <input class="form-control" name="email" placeholder="example@email.com" type="email" />
            </div>
            <div class="mb-3">
                <label class="form-label text-primary" for="password"> Số điện thoại </label>
                <input class="form-control" name="password" placeholder="Số điện thoại" type="text" />
            </div>
            <button class="btn btn-primary mt-2 submit-button" type="submit">Đăng ký</button>
            <div class="login">
                Bạn đã có tài khoản?
                <a href="{{ route('login') }}" class="text-primary">Đăng nhập ngay</a>
            </div>
        </form>
    </div>
    <div class="side-photo">
        <img src="{{ asset('images/no-image.png') }}" alt="no iamge">
    </div>
</div>
@endsection