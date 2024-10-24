/************************
 * COMMON FUNCTIONS
 ***********************/

const showSuccessAlert = (message = 'Thành công', title = "Thông báo", callback = null, timer = 1700) => {
    Swal.fire({
        title: title,
        text: message,
        icon: "success",
        timer: timer,
        showConfirmButton: false,
    }).then(() => {
        if (callback) callback();
    });
}

const showErrorAlert = (message = 'Thất bại', title = "Thông báo", callback = null, timer = 1700) => {
    Swal.fire({
        title: title,
        text: message,
        icon: "error",
        timer: timer,
        showConfirmButton: false,
    }).then(() => {
        if (callback) callback();
    });
}

// Reload page
const reloadPage = () => {
    location.reload();
}

// Redirect to home base
const redirectHomeBase = () => {
    location.href = "/";
}

// Toggle loading overlay
const toggleLoadingOverlay = (isActive = null) => {
    const loadingOverlay = $(".loading-overlay");

    if (isActive === null) {
        loadingOverlay.toggleClass('is-active');
    }
    else {
        loadingOverlay.toggleClass('is-active', isActive);
    }
}

/************************
 * AUTH MODAL
 ***********************/

$(function () {
    // Switch register/login tab in modal
    $(document).on('click', '.auth-modal a[data-tab]', function (event) {
        event.preventDefault();

        const activeTabName = $(this).data('tab');
        const activeTab = $(`${activeTabName}`);
        const hiddenTab = $(this).closest('.auth-content');
        hiddenTab.addClass('animate__animated animate__fadeOut');
        setTimeout(() => {
            hiddenTab.addClass('d-none').removeClass('animate__fadeOut');
            activeTab.removeClass('d-none').addClass('animate__fadeIn');
        }, 10);
    });

    // Validate required input
    $(document).on('submit', '.auth-modal form.needs-validation', function(event) {
        event.preventDefault();

        if (!checkRequiredInputAuthModal(this)) {
            event.stopPropagation();
            return false;
        }

        if ($(this).attr('id') === 'register_form') {
            submitRegister();
        }
        else if ($(this).attr('id') === 'login_form') {
            submitLogin();
        }
    });
});

// Check required input for auth modal
const checkRequiredInputAuthModal = (form) => {
    let checking = true;
    $(form).find('.form-control').each(function() {
        if ($(this).val() === '') {
            checking = false;
            $(this).addClass('is-invalid');
            $(this).siblings('.invalid-feedback').text('Vui lòng nhập vào đây');
        }
    });

    return checking;
}

// Submit register
const submitRegister = () => {
    toggleLoadingOverlay(true);
    const formData = $('#register_form').serialize();

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: "/register",
        type: "post",
        data: formData + "&_method=POST",
        success: function (response) {
            showSuccessAlert(
                "Bạn hãy kiểm tra email để nhận thông tin đăng nhập.\n(Vui lòng kiểm tra cả spambox!)",
                "Đăng ký thành công",
              reloadPage
            );
        },
        error: function (error) {
            error = error.responseJSON || error;
            const message = error.messages || "Tạo tài khoản thất bại";
            showErrorAlert(message);
            $(".auth-modal .modal-content").replaceWith(error.html);
        },
        complete: function () {
            toggleLoadingOverlay(false);
        }
    });
}

// Submit login
const submitLogin = () => {
    const formData = $('#login_form').serialize();

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        url: "/login",
        type: "post",
        data: formData + "&_method=POST",
        success: function (response) {
            $(".modal.auth-modal").modal("hide");
            showSuccessAlert("Đăng nhập thành công", "Thông báo", redirectHomeBase);
        },
        error: function (error) {
            showErrorAlert("Đăng nhập thất bại");
            $(".login-failed").removeClass("d-none");
        },
    });
}

// Show auth modal
const showAuthModal = (isLogin = true) => {
    if (isLogin)
        $('[data-tab="#login_content"]').trigger('click');
    else
        $('[data-tab="#register_content"]').trigger('click');

    $('.modal.auth-modal').modal('show');
}