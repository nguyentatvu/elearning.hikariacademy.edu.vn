@extends($layout)
@section('content')<div id="page-wrapper">

    <div class="container" style="background-color: #fff;">

        <div class="row">

            <div class="col-sm-12">

                <nav aria-label="breadcrumb">

                    <ol class="breadcrumb breadcrumb-custom bg-inverse-info">

                         <li class="breadcrumb-item"><a href="/home"><i class="mdi mdi-home menu-icon"></i></a></li>

                         <li class="breadcrumb-item" aria-current="page"><a href="/site/shop">Cửa hàng</a></li>

                         <li class="breadcrumb-item active" aria-current="page"><span>{{ ucfirst($title) }}</span></li>

                    </ol>

                </nav>

                <div class="ed_heading_top col-sm-12">

                    <h3 class="tilte-h3 wow fadeInDown text-danger animated text-uppercase" style="visibility: visible;">{{ $title }}</h3>

                </div>

                <div class="table-responsive"> 
                    <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Thanh toán</th>
                                <th>Phương thức</th>
                                <th>Số tiền (vnđ)</th>
                                <th>Số Hi Koi</th>
                                <th>Ngày nạp tiền</th>
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
            'orderId', 'type', 'amount', 'point', 'created_at'
        ];
    @endphp

    @include('admin.common.validations')
    @include('admin.common.alertify')
    @include('admin.classes.scripts.js-scripts')
    <script src="{{JS}}bootstrap-toggle.min.js"></script>
    <script src="{{JS}}jquery.dataTables.min.js"></script>
    <script src="{{JS}}dataTables.bootstrap.min.js"></script>
    @include('admin.common.datatables', array('route'=> PREFIX.'payments-report/history', 'route_as_url' => 'TRUE', 'table_columns' => $defaultColumns))
    @include('admin.common.deletescript', array('route'=> URL_CLASSES_USER_DELETE))
@stop