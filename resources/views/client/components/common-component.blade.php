@component('client.components.auth-modal') @endcomponent
<div class="loading-overlay">
    <div class="loading-spinner"></div>
</div>
@if (Session::has('logout_successful'))
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", (event) => {
            showSuccessAlert('Bạn đã đăng xuất thành công!');
        });
    </script>
@endif

@if (Session::has('flash_message'))
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", (event) => {
            Swal.fire({
                title: "{{{ Session::get('flash_message.title') }}}",
                text: "{{{ Session::get('flash_message.text') }}}",
                icon: "{{{ Session::get('flash_message.type') }}}",
                timer: 1700,
                showConfirmButton: false
            });
        });
    </script>
@endif