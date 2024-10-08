@component('client.components.auth-modal') @endcomponent
<style>
    #dify-chatbot-bubble-button {
      background-color: #0e6efd !important;
    }
</style>

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

<script>
    window.difyChatbotConfig = {
        token: 'YbbDYAQxsbf9OF8w',
        baseUrl: 'http://35.190.235.118:8880'
    }
</script>
<script
    src="http://35.190.235.118:8880/embed.min.js"
    id="YbbDYAQxsbf9OF8w"
    defer>
</script>