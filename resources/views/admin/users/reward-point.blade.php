@extends($layout)
<style>
    .score-result {
        display: inline-block;
        text-align: center;
    }

    .total-score-result-information {
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .score-result {
            width: 100%;
            margin-bottom: 10px;
        }
    }

    .rounded-gradient-borders {
        width: 150px;
        height: 150px;
        border: double 10px transparent;
        border-radius: 50%;
        background-image: linear-gradient(white, white), radial-gradient(circle at top left, #f00, #3020ff);
        background-origin: border-box;
        background-clip: content-box, border-box;
        display: inline-block;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
        position: relative;
    }

    .rounded-gradient-borders>.total-score-result {
        font-size: 1rem;
        font-weight: bold;
        color: #3020ff;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .rounded-gradient-borders>.total-score-text {
        font-size: 1rem;
        font-weight: bold;
        color: #3020ff;
        position: absolute;
        top: 80%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .track-process-personal {
        height: 120px;
        width: 100%;
        border-radius: 15px;
        background: #f2f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .track-process-personal .track-process-information {
        height: 100px;
        width: 95%;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: space-around;
        background: #ffffff;
    }

    .track-process-personal .track-process-text {
        text-align: center;
        font-weight: bold;
    }

    .achievement-overview-personal {
        min-width: 500px;
        display: flex;
        flex-wrap: wrap;
        gap: 25px;
    }

    @media (max-width: 768px) {
        .achievement-overview-personal {
            min-width: 100%;
            flex-direction: column;
        }

        .score-history-title {
            margin-top: 20px;
        }
    }

    .achievement-overview-information {
        height: 100px;
        min-width: 150px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: space-around;
        background: #ffffff;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }

    .achievement-overview-text {
        text-align: center;
        font-weight: bold;
    }

    .redeem-reward-icon {
        text-align: center;
    }

    .redeem-rewards-information {
        width: 48%;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .redeem-rewards-information {
            width: 100%;
            margin-bottom: 15px;
        }
    }

    .redeem-reward {
        display: flex;
        align-items: center;
        max-width: 100%;
    }

    .redeem-reward>img {
        width: 100%;
        max-width: 100px;
        height: auto;
        border: 5px solid #d3d3d3;
        padding: 5px;
        background-color: #f0f0f0;
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
        border-radius: 15px;
    }

    .redeem-reward-body {
        padding: 10px;
    }

    .score-history-title {
        display: flex;
        justify-content: space-around;
        justify-items: center;
        align-items: center;
    }

    @media (max-width: 768px) {
        .score-history-title {
            flex-direction: column;
            text-align: center;
        }
    }

    .score-history td {
        font-size: 16px;
    }

    .line-clamp-1 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        line-clamp: 1;
        -webkit-box-orient: vertical;
    }

    .redeem-reward-score {
        font-size: 16px;
        margin-left: 6px;
        margin-top: 2px;
    }

    .off-price-percent {
        font-weight: 700;
        font-size: 12px;
        color: white;
        background: #dc3545 !important;
        padding: 2px 10px;
        border-radius: 12px;
        margin-left: 8px;
        margin-top: 8px;
        width: fit-content;
    }

    .series-old-price {
        text-decoration: line-through;
        color: gray;
        font-size: 14px;
        margin-left: 4px;
    }

    .series-new-price {
        font-weight: bold;
        color: black;
        font-size: 18px;
    }

    .score-redeem-rewards {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        column-gap: 12px;
        row-gap: 12px;
    }

    .redeem-reward-item {
        position: relative;
        border: 1px solid #f2f5fa;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: rgba(0, 0, 0, 0.1) 0px 0px 5px 0px, rgba(0, 0, 0, 0.1) 0px 0px 1px 0px;
    }

    .redeem-reward-info {
        padding: 12px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .redeem-reward-info h4 {
        margin-top: -2px;
    }

    .redeem-info {
        margin-top: 4px;
        display: flex;
    }

    .redeem-info .fa-arrow-right {
        font-size: 20px;
        color: #156ac8;
    }

    .bullet-icon {
        font-size: 20px !important;
        margin-right: 6px;
        color: #156ac8;
        width: 24px
    }

    .redeem-reward-btn {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 12px;
        background-color: #f8fafc;
    }

    .redeem-reward-btn > a {
        width: 100%;
        border: none;
        background-color: #156ac8;
        color: white;
        border-radius: 4px;
        font-size: 16px;
        padding: 8px 0;
        text-align: center;
        transition: all 0.2s ease-in-out;
    }

    .redeem-reward-btn.not-allowed > a {
        pointer-events: none;
    }

    .redeem-reward-btn > a:hover {
        color: white !important;
        opacity: 0.85;
    }

    .redeem-reward-img {
        position: relative;
    }

    .dark-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgb(255,255,255);
        background: linear-gradient(0deg, rgba(255,255,255,0) 73%, rgba(0,0,0,0.45704219187675066) 100%);
        z-index: 0;
    }

    .series-title {
        height: 54px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .gray-filter {
        filter: grayscale(100%);
    }
</style>
@section('content')
    @php
        $number = 12312312;
        $formattedNumber = $number;
    @endphp
    <div id="page-wrapper">
        <div class="card mb-0">
            <div class="card-header">
                <h3 class="card-title">
                    {{ $title }}
                </h3>
            </div>
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="total-score-result-information">
                                <div class="score-result">
                                    <h4>Điểm tích luỹ</h4>
                                    <div class="rounded-gradient-borders">
                                        <span class="total-score-result">{{ $formattedNumber }}</span>
                                        <span class="total-score-text">Điểm</span>
                                    </div>
                                </div>
                                <div class="score-result">
                                    <h4>Điểm đã sử dụng</h4>
                                    <div class="rounded-gradient-borders">
                                        <span class="total-score-result">1500</span>
                                        <span class="total-score-text">Điểm</span>
                                    </div>
                                </div>
                            </div>
                            <h4 class="mt-4">Theo dõi quá trình cá nhân</h4>
                            <div class="track-process-personal">
                                <div class="track-process-information">
                                    <div class="track-process-text">
                                        <div class="d-flex justify-content-center">
                                            {{ $formattedNumber }}
                                            <img width="20"
                                                src="https://data-gcdn.basecdn.net/202408/sys7763/message/19/17/UGMF9F9BVJ43KYZPDGMZ/a5e81134d848bb19eb1371a9869a7eb3/XKV6HB9AUNDJYDGEN9SW8NZYNRXEND4R29QPKGTX4QAKX2GG7CX28G9A5UTC6NGPGMWWXVYM3P8SV6M2A4YGWJ/dc/e6/58/41/00/1e23cebee25795a8e415afc3a1ae07da/6975b744_8482_4197_a3ae_baaadd663878.png"
                                                alt="">
                                        </div>
                                        <div class="mt-3">Tổng điểm</div>
                                    </div>
                                    <div class="track-process-text">
                                        <div>99</div>
                                        <div class="mt-3">Tổng các hoạt động</div>
                                    </div>
                                </div>
                            </div>
                            <h4 class="mt-4">Chi tiết các hoạt động</h4>
                            <div class="achievement-overview-personal">
                                <div class="achievement-overview-information">
                                    <div class="achievement-overview-text">
                                        <img width="40" src="https://cdn-icons-png.flaticon.com/512/1987/1987985.png"
                                            alt="">
                                        <div class="d-flex justify-content-center">
                                            <span>9</span>
                                            <img width="20"
                                                src="https://data-gcdn.basecdn.net/202408/sys7763/message/19/17/UGMF9F9BVJ43KYZPDGMZ/a5e81134d848bb19eb1371a9869a7eb3/XKV6HB9AUNDJYDGEN9SW8NZYNRXEND4R29QPKGTX4QAKX2GG7CX28G9A5UTC6NGPGMWWXVYM3P8SV6M2A4YGWJ/dc/e6/58/41/00/1e23cebee25795a8e415afc3a1ae07da/6975b744_8482_4197_a3ae_baaadd663878.png"
                                                alt="">
                                        </div>
                                        <div>Bài tập/ kiểm tra</div>
                                    </div>
                                </div>
                                <div class="achievement-overview-information">
                                    <div class="achievement-overview-text">
                                        <img width="40"
                                            src="https://data-gcdn.basecdn.net/202408/sys7763/message/20/14/DWTL2N528LRAWSFWHZL9/fd00d04772410d0ae9bbd9e73616afed/EWNAWLK8WXQ8JCDN4C7NCMSCMGU9GCT736HCXM83SCQ9MALP7K526LSBDC3YCRRRLA8UNPRKXRYAV7WTT8RP27/dc/e6/58/41/00/1e23cebee25795a8e415afc3a1ae07da/video.png"
                                            alt="">
                                        <div class="d-flex justify-content-center">
                                            <span>30</span>
                                            <img width="20"
                                                src="https://data-gcdn.basecdn.net/202408/sys7763/message/19/17/UGMF9F9BVJ43KYZPDGMZ/a5e81134d848bb19eb1371a9869a7eb3/XKV6HB9AUNDJYDGEN9SW8NZYNRXEND4R29QPKGTX4QAKX2GG7CX28G9A5UTC6NGPGMWWXVYM3P8SV6M2A4YGWJ/dc/e6/58/41/00/1e23cebee25795a8e415afc3a1ae07da/6975b744_8482_4197_a3ae_baaadd663878.png"
                                                alt="">
                                        </div>
                                        <div>Xem video</div>
                                    </div>
                                </div>
                                <div class="achievement-overview-information">
                                    <div class="achievement-overview-text">
                                        <img width="40" src="https://cdn-icons-png.flaticon.com/512/771/771222.png"
                                            alt="">
                                        <div class="d-flex justify-content-center">
                                            <span>1000</span>
                                            <img width="20"
                                                src="https://data-gcdn.basecdn.net/202408/sys7763/message/19/17/UGMF9F9BVJ43KYZPDGMZ/a5e81134d848bb19eb1371a9869a7eb3/XKV6HB9AUNDJYDGEN9SW8NZYNRXEND4R29QPKGTX4QAKX2GG7CX28G9A5UTC6NGPGMWWXVYM3P8SV6M2A4YGWJ/dc/e6/58/41/00/1e23cebee25795a8e415afc3a1ae07da/6975b744_8482_4197_a3ae_baaadd663878.png"
                                                alt="">
                                        </div>
                                        <div>Đổi điểm</div>
                                    </div>
                                </div>
                                <div class="achievement-overview-information">
                                    <div class="achievement-overview-text">
                                        <img width="40" src="https://cdn-icons-png.freepik.com/512/2695/2695971.png"
                                            alt="">
                                        <div class="d-flex justify-content-center">
                                            <span>300</span>
                                            <img width="20"
                                                src="https://data-gcdn.basecdn.net/202408/sys7763/message/19/17/UGMF9F9BVJ43KYZPDGMZ/a5e81134d848bb19eb1371a9869a7eb3/XKV6HB9AUNDJYDGEN9SW8NZYNRXEND4R29QPKGTX4QAKX2GG7CX28G9A5UTC6NGPGMWWXVYM3P8SV6M2A4YGWJ/dc/e6/58/41/00/1e23cebee25795a8e415afc3a1ae07da/6975b744_8482_4197_a3ae_baaadd663878.png"
                                                alt="">
                                        </div>
                                        <div>Nạp</div>
                                    </div>
                                </div>
                                <div class="achievement-overview-information">
                                    <div class="achievement-overview-text">
                                        <img width="40" src="https://cdn-icons-png.freepik.com/512/4083/4083886.png"
                                            alt="">
                                        <div class="d-flex justify-content-center">
                                            <span>0</span>
                                            <img width="20"
                                                src="https://data-gcdn.basecdn.net/202408/sys7763/message/19/17/UGMF9F9BVJ43KYZPDGMZ/a5e81134d848bb19eb1371a9869a7eb3/XKV6HB9AUNDJYDGEN9SW8NZYNRXEND4R29QPKGTX4QAKX2GG7CX28G9A5UTC6NGPGMWWXVYM3P8SV6M2A4YGWJ/dc/e6/58/41/00/1e23cebee25795a8e415afc3a1ae07da/6975b744_8482_4197_a3ae_baaadd663878.png"
                                                alt="">
                                        </div>
                                        <div>Nhận</div>
                                    </div>
                                </div>
                                <div class="achievement-overview-information">
                                    <div class="achievement-overview-text">
                                        <img width="40" src="https://cdn-icons-png.freepik.com/512/4504/4504177.png"
                                            alt="">
                                        <div class="d-flex justify-content-center">
                                            <span>20</span>
                                            <img width="20"
                                                src="https://data-gcdn.basecdn.net/202408/sys7763/message/19/17/UGMF9F9BVJ43KYZPDGMZ/a5e81134d848bb19eb1371a9869a7eb3/XKV6HB9AUNDJYDGEN9SW8NZYNRXEND4R29QPKGTX4QAKX2GG7CX28G9A5UTC6NGPGMWWXVYM3P8SV6M2A4YGWJ/dc/e6/58/41/00/1e23cebee25795a8e415afc3a1ae07da/6975b744_8482_4197_a3ae_baaadd663878.png"
                                                alt="">
                                        </div>
                                        <div>Tặng</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="score-history">
                                <div class="score-history-title">
                                    <h4>Lịch sử tích luỹ</h4>
                                    <button class="btn btn-primary btn-sm">Xem chi tiết</button>
                                </div>
                                <div class="card border-light mb-3" style="max-width: 18rem;">
                                    <div class="card-body">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Hoạt động</th>
                                                    <th scope="col" class="text-center">Điểm</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Bài tập/ kiểm tra</td>
                                                    <td class="text-center">4</td>
                                                </tr>
                                                <tr>
                                                    <td>Đổi điểm</td>
                                                    <td class="text-center">-1500</td>
                                                </tr>
                                                <tr>
                                                    <td>Nạp</td>
                                                    <td class="text-center">100</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="score-connection mt-5">
                                <h4>Kết nối</h4>
                                <div class="card border-light">
                                    <ul class="list-group list-group-light">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <img src="https://mdbootstrap.com/img/new/avatars/8.jpg"
                                                    class="rounded-circle" alt=""
                                                    style="width: 45px; height: 45px" />
                                                <div class="ms-3 ml-1">
                                                    <p class="fw-bold mb-1">Alex Ray</p>
                                                    <p class="text-muted mb-0">Online 15 phút trước</p>
                                                </div>
                                            </div>
                                            <span class="badge rounded-pill badge-primary">
                                                Tặng
                                                <img width="20"
                                                    src="https://data-gcdn.basecdn.net/202408/sys7763/message/19/17/UGMF9F9BVJ43KYZPDGMZ/a5e81134d848bb19eb1371a9869a7eb3/XKV6HB9AUNDJYDGEN9SW8NZYNRXEND4R29QPKGTX4QAKX2GG7CX28G9A5UTC6NGPGMWWXVYM3P8SV6M2A4YGWJ/dc/e6/58/41/00/1e23cebee25795a8e415afc3a1ae07da/6975b744_8482_4197_a3ae_baaadd663878.png"
                                                    alt="">
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <img src="https://mdbootstrap.com/img/new/avatars/6.jpg"
                                                    class="rounded-circle" alt=""
                                                    style="width: 45px; height: 45px" />
                                                <div class="ms-3 ml-1">
                                                    <p class="fw-bold mb-1">Alex Ray</p>
                                                    <p class="text-muted mb-0">Online</p>
                                                </div>
                                            </div>
                                            <span class="badge rounded-pill badge-primary">
                                                Tặng
                                                <img width="20"
                                                    src="https://data-gcdn.basecdn.net/202408/sys7763/message/19/17/UGMF9F9BVJ43KYZPDGMZ/a5e81134d848bb19eb1371a9869a7eb3/XKV6HB9AUNDJYDGEN9SW8NZYNRXEND4R29QPKGTX4QAKX2GG7CX28G9A5UTC6NGPGMWWXVYM3P8SV6M2A4YGWJ/dc/e6/58/41/00/1e23cebee25795a8e415afc3a1ae07da/6975b744_8482_4197_a3ae_baaadd663878.png"
                                                    alt="">
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <img src="https://mdbootstrap.com/img/new/avatars/6.jpg"
                                                    class="rounded-circle" alt=""
                                                    style="width: 45px; height: 45px" />
                                                <div class="ms-3 ml-1">
                                                    <p class="fw-bold mb-1">Alex Ray</p>
                                                    <p class="text-muted mb-0">Online</p>
                                                </div>
                                            </div>
                                            <span class="badge rounded-pill badge-primary">
                                                Tặng
                                                <img width="20"
                                                    src="https://data-gcdn.basecdn.net/202408/sys7763/message/19/17/UGMF9F9BVJ43KYZPDGMZ/a5e81134d848bb19eb1371a9869a7eb3/XKV6HB9AUNDJYDGEN9SW8NZYNRXEND4R29QPKGTX4QAKX2GG7CX28G9A5UTC6NGPGMWWXVYM3P8SV6M2A4YGWJ/dc/e6/58/41/00/1e23cebee25795a8e415afc3a1ae07da/6975b744_8482_4197_a3ae_baaadd663878.png"
                                                    alt="">
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <img src="https://mdbootstrap.com/img/new/avatars/6.jpg"
                                                    class="rounded-circle" alt=""
                                                    style="width: 45px; height: 45px" />
                                                <div class="ms-3 ml-1">
                                                    <p class="fw-bold mb-1">Alex Ray</p>
                                                    <p class="text-muted mb-0">Online</p>
                                                </div>
                                            </div>
                                            <span class="badge rounded-pill badge-primary">
                                                Tặng
                                                <img width="20"
                                                    src="https://data-gcdn.basecdn.net/202408/sys7763/message/19/17/UGMF9F9BVJ43KYZPDGMZ/a5e81134d848bb19eb1371a9869a7eb3/XKV6HB9AUNDJYDGEN9SW8NZYNRXEND4R29QPKGTX4QAKX2GG7CX28G9A5UTC6NGPGMWWXVYM3P8SV6M2A4YGWJ/dc/e6/58/41/00/1e23cebee25795a8e415afc3a1ae07da/6975b744_8482_4197_a3ae_baaadd663878.png"
                                                    alt="">
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="w-100">
                            <h4 class="mt-5">Ưu đãi về khoá học</h4>
                            <div class="score-redeem-rewards">
                                @foreach ($redeemed_series as $series)
                                    @php $is_payable = $total_point >= $series->redeem_point @endphp
                                    <div class="redeem-reward-item {{ $is_payable ? '' : 'gray-filter' }}">
                                        <div class="redeem-reward-img">
                                            <div class="dark-overlay">
                                                <div class="off-price-percent">Giảm {{ $series->redeemed_percent }}%</div>
                                            </div>
                                            <img src="{{ asset('/public/' . config('constant.series.upload_path') . $series->image) }}" alt="series image">
                                        </div>
                                        <div class="redeem-reward-info">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <span class="font-weight-bold fs-18 series-title">{{ $series->title }}</span>
                                                <div class="d-flex align-items-center">
                                                    <div class="redeem-reward-score font-weight-bold">
                                                        {{ $series->redeem_point }}
                                                    </div>
                                                    <img width="20" alt="hi-coin"
                                                        src="{{ asset('/public/assets/images/icons/hi-coin.png') }}">
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="font-weight-semibold fs-14">Giá:</span>
                                                <div class="d-flex align-items-center">
                                                    <span class="series-new-price ml-1">{{ formatCurrencyVND($series->cost - $series->redeemed_amount) }}</span>
                                                    <span class="series-old-price">{{ formatCurrencyVND($series->cost) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($is_payable)
                                            <div class="redeem-reward-btn">
                                                <a href="{{ url('payments/lms/' . $series->slug . '?is_redeemed=1') }}">Quy đổi</a>
                                            </div>
                                        @else
                                            <div class="redeem-reward-btn not-allowed">
                                                <a href="#">Quy đổi</a>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
</div>
