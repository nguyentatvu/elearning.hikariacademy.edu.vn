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
</style>
<nav class="navbar navbar-custom bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand mr-2 d-flex align-items-center justify-content-center" href="#">
            <i class="fas fa-arrow-left"></i>
            <img alt="Hikari E-Learning Logo"
                src="{{ asset('images/Logo-hikari.png') }}" width="100px" height="25px"/>
            <span>Khoá học N1</span>
        </a>
        <div class="d-flex align-items-center">
            <div class="progress-container">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 36%;" aria-valuenow="36"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span class="progress-text">74/204 bài học</span>
            </div>
            <a class="nav-link" href="#">
                <i class="fas fa-question-circle"></i>
                Hướng dẫn
            </a>
        </div>
    </div>
</nav>
