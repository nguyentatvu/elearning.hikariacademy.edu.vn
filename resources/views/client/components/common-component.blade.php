@component('client.components.auth-modal') @endcomponent
<style>
    #dify-chatbot-bubble-button {
      background-color: #0e6efd !important;
    }
</style>

<div class="loading-overlay {{ Request::is('home') ? 'is-active hiding' : '' }}">
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
    const chatbotUrl = "{{ env('CHAT_BOT_URL') }}";
    const chatbotToken = "{{ env('CHAT_BOT_TOKEN') }}";

    window.difyChatbotConfig = {
        token: chatbotToken,
        baseUrl: chatbotUrl
    }
</script>
<script
    src="{{ admin_asset('js/embed.min.js') }}"
    id="{{ env('CHAT_BOT_TOKEN') }}"
    defer>
</script>