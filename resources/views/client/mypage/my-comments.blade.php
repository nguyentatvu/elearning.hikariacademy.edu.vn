@extends('client.shared.mypage')

@section('mypage-styles')
    <link href="{{ asset('css/pages/mypage.css') }}" rel="stylesheet">
@endsection

@section('mypage-content')
    <div class="container">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" scope="col">STT</th>
                        <th class="text-center" scope="col">CÂU HỎI CỦA BẠN</th>
                        <th class="text-center" scope="col">BÀI HỌC</th>
                        <th class="text-center" scope="col">THỜI GIAN</th>
                        <th class="text-center" scope="col">TRẠNG THÁI</th>
                        <th class="text-center" scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @php $page = request()->input('page', 1) - 1; @endphp
                    @if ($comments->count() > 0)
                    @foreach ($comments as $comment_index => $comment)
                    <tr>
                        <td>{{ $page * 15 + $comment_index + 1 }}</td>
                        <td>{{ $comment->body }}</td>
                        <td>{{ $comment->lesson->bai }}</td>
                        <td class="text-center">{{ $comment->created_at }}</td>
                        <td class="align-middle text-center">
                            @if ($comment->status == \App\Comment::UNSEEN_STATUS)
                                <span class="badge bg-warning">Chưa xem</span>
                            @elseif ($comment->status == \App\Comment::ANSWERED_STATUS)
                                <span class="badge bg-info">Đã trả lời</span>
                            @elseif ($comment->status == \App\Comment::SEEN_STATUS)
                                <span class="badge bg-success">Đã xem</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn btn-primary btn-sm ms-2" onclick="location.href='{{ $comment->lesson->url }}'">
                                <i class="bi bi-chat"></i>
                                <span>Xem chi tiết</span>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="6">
                            <h5 style="color: #ee2833!important" class="mb-0">
                                Bạn chưa có Câu hỏi
                            </h5>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
            <div class="mt-4">
                @component('client.components.custom-pagination',
                    ['paginations' => $comments])
                @endcomponent
            </div>
        </div>
    </div>
@endsection
