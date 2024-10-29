@if ($paginations->hasPages())
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            {{-- Previous Page Link --}}
            @if ($paginations->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">Trước</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginations->previousPageUrl() }}" rel="prev">Trước</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @php
                $start = $paginations->currentPage() - 2;
                $end = $paginations->currentPage() + 2;

                if($start < 1) {
                    $start = 1;
                    $end = min(6, $paginations->lastPage());
                }

                if($end > $paginations->lastPage()) {
                    $end = $paginations->lastPage();
                    $start = max(1, $end - 4);
                }
            @endphp

            {{-- First Page + Dots --}}
            @if($start > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ $paginations->url(1) }}">1</a>
                </li>
                @if($start > 2)
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                @endif
            @endif

            {{-- Page Numbers --}}
            @for($i = $start; $i <= $end; $i++)
                @if ($i == $paginations->currentPage())
                    <li class="page-item active">
                        <span class="page-link">{{ $i }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginations->url($i) }}">{{ $i }}</a>
                    </li>
                @endif
            @endfor

            {{-- Last Page + Dots --}}
            @if($end < $paginations->lastPage())
                @if($end < $paginations->lastPage() - 1)
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                @endif
                <li class="page-item">
                    <a class="page-link" href="{{ $paginations->url($paginations->lastPage()) }}">{{ $paginations->lastPage() }}</a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginations->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginations->nextPageUrl() }}" rel="next">Sau</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">Sau</span>
                </li>
            @endif
        </ul>
    </nav>
@endif