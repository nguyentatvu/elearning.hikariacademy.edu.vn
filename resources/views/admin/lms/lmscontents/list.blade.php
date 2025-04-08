@extends('admin.layouts.admin.adminlayout')
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
<style>
    .flex-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }

    .fa.fa-info-circle::before {
        font-size: 16px;
    }
</style>
@stop
@section('content')
<div id="page-wrapper">
	<div class="container-fluid">
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
					<li><a href="{{url('/')}}"><i class="mdi mdi-home"></i></a> </li>
					<li>{{ $title }}</li>
				</ol>
			</div>
		</div>
		<!-- /.row -->
		<div class="panel panel-custom">
			<div class="panel-heading">
				<!-- <div class="pull-right messages-buttons">
					<button type="button" class="btn btn-primary btn-rounded btn-fw" data-toggle="modal" data-target="#import-exams">
						<i class="mdi mdi-plus-circle"></i>	Import Exams
					</button>
				</div> -->
				<div class="pull-right messages-buttons">
					<button type="button" class="btn btn-primary btn-rounded btn-fw" data-toggle="modal" data-target="#import-mucluc">
						<i class="mdi mdi-plus-circle"></i>	Import Mục lục
					</button>
				</div>
				<!-- <div class="pull-right messages-buttons">
					<a href="{{$URL_LMS_CONTENT_ADD}}" class="btn  btn-primary button" >{{ getPhrase('create')}}</a>
				</div> -->
				<h1>{{ $title }}</h1>
			</div>
			<div class="panel-body packages">
				<div>
					<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>STT</th>
								<th>Bài học</th>
                                <th>Icon</th>
								<th>Loại</th>
								<th>Trạng thái</th>
								<th class="text-center">Học thử</th>
								<th>{{ getPhrase('action')}}</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!-- /.container-fluid -->
</div>
<div class="modal fade" id="import-exams" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel-2">Import exams excel</h5>
			</div>
			<form action="{{$URL_IMPORT_EXAMS}}" class="forms-sample" method="post" id="form-importExcel"  enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="modal-body">
					<div class="card-body">
						<input type="hidden" name="series_slug" value="{{$series_slug}}">
						<label>File (.xlsx)</label>
						<input type="file" name="file" class="form-control">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Hủy bỏ</button>
					<button type="submit" class="btn btn-success">Tải lên</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="import-mucluc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" style="cursor: pointer;" id="exampleModalLabel-2" onclick="showErrorInstruction()">
                    Format Excel import
                    <i class="fa fa-info-circle"></i>
                </h5>
			</div>
			<form action="{{$URL_IMPORT_MUCLUC}}" class="forms-sample" method="post" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="modal-body">
					<div class="card-body">
						<input type="hidden" name="series_slug" value="{{$series_slug}}">
						<label>File (.xlsx)</label>
						<input type="file" name="file" class="form-control">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Hủy bỏ</button>
					<button type="submit" class="btn btn-success">Tải lên</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade " id="Comment" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleCommentLongTitle">Chuyển bài học</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body p-0" id="comment_boby">
                    </div>
                    {{ Form::model(null,array('url' => url('lms/update/changeposition'),'method'=>'post', 'files' => false, 'name'=>'formComments', 'novalidate'=>'')) }}
                    {{-- <input hidden name="user_id" value="{{Auth::id()}}"> --}}
                    <input type="hidden" name="khoahoc" value="{{$series_slug}}">
                    <input hidden name="baihoc" value="">
                    <fieldset class="form-group col-md-12">

						{{-- {{ Form::label('category_id', 'Thêm vào sau bài học') }} --}}

						{{Form::select('saubaihoc', $baihoc, null, ['class'=>'form-control select2'])}}

					</fieldset>
                    
                    {!! Form::close() !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" onclick="upBaihoc(event)" class="btn btn-success">Gửi</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Instruction modal --}}
    @component('admin.errors.instructions.import-menu-contents')
    @endcomponent
@endsection
@section('footer_scripts')
    @php
	// MAKE DEFAULT VALUE COLUMN
	$defaultColumns = [
		'stt', 'title', 'image', 'type', 'import', 'hocthu', 'action'
	];
	@endphp
    @include('admin.common.datatables', array('route'=>$datatbl_url, 'route_as_url' => true, 'table_columns' => $defaultColumns))
    @include('admin.common.deletescript', array('route'=>URL_LMS_CONTENT_DELETE))
    <script >
        function update_try(id,try_type){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}',
                }
            });
            $.ajax({
                url: "{{url('lms/content/ajax-update-try')}}",
                method: 'post',
                data: {
                    'id': id,
                },
                success: function(response){
                    console.log(response);
                    $('.datatable').DataTable().ajax.reload(null,false);
                },
                error: function(response){
                    console.log(response);
                }
            });
        }
        function myModal(id,slug,combo_slug){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN':'{{csrf_token()}}'
                    },
                    url: '{{url('comments/getComments')}}',
                    type: 'post',
                    dataType: "json",
                    data: {
                        id : id,
                    },
                    beforeSend: function() {
                        // setting a timeout
                        /*swal({
                            html:true,
                            title: 'Đang xử lý vui lòng chờ',
                            text: '<img style="position: relative;" src="/public/assets/images/loader.svg" class="loader-img" alt="img">',
                            type: '',
                            showConfirmButton: false,
                            showCancelButton: false,
                        });*/
                    },
                    success:  function(data){
                        //console.log(data)
                        if(data.error === 1) {
                            $('#comment_boby').empty();
                            $('#comment_boby').html(data.message)
                        }
                        $('input[name="baihoc"]').val(id);
                        $('#Comment').modal('show')
                        /*swal({
                            title: 'Thông báo',
                            text: 'Thành công',
                            type: 'success',
                            showConfirmButton: false,
                            showCancelButton: false,
                            timer: 1000,
                        });*/
                    }
                })
                $('.datatable').DataTable().ajax.reload();
            }
        function upBaihoc(e){
                e.preventDefault();
                let form = $('form[name="formComments"]');
                let route = form.attr('action');
                let data = form.serialize();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN':'{{csrf_token()}}'
                    },
                    url: route,
                    type: 'post',
                    dataType: "json",
                    data: data,
                    beforeSend: function() {
                        // setting a timeout
                        // swal({
                        //     html:true,
                        //     title: 'Đang xử lý vui lòng chờ',
                        //     text: '<img style="position: relative;" src="/public/assets/images/loader.svg" class="loader-img" alt="img">',
                        //     type: '',
                        //     showConfirmButton: false,
                        //     showCancelButton: false,
                        // });
                    },
                    success: function(data){
                        console.log(data)
                        if(data.error === 1){
                            // $('textarea[name="body"]').val('');
                            swal({
                                title: 'Thông báo',
                                text: data.message,
                                type: 'success',
                                showConfirmButton: false,
                                showCancelButton: false,
                                timer: 3000,
                            });
                        }else {
                            swal({
                                title: 'Thông báo',
                                text: data.message,
                                type: 'warning',
                                showConfirmButton: false,
                                showCancelButton: false,
                                timer: 3000,
                            });
                        }
                        $('#Comment').modal('hide');
                        $('.datatable').DataTable().ajax.reload();
                    }
                })
            }

            $(document).on('click', '.upload-image', function() {
                var inputFile = $(this).prev('.image-upload-input');
                inputFile.trigger('click');
            });

            $(document).on('change', '.image-upload-input', function() {
                var fileInput = this;
                var file = fileInput.files[0];
                var recordId = $(this).data('id');

                if (file) {
                    var formData = new FormData();
                    formData.append('image', file);
                    formData.append('record_id', recordId);

                    $.ajax({
                        url: '{{ route("lms.content.upload-image") }}',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            let newImageUrl = response.imageUrl + '?v=' + new Date().getTime();
                            let dataId = $(fileInput).data('id');
                            $('img[data-id="' + dataId + '"]').css('display', 'block');
                            $('img[data-id="' + dataId + '"]').attr('src', newImageUrl);
                        },
                        error: function(xhr, status, error) {
                            swal('Lỗi', 'Không thể upload ảnh', 'error');
                        }
                    });
                }
            });
    </script>
    <link rel="stylesheet" type="text/css" href="/public/css/select2.css">
    <script src="/public/js/select2.js"></script>
    <script>
      $('.select2').select2({
       placeholder: "Thêm vào sau khóa học",
       dropdownAutoWidth : true,
       width: '100%'
    });
    </script>
    <script>
        function showErrorInstruction () {
            $('#errorInstructionModal').modal("show");
        }
    </script>
@stop