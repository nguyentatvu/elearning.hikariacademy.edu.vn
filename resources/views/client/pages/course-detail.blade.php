@extends('client.app')

@section('styles')
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            /* Vô hiệu hóa cuộn toàn trang */
        }

        .comment-section {
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .comment-input {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .comment-input img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .comment-input input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
        }

        .comment {
            display: flex;
            margin-bottom: 20px;
        }

        .comment img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .comment-body {
            flex: 1;
        }

        .comment-body .name {
            font-weight: bold;
            color: #007bff;
        }

        .comment-body .time {
            color: #6c757d;
            font-size: 0.9em;
        }

        .comment-body .text {
            margin: 5px 0;
        }

        .comment-body .actions {
            color: #007bff;
            font-size: 0.9em;
        }

        .comment-body .actions span {
            margin-right: 10px;
            cursor: pointer;
        }

        .comment-body .reply {
            margin-left: 50px;
            margin-top: 10px;
        }

        .comment-body .reply img {
            width: 30px;
            height: 30px;
        }

        .comment-body .reply .name {
            font-weight: bold;
            color: #007bff;
        }

        .comment-body .reply .time {
            color: #6c757d;
            font-size: 0.8em;
        }

        .comment-body .reply .text {
            margin: 5px 0;
        }

        .list-lesson {
            height: calc(100vh - 45px);
            overflow-y: auto;
        }

        .study-main-content {
            height: calc(100vh - 45px);
            overflow-y: auto;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-9 study-main-content">
            <div class="study-content">
                <iframe width="100%" height="500px" src="https://www.youtube.com/embed/D0xos2XTQPs?si=vy3ftZnn3YbYKsZi"
                    title="YouTube video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                        type="button" role="tab" aria-controls="nav-home" aria-selected="true">Mô tả bài học</button>
                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile"
                        type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Câu hỏi của
                        bạn</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab"
                    tabindex="0">
                    <div class="lesson-container">
                        <h1 class="lesson-title">Bài học tiếng Nhật N5 - Giới thiệu về Kanji</h1>
                        <div class="lesson-content">
                            <h2>Từ vựng</h2>
                            <ul>
                                <li><b>日本</b> (にほん) - Nhật Bản</li>
                                <li><b>学生</b> (がくせい) - Học sinh</li>
                                <li><b>先生</b> (せんせい) - Giáo viên</li>
                            </ul>

                            <h2>Ngữ pháp</h2>
                            <p>
                                Trong bài học này, bạn sẽ học cách dùng trợ từ <b>は</b> để chỉ chủ ngữ trong câu. Ví dụ:
                            </p>
                            <p class="example">私は学生です。(わたしはがくせいです) - Tôi là học sinh.</p>

                            <h2>Kanji</h2>
                            <p>Chữ Kanji mới: <b>月</b> (つき) - mặt trăng, tháng.</p>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"
                    tabindex="0">
                    <div class="comment-section">
                        <div class="comment-input">
                            <img alt="User avatar" height="40"
                                src="https://oaidalleapiprodscus.blob.core.windows.net/private/org-RcpoXHkzChYnDbFAyeQ8tamr/user-ehrvabJ3DufsCu8YJ7PqY5gl/img-n9j6emrQw2WaT5tejDqXdJa3.png?st=2024-09-27T01%3A28%3A28Z&amp;se=2024-09-27T03%3A28%3A28Z&amp;sp=r&amp;sv=2024-08-04&amp;sr=b&amp;rscd=inline&amp;rsct=image/png&amp;skoid=d505667d-d6c1-4a0a-bac7-5c84a87759f8&amp;sktid=a48cca56-e6da-484e-a814-9c849652bcb3&amp;skt=2024-09-26T23%3A12%3A57Z&amp;ske=2024-09-27T23%3A12%3A57Z&amp;sks=b&amp;skv=2024-08-04&amp;sig=9XnKWIno1gLJOLDueu9XlXJ%2Bx0yJ4gUgV9ixNyY14XQ%3D"
                                width="40" />
                            <input placeholder="Nhập bình luận mới của bạn" type="text" />
                        </div>
                        <div class="comment-count">
                            <strong>
                                132 bình luận
                            </strong>
                            <span class="text-muted float-end">
                                Nếu thấy bình luận spam, các bạn bấm report giúp admin nhé
                            </span>
                        </div>
                        <div class="comment">
                            <img alt="User avatar" height="40"
                                src="https://oaidalleapiprodscus.blob.core.windows.net/private/org-RcpoXHkzChYnDbFAyeQ8tamr/user-ehrvabJ3DufsCu8YJ7PqY5gl/img-n9j6emrQw2WaT5tejDqXdJa3.png?st=2024-09-27T01%3A28%3A28Z&amp;se=2024-09-27T03%3A28%3A28Z&amp;sp=r&amp;sv=2024-08-04&amp;sr=b&amp;rscd=inline&amp;rsct=image/png&amp;skoid=d505667d-d6c1-4a0a-bac7-5c84a87759f8&amp;sktid=a48cca56-e6da-484e-a814-9c849652bcb3&amp;skt=2024-09-26T23%3A12%3A57Z&amp;ske=2024-09-27T23%3A12%3A57Z&amp;sks=b&amp;skv=2024-08-04&amp;sig=9XnKWIno1gLJOLDueu9XlXJ%2Bx0yJ4gUgV9ixNyY14XQ%3D"
                                width="40" />
                            <div class="comment-body">
                                <div class="name">
                                    Học viên A
                                </div>
                                <div class="time">
                                    2 tháng trước
                                </div>
                                <div class="text">
                                    Theo em thấy thì học xong một ngôn ngữ hướng đối tượng rồi qua học javascript sẽ dễ hiểu
                                    hơn ở phần này
                                </div>
                                <div class="actions">
                                    <span>
                                        Phản hồi
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="comment">
                            <img alt="User avatar" height="40"
                                src="https://oaidalleapiprodscus.blob.core.windows.net/private/org-RcpoXHkzChYnDbFAyeQ8tamr/user-ehrvabJ3DufsCu8YJ7PqY5gl/img-n9j6emrQw2WaT5tejDqXdJa3.png?st=2024-09-27T01%3A28%3A28Z&amp;se=2024-09-27T03%3A28%3A28Z&amp;sp=r&amp;sv=2024-08-04&amp;sr=b&amp;rscd=inline&amp;rsct=image/png&amp;skoid=d505667d-d6c1-4a0a-bac7-5c84a87759f8&amp;sktid=a48cca56-e6da-484e-a814-9c849652bcb3&amp;skt=2024-09-26T23%3A12%3A57Z&amp;ske=2024-09-27T23%3A12%3A57Z&amp;sks=b&amp;skv=2024-08-04&amp;sig=9XnKWIno1gLJOLDueu9XlXJ%2Bx0yJ4gUgV9ixNyY14XQ%3D"
                                width="40" />
                            <div class="comment-body">
                                <div class="name">
                                    Học viên B
                                </div>
                                <div class="time">
                                    3 tháng trước
                                </div>
                                <div class="text">
                                    Nếu không có từ khóa new thì có ảnh hưởng gì không nhỉ?
                                </div>
                                <div class="actions">
                                    <span>
                                        Phản hồi
                                    </span>
                                    <span>
                                        Xem 1 câu trả lời
                                    </span>
                                </div>
                                <div class="reply">
                                    <img alt="User avatar" height="30"
                                        src="https://oaidalleapiprodscus.blob.core.windows.net/private/org-RcpoXHkzChYnDbFAyeQ8tamr/user-ehrvabJ3DufsCu8YJ7PqY5gl/img-n9j6emrQw2WaT5tejDqXdJa3.png?st=2024-09-27T01%3A28%3A28Z&amp;se=2024-09-27T03%3A28%3A28Z&amp;sp=r&amp;sv=2024-08-04&amp;sr=b&amp;rscd=inline&amp;rsct=image/png&amp;skoid=d505667d-d6c1-4a0a-bac7-5c84a87759f8&amp;sktid=a48cca56-e6da-484e-a814-9c849652bcb3&amp;skt=2024-09-26T23%3A12%3A57Z&amp;ske=2024-09-27T23%3A12%3A57Z&amp;sks=b&amp;skv=2024-08-04&amp;sig=9XnKWIno1gLJOLDueu9XlXJ%2Bx0yJ4gUgV9ixNyY14XQ%3D"
                                        width="30" />
                                    <div class="comment-body">
                                        <div class="name">
                                            Học viên C
                                        </div>
                                        <div class="time">
                                            4 tháng trước
                                        </div>
                                        <div class="text">
                                            <img alt="Image placeholder" height="20"
                                                src="https://oaidalleapiprodscus.blob.core.windows.net/private/org-RcpoXHkzChYnDbFAyeQ8tamr/user-ehrvabJ3DufsCu8YJ7PqY5gl/img-JOZYC1q0zrKmhvJAMvJ4BlNl.png?st=2024-09-27T01%3A28%3A26Z&amp;se=2024-09-27T03%3A28%3A26Z&amp;sp=r&amp;sv=2024-08-04&amp;sr=b&amp;rscd=inline&amp;rsct=image/png&amp;skoid=d505667d-d6c1-4a0a-bac7-5c84a87759f8&amp;sktid=a48cca56-e6da-484e-a814-9c849652bcb3&amp;skt=2024-09-26T23%3A10%3A54Z&amp;ske=2024-09-27T23%3A10%3A54Z&amp;sks=b&amp;skv=2024-08-04&amp;sig=EwaQpzPzQH7OkxVdndBvOyyMhnVh29ovoL4%2Brg3v%2B/s%3D"
                                                width="100" />
                                            Làm sao để tạo được dấu huyền ntn thế ạ, không phải dấu ngoặc đơn phải không mọi
                                            người ơi ?
                                        </div>
                                        <div class="actions">
                                            <span>
                                                Phản hồi
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3 list-lesson">
            <h4>Nội dung khóa học</h4>
            <div class="accordion" id="accordionExample">
                @for ($i = 1; $i <= 16; $i++)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $i }}">
                            <div class="accordion-button d-block {{ $i === 1 ? '' : 'collapsed' }}" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $i }}"
                                aria-expanded="{{ $i === 1 ? 'true' : 'false' }}"
                                aria-controls="collapse{{ $i }}">
                                <div class="fw-bold"><i class="bi bi-book"></i> Bài {{ $i }}:「はじめまして」</div>
                            </div>
                        </h2>
                        <div id="collapse{{ $i }}"
                            class="accordion-collapse collapse {{ $i === 1 ? 'show' : '' }}"
                            aria-labelledby="heading{{ $i }}" data-bs-parent="#accordionExample">
                            <div>
                                <div class="accordio" id="accordionExample">
                                    <div class="accordion accordion-flush" id="accordionFlushExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingOne">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                                                    aria-expanded="false" aria-controls="flush-collapseOne">
                                                    <i class="bi bi-stickies mx-3"></i> Từ vụng
                                                </button>
                                            </h2>
                                            <div id="flush-collapseOne" class="accordion-collapse collapse"
                                                aria-labelledby="flush-headingOne"
                                                data-bs-parent="#accordionFlushExample">
                                                <div>
                                                    <ul class="list-group">
                                                        <li class="list-group-item">
                                                            <div class="mx-5"><i class="bi bi-play-circle-fill"></i> 1. Từ vựng bài 1 Từ vựng bài 1 Từ vựng bài 1 <span>7:30</span></div>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <div class="mx-5"><i class="bi bi-play-circle-fill"></i> 1. Từ vựng bài 1 Từ vựng bài 1 Từ vựng bài 1 <span>7:30</span></div>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <div class="mx-5"><i class="bi bi-play-circle-fill"></i> 1. Từ vựng bài 1 Từ vựng bài 1 Từ vựng bài 1 <span>7:30</span></div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingTwo">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo"
                                                    aria-expanded="false" aria-controls="flush-collapseTwo">
                                                    <i class="bi bi-diagram-2-fill mx-3"></i> Ngữ pháp
                                                </button>
                                            </h2>
                                            <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                                aria-labelledby="flush-headingTwo"
                                                data-bs-parent="#accordionFlushExample">
                                                <div>Placeholder content for this accordion, which
                                                    is intended to demonstrate the <code>.accordion-flush</code> class. This
                                                    is the second item's accordion body. Let's imagine this being filled
                                                    with some actual content.</div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingThree">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#flush-collapseThree"
                                                    aria-expanded="false" aria-controls="flush-collapseThree">
                                                    <i class="bi bi-pencil-square mx-3"></i>Bài tập
                                                </button>
                                            </h2>
                                            <div id="flush-collapseThree" class="accordion-collapse collapse"
                                                aria-labelledby="flush-headingThree"
                                                data-bs-parent="#accordionFlushExample">
                                                <div>Placeholder content for this accordion, which
                                                    is intended to demonstrate the <code>.accordion-flush</code> class. This
                                                    is the third item's accordion body. Nothing more exciting happening here
                                                    in terms of content, but just filling up the space to make it look, at
                                                    least at first glance, a bit more representative of how this would look
                                                    in a real-world application.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Add your custom scripts here -->
@endsection
