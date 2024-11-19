@extends('admin.layouts.' . getRole() . '.' . getRole() . 'layout')

@section('header_scripts')
    <link href="{{ CSS }}ajax-datatables.css" rel="stylesheet">

    <style>
        .tr-head {
            pointer-events: none;
        }

        .th-text {
            width: 200px !important;
        }

        .th-audio {
            width: 450px !important;
        }

        .td-text {
            word-wrap: break-word;
            white-space: normal;
            overflow-wrap: break-word;
            max-width: 200px;
        }

        .audio-cell {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .audio-cell>button {
            height: 40px;
        }

        .dropdown-menu {
            right: 0 !important;
            left: auto !important;
        }
    </style>
@stop

@section('content')
    <?php $image_path = PREFIX . (new App\ImageSettings())->getExamImagePath(); ?>
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="/"><i class="mdi mdi-home"></i></a> </li>
                        <li><a href="/lms/pronunciation-assessment">Luyện phát âm</a></li>
                        <li>{{ $title }}</li>
                    </ol>
                </div>
            </div>
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <div class="pull-right messages-buttons">
                        <a href="{{ route('lms.pronunciation_assessment.detail.create', ['id' => $pronunciation_assessment->id]) }}"
                            class="btn  btn-primary button">
                            Tạo mới
                        </a>
                    </div>
                    <h1>{{ $title }}</h1>
                </div>
                <div class="panel-body packages">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered datatable" id="hikari-table-view" cellspacing="0"
                            width="100%">
                            <thead>
                                <tr class="tr-head">
                                    <th class="th-text">Câu luyện phát âm</th>
                                    <th class="th-text">Văn bản nhận diện được từ audio</th>
                                    <th class="th-audio">Audio</th>
                                    <th>Trạng thái</th>
                                    <th>{{ getPhrase('action') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer_scripts')
    @php
        $defaultColumns = ['text', 'recognized_text', 'audio', 'status', 'action'];
    @endphp
    @include('admin.common.datatables', [
        'route' => route('lms.pronunciation_assessment.show', $pronunciation_assessment->id),
        'route_as_url' => 'TRUE',
        'table_columns' => $defaultColumns,
    ])
    @include('admin.common.deletescript', [
        'route' => '/lms/pronunciation-assessment/' . $pronunciation_assessment->id . '/detail/delete/'
    ])
    <script>
        $(document).on('click', '.upload-audio', function() {
            let id = $(this).data('id');
            let pronunciationId = {{ $pronunciationId }};
            let inputFile = $(this).prev('.audio-upload-input');

            inputFile.click();

            inputFile.on('change', function() {
                let fileData = inputFile[0].files[0];

                if (fileData) {
                    let formData = new FormData();
                    formData.append('audio', fileData);

                    let uploadUrl =
                        "{{ route('lms.pronunciation_assessment.detail.upload_audio', ['id' => ':pronunciationId', 'detailId' => ':detailId']) }}";
                    uploadUrl = uploadUrl.replace(':pronunciationId', pronunciationId).replace(':detailId',
                        id);

                    $.ajax({
                        url: uploadUrl,
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content')
                        },
                        success: function(response) {
                            window.location.reload();
                        },
                        error: function(xhr) {
                            swal('Lỗi!', 'Hệ thống không nhận diện được file audio, xin vui lòng ghi âm lại hoặc đổi file khác.', 'error');
                        }
                    });
                }
            });
        });
    </script>
@stop
