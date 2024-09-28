@extends($layout)
@section('content')
<div id="page-wrapper">
    <div class="card mb-0">
        <div class="card-header">
            <h3 class="card-title">
                {{$title}}
            </h3>
        </div>
        <?php $button_name = getPhrase('create'); ?>
        @if ($record)
        <?php $button_name = 'Cập nhật thông tin'; ?>
        {{ Form::model($record, 
        array('url' => '/users/profile/'.$record->slug, 
        'method'=>'patch','novalidate'=>'','name'=>'formUsers ', 'files'=>'true' )) }}
        @else
        {!! Form::open(array('url' => URL_USERS_ADD, 'method' => 'POST', 'novalidate'=>'','name'=>'formUsers ', 'files'=>'true')) !!}
        @endif
        <div class="card-body">
            @include('admin.users.form_elements_profile', array('button_name'=> $button_name, 'record' => $record))
        </div>
        <div class="card-footer">
            <button class="btn btn-primary" 
            ng-disabled='!formUsers.$valid'>{{ $button_name }}</button>
        </div>
         {!! Form::close() !!}
    </div>
    @endsection
@section('footer_scripts')
@include('admin.common.validations')
@include('admin.common.alertify')
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
</div>