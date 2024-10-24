@extends($layout)



@section('content')

<style>

.bd-example-modal-lg .modal-dialog{

  display: table;

  position: relative;

  margin: 0 auto;

  top: calc(50% - 24px);

}



.bd-example-modal-lg .modal-dialog .modal-content{

  background-color: transparent;

  border: none;

}

</style>

<div id="page-wrapper">

  <div class="container-fluid">

    <!-- Page Heading -->

    <div class="row">

      <div class="col-lg-12">

        <ol class="breadcrumb">

          <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>

          <li><a href="{{$URL_LMS_CONTENT}}">LMS {{ getPhrase('contents')}}</a></li>

          <li class="active">{{isset($title) ? $title : ''}}</li>

        </ol>

      </div>

    </div>

    @include('admin.errors.errors')

    <!-- /.row -->

    <?php

    $settings = ($record) ? $settings : '';

    ?>



    <div class="panel panel-custom col-lg-12" ng-init="initAngData('{{ $settings }}');" ng-controller="angLmsController">

      <div class="panel-heading">

        <div class="pull-right messages-buttons">

          <a href="{{$URL_LMS_CONTENT}}" class="btn  btn-primary button" >{{ getPhrase('list')}}</a>

        </div>

        <h1>{{ $title }}  </h1>

      </div>

      <div class="panel-body" >

        <?php $button_name = getPhrase('create'); ?>

        @if ($record)

        <?php $button_name = getPhrase('update'); ?>

        {{ Form::model($record,

          array('url' => $URL_LMS_CONTENT_EDIT. $record->slug, 'novalidate'=>'','name'=>'formLms',

          'method'=>'patch', 'files' => true)) }}

          @else

          {!! Form::open(array('url' => '/lms/content/add/after',

          'novalidate'=>'','name'=>'formLms',

          'method' => 'POST', 'files' => true)) !!}

          @endif

          @include('admin.lms.lmscontents.form_elements_after',

          array('button_name'=> $button_name),

          array('record'=>$record,'series_slug'=>$series_slug, 'lms_content_after'=>$lms_content_after, 'flashcard'=>$flashcard, 'handwriting'=>$handwriting, 'pronunciation' => $pronunciation))



          {!! Form::close() !!}

        </div>



      </div>

    </div>

    <!-- /.container-fluid -->

  </div>



  <div class="modal fade bd-example-modal-lg" data-backdrop="static" data-keyboard="false" tabindex="-1">

    <div class="modal-dialog modal-sm">

      <div class="modal-content" style="text-align: center">

        <span class="fa fa-spinner fa-spin fa-3x"></span>

      </div>

    </div>

  </div>

  <!-- /#page-wrapper -->

  @stop

  @section('footer_scripts')

  @include('admin.lms.lmscontents.scripts.js-scripts')

  @include('admin.common.validations', array('isLoaded'=>'1'));

  @include('admin.common.editor');

  @include('admin.common.alertify')

  <script>

  $('#submitForm').click(function(){

    $('#submitForm').css('display','none');

    $('.modal').modal('show');

  })

  </script>

  <script>

  var file = document.getElementById('image_input');



  file.onchange = function(e){

    var ext = this.value.match(/\.([^\.]+)$/)[1];

    switch(ext)

    {

      case 'jpg':

      case 'jpeg':

      case 'png':

      break;

      default:

      alertify.error("{{getPhrase('file_type_not_allowed')}}");

      this.value='';

    }

  };

  </script>

  @stop

