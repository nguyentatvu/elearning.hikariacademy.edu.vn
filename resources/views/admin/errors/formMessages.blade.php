
@if (Session::has('flash_message'))
    {{-- Show error alert with instruction --}}
    @if(Session::get('flash_message.type') === 'error_instruction')
        @component('admin.errors.error-instruction-alert')
        @endcomponent
    @else
        <script type="text/javascript">
            swal({
                title: "{{{ Session::get('flash_message.title') }}}",
                text: "{{{ Session::get('flash_message.text') }}}",
                type: "{{{ Session::get('flash_message.type') }}}",
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif
@endif

@if (Session::has('flash_overlay'))
    <script type="text/javascript">
        swal({
            title: "{{{ Session::get('flash_overlay.title') }}}",
            text: "{{{ Session::get('flash_overlay.text') }}}",
            type: "{{{ Session::get('flash_overlay.type') }}}",
            confirmButtonText: "Ok"
        });
    </script>
@endif

@if (Session::has('flash_message_2'))
    <script type="text/javascript">
        Swal.fire({
            title: "{{{ Session::get('flash_message_2.title') }}}",
            text: "{{{ Session::get('flash_message_2.text') }}}",
            icon: "{{{ Session::get('flash_message_2.type') }}}",
            timer: 2000,
            showConfirmButton: false,
        });
    </script>
@endif