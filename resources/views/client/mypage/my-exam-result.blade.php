@extends('client.shared.mypage')

@section('mypage-styles')
    <link href="{{ asset('css/pages/mypage.css') }}" rel="stylesheet">
@endsection

@section('mypage-content')
    <div class="w-100 mx-auto overflow-x-auto">
        <table class="table table-bordered table-hover exam-result-table">
            <thead>
                <tr>
                    <th class="text-center">STT</th>
                    <th class="text-center">TÊN KỲ THI</th>
                    <th class="text-center">THỜI GIAN TIẾN HÀNH</th>
                    <th class="text-center">ĐIỂM SỐ</th>
                    <th class="text-center">TỔNG ĐIỂM</th>
                    <th class="text-center">ĐÁNH GIÁ</th>
                    <th class="text-center">CHỨNG NHẬN</th>
                    <th class="text-center">XEM CHI TIẾT</th>
                </tr>
            </thead>
            <tbody>
                @if ($results->count() > 0)
                    @php $page = request()->input('page', 1) - 1; @endphp
                    @foreach ($results as $index => $result)
                        <tr>
                            <td class="text-center">{{ $page * 15 + $index + 1 }}</td>
                            <td>{{ $result->title }}</td>
                            <td>{{ date_format(date_create($result->created_at),"d-m-Y") }}</td>
                            <td>{!! $result->detail !!}</td>
                            <td class="text-center align-middle">{!! $result->totalMark !!}</td>
                            <td class="text-center align-middle">{!! $result->ketqua !!}</td>
                            <td style="width: 10%;" class="text-center align-middle">
                                @if($result->status == 1)
                                    <button type="button" class="btn btn-light text-light btn-certificate"
                                        data-category-id="{{$result->category_id}}"
                                        data-quiz-1-total="{{$result->quiz_1_total}}"
                                        data-quiz-2-total="{{$result->quiz_2_total}}"
                                        data-quiz-3-total="{{$result->quiz_3_total}}"
                                        data-total-marks="{{$result->total_marks}}">
                                        <img width="35" height="35" src="{{ asset('images/icons/Icon-reward.svg') }}" />
                                    </button>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <button type="button" class="btn btn-primary" onclick="loadDetailResultExam('{{$result->id}}', '{{ $result->title }}')" data-toggle="modal" data-target="#series_detail_modal_">
                                    Chi tiết
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">
                            <h5 style="color: #ee2833!important" class="mb-0">Bạn chưa có bài thi nào</h5>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="mt-4">
            @component('client.components.custom-pagination',
                ['paginations' => $results])
            @endcomponent
        </div>
    </div>
    @include('client.components.certification-modal')
    @include('client.components.common-modal')
@endsection
@section('mypage-scripts')
    <script>
        $('.btn-certificate').on('click', function(event){
            console.log('click');
            const currentTarget = event.currentTarget;
            const categoryId = Number($(currentTarget).data('category-id')) || 0;
            const quiz1Total = $(currentTarget).data('quiz-1-total');
            const quiz2Total = $(currentTarget).data('quiz-2-total');
            const quiz3Total = $(currentTarget).data('quiz-3-total');
            const totalMarks = $(currentTarget).data('total-marks');
            $('#label-level').text(`N${categoryId}`);
            if(categoryId <= 3)
            {
                $('#label-vocabulary').text(`${quiz1Total}/60`);
                $('#label-reading').removeClass('d-none');
                $('#td-reading').removeClass('d-none');
                $('#td-colspan').attr('colspan', '3');
                $('#label-reading').text(`${quiz2Total}/60`);
                $('#label-listening').text(`${quiz3Total}/60`);

            } else {
                $('#label-vocabulary').text(`${quiz1Total}/120`);
                $('#label-reading').addClass('d-none');
                $('#td-reading').addClass('d-none');
                $('#td-colspan').attr('colspan', '2');
                $('#label-listening').text(`${quiz3Total}/60`);
            }
            $('#label-total').text(`${totalMarks}/180`);
            $('#certificationModal').modal('toggle');
        });

        /**
         * Displays the detailed exam result in a modal.
         *
         * @param {number} id - The ID of the exam whose details are to be displayed.
         * @param {string} title - The title to be set for the modal.
         *
         */
        function loadDetailResultExam(id, title) {
            const modal = $('#common_modal');
            const modalTitle = $('#common_modal_title');
            const modalBody = $('#common_modal_body');

            modal.modal('show');
            modalTitle.text(title);
            if (!modal.find('.modal-dialog').hasClass('modal-lg')) {
                modal.find('.modal-dialog').addClass('modal-lg');
            }
            $.ajax({
                url: "{{ route('exam-categories.result.detail', ['id' => '__id__']) }}".replace('__id__', id),
                type: 'GET',
                data: {
                    id: id
                },
                success: function(response) {
                    modalBody.html(response.view_name);
                    initializeComponents();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Có lỗi xảy ra:', textStatus, errorThrown);
                }
            });
        }
    </script>
@endsection