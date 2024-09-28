@extends($layout)
@section('header_scripts')
    <link href="{{ CSS }}ajax-datatables.css" rel="stylesheet">
    <style>
        .border-top {
            border-top: 1px solid #e0e8f3 !important;
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
                        <li><a href="{{ PREFIX }}"><i class="mdi mdi-home"></i></a> </li>
                        <li><a href="{{ PREFIX }}">Comments</a> </li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
            <div class="panel panel-custom">
                <div class="panel-body packages">
                    <div>
                        <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Comments</th>
                                    <th>Học viên</th>
                                    <th>Khóa học</th>
                                    <th>Thời gian</th>
                                    <th>Trạng thái</th>
                                    <th>{{ getPhrase('action') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade " id="Comment" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleCommentLongTitle">Comments</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body p-0" id="comment_boby">
                    </div>
                    {{ Form::model(null, [
                        'url' => url('comments/reply'),
                        'method' => 'post',
                        'files' => false,
                        'name' => 'formComments',
                        'novalidate' => '',
                    ]) }}
                    <input hidden name="user_id" value="{{ Auth::id() }}">
                    <input hidden name="parent_id" value="">
                    <div class="form-group">
                        {{ Form::textarea(
                            'body',
                            $value = null,
                            $attributes = [
                                'class' => 'form-control',
                                'required' => 'true',
                                'rows' => '5',
                                'placeholder' => getPhrase('Comment'),
                            ]
                        ) }}
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" onclick="upComment(event)" class="btn btn-success">Gửi</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    @php
        // MAKE DEFAULT VALUE COLUMN
        $defaultColumns = [
            'stt', 'body', 'name', 'title', 'updated_at', 'status', 'action'
        ];
	@endphp

    @include('admin.common.datatables', ['route' => url('comments/index/getExamList'), 'route_as_url' => true, 'table_columns' => $defaultColumns])
    @include('admin.common.deletescript', ['route' => PREFIX . 'lms/seriescombo/delete/'])
    <script>
        // Get all the comments
        function myModal(id, slug, combo_slug) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: '{{ url('comments/getComments') }}',
                type: 'post',
                dataType: "json",
                data: {
                    id: id,
                },
                beforeSend: function() {
                    // setting a timeout
                    swal({
                        html: true,
                        title: 'Đang xử lý vui lòng chờ',
                        text: '<img style="position: relative;" src="/public/assets/images/loader.svg" class="loader-img" alt="img">',
                        type: '',
                        showConfirmButton: false,
                        showCancelButton: false,
                    });
                },
                success: function(data) {
                    if (data.error === 1) {
                        $('#comment_boby').empty();
                        $('#comment_boby').html(data.message);
                    }

                    $('input[name="parent_id"]').val(id);
                    $('#Comment').modal('show')
                    swal.close();
                }
            })
            $('.datatable').DataTable().ajax.reload();
        }

        function upComment(e) {
            e.preventDefault();

            let form = $('form[name="formComments"]');
            if (form.find('textarea').val().length == 0) {
                swal({
                    title: "Thông báo",
                    text: "Vui lòng nhập thông tin phản hồi",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: '#8CD4F5',
                    confirmButtonText: "Đồng ý",
                    closeOnConfirm: false,
                    closeOnCancel: true
                });
                return;
            }

            let route = form.attr('action');
            let data = form.serialize();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: route,
                type: 'post',
                dataType: "json",
                data: data,
                beforeSend: function() {
                    // setting a timeout
                    swal({
                        html: true,
                        title: 'Đang xử lý vui lòng chờ',
                        text: '<img style="position: relative;" src="/public/assets/images/loader.svg" class="loader-img" alt="img">',
                        type: '',
                        showConfirmButton: false,
                        showCancelButton: false,
                    });
                },
                success: function(data) {
                    if (data.error === 1) {
                        $('textarea[name="body"]').val('');
                        swal({
                            title: 'Thông báo',
                            text: data.message,
                            type: 'success',
                            showConfirmButton: false,
                            showCancelButton: false,
                            timer: 3000,
                        });
                    } else {
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

                    // Reload the table
                    $('.datatable').DataTable().ajax.reload();
                }
            })
        }
    </script>
@stop
