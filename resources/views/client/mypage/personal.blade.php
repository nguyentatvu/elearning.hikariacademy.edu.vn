@extends('client.shared.mypage')

@section('styles')
    <link href="{{ asset('css/pages/mypage.css') }}" rel="stylesheet">
    
@endsection

@section('mypage-content')
    <div class="px-5 pb-5 personal-wrapper">
        <div>
            <div class="banner">
                <div class="profile-pic">
                    @if (Auth::user()->image)
                        <img src="{{ asset('uploads/users/thumbnail/' . Auth::user()->image) }}" class="rounded-circle object-fit-cover size-full" alt="Avatar" />
                    @else
                        <img src="{{ asset('images/no-avatar.png') }}" class="rounded-circle object-fit-cover size-full" alt="Avatar" />
                    @endif
                </div>
                <h3 class="profile-name">{{ Auth::user()->name }}</h3>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-lg-6">
                <section class="section section-introduction">
                    <h5 class="section-title">
                        Giới thiệu
                    </h5>
                    <p class="section-subtitle">
                        Thành viên của HIKARI từ {{ compareDates(Auth::user()->created_at) }}
                    </p>
                </section>

                <section class="section">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="section-title">
                            Khoá học đã tham gia
                        </h5>
                    </div>
                    @if ($view_series_history->count() > 0)
                        @foreach ($view_series_history as $series)
                        <div class="course-item d-flex align-items-center pb-3">
                            <img class="series-image" alt="series image" src="{{ '/public/uploads/lms/combo/'.$series->image }}" />
                            <div class="course-info flex-grow-1">
                                <div class="course-title">{{ $series->title }}</div>
                                <div class="course-time mb-1">Học cách đây {{ compareTime($series->viewed_time) }}</div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                        aria-valuenow="{{ $series->progressPercent }}" aria-valuemin="0" aria-valuemax="100"
                                        style="width: {{ $series->progressPercent }}%">
                                        <span class="{{ $series->progressPercent <= 10 ? 'd-none' : '' }}">
                                            {{ $series->progressPercent }}%
                                        </span>
                                    </div>
                                    <span class="ms-1 text-primary {{ $series->progressPercent <= 10 ? '' : 'd-none' }}">
                                        {{ $series->progressPercent }}%
                                    </span>
                                </div>
                                <a href="{{ route('learning-management.lesson.show', ['combo_slug' => $series->combo_slug, 'slug' => $series->slug]) }}"
                                    class="text-primary mt-1 fs-5 d-block">
                                    Tiếp tục học
                                </a>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div>
                            <span>Bạn chưa học bài học nào.</span>
                            <a href="#" class="fs-5">Hãy chọn ngay cho mình một khoá học!</a>
                        </div>
                    @endif
                </section>
            </div>

            <div class="col-lg-6">
                <div class="personal-information">
                    <h4 class="mb-4">Thông tin tài khoản</h4>
                    <form id="update_info_form" action="{{ route('mypage.update-info') }}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-personal-infomation">Username</label>
                                <input type="text" class="form-control input-personal-infomation"
                                    value="{{ Auth::user()->username }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="text-personal-infomation">Email</label>
                                <input type="email" class="form-control input-personal-infomation"
                                    value="{{ Auth::user()->email }}" disabled>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="text-personal-infomation">Họ và tên</label>
                                <input type="text" name="name"
                                    class="form-control input-personal-infomation {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                    value="{{ old('name', Auth::user()->name) }}" placeholder="Họ và tên">
                                    <span class="text-danger invalid-feedback">{{ $errors->first('name') }}</span>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="text-personal-infomation">Số điện thoại</label>
                                <input type="text" name="phone"
                                    class="form-control input-personal-infomation {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                    value="{{ old('phone', Auth::user()->phone) }}" placeholder="Số điện thoại">
                                <span class="text-danger invalid-feedback">{{ $errors->first('phone') }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="old_password" class="text-personal-infomation">Mật khẩu cũ</label>
                            <input type="password" class="form-control input-personal-infomation {{ $errors->has('old_password') ? 'is-invalid' : '' }}"
                                name="old_password" placeholder="">
                            <span class="text-danger invalid-feedback">{{ $errors->first('old_password') }}</span>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="text-personal-infomation">Mật khẩu mới</label>
                                <input type="password" class="form-control input-personal-infomation {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                    name="password" placeholder="">
                                <span class="text-danger invalid-feedback">{{ $errors->first('password') }}</span>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="text-personal-infomation">Xác nhận mật khẩu mới</label>
                                <input type="password" class="form-control input-personal-infomation" name="password_confirmation" placeholder="">
                            </div>
                        </div>
                        <div class="row mb-3 dropzone-file-section">
                            <label for="dropzone_file" class="text-personal-infomation">Cập nhật ảnh đại diện</label>
                            <div class="col-xl-6 d-flex justify-content-center">
                                <div id="file-list" class="mt-3">
                                    <div class="file-list-information position-relative {{ $errors->has('avatar') ? 'is-invalid' : '' }}">
                                        @if (Auth::user()->image)
                                            <img src="{{ asset('uploads/users/thumbnail/' . Auth::user()->image) }}"
                                                class="rounded-circle object-fit-cover size-full file-image-list" alt="Avatar" />
                                        @else
                                            <img src="{{ asset('images/no-avatar.png') }}" class="rounded-circle object-fit-cover size-full file-image-list" alt="Avatar" />
                                        @endif
                                        <div class="change-file" data-index="${index}">Chỉnh sửa</div>
                                    </div>
                                    <span class="text-danger invalid-feedback">{{ $errors->first('avatar') }}</span>
                                </div>
                                <div id="file-error mt-1"></div>
                            </div>
                            <div class="col-xl-6">
                                <div class="dropzone" id="dropzone_file">
                                    <label for="files" class="dropzone-container" role="button">
                                        <div class="file-icon">
                                            <i class="bi bi-file-earmark-plus-fill"></i>
                                        </div>
                                        <div class="text-center px-5">
                                            <p class="text-dark fw-bold dropzone-text">
                                                Kéo tài liệu, ảnh của bạn vào đây để bắt đầu tải lên.
                                            </p>
                                        </div>
                                    </label>
                                    <input id="files" name="avatar" type="file" class="file-input"
                                        accept=".png, .jpeg, .jpg" />
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
                                    <img src="${e.target.result}" alt="${file.name}" class="file-image-list object-fit-cover">
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
