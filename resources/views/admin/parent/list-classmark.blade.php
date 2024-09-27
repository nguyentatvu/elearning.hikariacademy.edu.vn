@extends($layout)
@section('header_scripts')
<link href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css" rel="stylesheet">
<style>
    .modal-header {
        display: flex;
        justify-content: space-between;
    }

    .modal-header::before {
        display: none;
    }

    .modal-header::after {
        display: none;
    }

    .btn-review_yet {
        background-color: #438afe;
        color: #fff;
        font-size: 12px;
    }

    .btn-review_done {
        background-color: #f16a43;
        color: white;
        font-size: 12px;
    }

    form {
        margin-top: 1rem;
    }

    .is-invalid {
        border-color: #dc3545;
    }
</style>
@stop
@section('content')
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{url('/')}}"><i class="mdi mdi-home"></i></a> </li>
							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>
								
				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						
						<div class="pull-right messages-buttons">
							 
							<a href="/parent/class" class="btn  btn-primary button" >Danh sách lớp</a>
						</div>
						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">
						<div > 
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>
								 	<th width="30%">Họ Tên </th>
									<th width="20%">Điểm thi</th>
									<th width="10%">Tổng điểm</th>
									<th width="10%">Kết quả</th>
                                    <th width="5%">{{ getPhrase('review')}}</th>
								</tr>
							</thead>
						</table>
						</div>
					</div>

				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
        {{-- Exam review modal --}}
        <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reviewModalLabel">Đánh giá bài thi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Hủy</button>
                        <button type="button" onclick="postQuizReview()" class="btn btn-success">Gửi</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- End exam review modal --}}
@endsection
 
<?php $url = URL_PARENT_CLASSMARK_GETLIST.$slug.'/'.$slug_exam.'/'.$slug_category; ?>
@section('footer_scripts')
    @php
		// MAKE DEFAULT VALUE COLUMN
		$defaultColumns = [
			'name', 'quiz_1_total', 'total_marks', 'finish', 'review'
		];
	@endphp
    @include('admin.common.datatables', array('route'=>$url, 'route_as_url' => TRUE, 'pdf'=>'0,1,2,3', 'table_columns' => $defaultColumns))
    <script>
        const teacher_id = {{ Auth::user()->id }};
        let currentReviewButton = null;
        let currentResultId = null;

        const openReviewModal = (quiz_result_id) => {
            currentReviewButton = $(event.target).closest('button');
            currentResultId = quiz_result_id;
            const modalBody = $('#reviewModal').find('.modal-body');
            const route = '{{ route('exam.quiz-review', ':quiz_result_id') }}';
            const url = route.replace(':quiz_result_id', quiz_result_id);

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                type: "GET",
                url,
                datatype: 'json',
                success: function(data) {
                    modalBody.html(data.html);
                },
                error: function(data) {
                    swal({
                        title: 'Thông báo',
                        text: 'Lỗi khi lấy ra đánh giá của giáo viên!',
                        type: 'error',
                        showConfirmButton: false,
                        showCancelButton: false,
                        timer: 2000,
                    });
                }
            });
        }

        const changeReviewButton = () => {
            if (currentReviewButton == null || currentResultId == null) return;

            $(currentReviewButton).replaceWith(`
                <button class="btn btn-review_done mb-3 mb-xl-0" data-toggle="modal" data-target="#reviewModal"
                    onclick="openReviewModal('${currentResultId}')">
                    <i class="fa fa-check"></i> Đã đánh giá
                </button>
            `);
        }

        const checkRequiredReview = () => {
            const reviewContent = $('#reviewModal').find('textarea[name="review"]');

            if (reviewContent.val() == '') {
                reviewContent.addClass('is-invalid');
                swal({
                    title: 'Thể báo',
                    text: 'Vui lòng nhập đánh giá bài thi!',
                    type: 'error',
                    showConfirmButton: false,
                    showCancelButton: false,
                    timer: 2000,
                });
                return false;
            }

            return true;
        }

        const postQuizReview = () => {
            if (checkRequiredReview() == false) return;

            const data = {
                quiz_result_id: $('#reviewForm').find('input[name="quiz_result_id"]').val(),
                student_id: $('#reviewForm').find('input[name="student_id"]').val(),
                teacher_id: $('#reviewForm').find('input[name="teacher_id"]').val(),
                review: $('#reviewForm').find('textarea[name="review"]').val(),
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                type: "POST",
                url: "{{ route('exam.post-quiz-review') }}",
                data: data,
                datatype: 'json',
                beforeSend: function() {
                    swal({
                        html: true,
                        title: 'Đang xử lý vui lòng chờ',
                        text: '<img style="position: relative;" src="/public/assets/images/loader.svg" class="loader-img" alt="img">',
                        type: '',
                        showConfirmButton: false,
                        showCancelButton: false,
                    });
                },
                success: function(data) {
                    changeReviewButton();
                    swal({
                        title: 'Thông báo',
                        text: 'Thành công',
                        type: 'success',
                        showConfirmButton: false,
                        showCancelButton: false,
                        timer: 2000,
                    });
                },
                error: function(data) {
                    swal({
                        title: 'Thông báo',
                        text: 'Thông tin nhập liệu không hợp lệ!',
                        type: 'error',
                        showConfirmButton: false,
                        showCancelButton: false,
                        timer: 2000,
                    });
                }
            });
        }
    </script>
@stop
