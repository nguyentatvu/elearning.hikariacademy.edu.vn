@foreach($contents as $content_index => $content)
    <li class="list-group-item px-5 {{ $content->css_class }}">
        <a href="{{ $content->url }}" class="text-dark">
            @if ($is_valid_payment || $chapter_index !== 0)
                <img src="{{ asset("images/icons/{$content->checkbox_icon}") }}"alt="check box"
                    style="bottom: 1px;" class="position-relative size-16">
            @else
                <img src="{{ asset("images/icons/empty-box.svg") }}"alt="check box"
                    style="bottom: 1px;" class="position-relative">
            @endif
            <span>{{ $content->bai }}</span>
        </a>
    </li>
@endforeach