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