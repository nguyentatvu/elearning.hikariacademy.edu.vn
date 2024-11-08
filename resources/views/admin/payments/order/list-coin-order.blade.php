@extends($layout)
@section('header_scripts')
    <link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop

@section('content')
    <div id="page-wrapper" ng-controller="payments_report">
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
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <h1>{{ $title }}</h1>
                </div>
                <div class="panel-body packages">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Num</th>
                                    <th>Tên</th>
                                    <th>Tài khoản</th>
                                    <th>Tên đơn hàng</th>
                                    <th>Số tiền</th>
                                    <th>Số coin nạp</th>
                                    <th>Ngày tạo đơn hàng</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer_scripts')
    @php
        // MAKE DEFAULT VALUE COLUMN
        $defaultColumns = [
            'num', 'name', 'user_name', 'orderInfo', 'amount', 'recharge_coin_amount', 'created_at', 'status', 'action'
        ];
    @endphp
    @include('admin.common.datatables', array('route' => route('payments.order.coin.list'), 'route_as_url' => TRUE, 'table_columns' => $defaultColumns))
    @include('admin.payments.scripts.js-scripts');
    <script>
        // Confirm coin order button event listener
        const successOrder = (paymentId) => {
            swal({
                    title: "Xác nhận đã thanh toán",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#8CD4F5',
                    confirmButtonText: "Đồng ý",
                    cancelButtonText: "Hủy bỏ",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function(isConfirm) {
                    if (isConfirm) {
                        submitConfirmCoinOrder(paymentId);
                    } else {
                        swal.close();
                    }
                });
        }

        // Cancel coin order button event listener
        const cancelOrder = (paymentId) => {
            swal({
                    title: "Xác nhận xoá đơn hàng",
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#8CD4F5',
                    confirmButtonText: "Đồng ý",
                    cancelButtonText: "Hủy bỏ",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function(isConfirm) {
                    if (isConfirm) {
                        submitCancelCoinOrder(paymentId);
                    } else {
                        swal.close();
                    }
                }
            );
        }

        // Submit confirm coin order
        const submitConfirmCoinOrder = (paymentId) => {
            let route = '{{ route('payment.order.coin.confirm') }}';
            let token = '{{ csrf_token() }}';
            $.ajax({
                url:route,
                type: 'post',
                dataType: "json",
                data: {
                    _method: 'post',
                    _token : token,
                    paymentId : paymentId,
                },
                success: function(data){
                    swal({
                        title: 'Thông báo',
                        text: data.messages,
                        type: 'success',
                        showConfirmButton: false,
                        showCancelButton: false,
                        timer: 2000,
                    });
                },
                error: function(data){
                    if(data.responseJSON) {
                        data = data.responseJSON;
                    }
                    swal({
                        title: 'Thông báo',
                        text: data.messages,
                        type: 'warning',
                        showConfirmButton: false,
                        showCancelButton: false,
                        timer: 2000,
                    });
                },
                complete: function() {
                    $('.datatable').DataTable().ajax.reload();
                }
            });
        }

        // Submit cancel coin order
        const submitCancelCoinOrder = (paymentId) => {
            let route = '{{ route('payment.order.coin.cancel') }}';
            let token = '{{ csrf_token() }}';
            $.ajax({
                url:route,
                type: 'post',
                dataType: "json",
                data: {
                    _method: 'delete',
                    _token : token,
                    paymentId : paymentId,
                },
                success: function(data){
                    swal({
                        title: 'Thông báo',
                        text: data.messages,
                        type: 'success',
                        showConfirmButton: false,
                        showCancelButton: false,
                        timer: 2000,
                    });
                },
                error: function(data){
                    if(data.responseJSON) {
                        data = data.responseJSON;
                    }
                    swal({
                        title: 'Thông báo',
                        text: data.messages,
                        type: 'warning',
                        showConfirmButton: false,
                        showCancelButton: false,
                        timer: 2000,
                    });
                },
                complete: function() {
                    $('.datatable').DataTable().ajax.reload();
                }
            });
        }
    </script>
@stop

