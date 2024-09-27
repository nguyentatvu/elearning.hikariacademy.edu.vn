 				
		 	<div class="panel-heading countdount-heading">
					<h2>{{$item->total_exams }}つのテスト</h2>
				</div>
				<?php 
					$items_list = $item->itemsList();
				?>				
				<div class="panel-body">
					<ul class="offer-list">
					@foreach($items_list as $quizitem)
						<li>
						<i class="mdi mdi-star-circle"></i><h4><?php change_furigana_text ($quizitem->title); ?></h4>
						<p>{{$quizitem->total_questions}} の質問 </p>
						
						</li>
					@endforeach
					</ul>
				</div>