@extends('admin.layouts.admin.adminlayout')
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{URL_ADMIN_DASHBOARD}}"><i class="mdi mdi-home"></i></a> </li>
							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>
				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						<!-- <div class="pull-right messages-buttons">
							<a href="{{URL_EXAM_SERIES_ADD}}" class="btn  btn-primary button" >{{ getPhrase('create')}}</a>
						</div> -->
						<h1>{{ $title }} : <?php echo $examseries->title; ?></h1>
					</div>
					<div class="panel-body packages">
						<?php foreach ($anwser as $key_anwser => $value_anwser): ?>
						<div>
						<h2>Phần: <?php echo $key_anwser; ?></h2> 
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="200" id="table-check">
							<thead>
								<tr>
									<th align="center" style="text-align: center">Câu số</th>
									<th align="center" style="text-align: center">Đáp án</th>
								</tr>
							</thead>
							<?php if ($key_anwser == 'Nghe') { ?>
							<tbody>
								<?php $rei_mondai =  $examseries->category_id; ?>
								<?php $j = 1; $so_cau = 1; $mondai_tr = '';?>
								<?php foreach ($value_anwser as $k_anwser => $v_anwser): ?>
									<?php 
									if ($rei_mondai == 3) {
										switch ($j) {
											case 1:
											$so_cau = 1;
											echo $mondai_tr = '<tr>
												<td colspan="2" align="center"><span class="label label-info">Mondai 1</span></td>
											</tr>';
											break;
											case 7:
											$so_cau = 1;
											echo $mondai_tr = '<tr>
												<td colspan="2" align="center"><span class="label label-info">Mondai 2</span></td>
											</tr>';
											break;
											case 13:
											$so_cau = 1;
											echo $mondai_tr = '<tr>
												<td colspan="2" align="center"><span class="label label-info">Mondai 3</span></td>
											</tr>';
											break;
											case 16:
											$so_cau = 1;
											echo $mondai_tr = '<tr>
												<td colspan="2" align="center"><span class="label label-info">Mondai 4</span></td>
											</tr>';
											break;
											case 20:
											$so_cau = 1;
											echo $mondai_tr = '<tr>
												<td colspan="2" align="center"><span class="label label-info">Mondai 5</span></td>
											</tr>';
											break;
											
										
										}
									}
									if ($rei_mondai == 4) {
										switch ($j) {
											case 1:
											$so_cau = 1;
											echo $mondai_tr = '<tr>
												<td colspan="2" align="center"><span class="label label-info">Mondai 1</span></td>
											</tr>';
											break;
											case 9:
											$so_cau = 1;
											echo $mondai_tr = '<tr>
												<td colspan="2" align="center"><span class="label label-info">Mondai 2</span></td>
											</tr>';
											break;
											case 16:
											$so_cau = 1;
											echo $mondai_tr = '<tr>
												<td colspan="2" align="center"><span class="label label-info">Mondai 3</span></td>
											</tr>';
											break;
											case 21:
											$so_cau = 1;
											echo $mondai_tr = '<tr>
												<td colspan="2" align="center"><span class="label label-info">Mondai 4</span></td>
											</tr>';
											break;
										}
									}
									if ($rei_mondai == 5) {
										switch ($j) {
											case 1:
											$so_cau = 1;
											echo $mondai_tr = '<tr>
												<td colspan="2" align="center"><span class="label label-info">Mondai 1</span></td>
											</tr>';
											break;
											case 8:
											$so_cau = 1;
											echo $mondai_tr = '<tr>
												<td colspan="2" align="center"><span class="label label-info">Mondai 2</span></td>
											</tr>';
											break;
											case 14:
											$so_cau = 1;
											echo $mondai_tr = '<tr>
												<td colspan="2" align="center"><span class="label label-info">Mondai 3</span></td>
											</tr>';
											break;
											case 19:
											$so_cau = 1;
											echo $mondai_tr = '<tr>
												<td colspan="2" align="center"><span class="label label-info">Mondai 4</span></td>
											</tr>';
											break;
										}
									}
									 ?>
									<tr>
										<td align="center"><?php echo $so_cau; ?></td>
										<td align="center"><span class="label label-success"><?php echo $v_anwser->correct_answers; ?></span></td>
									</tr>
									<?php $j++; $so_cau++;?>
								<?php endforeach ?>
							</tbody>
							<?php } else { ?>

								<tbody>
									<?php $j = 1; ?>
									<?php foreach ($value_anwser as $k_anwser => $v_anwser): ?>
										<tr>
											<td align="center"><?php echo $j; ?></td>
											<td align="center"><span class="label label-success"><?php echo $v_anwser->correct_answers; ?></span></td>
										</tr>
										<?php $j++; ?>
									<?php endforeach ?>
								</tbody>
							<?php } ?>
						</table>
						<style type="text/css">
							table#table-check {
								width: 300px;
							}
						</style>
						</div>
						<?php endforeach ?>
					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</div>
@endsection
@section('footer_scripts')
@stop
