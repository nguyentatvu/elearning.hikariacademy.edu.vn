<style>
    .custom-container {
        background-color: #f8f9fa;
        padding: 10px;
        position: fixed; /* Giữ nó ở vị trí cố định */
        bottom: 0; /* Đặt nó ở dưới cùng */
        left: 0;
        right: 0;
    }

    .btn-custom {
        border-radius: 20px;
        border: 1px solid #00a2ff;
        color: #00a2ff;
        background-color: white;
    }

    .btn-custom:hover {
        background-color: #e6f7ff;
    }

    .btn-custom-primary {
        border-radius: 20px;
        background-color: #b3d7ff;
        color: white;
    }

    .btn-custom-primary:hover {
        background-color: #99c2ff;
    }

    .text-custom {
        font-weight: bold;
        font-size: 16px;
    }
</style>

<div class="custom-container d-flex justify-content-between align-items-center">
    <div>
        <button class="btn btn-custom"><i class="fas fa-chevron-left"></i> bài trước</button>
        <button class="btn btn-custom-primary">bài tiếp theo <i class="fas fa-chevron-right"></i></button>
    </div>
    <div class="text-custom">
        Bài 1: 「はじめまして」 <i class="fas fa-arrow-right"></i>
    </div>
</div>
