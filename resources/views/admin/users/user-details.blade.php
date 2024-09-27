 @extends($layout)
 @section('header_scripts')

@stop
@section('content')
<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i> </a> </li>
							@if(checkRole(getUserGrade(2)))
							<li><a href="{{URL_USERS}}">{{ getPhrase('users') }}</a> </li>
							@endif
							
							@if(checkRole(getUserGrade(7)))
							<li><a href="{{URL_PARENT_CHILDREN}}">{{ getPhrase('users') }}</a> </li>
							@endif

							<li><a href="javascript:void(0);">{{ $title }}</a> </li>
						</ol>
					</div>
				
				</div>

<div class="panel panel-custom">
				 	<div class="panel-heading">					
				 		<h1>CHI TIẾT CỦA {{$record->name }}</h1>		<!-- getPhrase('details_of').' '. -->			
				 	</div>
					<div class="panel-body">
						
				
						
						<div class="row">
               <div class="col-sm-6">
               	<div class="row">
               		<div class="col-sm-12">
                         
                         <div class="profile-details text-center">
							<div class="profile-img"><img src="{{ getProfilePath($record->image,'profile')}}" alt=""></div>
							<div class="aouther-school">
								<h2>{{ $record->name}}</h2>
								<p><span>{{$record->email}}</span></p>
								
							</div>

						</div>               			

               		</div>
               	</div>
               	<div class="row">
               <div class="col-md-6 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a href="{{URL_STUDENT_EXAM_ATTEMPTS.$record->slug}}"><div class="state-icn bg-icon-info"><i class="fa fa-history"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				
								<a href="{{URL_STUDENT_EXAM_ATTEMPTS.$record->slug}}">
Lịch sử thi<!-- {{ getPhrase('exam_history')}} --></a>
				 			</div>
				 		</div>
				 	</div>

				 	<div class="col-md-6 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a href="{{URL_STUDENT_ANALYSIS_BY_EXAM.$record->slug}}"><div class="state-icn bg-icon-success"><i class="fa fa-flag"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				
								<a href="{{URL_STUDENT_ANALYSIS_BY_EXAM.$record->slug}}">Bài thi<!-- {{ getPhrase('by_exam')}} --></a>
				 			</div>
				 		</div>
				 	</div>


				 	 	<div class="col-md-6 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a href="{{URL_STUDENT_ANALYSIS_SUBJECT.$record->slug}}"><div class="state-icn bg-icon-purple"><i class="fa fa-key"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				
								<a href="{{URL_STUDENT_ANALYSIS_SUBJECT.$record->slug}}"><!-- {{ getPhrase('by_subject')}} --> Phần tích</a>
				 			</div>
				 		</div>
				 	</div>


				 		<div class="col-md-6 col-sm-6">
				 		<div class="media state-media box-ws">
				 			<div class="media-left">
				 				<a href="{{URL_PAYMENTS_LIST.$record->slug}}"><div class="state-icn bg-icon-pink"><i class="fa fa-credit-card"></i></div></a>
				 			</div>
				 			<div class="media-body">
				 				
								<a href="{{URL_PAYMENTS_LIST.$record->slug}}"><!-- {{ getPhrase('subscriptions')}} -->Danh sách đăng ký</a>
				 			</div>
				 		</div>
				 	</div>

				
                  </div>
               </div>
                <div class="col-sm-6">

                  	<div class="row">
                       <div class="col-sm-12">
                              
								<?php $ids=[];?>
								@for($i=0; $i<count($chart_data); $i++)
								<?php 
								$newid = 'myChart'.$i;
								$ids[] = $newid; ?>
								
								<div class="panel-body">
									<canvas id="{{$newid}}" width="25" height="25"></canvas>
								</div>

								@endfor
                       </div>
                      
                   </div>
               </div>
           </div>

			

 
	
	
	 

						 
						</div>
						 
 
					</div>
				</div>
				</div>
			<!-- /.container-fluid -->
</div>
@endsection
 

@section('footer_scripts')
 
 @include('common.chart', array($chart_data,'ids' =>$ids));

@stop
