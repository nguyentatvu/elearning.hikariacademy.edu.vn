@php
    $instructionModalName = 'admin.errors.instructions.' . Session::get('flash_message.instruction_type');
@endphp

@if (View::exists($instructionModalName))
    @component($instructionModalName)
    @endcomponent
@endif

<style>
    .sweet-alert.error-instruction button.cancel {
        background: #428bfc;
    }
    .section-title {
        margin-top: 30px;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .value-table td:first-child {
        font-weight: bold;
    }

    .example-table th,
    .example-table td {
        vertical-align: middle !important;
    }

    .note {
        font-style: italic;
        color: #888;
    }

    .px-8 {
        padding-left: 32px;
        padding-right: 32px;
    }
</style>
<script type="text/javascript">
    swal({
        title: "{{ Session::get('flash_message.title') }}",
        text: "{{ Session::get('flash_message.text') }}",
        type: "error",
        showCancelButton: true,
        cancelButtonText: 'Hướng dẫn',
        customClass: 'error-instruction'
    }, function (isCheckingOk) {
        // If use choose to see instruction
        if (!isCheckingOk) {
            $('#errorInstructionModal').modal("show");
        }
    });
</script>