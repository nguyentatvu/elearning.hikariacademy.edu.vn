@extends('admin.front-exams.examlayout-front')
@section('header_scripts')
@stop
@section('content')
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="panel panel-custom">
			<div class="panel-heading">
				<h1><!-- {{getPhrase('result_for') }} --> <?php change_furigana_text($title); ?><?php change_furigana_text ('の[furi k=#採点# f=#さいてん#]'); ?></h1></div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-4">
							<ul class="library-statistic mt-40">
								<li class="total-books">
									<?php change_furigana_text ('[furi k=#点数# f=#てんすう#]'); ?> <!-- {{getPhrase('score') }} --> <span>{{$marks_obtained}} / {{ intval($total_marks)}}</span>
								</li>
								<li class="total-journals">
									<?php change_furigana_text ('[furi k=#正解率# f=#せいかいりつ#]'); ?> <!-- {{getPhrase('percentage')}} --> <span><?php echo sprintf('%0.2f', $percentage); ?></span>
								</li>
								<li class="digital-items">
									<?php $grade_system = getSettings('general')->gradeSystem; ?>
									<?php change_furigana_text ('[furi k=#評価# f=#ひょうか#]'); ?><span><?php if ($marks_obtained < 19) { echo change_furigana_text('[furi k=#不合格# f=#ふごうかく#]');} else { echo change_furigana_text('[furi k=#合格# f=#ごうかく#]');}?></span>
								</li>
							</ul>
						</div>
						<div class="col-md-4">
							@if(isset($marks_data))
							<div class="row">
								<?php $ids=[];?>
								@for($i=0; $i<count($marks_data); $i++)
								<?php 
								$newid = 'myMarksChart'.$i;
								$mark_ids[] = $newid; ?>
								<canvas id="{{$newid}}" width="100" height="60"></canvas>
								@endfor
							</div>
							@endif
						</div>
						<div class="col-md-4">
							@if(isset($time_data))
							<div class="row">
								<?php $ids=[];?>
								@for($i=0; $i<count($time_data); $i++)
								<?php 
								$newid = 'myTimeChart'.$i;
								$time_ids[] = $newid; ?>
								<canvas id="{{$newid}}" width="100" height="60"></canvas>
								@endfor
							</div>
							@endif
						</div>
					</div>
					<br/>
					<div class="row">
						<div class="col-lg-12 text-center">
							
							<!-- <a href="{{PREFIX}}" class="btn t btn-primary"><?php change_furigana_text ('[furi k=#閉# f=#と#]じる'); ?></a> -->


							<a onClick="setLocalItem('{{PREFIX}}')" href="javascript:void(0);" class="btn t btn-primary">{{ getPhrase('close') }}</a>

							<!-- <a href="{{PREFIX}}" class="btn t btn-primary">{{ getPhrase('close') }}</a> -->
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
<script src="{{JS}}chart-vue.js"></script>
@if(isset($marks_data))
@include('admin.common.chart', array('chart_data'=>$marks_data,'ids' => $mark_ids))
@endif
@if(isset($time_data))
@include('admin.common.chart', array('chart_data'=>$time_data,'ids' => $time_ids))
@endif
<script>
	function setLocalItem(url) {
		localStorage.setItem('redirect_url',url);
		window.close();
	}
</script>
@stop