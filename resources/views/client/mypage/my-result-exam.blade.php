@extends('client.shared.mypage')

@section('styles')
    <link href="{{ asset('css/pages/mypage.css') }}" rel="stylesheet">
@endsection

@section('mypage-content')
    <div class="container">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th class="text-center">STT</th>
                    <th class="text-center">TÊN KỲ THI</th>
                    <th class="text-center">THỜI GIAN TIẾN HÀNH</th>
                    <th class="text-center">ĐIỂM SỐ</th>
                    <th class="text-center">ĐÁNH GIÁ</th>
                    <th class="text-center">CHỨNG NHẬN</th>
                    <th class="text-center">XEM CHI TIẾT</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td class="text-center">Bộ đề thi thử N5-12</td>
                    <td class="text-center">26-08-2024</td>
                    <td>
                        <div>言語知識（文字・語彙・文法）：<span class="badge bg-info">120</span></div>
                        <div>聴解：<span class="badge bg-info">120</span></div>
                    </td>
                    <td class="text-center align-middle"><span class="badge bg-success">Đạt</span></td>
                    <td class="text-center">
                        <button class="btn btn-light text-light" data-bs-toggle="modal" href="#exampleModalToggle"
                            role="button">
                            <img height="30" src="{{ asset('images/icons/Icon-reward.svg') }}" alt=""
                                srcset="">
                        </button>
                    </td>
                    <td class="text-center align-middle"><button class="btn btn-primary">Chi tiết</button></td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td class="text-center">Bộ đề thi thử N4-01</td>
                    <td class="text-center">16-08-2024</td>
                    <td>
                        <div>言語知識（文字・語彙・文法）：<span class="badge bg-warning">60</span></div>
                        <div>聴解：<span class="badge bg-danger">22</span></div>
                    </td>
                    <td class="text-center align-middle"><span class="badge bg-warning text-dark">Chưa đạt</span></td>
                    <td></td>
                    <td class="text-center align-middle"><button class="btn btn-primary">Chi tiết</button></td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td class="text-center">Bộ đề thi thử N1-01</td>
                    <td class="text-center">16-08-2024</td>
                    <td>
                        <div>言語知識（文字・語彙・文法）：<span class="badge bg-danger">0</span></div>
                        <div>読解：<span class="badge bg-danger">0</span></div>
                        <div>聴解：<span class="badge bg-danger">0</span></div>
                    </td>
                    <td class="text-center align-middle"><span class="badge bg-danger">Chưa hoàn thành</span></td>
                    <td class="text-center"></td>
                    <td class="text-center align-middle"><button class="btn btn-primary">Chi tiết</button></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="panel-body p-4">
                        <div class="text-center">
                            <img src="{{ asset('images/Logo-hikari.png') }}" alt="logo" class="cs-logo" style="width: 140px;">
                        </div>
                        <div class="text-center">
                            <h4>HIKARI ACADEMY 日本語試験</h4>
                            <h4>認定結果及び成績に関する証明書</h4>
                        </div>
                        <div class="text-center">
                            <h4>HIKARI ACADEMY TEST</h4>
                            <h4>CERTIFICATE OF RESULT AND SCORES</h4>
                        </div>
                        <div class="text-center">
                            <h5>HIKARI ACADEMY 株式会社が2021年06月20日に実施した日本語試験に関し、</h5>
                            <h5>認定結果及び成績を次のとおり証明します。</h5>
                        </div>
                        <div class="text-center">
                            <h5>This is to certify the result and the scores of Hikari Academy - Japanese Test</h5>
                            <h5>given on Jun 20, 2021 administered by Hikari Academy</h5>
                        </div>
                        <table class="table table-bordered w-100 mt-5" id="table-result">
                            <tbody>
                                <tr>
                                    <td>氏名&nbsp;Name</td>
                                    <td>Nguyễn Tất Vũu</td>
                                </tr>
                                <tr>
                                    <td>生年月日&nbsp;Date of Birth</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>件所&nbsp;Address</td>
                                    <td>TPHCM</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered w-100 mt-4" id="table-result">
                            <tbody>
                                <tr>
                                    <td>レべル&nbsp;Level</td>
                                    <td id="label-level">N5</td>
                                </tr>
                                <tr>
                                    <td>結果&nbsp;Result</td>
                                    <td>合格&nbsp;PASSED</td>
                                </tr>
                                <tr>
                                    <td>受験地&nbsp;Test site</td>
                                    <td>https://elearning.hikariacademy.edu.vn/</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered w-100 mt-4 text-center" id="table-ketqua">
                            <tbody>
                                <tr>
                                    <td colspan="2">得点区分別得点<br>Scores by Scoring Section</td>
                                    <td rowspan="2">総合得点 <br>Total scores</td>
                                </tr>
                                <tr>
                                    <td>言語知識（文字・語業・文法）由 <br> Language Knowledge <br>(Vocabulary・Grammar)</td>
                                    <td id="td-reading" class="d-none">読解 <br>Reading</td>
                                    <td>聴解 <br>Listening</td>
                                </tr>
                                <tr>
                                    <td id="label-vocabulary">56/120</td>
                                    <td id="label-reading" class="d-none"></td>
                                    <td id="label-listening">24/60</td>
                                    <td id="label-total">80/180</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-borderless text-end w-100 mt-5">
                            <tbody>
                                <tr>
                                    <td style="border-top: none; font-size: 12px">主催者</td>
                                </tr>
                                <tr>
                                    <td style="border-top: none; font-size: 12px">Administrator</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-borderless text-end w-100 mt-5">
                            <tbody>
                                <tr>
                                    <td style="border-top: none; font-size: 12px">Hikari Academy 株式会社</td>
                                </tr>
                                <tr>
                                    <td style="border-top: none; font-size: 12px">Hikari Academy Joint Stock Company</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
