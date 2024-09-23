$(function () {
    selectCoinPackage();
    selectPaymentMethod();
    askToCreateTransferOrder();
});

const selectCoinPackage = () => {
    $('.coin-payment-selections tr td').on('click', function () {
        const coinCheckBox = $(this).closest('tr').find('input');
        coinCheckBox.prop('checked', true);
        changeTransactionDetail();
        changeTransactionInstruction();
        changeTransactionSubmit();
    });
}

const selectPaymentMethod = () => {
    $('.payment-method').on('click', function () {
        $('.payment-method').removeClass('selected');
        $(this).addClass('selected');
        changeTransactionDetail();
        changeTransactionInstruction();
        changeTransactionSubmit();
    });
}

const changeTransactionDetail = () => {
    const checkedCoinInput = $('.coin-payment-selections input:checked');
    if (!checkedCoinInput.length) return;

    const coinPackage = checkedCoinInput.closest('tr');
    const selectedPaymentMethod = $('.payment-method.selected').data('name');
    const formattedTransactionAmount = coinPackage.data('formatted-price');
    const transactionAmount = coinPackage.data('price');
    const coinCost = coinPackage.data('coin');

    $('.transaction-details').removeClass('d-none');
    $('.selected-payment-method').text(selectedPaymentMethod);
    $('.coin-cost').text(coinCost);
    $('.transaction-amount').text(formattedTransactionAmount);
    $('input[name="price"]').val(transactionAmount);
}

const changeTransactionInstruction = () => {
    const selectedPaymentMethod = $('.payment-method.selected');
    const instructionId = selectedPaymentMethod.data('instruction');
    $('.transaction-instruction').addClass('d-none');
    $(`#${instructionId}`).removeClass('d-none');
}

const changeTransactionSubmit = () => {
    const selectedPaymentMethod = $('.payment-method.selected');
    const selectedSubmitBtn = $(`#${selectedPaymentMethod.data('submit')}`);

    $('.transaction-submit').addClass('d-none');
    selectedSubmitBtn.removeClass('d-none');
}

const askToCreateTransferOrder = () => {
    $('#submit_bank_transfer>button').on('click', function () {
        swal({
            title: "Xác nhận tạo đơn hàng",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#8CD4F5',
            confirmButtonText: "Đồng ý",
            cancelButtonText: "Hủy bỏ",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function (isConfirm) {
            if(!isConfirm) {
                swal("Hủy bỏ", "Đơn hàng của bạn đã bị hủy bỏ", "error");
            }
            else {
                submitCreateTransferOrder();
            }
        });
    });
}

const submitCreateTransferOrder = () => {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        },
        url: TRANSFER_ORDER_URL,
        type: 'POST',
        data: {
            price: $('input[name="price"]').val()
        },
        dataType: 'json',
        success: function (data) {
            swal({
                title: 'Thông báo',
                text: data.messages,
                type: 'success',
                showConfirmButton: false,
                showCancelButton: false,
                timer: 2000,
            });
        },
        error: function (data) {
            if (data.responseJSON) {
                data = data.responseJSON;
            }

            swal({
                title: 'Thông báo',
                text: data.messages,
                type: 'error',
                showConfirmButton: false,
                showCancelButton: false,
                timer: 2000,
            });
        }
    });
}