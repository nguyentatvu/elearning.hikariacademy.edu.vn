@extends('client.shared.mypage')

@section('styles')
    <link href="{{ asset('css/pages/mypage.css') }}" rel="stylesheet">
    
@endsection

@section('mypage-content')
    <style>

    </style>
    <div class="px-5 pb-5">
        <div>
            <div class="banner">
                <div class="profile-pic"></div>
                <h3 class="profile-name">Nguyen Van A</h3>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-6">
                <section class="section">
                    <h5 class="section-title">
                        Giới thiệu
                    </h5>
                    <p class="section-subtitle">
                        Thành viên của HIKARI từ 3 tháng trước
                    </p>
                </section>

                <section class="section">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="section-title">
                            Khoá học đã tham gia
                        </h5>
                        <a class="view-all" href="#">
                            Xem tất cả
                        </a>
                    </div>

                    <div class="course-item d-flex align-items-center mb-3">
                        <img alt="Ảnh đại diện khoá học N1" src="{{ asset('images/logo-N1.png') }}" />
                        <div class="course-info flex-grow-1">
                            <div class="course-title">Khoá học N1</div>
                            <div class="course-time">Học cách đây 2 phút trước</div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 25%">25%</div>
                            </div>
                        </div>
                    </div>

                    <div class="course-item d-flex align-items-center mb-3">
                        <img alt="Ảnh đại diện khoá học N1" src="{{ asset('images/logo-N1.png') }}" />
                        <div class="course-info flex-grow-1">
                            <div class="course-title">Khoá học N1</div>
                            <div class="course-time">Học cách đây 30 phút trước</div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 50%">50%</div>
                            </div>
                        </div>
                    </div>

                    <div class="course-item d-flex align-items-center mb-3">
                        <img alt="Ảnh đại diện khoá học N1" src="{{ asset('images/logo-N1.png') }}" />
                        <div class="course-info flex-grow-1">
                            <div class="course-title">Khoá học N1</div>
                            <div class="course-time">Học cách đây 40 phút trước</div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">75%</div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-md-6">
                <div class="personal-information">
                    <h4 class="mb-4">Thông tin tài khoản</h4>
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="id" class="text-personal-infomation">ID</label>
                                <input type="text" class="form-control input-personal-infomation" id="id"
                                    placeholder="ID">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="text-personal-infomation">Email</label>
                                <input type="email" class="form-control input-personal-infomation" id="email"
                                    placeholder="Email">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="text-personal-infomation">Họ và tên</label>
                                <input type="text" class="form-control input-personal-infomation" id="name"
                                    placeholder="Họ và tên">
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="text-personal-infomation">Số điện thoại</label>
                                <input type="text" class="form-control input-personal-infomation" id="phone"
                                    placeholder="Số điện thoại">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="text-personal-infomation">Địa chỉ</label>
                            <input type="text" class="form-control input-personal-infomation" id="address"
                                placeholder="Địa chỉ">
                        </div>
                        <div class="row mb-3 dropzone-file-section">
                            <label for="dropzone_file" class="text-personal-infomation">Cập nhật ảnh đại diện</label>
                            <div class="col-xl-6 d-flex justify-content-center">
                                <div id="file-list" class="mt-3">
                                    <div class="file-list-information position-relative">
                                        <img src="{{ asset('images/icons/avatar-icon.svg') }}" alt="ảnh mặc định"
                                            class="file-image-list">
                                        <div class="change-file" data-index="${index}">Chỉnh sửa</div>
                                    </div>
                                </div>
                                <div id="file-error mt-1"></div>
                            </div>
                            <div class="col-xl-6 col-">
                                <div class="dropzone" id="dropzone_file">
                                    <label for="files" class="dropzone-container">
                                        <div class="file-icon">
                                            <i class="bi bi-file-earmark-plus-fill"></i>
                                        </div>
                                        <div class="text-center px-5">
                                            <p class="text-dark fw-bold dropzone-text">
                                                Kéo tài liệu, ảnh của bạn vào đây để bắt đầu tải lên.
                                            </p>
                                        </div>
                                    </label>
                                    <input id="files" name="files[]" type="file" class="file-input"
                                        accept="image/*" />
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary w-200px">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="module">
        $(document).ready(function() {
            $('#files').on('change', function() {
                $('#file-list').empty();
                $('#file-list').show();
                $('#file-error').text('');
                $('#dropzone').hide();
                $.each(this.files, function(index, file) {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const imgHtml = `
                            <div>
                                <div class="file-list-information position-relative">
                                    <img src="${e.target.result}" alt="${file.name}" class="file-image-list">
                                    <div class="change-file" data-index="${index}">Chỉnh sửa</div>
                                </div>
                            </div>`;
                            $('#file-list').append(imgHtml);
                            $('#dropzone_file').hide();
                        };
                        reader.readAsDataURL(file);
                    } else {
                        $('#file-error').text('Chỉ được chọn file ảnh.');
                    }
                });
            });

            $(document).on('click', '.change-file', function() {
                $('#dropzone_file').show();
            });
        });
    </script>
@endsection
