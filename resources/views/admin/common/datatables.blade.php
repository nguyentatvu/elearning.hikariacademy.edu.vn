<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" type="text/css">

<link href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css" type="text/css">

<script src="{{themes('js/bootstrap-toggle.min.js')}}"></script>

<script src="{{themes('js/jquery.dataTables.min.js')}}"></script>

<script src="{{themes('js/dataTables.bootstrap.min.js')}}"></script>

<script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>

<script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.flash.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.3/pdfmake.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.3/vfs_fonts.js"></script>

<!--
<script src="{{themes('js/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{themes('js/pdfmake/vjs_fonts.js')}}"></script>
-->

<script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>

<script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.print.min.js"></script>

	<?php 	$routeValue= $route; ?> 

	@if(!isset($route_as_url))

	{

		<?php $routeValue =  route($route); ?>

	}

	@endif
	<?php  
	$setData = array();
		
		if(isset($table_columns))

		{
			foreach($table_columns as $col) {

				$temp['data'] = $col;

				$temp['name'] = $col;

				array_push($setData, $temp);

			}

			$setData = json_encode($setData);

		}


	?>

  <script>

  var tableObj;

    $(document).ready(function(){

    	$.ajaxSetup({

        headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        	}

		});

   		 tableObj = $('.datatable').DataTable({

	            processing: true,

	            serverSide: true,

	            // responsive: true,

	            cache: true,

	            type: 'GET',

	            ajax: '{{ $routeValue }}',

	            order: false,
                pageLength: 25,

	            // lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
	            lengthMenu: [[-1, 10, 25, 50], ["All", 10, 25, 50]],

	            <?php if (isset($pdf)) {?>

	            	dom: 'Blfrtip',

	            	buttons: [

	            			{

	                            extend: 'pdfHtml5',
								<?php 
									if (isset($pdf_orientation) && $pdf_orientation==1) {
										echo "orientation: 'landscape',";
									}
								?>
	                            exportOptions: {

	                                columns: [<?php echo $pdf; ?>]

	                            }

	                        },

	            	],

	            <?php } ?>

	            <?php

	            if(checkRole(getUserGrade(7))) { ?>



	            <?php if (isset($excel)) {?>

	            	dom: 'Blfrtip',

		            buttons: [

					            			{

					                            extend: 'excel',

					                            exportOptions: {

					                                columns: [<?php echo $excel; ?>]

					                            }

					                        },

					        ],

	            <?php } ?>

	            <?php if (isset($csv)) {?>

	            	dom: 'Blfrtip',

		            buttons: [

					            			{

					                            extend: 'csv',

					                            exportOptions: {

					                                columns: [<?php echo $csv; ?>]

					                            }

					                        },

					        ],

	            <?php } ?>



	            <?php } ?>

				"language": {

		            "sProcessing":   "Đang xử lý...",

				    "sLengthMenu":   "Xem _MENU_ mục",

				    "sZeroRecords":  "Không tìm thấy dòng nào phù hợp",

				    "sInfo":         "Đang xem _START_ đến _END_ trong tổng số _TOTAL_ mục",

				    "sInfoEmpty":    "Đang xem 0 đến 0 trong tổng số 0 mục",

				    "sInfoFiltered": "(được lọc từ _MAX_ mục)",

				    "sInfoPostFix":  "",

				    "sSearch":       "Tìm:",

				    "sUrl":          "",

				    "oPaginate": {

				        "sFirst":    "Đầu",

				        "sPrevious": "Trước",

				        "sNext":     "Tiếp",

				        "sLast":     "Cuối"

				    }

		        }

	            @if(isset($table_columns))

	            ,columns: {!!$setData!!}

	            @endif

	    });

    });

  </script>

  <style type="text/css">

  	.dt-buttons {

  	    margin-bottom: 30px;

  	}

  	.dt-button {

  	    background: #f16a43;

  	    padding: 6px 15px;

  	    color: #f8fafb;

  	    border-radius: 3px;

  	}

  </style>