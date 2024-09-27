
@extends($layout)
<style>
	.subject-exam {
		position: relative;
	}
	.subject-exam .score-badge {
		position: absolute;	
		right: 20px;
	}
	.lesson-exam {
		position: relative;
	}
	.lesson-exam .score-badge {
		position: absolute !important;	
		right: 30px;
	}

	.card .fa-chevron-down {
		height: 1rem;
		transition: transform 0.3s ease;
	}

	.rotate-up {
		transform: rotate(-180deg);
	}

	.rotate-down {
		transform: rotate(0deg);
	}
</style>
@section('content')
<div class="card mb-0">
    <div class="card-header">
        <h3 class="card-title">
            {{$title}}
        </h3>
    </div>
    <div class="card-body">
        <div class="manged-ad table-responsive border-top userprof-tab">
		<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th class="text-center align-middle" style="width: 5%;">
							STT
						</th>
						<th>
							Tên kỳ thi
						</th>
						<th>
							Thời gian tiến hành
						</th>
						<th>
							Điểm số
						</th>
						<th>
							Đánh giá
						</th>
						<th style="width: 10%;">
							Chứng nhận
						</th>
						<th style="width: 20%;">
							Xem chi tiết
						</th>
					</tr>
				</thead>
				<tbody>

				@if(count($results) > 0)

					@foreach($results as $r)
				<tr>
					<td class="text-center align-middle">
						{{$loop->index+1}}
					</td>
					<td>
						{{$r->title}}
					</td>
					<td>
						{{date_format(date_create($r->created_at),"d-m-Y")}}
					</td>
					<td>
						<!-- {{ $r->quiz_1_total }} {{$r->quiz_2_total}} {{$r->quiz_3_total}} <br>
						{{ $r->total_marks }} / 180 -->
						{!!$r->detail !!}
					</td>
					<td>
						<!-- @if($r->status == 1)
							<p class="text-success">Đạt</p>
						@else
							<p class="text-danger">Chưa đạt</p>
						@endif -->
						{!!$r->ketqua !!}
					</td>
					<td style="width: 10%;">
					@if($r->status == 1)
						<button type="button" class="btn btn-success btn-certificate" 
							data-category-id="{{$r->category_id}}" 
							data-quiz-1-total="{{$r->quiz_1_total}}" 
							data-quiz-2-total="{{$r->quiz_2_total}}" 
							data-quiz-3-total="{{$r->quiz_3_total}}" 
							data-total-marks="{{$r->total_marks}}">
							<!-- <i class="fa fa-certificate"></i> -->
							<img width="20" height="20" src="{{env('CER_PATH')}}" />
						</button>
					@endif
					</td>
					<td style="width: 10%;" class="text-center align-middle">
						<button type="button" class="btn btn-primary" onclick="loadDetailResultExam('{{$r->id}}', '{{ $r->title }}')" data-toggle="modal" data-target="#series_detail_modal_">
							Chi tiết
						</button>
					</td>
				</tr>
				@endforeach

				@else
				<tr>
					<td colspan="6">
						<h5 style="color: #ee2833!important">
							Không có dữ liệu kết quả thi
						</h5>
					</td>
				</tr>
				@endif

				</tbody>
			</table>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
	<div class="modal-body">
	<div class="panel-body" style="padding: 30px" class="chungchi">
		<div class="text-center">
			<div class="text-center"><img src="/public/uploads/settings/logo-elearning.png" alt="logo" class="cs-logo" style="width: 140px;"></div>
			<div class="text-center">
				<h4>HIKARI ACADEMY 日本語試験</h4>
				<h4>認定結果及び成績に関する証明書</h4>
			</div>
			<div class="text-center">
				<h4>HIKARI ACADEMY TEST</h4>
				<h4>CERTIFICATE OF RESULT AND SCORES</h4>
			</div>
			<div class="text-center">
				<h5>HIKARI ACADEMY 株式会社が2021年06月20 日に実施した日本語試験に関し、</h5>
				<h5>認定結果及び成績を次のとおり証明します。</h5>
			</div>
			<div class="text-center">
				<h5>This is to certify the result and the scores of Hikari Academy - Japanese Test</h5>
				<h5>given on Jun 20, 2021 administered by Hikari Academy</h5>
			</div>
		</div>
		<table class="table table-bordered" style="width: 100%; margin-top: 60px" id="table-result">
			<tbody>
			<tr>
				<td>氏名&nbsp;Name</td>
				<td>{{$user->name}}</td>
			</tr>
			<tr>
				<td>生年月日&nbsp;Date of Birth (dimly)</td>
				<td></td>
			</tr>
			<tr>
				<td>件所&nbsp;Address</td>
				<td>{{$user->address}}</td>
			</tr>
			</tbody>
		</table>
		<table class="table table-bordered" style="width: 100%; margin-top: 40px" id="table-result">
			<tbody>
			<tr>
				<td>レべル&nbsp;Level</td>
				<td id="label-level"></td>
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
		<table class="table table-bordered" style="width: 100%; margin-top: 40px; text-align: center;" id="table-ketqua">
			<tbody>
				<tr>
					<td id="td-colspan" colspan="3">得点区分別得点<br>Scores by Scoring Section</td>
					<td rowspan="2">総合得点 <br/>Total scores</td>
				</tr>
				<tr>
					<td>言語知識（文字・語業・文法）由 <br/> Language Knowledge <br/>(Vocabulary・Grammar)</td>
					<td id="td-reading">読解 <br/>Reading</td>
					<td>聴解 <br/>Listening</td>
				</tr>
				<tr>
					<td id="label-vocabulary"></td>
					<td id="label-reading"></td>
					<td id="label-listening"></td>
					<td id="label-total"></td>
				</tr>
			</tbody>
		</table>
		<table class="table table-borderless text-right" style="width: 100%; margin-top: 60px" id="">
			<tbody>
				<tr>
					<td style="border-top: none; font-size: 12px">主催者</td>
				</tr>
				<tr>
					<td style="border-top: none; font-size: 12px">Administrator</td>
				</tr>
			</tbody>
		</table>
		<table class="table table-borderless text-right" style="width: 100%; margin-top: 160px" id="">
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
@include('admin.common.common-modal')

@endsection
@section('footer_scripts')
<script>
	// Handle event extend
	$('.btn-certificate').on('click', function(event){
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
		$('#myModal').modal('toggle');
	})

	const modal = $('#common_modal');
	const modalTitle = $('#common_modal_title');
	const modalBody = $('#common_modal_body');

	/**
	 * Initializes components and sets up event handlers for collapsible elements.
	 */
	function initializeComponents() {
		$('#accordion').on('shown.bs.collapse hidden.bs.collapse', function (event) {
			const icon = $(event.target).closest('.card').find('.fa-chevron-down');
			if (event.type === 'shown') {
				icon.removeClass('rotate-down').addClass('rotate-up');
			} else if (event.type === 'hidden') {
				icon.removeClass('rotate-up').addClass('rotate-down');
			}
		});
	}

	/**
	 * Displays the detailed exam result in a modal.
	 *
	 * @param {number} id - The ID of the exam whose details are to be displayed.
	 * @param {string} title - The title to be set for the modal.
	 *
	 */
	function loadDetailResultExam(id, title) {
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

