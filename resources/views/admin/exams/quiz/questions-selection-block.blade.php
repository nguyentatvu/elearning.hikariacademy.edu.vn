<div class="crearfix selected-questions-details">
	<span class="pull-left" ng-if="is_have_section==0">Câu hỏi (@{{savedQuestions.length}})</span>
	<span class="pull-left" ng-if="is_have_section==1">Total Sections (@{{keys.length}})
	</span>
	<span class="pull-right">Tổng điểm: @{{ totalMarks }}</span>
</div>	

{!! Form::open(array('url' => URL_QUIZ_UPDATE_QUESTIONS.$record->slug, 'method' => 'POST', 'id'=>'frm_update_question')) !!}
<input ng-if="is_have_section==0" type="hidden" name="saved_questions" value="@{{savedQuestions}}">
<input ng-if="is_have_section==1" type="hidden" name="saved_questions" value="@{{final_questions}}">
<div class="panel-body">
	<div class="row">

		
		<div class="col-md-12 clearfix">
			<div class="vertical-scroll" >
				<a class="remove-all-questions text-red" style="cursor: pointer;" ng-click="removeAll()">Xóa tất cả</a>
			<table  
				ng-if="is_have_section==0"
				class="table table-hover" id="table_right">
				<thead>
					<tr>
						<th>STT</th>
						<th>Mondai</th>
						<th>Câu hỏi</th>
						<th>C.Tạo</th>	
						<th>Đ</th>
						<th></th>	
					</tr>
				</thead>
				<tbody class="sortable" ui-sortable="sortableOptions" ng-model="savedQuestions">
					<tr ng-repeat="(k,i) in savedQuestions track by $index" style="cursor: move;" class="item">
						<td class="@{{ savedQuestions[$index].subject_id}}  dataquestion" data-question="@{{ savedQuestions[$index].question_id}}">@{{k + 1}} </td>
						<td>@{{ savedQuestions[$index].subject_title}} @{{ savedQuestions[$index].subject_id}}</td>
						<td title="@{{ savedQuestions[$index].question}}" ng-bind-html="trustAsHtml(savedQuestions[$index].question)"></td>
						<td>@{{ savedQuestions[$index].topic_name}}</td>
						<td>@{{ savedQuestions[$index].marks}}</td>
						<td><a ng-click="removeQuestion(i)" style="cursor: pointer;" class="btn-outline btn-close text-red"><i class="fa fa-close"></i></a></td>
					</tr>
				</tbody>
			</table>


		</div>
		<div class="buttons text-center" >
			<!-- <button class="btn btn-lg btn-success button">{{getPhrase('update')}}</button> -->
			<a class="btn btn-lg btn-success button" ng-click="submitForm()">{{getPhrase('update')}}</a>
		</div>


	</div>
</div>
</div>

{!! Form::close() !!}
