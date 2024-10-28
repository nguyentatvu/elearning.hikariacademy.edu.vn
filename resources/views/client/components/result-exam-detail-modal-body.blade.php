    @php
        use App\Enums\JapaneseProficiencyLevel;
        // Example data
        $results = $data['results']; // Assuming $data['results'] is available and populated

        // Predefined proficiency levels (though using the enum constants is preferred)
        $N1 = JapaneseProficiencyLevel::N1;
        $N2 = JapaneseProficiencyLevel::N2;
        $N3 = JapaneseProficiencyLevel::N3;
        $N4 = JapaneseProficiencyLevel::N4;
        $N5 = JapaneseProficiencyLevel::N5;

        $quiz1Analysis = $data['quiz_1_analysis'];
        $quiz2Analysis = $data['quiz_2_analysis'];
        $quiz3Analysis = $data['quiz_3_analysis'];
        $evaluation = $data['results']->evaluation;
        $badgeClass = $evaluation == 0 ? 'warning' : ($evaluation == 1 ? 'success' : 'danger');
        $badgeText = $evaluation == 0 ? 'Chưa hoàn thành' : ($evaluation == 1 ? 'Hoàn thành' : 'Không đạt');

        function getBadgeClass($totalScore, $maxScore)
        {
            $halfScore = $maxScore / 2;
            $eightyPercentScore = $maxScore * 0.8;

            if ($totalScore <= $halfScore) {
                return 'bg-danger';
            } elseif ($totalScore > $eightyPercentScore) {
                return 'bg-success';
            } else {
                return 'bg-warning';
            }
        }
        $quiz1BadgeClass = $quiz1Analysis
            ? getBadgeClass($results->quiz_1_total, $results->category_id > $N3 ? 120 : 60)
            : 'bg-warning';
        $quiz2BadgeClass = $quiz2Analysis ? getBadgeClass($results->quiz_2_total, 60) : 'bg-warning';
        $quiz3BadgeClass = $quiz3Analysis ? getBadgeClass($results->quiz_3_total, 60) : 'bg-warning';
    @endphp
    <div>
        <h5 class="font-weight-bold">Thông tin chi tiết</h5>
        <p class="d-flex justify-contents-center align-items-center">
            <span class="">Tổng điểm:&ensp;</span>
            <span class="badge bg-danger score-badge">{{ $results->total_marks }}/180</span>
        </p>
        <p>Thời gian tiến hành:&ensp;
            <span class="fw-bold">{{ date_format(date_create($results->created_at), 'd-m-Y') }}</span>
        </p>
        <p>
            Đánh giá:&ensp;<span class="badge bg-{{ $badgeClass }} score-badge">{{ $badgeText }}</span>
        </p>
        <p>Chứng nhận</p>
    </div>
    <h5 class="font-weight-bold mt-2">Danh sách các bài học</h5>
    <div class="list-result-exam">
        <div id="accordion">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center" id="headingIncompetent"
                    data-bs-toggle="collapse" data-bs-target="#collapseIncompetent" aria-expanded="false"
                    aria-controls="collapseIncompetent" role="button">
                    <h5 class="mb-0">
                        <div>言語知識（文字・語彙・文法）</div>
                        @if ($results->category_id < $N3)
                            <div class="mt-2">{{ $results->category_id != $N3 ? '読解' : '' }}</div>
                        @endif
                    </h5>
                    <div class="d-flex">
                        <div>
                            <div>
                                <span class="badge {{ $quiz1BadgeClass }} score-badge">
                                    {{ $quiz1Analysis ? ($results->category_id > 3 ? $results->quiz_1_total . '/120' : $results->quiz_1_total . '/60') : 'Chưa thi' }}
                                </span>
                            </div>
                            @if ($results->category_id < $N3)
                                <div class="mt-2">
                                    <span class="badge {{ $quiz1BadgeClass }} score-badge">
                                        {{ $quiz1Analysis ? $results->quiz_2_total . '/60' : 'Chưa thi' }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <i class="bi bi-chevron-down ms-2 @if ($results->category_id < $N3) mt-4 @endif"
                            aria-hidden="true"></i>
                    </div>
                </div>
                @if ($quiz1Analysis)
                    <div id="collapseIncompetent" class="collapse" aria-labelledby="headingIncompetent">
                        <ul class="list-group square-border">
                            @foreach ($quiz1Analysis as $subjectId => $analysis)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong></strong> {{ $analysis['subject_title'] }}<br>
                                        <strong>Số Câu Trả Lời Đúng:</strong> {{ $analysis['correct_answers'] }}<br>
                                        <strong>Số Câu Trả Lời Sai:</strong> {{ $analysis['wrong_answers'] }}<br>
                                        <strong>Số Câu Chưa Trả Lời:</strong> {{ $analysis['not_answered'] }}<br>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            @if ($results->category_id == $N3)
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center" id="headingReading"
                        data-bs-toggle="collapse" data-bs-target="#collapseReading" aria-expanded="false"
                        aria-controls="collapseReading" role="button">
                        <h5 class="mb-0">
                            読解
                        </h5>
                        <div>
                            <span
                                class="badge {{ $quiz2BadgeClass }} score-badge">{{ $quiz2Analysis ? $results->quiz_2_total . '/60' : 'Chưa thi' }}</span>
                            <i class="bi bi-chevron-down ms-2" aria-hidden="true"></i>
                        </div>
                    </div>
                    @if ($quiz2Analysis)
                        <div id="collapseReading" class="collapse" aria-labelledby="headingReading">
                            <ul class="list-group square-border">
                                @foreach ($quiz2Analysis as $subjectId => $analysis)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong></strong> {{ $analysis['subject_title'] }}<br>
                                            <strong>Số Câu Trả Lời Đúng:</strong>
                                            {{ $analysis['correct_answers'] }}<br>
                                            <strong>Số Câu Trả Lời Sai:</strong> {{ $analysis['wrong_answers'] }}<br>
                                            <strong>Số Câu Chưa Trả Lời:</strong> {{ $analysis['not_answered'] }}<br>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @endif
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center" id="headingListening"
                    data-bs-toggle="collapse" data-bs-target="#collapseListening" aria-expanded="false"
                    aria-controls="collapseListening" role="button">
                    <h5 class="mb-0">
                        聴解
                    </h5>
                    <div>
                        <span
                            class="badge {{ $quiz3BadgeClass }} score-badge">{{ $quiz3Analysis ? $results->quiz_3_total . '/60' : 'Chưa thi' }}</span>
                        <i class="bi bi-chevron-down ms-2" aria-hidden="true"></i>
                    </div>
                </div>
                @if ($quiz3Analysis)
                    <div id="collapseListening" class="collapse" aria-labelledby="headingListening">
                        <ul class="list-group square-border">
                            @foreach ($quiz3Analysis as $subjectId => $analysis)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong></strong> {{ $analysis['subject_title'] }}<br>
                                        <strong>Số Câu Trả Lời Đúng:</strong> {{ $analysis['correct_answers'] }}<br>
                                        <strong>Số Câu Trả Lời Sai:</strong> {{ $analysis['wrong_answers'] }}<br>
                                        <strong>Số Câu Chưa Trả Lời:</strong> {{ $analysis['not_answered'] }}<br>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div>
        @if ($data['quiz_result_review'] != null)
            <h5 class="card-link">Đánh giá của giảng viên</h5>
            <h4 class="mt-0 font-weight-bold d-inline">
                {{ $data['quiz_result_review']->teacher->name }}
                <span class="fs-14 ml-0" data-toggle="tooltip" data-placement="top" title="" data-original-title="verified">
                    <i class="bi bi-check-lg text-success"></i>
                </span>
            </h4>
            <textarea readonly class="form-control" name="" id="" cols="30" rows="5">{{ $data['quiz_result_review']->review }}</textarea>
        @else
            <h5 class="card-link">Đánh giá của giảng viên</h5>
            <textarea readonly class="form-control no-answer" name="" id="" cols="30" rows="5">Chưa có đánh giá!</textarea>
        @endif
    </div>
