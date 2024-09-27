const showSucessAlert = (message = 'Thành công', callback = null, timer = 2000) => {
    return Swal.fire({
        title: 'Thông báo',
        text: message,
        icon: 'success',
        showConfirmButton: false,
        showCancelButton: false,
        timer: 2000,
        willClose: () => {
            if (callback) {
                callback();
            }
        }
    });
}

const showErrorAlert = (message = 'Thất bại', callback = null, timer = 2000) => {
    return Swal.fire({
        title: 'Thông báo',
        text: message,
        icon: 'error',
        showConfirmButton: false,
        showCancelButton: false,
        timer: 2000,
        willClose: () => {
            if (callback) {
                callback();
            }
        }
    });
}

