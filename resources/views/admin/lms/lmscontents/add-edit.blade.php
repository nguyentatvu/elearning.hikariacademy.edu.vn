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
    @include('errors.errors')
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
          array('url' => $URL_LMS_CONTENT_EDIT, 'novalidate'=>'','name'=>'formLms ',
          'method'=>'patch', 'files' => true)) }}
          @else
          {!! Form::open(array('url' => $URL_LMS_CONTENT_ADD,
          'novalidate'=>'','name'=>'formLms ',
          'method' => 'POST', 'files' => true)) !!}
          @endif
          @include('lms.lmscontents.form_elements',
          array('button_name'=> $button_name),
          array('record'=>$record,'series_slug'=>$series_slug))

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
  @include('lms.lmscontents.scripts.js-scripts')
  @include('common.validations', array('isLoaded'=>'1'));
  @include('common.editor');
  @include('common.alertify')
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
  <script>
    $(document).on('change', '#pdf_input', function() {
      const fileName = $('#pdf_input')[0].files[0].name;
      const series_slug = $('input[name="series_slug"').val();

      // const fileNames = $('#pdf_input').val();
      const extension = fileName.substring(fileName.lastIndexOf('.') + 1);

      // Check extension
      if (extension !== 'pdf') {
        alertify.error("File Type Not Allowed");
        $('#pdf_input').val('');
        return;
      }

      // Check file size
      if ($('#pdf_input')[0].files[0]) {
        const size = $('#pdf_input')[0].files[0].size / 1024 / 1024;
        if (size > 10) {
          alertify.error("File size exceeds 10 MB");
          $('#pdf_input').val('');
          return;
        }
      }

      // Call ajax 
      $.ajax({
            headers: {
              'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            url: '{{url('lms/content/checkFile')}}',
            type: 'post',
            dataType: "json",
            data: {
              fileName: fileName,
              series_slug: series_slug,
            },
            beforeSend: function() {
              swal({
                html: true,
                title: 'Đang xử lý vui lòng chờ',
                text: '<img style="position: relative;" src="/public/assets/images/loader.svg" class="loader-img" alt="img">',
                type: '',
                showConfirmButton: false,
                showCancelButton: false,

              });
            },
            success: function(data) {
              if(data.error == 1)
              {
                swal({  
                  title: 'Đã có file cùng tên tồn tại, tiếp tục upload không?',
                  text: "",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: '#8CD4F5',
                  confirmButtonText: "Đồng ý",
                  cancelButtonText: "Hủy bỏ",
                },

                function(isConfirm) {
                  if (isConfirm) {
                    swal.close();

                  } else {
                    $('#pdf_input').val('');
                    swal.close();
                  }

                });

              } else {
                    swal.close();
              }
            }
         });
    });
  
</script>
  @stop
