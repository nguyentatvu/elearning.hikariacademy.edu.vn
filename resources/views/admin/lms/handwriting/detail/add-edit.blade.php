@extends('admin.layouts.' . getRole() . '.' . getRole() . 'layout')
@section('content')
    <div id="page-wrapper">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li>
                            <a href="{{ URL_ADMIN_DASHBOARD }}">
                                <i class="mdi mdi-home">
                                </i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('lms.handwriting.index') }}">
                                Luyện viết
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
                            'url' => route('lms.handwriting.detail.update', ['id' => $handwriting->id, 'detailId' => $record->id]),
                            'method' => 'PATCH',
                            'files' => true,
                            'name' => 'formHandwriting ',
                            'novalidate' => '',
                            'class' => 'validation-align',
                        ]) }}
                    @else
                        {!! Form::open([
                            'url' => route('lms.handwriting.detail.store', ['id' => $handwriting->id]),
                            'method' => 'POST',
                            'files' => true,
                            'name' => 'formHandwriting ',
                            'novalidate' => '',
                            'class' => 'validation-align',
                        ]) !!}
                    @endif
                    @include(
                        'admin.lms.handwriting.detail.form-elements',
                        ['button_name' => $button_name],
                        ['handwriting' => $handwriting, 'record' => $record]
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
