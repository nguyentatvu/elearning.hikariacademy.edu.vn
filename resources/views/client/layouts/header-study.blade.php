<style>
    .navbar-custom {
        background-color: #1565C0;
        color: white;
    }

    .navbar-custom .navbar-brand,
    .navbar-custom .nav-link {
        color: white;
    }

    .progress-container {
        display: flex;
        align-items: center;
    }

    .progress {
        width: 100px;
        height: 10px;
        margin-right: 10px;
    }

    .progress-text {
        margin-right: 20px;
    }

    /* Ẩn logo */
    .navbar-custom .navbar-brand img {
        display: none;
    }

    /* Điều chỉnh hiển thị tên khóa học */
    .navbar-custom .navbar-brand span {
        font-size: 1.25rem; /* Tăng kích thước font chữ */
    }
</style>

<nav class="navbar navbar-custom bg-primary header-study">
    <div class="container-fluid">
        <a class="navbar-brand me-2 d-flex align-items-center justify-content-center" href="#">
            <i class="fas fa-arrow-left d-none d-md-block"></i>
            <!-- Logo đã ẩn -->
            <span class="ms-2 d-inline-block">Khoá học N1</span>
        </a>
        <div class="d-flex align-items-center">
            <div class="progress-container d-none d-md-block">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 36%;" aria-valuenow="36" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span class="progress-text">74/204 bài học</span>
            </div>
            <a class="nav-link ms-3 d-none d-md-inline-block" href="#">
                <i class="fas fa-question-circle"></i> Hướng dẫn
            </a>
            <button class="navbar-toggler d-block d-md-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
    <div class="collapse navbar-collapse d-md-none" id="navbarContent">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-arrow-left"></i> Khoá học N1</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">74/204 bài học</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-question-circle"></i> Hướng dẫn</a>
            </li>
        </ul>
    </div>
</nav>
