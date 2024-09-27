@extends('admin.layouts.examlayout')
@section('header_scripts')
@stop
@section('content')
<div id="page-wrapper">
	<div class="container-fluid">
		<!-- Page Heading -->
				<!-- <div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li class="active"><?php change_furigana_text ($title); ?> </li>
						</ol>
					</div>
				</div> -->
				<!-- /.row -->
				<div class="panel panel-custom" style="margin-top: 40px; min-height: 700px;">
					<div class="panel-heading">
						<h1><?php change_furigana_text ($title); ?></h1></div>
						<div class="panel-body">
							<div class="profile-details text-center">
								
								<div class="aouther-school">
									
								</div>
							</div>
							<div class="panel-body" style="padding-bottom: 60px;">
								<div class="row">
									<div class="col-sm-12">
										<h2 style="color: red; text-align: center;">Bạn đã thi: <?php change_furigana_text ($title); ?></h2>
									</div>
								</div>
								<br/>
								<div class="row" id="btt_continue">
									<div class="col-lg-12 text-left">
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
											</style>
											<div class="panel panel-info panel-danhgia" style="width: 700px; margin: 0 auto;">
												<div class="panel-heading" style="padding: 6px 15px;">
													<h4>Bạn vui lòng đánh giá và xem kết quả.</h4>
												</div>
												<div class="panel-body">
													<div class="form-danhgia" style="width: 700px; margin: 0 auto; padding: 30px;">
														{!! Form::open(array('url' => '', 'method' => 'POST', 'files' => TRUE, 'name'=>'formQuestionBank ', 'novalidate'=>'', 'class'=>'validation-align')) !!}
														<fieldset class='form-group'>
															{{ Form::label('dethi', 'Đề thi') }}
															<div class="form-group row">
																<div class="col-md-3">
																	{{ Form::radio('dethi', 1, '', array('id'=>'dethi1', 'name'=>'dethi', 'ng-model'=>'dethi')) }}
																	<label for="dethi1"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span> Khó </label>
																</div>
																<div class="col-md-3">
																	{{ Form::radio('dethi', 2, '', array('id'=>'dethi2', 'name'=>'dethi', 'ng-model'=>'dethi')) }}
																	<label for="dethi2"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Trung bình</label> 
																</div>
																<div class="col-md-3">
																	{{ Form::radio('dethi', 3, '', array('id'=>'dethi3', 'name'=>'dethi', 'ng-model'=>'dethi')) }}
																	<label for="dethi3"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span> Dễ</label> 
																</div>
															</div>
														</fieldset>
														<fieldset class='form-group'>
															{{ Form::label('all_records', 'Giao diện và bố cục') }}
															<div class="form-group row">
																<div class="col-md-3">
																	{{ Form::radio('giaodien', 1, '', array('id'=>'giaodien1', 'name'=>'giaodien', 'ng-model'=>'giaodien')) }}
																	<label for="giaodien1"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span> Khó nhìn </label>
																</div>
																<div class="col-md-3">
																	{{ Form::radio('giaodien', 2, '', array('id'=>'giaodien2', 'name'=>'giaodien', 'ng-model'=>'giaodien')) }}
																	<label for="giaodien2"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Bình thường</label> 
																</div>
																<div class="col-md-3">
																	{{ Form::radio('giaodien', 3, '', array('id'=>'giaodien3', 'name'=>'giaodien', 'ng-model'=>'giaodien')) }}
																	<label for="giaodien3"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span> Đẹp</label> 
																</div>
															</div>
														</fieldset>
														<fieldset class='form-group'>
															{{ Form::label('thaotac', 'Thao tác sử dụng') }}
															<div class="form-group row">
																<div class="col-md-3">
																	{{ Form::radio('thaotac', 1, '', array('id'=>'thaotac1', 'name'=>'thaotac', 'ng-model'=>'thaotac')) }}
																	<label for="thaotac1"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span> Khó sử dụng</label>
																</div>
																<div class="col-md-3">
																	{{ Form::radio('thaotac', 2, '', array('id'=>'thaotac2', 'name'=>'thaotac', 'ng-model'=>'thaotac')) }}
																	<label for="thaotac2"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Bình thường</label> 
																</div>
																<div class="col-md-3">
																	{{ Form::radio('thaotac', 3, '', array('id'=>'thaotac3', 'name'=>'thaotac', 'ng-model'=>'thaotac')) }}
																	<label for="thaotac3"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span> Dễ</label> 
																</div>
															</div>
														</fieldset>
														<fieldset class='form-group'>
															{{ Form::label('amthanh', 'Âm thanh') }}
															<div class="form-group row">
																<div class="col-md-3">
																	{{ Form::radio('amthanh', 1, '', array('id'=>'amthanh1', 'name'=>'amthanh', 'ng-model'=>'amthanh')) }}
																	<label for="amthanh1"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span> Khó nghe</label>
																</div>
																<div class="col-md-3">
																	{{ Form::radio('amthanh', 2, '', array('id'=>'amthanh2', 'name'=>'amthanh', 'ng-model'=>'amthanh')) }}
																	<label for="amthanh2"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Bình thường</label> 
																</div>
																<div class="col-md-3">
																	{{ Form::radio('amthanh', 3, '', array('id'=>'amthanh3', 'name'=>'amthanh', 'ng-model'=>'amthanh')) }}
																	<label for="amthanh3"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span> Rõ ràng</label> 
																</div>
															</div>
														</fieldset>
														<fieldset class='form-group'>
															{{ Form::label('tocdo', 'Tốc độ load đề thi') }}
															<div class="form-group row">
																<div class="col-md-3">
																	{{ Form::radio('tocdo', 1, '', array('id'=>'tocdo1', 'name'=>'tocdo', 'ng-model'=>'tocdo')) }}
																	<label for="tocdo1"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span> Chậm</label>
																</div>
																<div class="col-md-3">
																	{{ Form::radio('tocdo', 2, '', array('id'=>'tocdo2', 'name'=>'tocdo', 'ng-model'=>'tocdo')) }}
																	<label for="tocdo2"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span>Bình thường</label> 
																</div>
																<div class="col-md-3">
																	{{ Form::radio('tocdo', 3, '', array('id'=>'tocdo3', 'name'=>'tocdo', 'ng-model'=>'tocdo')) }}
																	<label for="tocdo3"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span> Nhanh</label> 
																</div>
															</div>
														</fieldset>
														<fieldset class='form-group'>
															{{ Form::label('gopy', 'Góp ý') }}
															<div class="form-group row">
																<div class="col-md-12">
																	{{ Form::textarea('gopy', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '', 'rows' => '5',
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
										

											
											<div class="panel panel-info panel-danhgia" style="width: 700px; margin: 0 auto;" id="panel-danhgia">
												<div class="panel-heading" style="padding: 6px 15px;">
													<h4>Kết quả</h4>
												</div>
												<div class="panel-body" style="padding: 30px">
													<div class="text-center">
														<div class="text-center"><img src="https://elearning.hikariacademy.edu.vn/public/uploads/settings/logo-elearning.png" alt="logo" class="cs-logo" style="width: 140px;"></div>
														<div class="text-center">
															<h2>KẾT QUẢ THI HIKARI E-LEARNING</h2>
														</div>
														<div class="text-left result-info-user">
															<p>Ngày thi: <?php echo date('d-m-Y') ?></p>
															<p>Đề thi: <?php //echo $examseries_title; ?></p>
															<p>Họ tên: <?php echo Auth::user()->name; ?></p>
														</div>
													</div>
													
													<table class="table table-bordered" style="width: 100%; margin: 0 auto;" id="table-result">
													  <thead>
													    <tr class="info">
													      <th scope="col">KTNN</th>
													      <?php if($examseries_category == 3) {?>
													      <th scope="col">Đọc hiểu</th>
													      <?php } ?>
													      <th scope="col">Nghe hiểu</th>
													      <th scope="col">Tổng điểm</th>
													    </tr>
													  </thead>
													  <tbody>
													    <tr>
													      <th>123</th>
													      <?php if($examseries_category == 3) {?>
													      <td>123123</td>
													      <?php } ?>
													      <td>123</td>
													      <td>12312</td>
													    </tr>
													  </tbody>
													</table>

												</div>
												<?php }  else { ?>
												
												<?php } ?>
											</div>





											<table class="table table-bordered" style="width: 500px; margin: 0 auto; padding-bottom: 30px; display: none" id="table-result">
											  <thead>
											    <tr class="info">
											      <th scope="col">KTNN</th>
											      <?php if($examseries_category == 3) {?>
											      <th scope="col">Đọc hiểu</th>
											      <?php } ?>
											      <th scope="col">Nghe hiểu</th>
											      <th scope="col">Tổng điểm</th>
											    </tr>
											  </thead>
											  <tbody>
											    <tr>
											      <th>123</th>
											      <?php if($examseries_category == 3) {?>
											      <td>123123</td>
											      <?php } ?>
											      <td>123</td>
											      <td>12312</td>
											    </tr>
											  </tbody>
											</table>

										<?php }  else { ?>
											<p>
												Vui lòng click vào nút bên dưới để tiếp tục thi phần tiếp theo
											</p>
										<?php } ?>
									</div>
									<div class="col-lg-12 text-center" <?php if ($finish == 1) {echo 'style="display:none"';} ?> id="thitiep">
										<?php if ($finish == 1) {
											$redirect_url = '/exams/student/exam-attempts-finish/'. Auth::user()->slug;
											$thitiep = 'Xem lịch sử thi';
										} else {
											$redirect_url = URL_STUDENT_EXAM_SERIES_VIEW_ITEM;
											$thitiep = 'Tiếp tục thi'; //change_furigana_text ('[furi k=#閉# f=#と#]じる')
										}?>
										<a href="<?php echo $redirect_url; ?>" class="btn t btn-primary" ><?php echo $thitiep; ?></a>
	
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
		
		<script src="{{admin_asset('js/jquery-1.12.1.min.js')}}"></script>
		<script>
			
			function submit_danhgia() {
				
	            	$.ajax({
	        			headers: {
				    		'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
				  		},
	                    url : "https://elearning.hikariacademy.edu.vn/exams/student/ajax_rate1",
	                    type : "post",
	                    data: {},
	                    success : function (result){
	                        alert(123);
	                        // $('#table-result').toggle(500);
	                    }
		                });
			}


			function submit_danhgia1() {
				alert($('meta[name="csrf_token"]').attr('content'));
				$('.form-danhgia').hide();
				swal({   title: "Cám ơn bạn đã gửi đánh giá",   text: "",   timer: 3000,   showConfirmButton: false });
				$('#table-result').show();
				$('#thitiep').show();


	            	/*$.ajax({
	        			headers: {
				    		'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
				  		},
	                    url : "https://elearning.hikariacademy.edu.vn/exams/student/ajax_rate1",
	                    type : "post",
	                    data: {dethi: $('input[name="dethi"]:checked').val(), giaodien: $('input[name="giaodien"]:checked').val(), thaotac: $('input[name="thaotac"]:checked').val(), amthanh: $('input[name="amthanh"]:checked').val(), tocdo: $('input[name="tocdo"]:checked').val()},
	                    success : function (result){
	                        // alert(123);
	                        // $('#table-result').toggle(500);
	                    }
		                });*/
			}




		</script>
		@section('footer_scripts')
		@stop