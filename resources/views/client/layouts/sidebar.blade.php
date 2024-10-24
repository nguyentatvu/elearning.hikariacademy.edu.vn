<div class="d-flex flex-sm-column flex-row align-items-center sticky-top main-sidebar">
    <ul
        class="nav nav-pills flex-row mb-auto mx-auto text-center justify-content-center align-items-center">

        <li class="sidebar-item nav-item {{ Request::is('home') ? 'active' : '' }}">
            <a href="/" class="nav-link d-flex flex-column justify-content-center align-items-center"
                title="" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                <img src="{{ asset('images/icons/Home.svg') }}" alt="" srcset="">
                <span>Trang chủ</span>
            </a>
        </li>

        <li class="sidebar-item nav-item {{ Request::is('bai-viet') ? 'active' : '' }}">
            <a href="{{ route('user-articles.list') }}" class="nav-link d-flex flex-column justify-content-center align-items-center"
                title="" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Orders">
                <img src="{{ asset('images/icons/News.svg') }}" alt="" srcset="">
                <span>Bài viết</span>
            </a>
        </li>

        <li class="sidebar-item nav-item {{ Request::is('contact') ? 'active' : '' }}">
            <a href="{{ route('home.contact') }}"
                class="nav-link d-flex flex-column justify-content-center align-items-center" title=""
                data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Contact">
                <img src="{{ asset('images/icons/Contact.svg') }}" alt="" srcset="">
                <span>Liên hệ</span>
            </a>
        </li>
    </ul>
</div>
