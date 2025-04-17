<div class="modal fade" id="errorInstructionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Hướng dẫn import mục lục khoá học</h4>
            </div>
            <div class="modal-body px-8">
                <div class="">
                    <div class="section-title">1. Tình trạng hiện tại</div>
                    <p>Không có thay đổi nào về logic xử lý phần import mục lục.</p>
                    <p>Tình trạng không import được là do file Excel không đúng với định dạng hệ thống yêu cầu từ trước.
                    </p>

                    <div class="section-title">2. Logic xử lý import hiện tại trong file Excel</div>
                    <p>Hệ thống chỉ import những dòng có đủ cả 2 cột sau:</p>
                    <ul>
                        <li><strong>Cột C</strong>: Tên bài học</li>
                        <li><strong>Cột E</strong>: Loại bài học (type)</li>
                    </ul>
                    <p class="note">⮕ Những dòng không có đủ hai cột trên sẽ bị bỏ qua và không được import vào hệ
                        thống.</p>

                    <div class="section-title">3. Bảng giá trị loại bài học</div>
                    <table class="table table-bordered value-table">
                        <thead>
                            <tr>
                                <th>Giá trị</th>
                                <th>Loại bài học</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>0</td>
                                <td>menu</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>từ vựng</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>bài học (cấu trúc)</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>bài tập</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>bài tập toàn bài</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>bài test</td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>hán tự</td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>bài ôn tập</td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>sub menu</td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>giới thiệu</td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>flashcard</td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>luyện viết</td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>luyện phát âm</td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>bài test giao thông</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="section-title">4. Phương án xử lý & hướng dẫn chuẩn bị file đúng</div>
                    <p>Chuẩn bị file theo đúng định dạng với 2 cột:</p>
                    <ul>
                        <li><strong>Cột C</strong>: Nhập tên menu, sub menu hoặc tên bài học.</li>
                        <li><strong>Cột E</strong>: Nhập mã số phân loại theo bảng giá trị ở trên.</li>
                    </ul>
                    <p class="note">Hệ thống không đọc được file nếu thiếu hoặc sai định dạng ở 2 cột này.</p>
                    <p>Tham khảo file mẫu tại: <a
                            href="https://docs.google.com/spreadsheets/d/1bEXKXIDKVMEOo13DmMV38NDYxwqf52ceyoz4tfXCA84/edit?gid=1748908576#gid=1748908576"
                            target="_blank">Tài liệu mẫu Excel</a></p>

                    <div class="section-title">5. Ví dụ nội dung file Excel cần chuẩn bị</div>
                    <table class="table table-bordered example-table">
                        <thead>
                            <tr>
                                <th>Cột C (Tên bài học)</th>
                                <th>Cột E (Loại bài học)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Bài 26:「ごみはどこに出したらいいですか</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>II. Ngữ pháp</td>
                                <td>8</td>
                            </tr>
                            <tr>
                                <td>I. Từ vựng</td>
                                <td>8</td>
                            </tr>
                            <tr>
                                <td>1. Từ vựng bài 26</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>II. Ngữ pháp</td>
                                <td>8</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>