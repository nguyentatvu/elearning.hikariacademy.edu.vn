<div class="panel-heading">
		<h2>Bộ đề thi</h2>
	<div ng-if="savedSeries.length>0" class="crearfix selected-questions-details">
		<span class="pull-left">Số đề thi (@{{savedSeries.length}})</span>
		<span class="pull-right">Câu hỏi: @{{ totalQuestions }}</span>
	</div>
	 <div ng-if="savedSeries.length==0"  class="crearfix selected-questions-details">
		<span class="pull-left">{{getPhrase('no_data_available')}}</span>
	</div>	
	</div>
	{!! Form::open(array('url' => URL_EXAM_SERIES_UPDATE_SERIES.$record->slug, 'method' => 'POST')) !!}
					 	<input type="hidden" name="saved_series" value="@{{savedSeries}}">
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12 clearfix">
				<div ng-if="savedSeries!=''" class="vertical-scroll" >
					 				
					 				<a class="remove-all-questions text-red" ng-click="removeAll()">Xóa tất cả</a>
					 				<table  
								  class="table table-hover">
								  <thead>
								  <tr>
									<th>Đề thi</th>
									<th>Câu hỏi</th>
									<th>Điểm</th>	
									<th></th>	
									</tr>
									</thead>
									<tbody>
										<tr ng-repeat="i in savedSeries track by $index">
										<td>@{{ savedSeries[$index].title}}</td>
										<td title="@{{ savedSeries[$index].question}}">@{{ savedSeries[$index].total_questions  }}</td>
										<td>@{{ savedSeries[$index].total_marks}}</td>
										<td><a ng-click="removeQuestion(i)" class="btn-outline btn-close text-red"><i class="fa fa-close"></i></a></td>
										</tr>
									</tbody>
									</table>
					 			</div>

					 			<div class="buttons text-center" >
							<button class="btn btn-lg btn-success button">{{getPhrase('update')}}</button>
						</div>
			</div>
		</div>
	</div>

{!! Form::close() !!}

	 
	 
