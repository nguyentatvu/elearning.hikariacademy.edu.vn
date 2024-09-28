@extends('admin.layouts.owner.ownerlayout')

@section('header_scripts')
<link href="{{ admin_asset('css/sweetalert2.css') }}" rel="stylesheet">
<style>
    .detail-content {
        border-bottom: 1px solid #f2f2f2;
    }

    .coin-edit {
        cursor: pointer;
        display: inline;
        color: #438afe;
    }

    .detail-content {
        padding-bottom: 0px !important;
    }
</style>
@endsection

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/') }}"><i class="mdi mdi-home"></i></a> </li>
                    <li>{{ $page_title }}</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="panel panel-custom">
        <div class="panel-heading">
            <div class="pull-right messages-buttons">
                <button class="btn btn-primary button btn-danger" style="display: none;"
                    onclick="hideCancleButton()">Hủy</button>
                <button class="btn btn-primary button coin-save" onclick="coinSave()">Tạo mới</button>
            </div>
            <h1>{{ $page_title }}</h1>
        </div>
        <div class="coin-info" style="display: none;">
            <form method="post" id="coin_form">
                <div class="detail-content">
                    <input type="text" name="id" hidden disabled>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="price">
                                Số tiền
                                <span class="text-danger">*<span>
                            </label>
                            <input type="number" class="form-control" name="price" id="price" min="0">
                            <p class="text-danger invalid-feedback"></p>
                        </div>
                        <div class="form-group col-6">
                            <label for="coin">
                                Số lượng HiCoin
                                <span class="text-danger">*<span>
                            </label>
                            <input type="number" class="form-control" name="coin" id="coin" min="0">
                            <p class="text-danger invalid-feedback"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="bonus_percentage">
                                Phần trăm bonus
                                <span class="text-danger">*<span>
                            </label>
                            <input type="number" class="form-control" name="bonus_percentage" id="bonus_percentage" min="0" max="100">
                            <p class="text-danger invalid-feedback"></p>
                        </div>
                        <div class="form-group col-6">
                            <label for="is_active">
                                Trạng thái
                                <span class="text-danger">*<span>
                            </label>
                            <select name="is_active" class="form-control">
                                @foreach (config('constant.coin_recharge_package.status') as $status_code =>$status)
                                    <option value="{{ $status_code }}">{{ $status }}</option>
                                @endforeach
                            </select>
                            <p class="text-danger invalid-feedback"></p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @if ($coin_packages->count() > 0)
            <div class="panel-body packages">
                <table class="custom-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Số tiền</th>
                            <th>Số lượng HiCoin</th>
                            <th>Phần trăm bonus</th>
                            <th>Trạng thái</th>
                            <th style="width: 20%;">Ngày tạo</th>
                            <th style="width: 15%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $num = 0 @endphp
                        @foreach ($coin_packages as $coin_package)
                        <tr
                            data-price="{{ $coin_package->price }}" data-coin="{{ $coin_package->coin }}"
                            data-bonus_percentage="{{ $coin_package->bonus_percentage }}"
                            data-is_active="{{ $coin_package->is_active }}" data-id="{{ $coin_package->id }}">
                            <td>{{ ++$num }}</td>
                            <td>{{ formatCurrencyVND($coin_package->price) }}</td>
                            <td>{{ formatNumber($coin_package->coin) }}</td>
                            <td>{{ $coin_package->bonus_percentage . '%' }}</td>
                            <td>{{ config('constant.coin_recharge_package.status')[$coin_package->is_active] }}</td>
                            <td>{{ \Carbon\Carbon::parse($coin_package->created_at)->format('Y-m-d') }}</td>
                            <td style="font-size: 20px;">
                                <div class="coin-edit" onclick="coinEdit()"><i
                                        class="fa fa-edit"></i></div>
                                @component('admin.common.confirm-delete', ['id' => $coin_package->id, 'title' => 'Xác nhận xóa gói nạp', 'url' => route('payments-order.coin-recharge-packages.delete', $coin_package->id)])
                                    <div>
                                        <p>Bạn có chắc chắn muốn xóa gói nạp này?</p>
                                    </div>
                                @endcomponent
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
        <div class="panel-body packages" style="text-align: center;">
            <h3>Danh sách các gói nạp HiCoin đang trống</h3>
        </div>
        @endif
    </div>
</div>
@endsection

@section('footer_scripts')
<script>
    $(document).ready(function () {
    });

        // Reload page
        const reloadPage = () => {
            location.reload();
        }

        // Coin save button event handler
        const coinSave = () => {
            const coinSaveBtn = $('.coin-save');
            const coinContainer = $('.coin-info');
            const saveCoinURL = '{{ route('payments-order.coin-recharge-packages.update_or_create') }}';

            toggleCancleButton(true);
            clearAllValidationErrors();

            if (coinContainer.is(':hidden')) {
                coinContainer.show();
                coinSaveBtn.addClass('create');
                coinSaveBtn.removeClass('edit');
                coinSaveBtn.text('Lưu gói nap mới');

                return;
            }

            // If save button for creating new coin recharge package
            if (coinSaveBtn.hasClass('create')) {
                const formData = $('#coin_form').serialize();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN':'{{ csrf_token() }}'
                    },
                    url: saveCoinURL,
                    type: 'post',
                    data: formData,
                    success: function(response) {
                        showSucessAlert(
                            'Lưu gói nạp thành công!',
                            reloadPage
                        );
                    },
                    error: function(error) {
                        showErrorAlert('Lưu gói nạp thất bại!');
                        showValidatedErrors(error?.responseJSON?.errors);

                        // Fill the slug input in case admin did not fill it and slug being duplicated
                        if(error?.responseJSON?.inputs?.slug) {
                            $('input[name="slug"]').val(error.responseJSON.inputs.slug);
                        }
                    }
                });
            }

            // If save button for updating existing coin recharge package
            if (coinSaveBtn.hasClass('edit')) {
                const formData = $('#coin_form').serialize();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN':'{{ csrf_token() }}'
                    },
                    url: saveCoinURL,
                    type: 'post',
                    data: formData + '&_method=PUT',
                    success: function(response) {
                        showSucessAlert(
                            'Cập nhật gói nạp thành công!',
                            reloadPage
                        );
                    },
                    error: function(error) {
                        showErrorAlert('Cập gói nạp thất bại!');
                        showValidatedErrors(error?.responseJSON?.errors);
                    }
                });
            }
        }

        // Coin edit button event handler for every coin recharge package
        const coinEdit = () => {
            const dataRow = $(event.target).closest('tr');
            const coinSaveBtn = $('.coin-save');
            const coinContainer = $('.coin-info');
            const editForm = $('#coin_form');

            toggleCancleButton(true);

            coinContainer.show();
            coinSaveBtn.addClass('edit');
            coinSaveBtn.removeClass('create');
            coinSaveBtn.text('Cập nhật gói nạp');

            editForm.find('input[name="price"]').val(dataRow.data('price'));
            editForm.find('input[name="coin"]').val(dataRow.data('coin'));
            editForm.find('input[name="bonus_percentage"]').val(dataRow.data('bonus_percentage'));
            editForm.find('select[name="is_active"]').val(dataRow.data('is_active'));
            editForm.find('input[name="id"]').val(dataRow.data('id')).removeAttr('disabled');
        }

        // Toggle or show/hide cancle button next to save button
        const toggleCancleButton = (force) => {
            const cancleButton = $('button.btn-danger');

            if (force && typeof force == 'boolean') {
                cancleButton.toggle(force);
                return;
            }

            cancleButton.toggle();
        }

        // Hide cancle button when being clicked
        const hideCancleButton = () => {
            if ($(event.target).is(':visible')) {
                $('#coin_form').trigger('reset');
                $('.coin-save').text('Tạo mới');
                $('.coin-info').hide();
            }

            $('#coin_form').find('input[name="id"]').attr('disabled', 'disabled').val('');

            toggleCancleButton(false);
        }

        // Show error text below inputs when submit create/edit form
        const showValidatedErrors = (errors) => {
            $.each(errors, function(key, value) {
                $(`input[name="${key}"]`).addClass('is-invalid');
                $(`input[name="${key}"]`).next('.invalid-feedback').text(value);
            });
        }

        const clearAllValidationErrors = () => {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        }
</script>
<script src="{{ admin_asset('js/sweetalert2.js') }}"></script>
@endsection