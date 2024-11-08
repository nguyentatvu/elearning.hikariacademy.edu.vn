@extends('admin.layouts.'.getRole().'.'.getRole().'layout')
@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li>
                        <a href="{{URL_ADMIN_DASHBOARD}}">
                            <i class="mdi mdi-home">
                            </i>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            Flashcard
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
                    <a class="btn btn-primary button" href="/lms/flashcard/">
                        Flashcard
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
                {{ Form::model($record, 
                        array('url' => '/lms/flashcard/edit'.'/'.$record->id, 
                        'method'=>'patch', 'files' => TRUE, 'name'=>'formFlashcard ', 'novalidate'=>'',  'class'=>'validation-align')) }}
                    @else
                        {!! Form::open(array('url' => '/lms/flashcard/add', 'method' => 'POST', 'files' => TRUE, 'name'=>'formFlashcard ', 'novalidate'=>'', 'class'=>'validation-align')) !!}
                    @endif
                     @include('admin.lms.flashcard.form_element', 
                     array('button_name'=> $button_name),
                     array('record'=>$record))
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