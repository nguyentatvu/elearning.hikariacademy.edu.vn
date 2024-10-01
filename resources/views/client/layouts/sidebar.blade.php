<div class="sidebar d-flex flex-sm-column flex-row flex-nowrap align-items-center sticky-top">
    <ul
        class="nav nav-pills nav-flush flex-sm-column flex-row flex-nowrap mb-auto mx-auto text-center justify-content-between w-100 align-items-center">

        <li class="sidebar-item nav-item {{ Request::is('/') ? 'active' : '' }}">
            <a href="/" class="nav-link d-flex flex-column justify-content-center align-items-center w-100"
                title="" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Home">
                <img src="{{ asset('images/icons/Home.svg') }}" alt="" srcset="">
                <span>Trang chủ</span>
            </a>
        </li>

        <li class="sidebar-item nav-item {{ Request::is('roadmap') ? 'active' : '' }}">
            <a href="#" class="nav-link d-flex flex-column justify-content-center align-items-center w-100"
                title="" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Dashboard">
                <img src="{{ asset('images/icons/Roadmap.svg') }}" alt="" srcset="">
                <span>Lộ trình</span>
            </a>
        </li>

        <li class="sidebar-item nav-item {{ Request::is('news') ? 'active' : '' }}">
            <a href="#" class="nav-link d-flex flex-column justify-content-center align-items-center w-100"
                title="" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Orders">
                <img src="{{ asset('images/icons/News.svg') }}" alt="" srcset="">
                <span>Bài viết</span>
            </a>
        </li>

        <li class="sidebar-item nav-item {{ Request::is('contact') ? 'active' : '' }}">
            <a href="{{ route('home.contact') }}"
                class="nav-link d-flex flex-column justify-content-center align-items-center w-100" title=""
                data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Contact">
                <img src="{{ asset('images/icons/Contact.svg') }}" alt="" srcset="">
                <span>Liên hệ</span>
            </a>
        </li>

        <li class="sidebar-item nav-item {{ Request::is('chat') ? 'active' : '' }}">
            <a href="#" class="nav-link d-flex flex-column justify-content-center align-items-center w-100"
                title="" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Chat">
                <img src="{{ asset('images/icons/Message-Bot.svg') }}" alt="" srcset="">
                <span>Chat</span>
            </a>
        </li>
    </ul>
</div>
