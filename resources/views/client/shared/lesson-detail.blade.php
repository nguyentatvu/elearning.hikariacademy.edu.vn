@extends('client.app')

@section('styles')
<style>
    body,
    html {
        margin: 0;
        padding: 0;
        height: 100%;
        overflow: hidden;
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

    .topic-content-link {
        font-weight: 700;
        display: block;
        color: black;
        padding: 16px 20px;
        transition: color 0.3s ease;
    }

    .topic-content-link:hover {
        color: var(--primary);
    }

    .accordion-button.active-content {
        background-color: var(--primary);
        color: white;
    }

    .jap-character-icon {
        display: inline-block;
        width: 1em;
        height: 1em;
        background-repeat: no-repeat;
        background-size: 100% 100%;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cg fill='none' stroke='black' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' color='black'%3E%3Cpath d='M11.008 3C9.59 7 7.07 14 11.5 21'/%3E%3Cpath d='M3 5.32C6.706 6.198 15.177 6.637 21 4m-4.997 6c.495 3-3.463 9.5-8.85 9.956C.935 20.484 4.624 11 12.045 11.5c6.248.421 9.987 5.326 3.747 9.5'/%3E%3C/g%3E%3C/svg%3E");
    }

    .accordion-button.active-content > .jap-character-icon {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cg fill='none' stroke='white' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' color='white'%3E%3Cpath d='M11.008 3C9.59 7 7.07 14 11.5 21'/%3E%3Cpath d='M3 5.32C6.706 6.198 15.177 6.637 21 4m-4.997 6c.495 3-3.463 9.5-8.85 9.956C.935 20.484 4.624 11 12.045 11.5c6.248.421 9.987 5.326 3.747 9.5'/%3E%3C/g%3E%3C/svg%3E");
    }

    .vocab-icon {
        display: inline-block;
        width: 1em;
        height: 1em;
        background-repeat: no-repeat;
        background-size: 100% 100%;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='black' d='M7.5 22q-1.475 0-2.488-1.012T4 18.5v-13q0-1.45 1.013-2.475T7.5 2H20v15q-.65 0-1.075.438T18.5 18.5q0 .65.425 1.075T20 20v2zm1.225-9H9.95l.625-1.775H13.4L14.025 13h1.225L12.6 6h-1.25zm2.2-2.8l1.025-2.9h.075l1.025 2.9zM7.5 20h9.325q-.15-.35-.237-.712T16.5 18.5q0-.4.075-.775t.25-.725H7.5q-.65 0-1.075.438T6 18.5q0 .65.425 1.075T7.5 20'/%3E%3C/svg%3E");
    }

    .accordion-button.active-content > .vocab-icon {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='white' d='M7.5 22q-1.475 0-2.488-1.012T4 18.5v-13q0-1.45 1.013-2.475T7.5 2H20v15q-.65 0-1.075.438T18.5 18.5q0 .65.425 1.075T20 20v2zm1.225-9H9.95l.625-1.775H13.4L14.025 13h1.225L12.6 6h-1.25zm2.2-2.8l1.025-2.9h.075l1.025 2.9zM7.5 20h9.325q-.15-.35-.237-.712T16.5 18.5q0-.4.075-.775t.25-.725H7.5q-.65 0-1.075.438T6 18.5q0 .65.425 1.075T7.5 20'/%3E%3C/svg%3E");
    }

    .list-group-item.active-content{
        background-color: var(--primary);
    }

    .list-group-item.active-content > a{
        color: white !important;
    }

    .accordion-item.active-content {
        background-color: var(--primary);
    }

    .accordion-item.active-content a{
        color: white;
    }

    .size-20 {
        width: 20px;
        height: 20px;
    }

    .size-16 {
        width: 16px;
        height: 16px;
    }

    .chapter-image {
        width: 20px;
        height: 20px;
        position: relative;
        bottom: 2px;
    }

    .modal-content {
        border-radius: 15px;
    }

    .modal-header {
        border-bottom: none;
        padding-bottom: 0;
    }

    .modal-body {
        padding-top: 0;
    }

    .course-price {
        color: var(--primary);
        font-weight: bold;
        font-size: 20px;
    }

    .btn-buy {
        background-color: var(--secondary);
        color: #fff;
        border: none;
        border-radius: 5px;
    }

    .btn-buy:hover {
        background-color: #333;
    }

    .modal-footer {
        border-top: none;
        justify-content: flex-start;
        padding-top: 0;
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
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                <div class="comment-section">
                    <div class="comment-input">
                        <img alt="User avatar" height="40"
                            src="https://avatar.iran.liara.run/public/boy"
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
                            src="https://avatar.iran.liara.run/public/boy"
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
                            src="https://avatar.iran.liara.run/public/boy"
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
                                    src="https://avatar.iran.liara.run/public/boy"
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
                                            src="https://avatar.iran.liara.run/public/boy"
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
    <div class="modal fade" id="buy_course_modal" tabindex="-1" aria-labelledby="course_modal_label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="course_modal_label">
                        <i class="bi bi-book me-2"></i> {{ $seriesCombo->title }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Khóa học này sẽ giúp bạn nâng cao kỹ năng tiếng Nhật của mình thông qua các bài học tương tác và thực hành chuyên sâu.</p>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <span class="course-price">
                            <i class="bi bi-currency-dollar"></i> {{ formatCurrencyVND($seriesCombo->cost) }} VNĐ
                        </span>
                        <button class="btn btn-buy">Mua khóa học</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <p class="text-muted small">Mua khóa học để mở khóa toàn bộ nội dung và bắt đầu học ngay!</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-3 list-lesson">
        <h4>Nội dung khóa học</h4>
        <div class="accordion" id="accordion_container">
            @include('client.components.content-tree', ['contents' => $contents])
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        const showBuyCourseModal = () => {
            $('#buy_course_modal').modal('show');
        }
    </script>
@endsection