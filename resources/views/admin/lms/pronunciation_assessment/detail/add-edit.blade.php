@extends('admin.layouts.' . getRole() . '.' . getRole() . 'layout')
@section('content')
    <div id="page-wrapper">
        <div class="container-fluid">
            <!-- Page Heading -->
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
                    <h1>
                        {{ $title }}
                    </h1>
                </div>
                <div class="panel-body">
                    <?php $button_name = getPhrase('create'); ?>
                    @if ($record)
                        <?php $button_name = getPhrase('update'); ?>
                        {{ Form::model($record, [
                            'url' => route('lms.pronunciation_assessment.detail.update', ['id' => $pronunciation_assessment->id, 'detailId' => $record->id]),
                            'method' => 'PATCH',
                            'files' => true,
                            'name' => 'formPronunciationAssessment ',
                            'novalidate' => '',
                            'class' => 'validation-align',
                        ]) }}
                    @else
                        {!! Form::open([
                            'url' => route('lms.pronunciation_assessment.detail.store', ['id' => $pronunciation_assessment->id]),
                            'method' => 'POST',
                            'files' => true,
                            'name' => 'formPronunciationAssessment ',
                            'novalidate' => '',
                            'class' => 'validation-align',
                        ]) !!}
                    @endif
                    @include(
                        'admin.lms.pronunciation_assessment.detail.form-elements',
                        ['button_name' => $button_name],
                        ['pronunciation_assessment' => $pronunciation_assessment, 'record' => $record]
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
@stop
