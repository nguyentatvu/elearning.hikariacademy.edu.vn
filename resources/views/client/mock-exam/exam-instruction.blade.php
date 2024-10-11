@extends('client.app-exam')

@section('header_scripts')
@stop

@section('content')
<div id="page-wrapper" ng-model="academia" ng-controller="instructions" onload="max()">
    <div class="container-fluid" style="padding-top: 40px;">
        <div class="panel panel-custom col-lg-12">
            <div class="panel-heading">
                <h1 class="instruction-title">
                    <span>Hướng dẫn</span>
                    <span class="pull-right text-italic"> Xin vui lòng đọc kỹ các hướng dẫn</span>
                    <span class="collapse text-italic text-error exam-experience-warning">(Lưu ý: Sử dụng PC hoặc máy tính bảng để có trải nghiệm thi đầy đủ và tốt nhất.)</span>
                </h1>
            </div>
            <div class="panel-body instruction no-arrow" style="padding: 0px 0px 30px 0px">
                <div class="row">
                    <div class="col-md-12">
                        <h2 style="color: blue; display: none">
                            <strong>{{change_furigana_text ($record->title)}}</strong>
                        </h2>
                        @if($instruction_data=='')
                        <h3>{{getPhrase('general_instructions')}}:</h3>
                        @else
                        <h3>{{$instruction_title}}:</h3>
                        @endif
                        {!! $instruction_data !!}
                    </div>
                </div>
                <hr style="margin: 10px 0">
                <?php
                    $paid_type = $record->is_paid && !isItemPurchased($record->id, 'exam');
                ?>
                <div class="form-group row">
                    {!! Form::open(['url' => route('mock-exam.start-exam', $record->slug), 'method' => 'POST']) !!}
                    <div class="col-md-12">
                        <input type="checkbox" name="option" id="free" checked="" ng-model="agreeTerms">
                            <label for="free" class="take-exam-wrapper">
                                <span class="fa-stack checkbox-button">
                                    <i class="mdi mdi-check active"></i>
                                </span>
                                <span style="vertical-align: sub">Tôi đã đọc và hiểu các hướng dẫn nêu trên.</span>
                            </label>
                        <div class="text-danger instruction-click" ng-show="!agreeTerms">
                            <strong> Vui lòng click vào đây trước khi thi </strong>
                        </div>
                        <div class="text-center">
                            <button ng-if="agreeTerms" class="btn button btn-lg btn-success">Thi ngay</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer_scripts')
<script src="{{ asset('/js/client/mock-exam/angular.js')}}"></script>
<script>
    var app = angular.module('academia', []);
    app.controller('instructions', function($scope, $http) {});
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $.is_fs = false;

        $.requestFullScreen = function(calr) {
            var element = document.body;
            var requestMethod = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullScreen;
            if (requestMethod) {
                requestMethod.call(element);
            } else if (typeof window.ActiveXObject !== "undefined") {
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
            $.is_fs = true;
            $(calr).val('Exit Full Screen');
        }

        $.cancel_fs = function(calr) {
            var element = document;
            var requestMethod = element.exitFullScreen || element.mozCancelFullScreen || element.webkitExitFullScreen || element.mozExitFullScreen || element.msExitFullScreen || element.webkitCancelFullScreen;
            if (requestMethod) {
                requestMethod.call(element);
            } else if (typeof window.ActiveXObject !== "undefined") {
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
            $(calr).val('Full Screen');
            $.is_fs = false;
        }

        $.toggleFS = function(calr) {
            $.is_fs == true ? $.cancel_fs(calr) : $.requestFullScreen(calr);
        }
    });

    window.fullScreen = true;
</script>
@stop