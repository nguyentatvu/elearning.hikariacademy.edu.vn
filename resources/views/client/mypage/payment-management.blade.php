@extends('client.shared.mypage')

@section('styles')
    <link href="{{ asset('css/pages/mypage.css') }}" rel="stylesheet">
@endsection

@section('mypage-content')
    <div class="manged-ad table-responsive border-top userprof-tab payment-management">
        <table class="table table-bordered table-hover mb-0 text-nowrap">
            <thead>
                <tr>
                    <th class="text-center align-middle" style="width: 5%">STT</th>
                    <th>Quản lý thanh toán của bạn</th>
                    <th class="text-center align-middle">Giá</th>
                    <th class="text-center align-middle">Phương thức</th>
                    <th class="text-center align-middle">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @php $page = request()->input('page', 1) - 1; @endphp
                @foreach ($payment_list as $index => $payment)
                    <tr>
                        <td class="text-center align-middle">
                            {{ ($page * 10) + $index + 1 }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="card-aside-img">
                                    <a href="#">
                                        <img
                                            style="height: auto;" src="{{ '/public/uploads/lms/combo/' . $payment->image}}"
                                            alt="{{$payment->title}}" class="series-image">
                                    </a>
                                </div>
                                <div class="ms-3">
                                    @php
                                        $month_duration = config('constant.series_combo.month_duration_map')[$payment->time];
                                    @endphp
                                    <a href="javascript:void(0);" class="text-dark">
                                        <h5 class="fw-semibold">
                                            {{$payment->title}} ({{ $month_duration }} tháng)
                                        </h5>
                                    </a>
                                    <p>Ngày mua: {{ Carbon::parse($payment->created_at)->format('d-m-Y') }}</p>
                                    <p>Ngày hết hạn: {{ Carbon::parse($payment->created_at)->addMonths($month_duration)->format('d-m-Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="fw-semibold fs-16 text-center align-middle">{{ formatCurrencyVND($payment->cost) }}</td>
                        <td class="text-center align-middle text-center">
                            <span role="button" class="text-uppercase">{{$payment->orderType}}</span>
                        </td>
                        <td class="text-center align-middle" data-payment-pending="{{ $payment->payment_method_id }}">
                            @if ($payment->status == \App\PaymentMethod::PAYMENT_PENDING)
                                @if ($payment->orderType == 'transfer')
                                    <span class="text-warning">Đang xử lý</span><br>
                                @else
                                    <span class="text-warning">Chưa thanh toán</span><br>
                                @endif
                                <a href="javascript:void(0)" onclick="cancelPayment({{ $payment->payment_method_id }})" class="btn btn-sm btn-danger">Hủy đơn hàng</a>
                            @elseif ($payment->status == \App\PaymentMethod::PAYMENT_SUCCESS)
                                <span class="text-success">Thành công</span>
                            @elseif ($payment->status == \App\PaymentMethod::PAYMENT_FAILED)
                                <span class="text-danger">Đơn hàng bị huỷ</span><br>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="my-4">
            @component('client.components.custom-pagination',
                    ['paginations' => $payment_list])
                @endcomponent
        </div>
    </div>
@endsection

@section('mypage-scripts')
    <script>
        const cancelPayment = async (id) => {
            const confirmation = await Swal.fire({
                title: "Xác nhận",
                text: "Bạn có chắc muốn huỷ đơn hàng này?",
                icon: "warning",
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: 'grey',
                confirmButtonText: "Xác nhận",
                cancelButtonText: "Hủy bỏ",
            });
            if (!confirmation.isConfirmed) return;

            const route = '{{url('payments/transfer/delete')}}';
            $.ajax({
                url: route,
                headers: {
                    'X-CSRF-TOKEN':'{{ csrf_token() }}'
                },
                type: 'post',
                dataType: "json",
                data: {
                    slug: id
                },
                success:function(data){
                    if(data.error == 1){
                        showSuccessAlert(data.message, 'Thành công', null, 3000);

                        $(`[data-payment-pending="${id}"]`).html(`
                            <span class="text-danger">Đơn hàng bị huỷ</span><br>
                        `);
                    }else {
                        showErrorAlert(data.message, 'Thông báo', null, 3000);
                    }
                }
            });
        }
    </script>
@endsection
