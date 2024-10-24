@foreach($contents as $content_index => $content)
    @if ($content->el_try == App\LmsContent::TRIAL_TYPE)
        <li class="list-group-item px-5 {{ $content->css_class }}">
            <a href="{{ $content->url }}" class="text-dark">
                <i class="bi bi-file-earmark-text text-primary fs-5"></i>
                <span>{{ $content->bai }}</span>
            </a>
        </li>
    @else
        <li class="list-group-item px-5 {{ $content->css_class }}">
            <a href="javascript:void(0);" class="text-dark">
                <i class="bi bi-lock-fill"></i>
                <span>Nội dung bị ẩn</span>
            </a>
        </li>
    @endif
@endforeach