@extends('admin.layouts.' . getRole() . '.' . getRole() . 'layout')

@section('header_scripts')
    <style>
        .btn-loading {
            opacity: 0.7;
            pointer-events: none;
        }
    </style>
@endsection

@section('content')
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li>
                            <a href="{{ PREFIX }}">
                                <i class="mdi mdi-home">
                                </i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('lms.pronunciation_assessment.index') }}">
                                Luyện phát âm
                            </a>
                        </li>
                        <li>
                            {{ $title }}
                        </li>
                    </ol>
                </div>
            </div>
            @include('admin.errors.errors')
            <!-- /.row -->
            <div class="panel panel-custom col-lg-12">
                <div class="panel-heading">
                    <div class="pull-right messages-buttons">
                        <a class="btn btn-primary button" href="/lms/pronunciation-assessment/">
                            Danh sách bài luyện phát âm
                        </a>
                    </div>
                    <h1>
                        {{ $title }}
                    </h1>
                </div>
                <div class="panel-body">
                    <?php $button_name = getPhrase('create'); ?>
                    @if ($record)
                        <?php $button_name = getPhrase('update'); ?>
                        {{ Form::model($record, [
                            'url' => route('lms.pronunciation_assessment.update', $record->id),
                            'method' => 'PAtCH',
                            'files' => true,
                            'name' => 'formPronunciationAssessment ',
                            'novalidate' => '',
                            'class' => 'validation-align',
                        ]) }}
                    @else
                        {!! Form::open([
                            'url' => route('lms.pronunciation_assessment.store'),
                            'method' => 'POST',
                            'files' => true,
                            'name' => 'formPronunciationAssessment ',
                            'novalidate' => '',
                            'class' => 'validation-align',
                        ]) !!}
                    @endif
                    @include(
                        'admin.lms.pronunciation_assessment.form-element',
                        ['button_name' => $button_name],
                        ['record' => $record]
                    )
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->
@stop
@section('footer_scripts')
    @include('admin.common.validations');

    <script>
        $(document).ready(function() {
            $('#pronunciation_submit_btn').click(function() {
                $(this).addClass('btn-loading')
                       .html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
            });
        });
    </script>
@stop
