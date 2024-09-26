@extends('client.shared.mypage')

@section('styles')
    <link href="{{ asset('css/pages/mypage.css') }}" rel="stylesheet">
@endsection

@section('mypage-content')
    <div class="container">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" scope="col">STT</th>
                        <th class="text-center" scope="col">CÂU HỎI CỦA BẠN</th>
                        <th class="text-center" scope="col">BÀI HỌC</th>
                        <th class="text-center" scope="col">THỜI GIAN</th>
                        <th class="text-center" scope="col">TRẠNG THÁI</th>
                        <th class="text-center" scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>12</td>
                        <td>[Testing] Khóa học N2 確認問題 (2) (解説)</td>
                        <td>22-08-2024 22:08:51</td>
                        <td class="align-middle text-center">
                            <span class="badge bg-success">Đã xem</span>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm ms-2"><i class="fas fa-comment"></i> Xem chi tiết</button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>nhgdhgmda</td>
                        <td>Khóa học N5 1. Hán tự bài 21</td>
                        <td>22-08-2024 22:08:51</td>
                        <td class="align-middle text-center">
                            <span class="badge bg-success">Đã xem</span>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm ms-2"><i class="fas fa-comment"></i> Xem chi tiết</button>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>abc</td>
                        <td>Khóa học N5 Bài kiểm tra 1</td>
                        <td>16-08-2024 15:08:47</td>
                        <td class="align-middle text-center">
                            <span class="badge bg-success">Đã xem</span>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm ms-2"><i class="fas fa-comment"></i> Xem chi tiết</button>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>abc</td>
                        <td>Khóa học N5 Bài kiểm tra 1</td>
                        <td>16-08-2024 15:08:47</td>
                        <td class="align-middle text-center">
                            <span class="badge bg-success">Đã xem</span>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm ms-2"><i class="fas fa-comment"></i> Xem chi tiết</button>
                        </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>abc afs 123</td>
                        <td>Khóa học N5 Bài kiểm tra 1</td>
                        <td>16-08-2024 15:08:47</td>
                        <td class="align-middle text-center">
                            <span class="badge bg-success">Đã xem</span>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm ms-2"><i class="fas fa-comment"></i> Xem chi tiết</button>
                        </td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>abc afs 123</td>
                        <td>Khóa học N5 Bài kiểm tra 1</td>
                        <td>16-08-2024 15:08:47</td>
                        <td class="align-middle text-center">
                            <span class="badge bg-success">Đã xem</span>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm ms-2"><i class="fas fa-comment"></i> Xem chi tiết</button>
                        </td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>nhgdhgmda</td>
                        <td>Khóa học N5 1. Hán tự bài 21</td>
                        <td>09-09-2024 16:09:06</td>
                        <td class="align-middle text-center">
                            <span class="badge bg-info">Giáo viên đã trả lời</span>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm ms-2"><i class="fas fa-comment"></i> Xem chi tiết</button>
                        </td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>1</td>
                        <td>[Testing] Khóa học N2 確認問題 (2) (解説)</td>
                        <td>09-09-2024 16:09:06</td>
                        <td class="align-middle text-center">
                            <span class="badge bg-info">Giáo viên đã trả lời</span>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm ms-2"><i class="fas fa-comment"></i> Xem chi tiết</button>
                        </td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td>test</td>
                        <td>Khóa học N5 6. Cấu trúc 6: 「これは[giá tiền]です。」</td>
                        <td>19-08-2024 09:08:59</td>
                        <td class="align-middle text-center">
                            <span class="badge bg-info">Giáo viên đã trả lời</span>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm ms-2"><i class="fas fa-comment"></i> Xem chi tiết</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
