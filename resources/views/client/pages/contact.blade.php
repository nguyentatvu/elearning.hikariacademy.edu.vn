@extends('client.app')

@section('styles')
@endsection

@section('content')
    <div class="mx-5">
        <div class="custom-contact-section row">
            <div class="custom-contact-form col-xl-6 p-5">
                <h5 class="custom-contact-title">
                    Liên hệ ngay
                </h5>
                <div class="mb-3 position-relative">
                    <label for="name" class="custom-text-info">Họ và tên</label>
                    <input type="text" class="form-control custom-form-control" id="name" placeholder="Họ và tên">
                </div>
                <div class="mb-3 position-relative">
                    <label for="email" class="custom-text-info">Email</label>
                    <input type="text" class="form-control custom-form-control" id="email" placeholder="Email">
                </div>
                <div class="mb-3 position-relative">
                    <label for="message" class="custom-text-info">Tin nhắn</label>
                    <textarea class="form-control" placeholder="Tin nhắn" rows="5">Lorem ipsum dolor sit amet, consectetur adipiscing elit...</textarea>
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="d-flex justify-content-center align-items-center">
                    <button class="btn btn-primary custom-submit-btn" type="button">Liên hệ</button>
                </div>
            </div>
            <div class="custom-contact-image col-xl-6">
                <img src="{{ asset('images/background/Background-contact.svg') }}" alt="Contact background">
            </div>
        </div>
        <div class="row my-5">
            <div class="col-12 col-md-6 col-lg-3 text-center mb-4 mb-lg-0">
                <img alt="Phone icon" src="{{ asset('images/icons/Icon-contact-1.svg') }}" />
                <p>
                    028 3849 7875
                    <br /> Hỗ trợ miễn phí!
                </p>
            </div>
            <div class="col-12 col-md-6 col-lg-3 text-center mb-4 mb-lg-0">
                <img alt="Calendar icon" src="{{ asset('images/icons/Icon-contact-2.svg') }}" />
                <p>
                    Thứ 2 - Thứ 6 (08:00-21:00)
                    <br /> Giờ làm việc!
                </p>
            </div>
            <div class="col-12 col-md-6 col-lg-3 text-center mb-4 mb-md-0">
                <img alt="Location icon" src="{{ asset('images/icons/Icon-contact-3.svg') }}" />
                <p>
                    310 Lê Quang Định, Phường 11, Quận Bình Thạnh, Tp. HCM
                </p>
            </div>
            <div class="col-12 col-md-6 col-lg-3 text-center">
                <img alt="Email icon" src="{{ asset('images/icons/Icon-contact-4.svg') }}" />
                <p>
                    tieptan@hikariacademy.edu.vn
                    <br /> Email liên hệ!
                </p>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script type="module">
        $(document).ready(function() {
            // Optional script logic
        });
    </script>
@endsection
