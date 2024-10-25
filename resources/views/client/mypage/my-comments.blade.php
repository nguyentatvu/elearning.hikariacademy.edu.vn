@extends('client.shared.mypage')

@section('styles')
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
                    @foreach ($comments as $comment_index => $comment)
                    <tr>
                        <td>{{ $comment_index + 1 }}</td>
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
                </tbody>
            </table>
            <div class="mt-4">
                @if ($comments->hasPages())
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            {{-- Previous Page Link --}}
                            @if ($comments->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">Trước</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $comments->previousPageUrl() }}" rel="prev">Trước</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @php
                                $start = $comments->currentPage() - 2;
                                $end = $comments->currentPage() + 2;

                                if($start < 1) {
                                    $start = 1;
                                    $end = min(6, $comments->lastPage());
                                }

                                if($end > $comments->lastPage()) {
                                    $end = $comments->lastPage();
                                    $start = max(1, $end - 4);
                                }
                            @endphp

                            {{-- First Page + Dots --}}
                            @if($start > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $comments->url(1) }}">1</a>
                                </li>
                                @if($start > 2)
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                @endif
                            @endif

                            {{-- Page Numbers --}}
                            @for($i = $start; $i <= $end; $i++)
                                @if ($i == $comments->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $i }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $comments->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endif
                            @endfor

                            {{-- Last Page + Dots --}}
                            @if($end < $comments->lastPage())
                                @if($end < $comments->lastPage() - 1)
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="{{ $comments->url($comments->lastPage()) }}">{{ $comments->lastPage() }}</a>
                                </li>
                            @endif

                            {{-- Next Page Link --}}
                            @if ($comments->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $comments->nextPageUrl() }}" rel="next">Sau</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">Sau</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                @endif
            </div>
        </div>
    </div>
@endsection
