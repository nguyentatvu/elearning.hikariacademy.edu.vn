$(function () {
    selectCoinPackage();
    selectPaymentMethod();
    askToCreateTransferOrder();
    showMaintainedPaymentMethodAlert();
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
    $('.payment-method:not(.disabled)').on('click', function () {
        $('.payment-method').removeClass('selected');
        $(this).addClass('selected');
        changeTransactionDetail();
        changeTransactionInstruction();
        changeTransactionSubmit();
    });
}

const showMaintainedPaymentMethodAlert = () => {
    $('.payment-method.disabled').on('click', function () {
        Swal.fire({
            title: `Thông báo`,
            icon: "warning",
            html: 'Cổng thanh toán <strong>VNPAY</strong> đang được bảo trì. Sẽ quay lại trong khoảng thời gian sớm nhất!'
        });
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
        Swal.fire({
            title: "Xác nhận tạo đơn hàng",
            text: "",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#8CD4F5',
            confirmButtonText: "Đồng ý",
            cancelButtonText: "Hủy bỏ",
            reverseButtons: true,
        }).then((result) => {
            if (result.isDismissed) {
                Swal.fire("Hủy bỏ", "Đơn hàng của bạn đã bị hủy bỏ", "error");
            } else if (result.isConfirmed) {
                submitCreateTransferOrder();
            }
        });
    });
}

const submitCreateTransferOrder = () => {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: TRANSFER_ORDER_URL,
        type: 'POST',
        data: {
            price: $('input[name="price"]').val()
        },
        dataType: 'json',
        success: function (data) {
            showSuccessAlert(data.messages);
        },
        error: function (data) {
            if (data.responseJSON) {
                data = data.responseJSON;
            }
            showErrorAlert(data.messages);
        }
    });
}