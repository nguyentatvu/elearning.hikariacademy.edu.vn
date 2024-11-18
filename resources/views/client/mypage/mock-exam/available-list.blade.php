@extends('client.shared.mypage')

@section('mypage-styles')
    <link rel="stylesheet" href="{{ asset('css/custom/mock-exam/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/my-page/mock-exam.css') }}">
@endsection

@section('mypage-content')
<div class="mock-exam-wrapper">
    @if(Auth::user()->is_hocvien == 1)
    <div class="card mb-10">
        <div class="card-header">
            <h3 class="card-title">Bộ đề thi chỉ định</h3>
        </div>
        <div class="card-body">
            <div class="manged-ad table-responsive border-top userprof-tab double-scroll">
                @if(count($series_cd))
                <table class="table table-bordered table-hover mb-0 text-nowrap">
                    <thead>
                        <tr>
                            <th class="text-center align-middle" style="width: 5%;">STT</th>
                            <th>Đề thi chỉ định</th>
                            <th class="text-center align-middle">Trình độ</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($series_cd as $c)
                        <tr>
                            <td class="text-center align-middle">
                                {{$loop->index+1}}
                            </td>
                            <td>
                                <div class="media mt-0 mb-0">
                                    <div class="card-aside-img">
                                        <?php $image = IMAGE_PATH_UPLOAD_SERIES . 'n' . $c->category_id . '.png'; ?>
                                        <a href=""></a><img style="height: auto;" src="{{ $image }}" alt="{{$c->title}}">
                                    </div>
                                    <div class="media-body">
                                        <div class="card-item-desc me-4 p-0 mt-2">
                                            <a href="{{ route('mypage.mock-exam.detail', $c->slug) }}" class="text-dark">
                                                <h4 class="fw-semibold ms-2">{{$c->title}}</h4>
                                            </a>
                                            <a href="#"><i class="bi bi-file-text fw-semibold ms-2"></i> {{ $c->total_exams}} bài kiểm tra
                                                <i class="bi bi-question-circle fw-semibold ms-2 me-1"></i>{{ $c->total_questions}} câu hỏi</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center align-middle">N{{$c->category_id}} </td>
                            <td class="text-center align-middle" style="width: 10%;">
                                <a href="{{ route('mypage.mock-exam.detail', $c->slug) }}"
                                    class="btn btn-primary mb-3 mb-xl-0">
                                    <i class="bi bi-star-fill bi-spin me-2"></i>Thi ngay</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="alert alert-primary" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="bi bi-bell ms-2" aria-hidden="true"></i>
                    <span>Bạn không có đề thi chỉ định!</span>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
    @for ($i = 1; $i <= 5 ; $i++)
    <div class="card mb-10 show_n" style="display: none;" id="show_n{{ $i }}">
        <div class="card-header">
            <h3 class="card-title">Bộ đề thi thử N{{ $i }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <?php $series = 'series_n' . $i; ?>
                @if(!empty($$series) && count($$series) > 0)
                    @foreach($$series as $c)
                        <?php $image = IMAGE_PATH_UPLOAD_SERIES . 'n' . $c->category_id . '.png'; ?>
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="card overflow-hidden">
                                <div class="item-card9-img">
                                    <div class="item-card9-imgs">
                                        <a href="{{ route('mypage.mock-exam.detail', $c->slug) }}"></a>
                                        <img src="{{ $image }}" alt="img" class="cover-image">
                                    </div>
                                    <div class="item-overly-trans">
                                        <a href="{{ route('mypage.mock-exam.detail', $c->slug) }}" class="bg-primary">Trình độ N{{ $i}}</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="item-card9">
                                        <a href="{{ route('mypage.mock-exam.detail', $c->slug) }}" class="text-dark mt-2"><h3 class="font-weight-semibold mt-1 mb-3">{{ $c->title }}</h3></a>
                                        <div class="item-card9-desc mb-2">
                                            <a href="#" class="me-2"><span class="text-muted"><i class="bi bi-book text-muted me-1"></i>Bài thi: {{ $c->total_exams }}</span></a>
                                            <a href="#" class=""><span class="text-muted"><i class="bi bi-question text-muted fs-6"></i>Câu hỏi: {{ $c->total_questions }}</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    @endfor
    <div class="card mb-0">
        <div class="card-header">
            <h3 class="card-title">Bộ đề thi thử</h3>
        </div>
        <div class="card-body">
            <div class="manged-ad table-responsive border-top userprof-tab double-scroll">
                @if(isset($exam_check) && ($exam_check == 'role_test' || $exam_check == 'exam' ))
                <table class="table table-bordered table-hover mb-0 text-nowrap">
                    <thead>
                        <tr>
                            <th class="text-center align-middle" style="width: 5%;">STT</th>
                            <th>Bộ đề thi</th>
                            <th class="text-center align-middle">Trình độ</th>
                            <th>Chọn đề thi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 1; $i <= 5 ; $i++) <?php $image="/public/uploads/exams/series/n{$i}.png" ; ?>
                            <tr>
                                <td class="text-center align-middle">
                                    {{$i}}
                                </td>
                                <td>
                                    <div class="media mt-0 mb-0">
                                        <div class="card-aside-img">
                                            <a href="javascript:void" onclick="show_free({{ $i }});"></a>
                                            <img style="height: auto;" src="{{ $image }}" alt="N">
                                        </div>
                                        <div class="media-body">
                                            <div class="card-item-desc ms-4 p-0 mt-2">
                                                <a href="javascript:void" class="text-dark" onclick="show_free({{ $i }});">
                                                    <h4 class="fw-semibold">Bộ đề thi N{{ $i }}</h4>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center align-middle">N{{ $i }} </td>
                                <td class="text-center align-middle" style="width: 10%;">
                                    <a href="javascript:void" onclick="show_free({{ $i }});"
                                        class="btn btn-primary mb-3 mb-xl-0">
                                        <i class="bi bi-star me-1"></i>Chọn đề thi</a>
                                </td>
                            </tr>
                            @endfor
                    </tbody>
                </table>
                @endif
                @if(($exam_check != "role_test" && $exam_check == null ))
                <div class="alert alert-primary" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="bi bi-bell ms-2" aria-hidden="true"></i>
                    <span>Bạn không có đề thi thử JLPT!</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('mypage-scripts')
    <script>
        function show_free(trinhdo) {
            // Smooth scrolling to mock exam list
            document.querySelector('.mock-exam-wrapper').scrollIntoView({
                behavior: 'smooth'
            });

            if (trinhdo > 0) {
                $('#show_trinhdo, .show_n').hide();
                $('#show_n' + trinhdo).show();
            }
        }
    </script>
@endsection