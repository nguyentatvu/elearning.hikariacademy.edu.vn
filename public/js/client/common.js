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

const showWarningAlert = (message = 'Thông báo', title = "Thông báo", callback = null, timer = 1700) => {
    return Swal.fire({
        title: title,
        text: message,
        icon: "warning",
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

// Redirect my page
const redirectMyPage = () => {
    location.href = "/mypage/my-personal";
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
        else if ($(this).attr('id') === 'forgot_password_form') {
            submitForgotPassword();
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
        beforeSend: function () {
            $(".login-failed").addClass("d-none");
            $(".captcha-failed").addClass("d-none");
        },
        success: function (response) {
            $(".modal.auth-modal").modal("hide");
            showSuccessAlert("Đăng nhập thành công", "Thông báo", redirectMyPage);
        },
        error: function (error) {
            grecaptcha.reset();

            showErrorAlert("Đăng nhập thất bại");

            if (error?.responseJSON?.errors && error?.responseJSON?.errors['g-recaptcha-response']) {
                $(".captcha-failed").removeClass("d-none");
            } else {
                $(".login-failed").removeClass("d-none");
            }
        },
    });
}

// Submit forgot password
const submitForgotPassword = () => {
    const formData = $('#forgot_password_form').serialize();

    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        url: "/forgot-password",
        type: "post",
        data: formData + "&_method=POST",
        beforeSend: function () {
            $('#forgot_password_btn').prop('disabled', true);
            $(".email-failed").addClass("d-none");
        },
        success: function (response) {
            console.log(response);
            $(".modal.auth-modal").modal("hide");
            showSuccessAlert(response.message, "Thông báo", redirectMyPage);
        },
        error: function (error) {
            if (error?.responseJSON?.errors && error?.responseJSON?.errors['email_forgot_password']) {
                $(".email-failed").removeClass("d-none");
                $(".email-failed").text(error?.responseJSON?.errors['email_forgot_password'][0]);
            }
            else if (error?.responseJSON?.message) {
                showErrorAlert(error.responseJSON.message, "Thông báo");
            }
        },
        complete: function () {
            $('#forgot_password_btn').prop('disabled', false);
        }
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

/************************
 * OTHERS
 ***********************/

const showMyCoursesDropdown = () => {
    if ($('.dropdown-my-course.no-content').length > 0) {
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "GET",
            url: '/users/my-courses-dropdown',
            success: function(data) {
                $('.dropdown-my-course')
                    .removeClass('no-content')
                    .html(data.html);
            }
        });
    }
}