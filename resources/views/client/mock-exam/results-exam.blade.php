@extends('client.app-exam')
@section('header_scripts')
@stop
@section('content')
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="" style="margin-top: 40px; min-height: 700px;">
	<div class="panel-body">
			<div class="panel-body no-padding" style="padding-bottom: 60px;">
				<div class="row">
					<div class="col-sm-12">
						<div class="alert alert-success" id="exam-alert-success" role="alert" style="width: 100%; margin: 0 auto; padding: 15px 0px"><h3 style="text-align: center; color: #185181;">Bạn đã hoàn thành bài thi</h3></div>
					</div>
				</div>
				<br/>
				<div class="row" id="btt_continue">
					<div class="col-lg-12 text-lef6">
						<?php if ($finish == 1) { ?>
								<style type="text/css">
								.form-danhgia .form-group {
									margin-bottom: 10px;
								}
								.form-danhgia .form-group > label {
									font-size: 15px;
									font-weight: 500;
									color: #44a1ef;
								}
								a.btn.btn-primary.button-submit-danhgia {
								    font-size: 16px;
								    font-weight: 500;
								    border-radius: 20px;
								}
								.result-info-user {
									font-size: 16px;
								}
								#table-result span.label {
									font-size: 14px;
								}
								.color-red {
									color: red;
								}
								.form-danhgia .col-md-2 {
									width: 20%;
								}
								.form-danhgia input[type="radio"] + label span.fa-stack, .form-danhgia input[type="checkbox"] + label span.fa-stack {
								    margin: 0 5px 0 0;
								}
							</style>
							<div class="panel panel-info panel-danhgia" style="width: 100%; margin: 0 auto;" id="panel-danhgia">
								<div class="panel-heading" style="padding: 6px 15px;">
									<h4>Bạn vui lòng đánh giá và xem kết quả</h4>
								</div>
								<div class="panel-body">
									<div class="form-danhgia" style="width: 100%; margin: 0 auto; padding: 30px 20px;">
										{!! Form::open(array('url' => '', 'method' => 'POST', 'files' => TRUE, 'name'=>'formQuestionBank ', 'novalidate'=>'', 'class'=>'validation-align')) !!}
										<fieldset class='form-group'>
											{{ Form::label('dethi', 'Đề thi') }} <span class="color-red">*</span>
											<div class="form-group row">
												<div class="col-md-2">
													{{ Form::radio('dethi', 1, '', array('id'=>'dethi1', 'name'=>'dethi', 'ng-model'=>'dethi')) }}
													<label for="dethi1"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Quá dễ</label>
												</div>
												<div class="col-md-2">
													{{ Form::radio('dethi', 2, '', array('id'=>'dethi2', 'name'=>'dethi', 'ng-model'=>'dethi')) }}
													<label for="dethi2"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Dễ</label>
												</div>
												<div class="col-md-2">
													{{ Form::radio('dethi', 3, '', array('id'=>'dethi3', 'name'=>'dethi', 'ng-model'=>'dethi')) }}
													<label for="dethi3"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Trung bình</label>
												</div>
												<div class="col-md-2">
													{{ Form::radio('dethi', 4, '', array('id'=>'dethi4', 'name'=>'dethi', 'ng-model'=>'dethi')) }}
													<label for="dethi4"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Khó</label>
												</div>
												<div class="col-md-2">
													{{ Form::radio('dethi', 5, '', array('id'=>'dethi5', 'name'=>'dethi', 'ng-model'=>'dethi')) }}
													<label for="dethi5"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Quá khó</label>
												</div>
											</div>
										</fieldset>
										<fieldset class='form-group'>
											{{ Form::label('all_records', 'Giao diện và bố cục') }} <span class="color-red">*</span>
											<div class="form-group row">
												<div class="col-md-2">
													{{ Form::radio('giaodien', 1, '', array('id'=>'giaodien1', 'name'=>'giaodien', 'ng-model'=>'giaodien')) }}
													<label for="giaodien1"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Rất khó nhìn </label>
												</div>
												<div class="col-md-2">
													{{ Form::radio('giaodien', 2, '', array('id'=>'giaodien2', 'name'=>'giaodien', 'ng-model'=>'giaodien')) }}
													<label for="giaodien2"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Khó nhìn </label>
												</div>
												<div class="col-md-2">
													{{ Form::radio('giaodien', 3, '', array('id'=>'giaodien3', 'name'=>'giaodien', 'ng-model'=>'giaodien')) }}
													<label for="giaodien3"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Bình thường</label>
												</div>
												<div class="col-md-2">
													{{ Form::radio('giaodien', 4, '', array('id'=>'giaodien4', 'name'=>'giaodien', 'ng-model'=>'giaodien')) }}
													<label for="giaodien4"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Đẹp</label>
												</div>
												<div class="col-md-2">
													{{ Form::radio('giaodien', 5, '', array('id'=>'giaodien5', 'name'=>'giaodien', 'ng-model'=>'giaodien')) }}
													<label for="giaodien5"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Rất đẹp</label>
												</div>
											</div>
										</fieldset>
										<fieldset class='form-group'>
											{{ Form::label('thaotac', 'Thao tác sử dụng') }} <span class="color-red">*</span>
											<div class="form-group row">
												<div class="col-md-2">
													{{ Form::radio('thaotac', 1, '', array('id'=>'thaotac1', 'name'=>'thaotac', 'ng-model'=>'thaotac')) }}
													<label for="thaotac1"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Rất khó</label>
												</div>
												<div class="col-md-2">
													{{ Form::radio('thaotac', 2, '', array('id'=>'thaotac2', 'name'=>'thaotac', 'ng-model'=>'thaotac')) }}
													<label for="thaotac2"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Khó</label>
												</div>
												<div class="col-md-2">
													{{ Form::radio('thaotac', 3, '', array('id'=>'thaotac3', 'name'=>'thaotac', 'ng-model'=>'thaotac')) }}
													<label for="thaotac3"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Bình thường</label>
												</div>
												<div class="col-md-2">
													{{ Form::radio('thaotac', 4, '', array('id'=>'thaotac4', 'name'=>'thaotac', 'ng-model'=>'thaotac')) }}
													<label for="thaotac4"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Dễ</label>
												</div>
												<div class="col-md-2">
													{{ Form::radio('thaotac', 5, '', array('id'=>'thaotac5', 'name'=>'thaotac', 'ng-model'=>'thaotac')) }}
													<label for="thaotac5"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Rất dễ</label>
												</div>
											</div>
										</fieldset>
										<fieldset class='form-group'>
											{{ Form::label('amthanh', 'Âm thanh') }} <span class="color-red">*</span>
											<div class="form-group row">
												<div class="col-md-2">
													{{ Form::radio('amthanh', 1, '', array('id'=>'amthanh1', 'name'=>'amthanh', 'ng-model'=>'amthanh')) }}
													<label for="amthanh1"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Khó nghe</label>
												</div>
												<div class="col-md-2">
													{{ Form::radio('amthanh', 2, '', array('id'=>'amthanh2', 'name'=>'amthanh', 'ng-model'=>'amthanh')) }}
													<label for="amthanh2"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Hơi khó nghe</label>
												</div>
												<div class="col-md-2">
													{{ Form::radio('amthanh', 3, '', array('id'=>'amthanh3', 'name'=>'amthanh', 'ng-model'=>'amthanh')) }}
													<label for="amthanh3"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Bình thường</label>
												</div>
												<div class="col-md-2">
													{{ Form::radio('amthanh', 4, '', array('id'=>'amthanh4', 'name'=>'amthanh', 'ng-model'=>'amthanh')) }}
													<label for="amthanh4"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Dễ nghe</label>
												</div>
												<div class="col-md-2">
													{{ Form::radio('amthanh', 5, '', array('id'=>'amthanh5', 'name'=>'amthanh', 'ng-model'=>'amthanh')) }}
													<label for="amthanh5"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span> Rất dễ nghe</label>
												</div>
											</div>
										</fieldset>
										<fieldset class='form-group'>
											{{ Form::label('tocdo', 'Tốc độ load đề thi') }} <span class="color-red">*</span>
											<div class="form-group row">
												<div class="col-md-2">
													{{ Form::radio('tocdo', 1, '', array('id'=>'tocdo1', 'name'=>'tocdo', 'ng-model'=>'tocdo')) }}
													<label for="tocdo1"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Rất chậm</label>
												</div>
												<div class="col-md-2">
													{{ Form::radio('tocdo', 2, '', array('id'=>'tocdo2', 'name'=>'tocdo', 'ng-model'=>'tocdo')) }}
													<label for="tocdo2"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Hơi chậm</label>
												</div>
												<div class="col-md-2">
													{{ Form::radio('tocdo', 3, '', array('id'=>'tocdo3', 'name'=>'tocdo', 'ng-model'=>'tocdo')) }}
													<label for="tocdo3"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Bình thường</label>
												</div>
												<div class="col-md-2">
													{{ Form::radio('tocdo', 4, '', array('id'=>'tocdo4', 'name'=>'tocdo', 'ng-model'=>'tocdo')) }}
													<label for="tocdo4"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Nhanh</label>
												</div>
												<div class="col-md-2">
													{{ Form::radio('tocdo', 5, '', array('id'=>'tocdo5', 'name'=>'tocdo', 'ng-model'=>'tocdo')) }}
													<label for="tocdo5"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Rất nhanh</label>
												</div>
											</div>
										</fieldset>
										<fieldset class='form-group'>
											{{ Form::label('gopy', 'Góp ý') }} <span class="color-red">*</span>(Bạn vui lòng nhập ít nhất 30 ký tự)
											<div class="form-group row">
												<div class="col-md-12">
													{{ Form::textarea('gopy', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '', 'rows' => '5', 'minlength'=>30,
													'ng-model'=>'gopy',
													'id'=>'gopy',
													)) }}
												</div>
											</div>
										</fieldset>
										<fieldset class='form-group'>
											<a href="javascript:void(0);"  onclick="submit_danhgia();" class="btn btn-primary button-submit-danhgia">Gửi đánh giá và xem kết quả</a>
										</fieldset>
										{!! Form::close() !!}
									</div>
								</div>
							</div>
							<?php }  ?>
			<div class="exam-result-wrapper">
				<div class="panel panel-info panel-ketqua" style="width: 100%; margin: 0 auto; display: none" id="panel-ketqua">
					<div class="panel-heading" style="padding: 6px 15px;">
						<h4>THÔNG BÁO KẾT QUẢ</h4>
					</div>
					<div class="panel-body" style="padding: 30px">
						<div class="text-center">
							<div class="text-center"><img src="/public/uploads/settings/logo-elearning.png" alt="logo" class="cs-logo" style="width: 140px;"></div>
							<div class="text-center">
								<h3>KẾT QUẢ THI ONLINE HIKARI E-LEARNING</h3>
							</div>
							<div class="text-left result-info-user">
								<p>Ngày thi: <?php echo date('d-m-Y') ?></p>
								<p>Đề thi: <?php echo $examseries->title; ?></p>
								<p>Họ tên: <span style="text-transform: capitalize;"><?php echo Auth::user()->name; ?></span></p>
							</div>
						</div>
						<?php
						$detail  =  'danger';
						$ketqua = 'Chưa đạt';
						switch ($examseries_category) {
							case '1':
							case '2':
							case '3':
								if ($record_resultfinish->status == 1) {
									$detail = 'success';
									$ketqua = 'Đạt';
								}
								$percent_mark = 60;
								$ktnn = 'Kiến thức ngôn ngữ <br> (Từ vựng-Ngữ pháp)';
								break;
							case '4':
							case '5':
								if ($record_resultfinish->status == 1) {
									$detail = 'success';
									$ketqua = 'Đạt';
								}
								$percent_mark = 120;
								$ktnn = 'Kiến thức ngôn ngữ <br> (Từ vựng-Ngữ pháp-Đọc hiểu)';
								break;
						}

						?>
						<table class="table table-bordered" style="width: 100%;" id="table-result">
						<thead>
							<tr class="info">
							<th scope="col"><?php echo $ktnn; ?></th>
							@if($examseries_category <= 3)
								<th scope="col">Đọc hiểu</th>
							@endif
							<th scope="col">Nghe hiểu</th>
							<th scope="col">Tổng điểm</th>
							<th scope="col">Kết quả</th>
							</tr>
						</thead>
						<tbody>
							<tr>
							<td><?php echo $record_resultfinish->quiz_1_total; ?>/<?php echo $percent_mark; ?></td>
							@if($examseries_category <= 3)
							<td><?php echo $record_resultfinish->quiz_2_total; ?>/60</td>
							@endif
							<td><?php echo $record_resultfinish->quiz_3_total; ?>/60</td>
							<td><?php echo $record_resultfinish->total_marks; ?>/180</td>
							<td><span class="label label-<?php echo $detail; ?>"><?php echo $ketqua; ?></span></td>
							</tr>
						</tbody>
						</table>
						<p><i>* Kết quả thi và chứng nhận cũng được gửi về địa chỉ email của bạn.</i></p>
					</div>
				</div>
			</div>
			<div style="padding-top: 40px; display: none" id="button-history">
				<div class="col-lg-12 text-center" id="thitiep" >
					<?php if ($finish == 1) {
						//$redirect_url = '/exams/student/exam-attempts-finish/';
						//$thitiep = 'Xem lại bài thi';

						$redirect_url = route('mypage.mock-exam.list');
						$thitiep = 'Quay lại phòng thi';
					} ?>
					<a href="<?php echo $redirect_url; ?>" class="btn t btn-primary" style="border-radius: 20px;"><?php echo $thitiep; ?></a>
				</div>
			</div>
				</div>
			</div>
		</div>
	</div>
	</div>
<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->
@stop
@section('footer_scripts')
<style type="text/css">
	table#table-result th {
	    text-align: center;
	    vertical-align: middle;
	}
	table#table-result td {
	    text-align: center;
	    vertical-align: middle;
	}
</style>
<script>
	function submit_danhgia() {
        var status_check = 0;
        if ($('input[name=dethi]:checked').length > 0 && $('input[name=giaodien]:checked').length > 0 && $('input[name=thaotac]:checked').length > 0 && $('input[name=amthanh]:checked').length > 0 && $('input[name=tocdo]:checked').length > 0) {
		    if ($('textarea[name=gopy]').val().length < 30) {
			swal({   title: "",   text: "Bạn vui nhập góp ý ít nhất 30 ký tự",   timer: 2000,   type: "warning", showConfirmButton: false });
			return;
			} else {
				status_check = 1;
			}
		}
		if (status_check == 0) {
			//alert('Bạn vui lòng đánh giá trước khi gửi');
			swal({   title: "",   text: "Bạn vui lòng đánh giá trước khi gửi",   timer: 2000,   type: "warning", showConfirmButton: false });
			return;
		} else {
        	$.ajax({
    			headers: {
		    		'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
		  		},
                url : "{{ route('mock-exam.ajax-rate') }}",
                type : "post",
                data: {dethi: $('input[name="dethi"]:checked').val(), giaodien: $('input[name="giaodien"]:checked').val(), thaotac: $('input[name="thaotac"]:checked').val(), amthanh: $('input[name="amthanh"]:checked').val(), tocdo: $('input[name="tocdo"]:checked').val(), gopy:$('#gopy').val(), exam: <?php echo $examseries->id; ?>, examfinish: <?php echo $record_resultfinish->id; ?>},
                success : function (result){
                	response = $.parseJSON(result);
                	if (response.status == true) {
                    	$('#panel-danhgia').hide();
                    	$('#exam-alert-success').hide();
                        $('#button-history').show();
                        $('#panel-ketqua').show();
						$('#thitiep').show();
						swal({   title: "Cám ơn bạn đã gửi đánh giá",   text: "",   timer: 2000,   type: "success", showConfirmButton: false });
                		//window.location.href = "{{PREFIX}}exams/student/exam-attempts-finish";
                	} else {
                		swal({   title: "",   text: "Bạn vui lòng đánh giá trước khi gửi",   timer: 2000,   type: "warning", showConfirmButton: false });
                	}
                }
            });
        }
	}
</script>
@stop