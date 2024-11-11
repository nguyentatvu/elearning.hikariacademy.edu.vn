@extends('admin.layouts.' . getRole() . '.' . getRole() . 'layout')

@section('header_scripts')
    <link href="{{ CSS }}ajax-datatables.css" rel="stylesheet">

    <style>
        .tr-head {
            pointer-events: none;
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
                        <li><a href="/lms/handwriting">Luyện viết</a></li>
                        <li>{{ $title }}</li>
                    </ol>
                </div>
            </div>
            <div class="panel panel-custom">
                <div class="panel-heading">
                    <div class="pull-right messages-buttons">
                        <a href="{{ route('lms.handwriting.detail.create', ['id' => $handwriting->id]) }}" class="btn  btn-primary button">
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
                                @if ($handwriting->type == \App\JapaneseWritingPractice::HIRAGANA)
                                    <tr class="tr-head">
                                        <th>STT</th>
                                        <th>Hiragana/Katakana</th>
                                        <th>{{ getPhrase('action') }}</th>
                                    </tr>
                                @elseif ($handwriting->type == \App\JapaneseWritingPractice::KANJI)
                                    <tr class="tr-head">
                                        <th>STT</th>
                                        <th>Từ</th>
                                        <th>Phần cần viết</th>
                                        <th>Hán tự</th>
                                        <th>{{ getPhrase('action') }}</th>
                                    </tr>
                                @endif
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('footer_scripts')
    <script src="{{ admin_asset('js/jquery-1.12.1.min.js') }}"></script>
    @php
        $defaultColumns = [];
        if ($handwriting->type == \App\JapaneseWritingPractice::HIRAGANA) {
            $defaultColumns = ['number', 'character', 'action'];
        } elseif ($handwriting->type == \App\JapaneseWritingPractice::KANJI) {
            $defaultColumns = ['number', 'full_word', 'underlined_word', 'kanji', 'action'];
        }

    @endphp
    @include('admin.common.datatables', [
        'route' => route('lms.handwriting.show', $handwriting->id),
        'route_as_url' => 'TRUE',
        'table_columns' => $defaultColumns,
    ])
    @include('admin.common.deletescript', ['route' => '/lms/handwriting/' . $handwriting->id . '/detail/delete/'])
@stop
