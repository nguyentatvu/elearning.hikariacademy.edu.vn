@extends($layout)
@section('content')<div id="page-wrapper">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
                    @if(checkRole(getUserGrade(2)))
                    <li><a href="{{URL_USERS}}">Thanh toán</a> </li>
                    <li><a href="{{URL_USERS}}">Offline</a> </li>
                    <li class="active">{{isset($title) ? $title : ''}}</li>
                    @else
                    <li class="active">{{$title}}</li>
                    @endif
                </ol>
            </div>
        </div>
        @include('admin.errors.errors')
        <!-- /.row -->
        <div class="panel panel-custom " ng-controller="users_controller">
            <div class="panel-heading">
                @if(checkRole(getUserGrade(2))) 
                <div class="pull-right messages-buttons"><a href="/payments-report/offline" class="btn  btn-primary button" >Thanh toán Offline</a></div>
                @endif
                <h1>{{ $title }}  </h1>
            </div>
            <div class="panel-body"> 
                {{ Form::model('', 
                array('url' => ['payments-report/add-offline'], 
                'method'=>'post')) }}
                <!-- <h3>Lớp học</h3> -->
                <?php 
                $user_record = '';
                ?>
                <div class="row">
                    <fieldset class='col-sm-6'>
                        <label for="exampleInputEmail1">Học viên đã có trong hệ thống</label>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <input type="radio" checked="checked" id="available" name="account" value="1" ng-model="account_available" ng-init="account_available=1; accountAvailable(1);" ng-click="accountAvailable(1)">
                                <label for="available"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span> Có </label>
                            </div>
                            <div class="col-md-6">
                                <input type="radio" id="not_available" name="account" value="0" ng-model="account_not_available" ng-click="accountAvailable(0)">
                                <label for="not_available"> <span class="fa-stack radio-button"> <i class="mdi mdi-check active"></i> </span> Không </label>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="sem-parent-container">
                    <div class="row" >
                        <div class="col-md-6">
                            <input  type="hidden" 
                            ng-model="current_user_id" 
                            name="current_user_id" 
                            value="">
                            <input  type="hidden" 
                            ng-model="parent_user_id" 
                            name="parent_user_id" 
                            value="@{{parent_user_id}}">
                            <fieldset class="form-group" ng-show="showSearch">
                                {{ Form::label('search', 'Học viên cần tìm') }}
                                <span class="text-red" >*</span>
                                {{ Form::text('search', $value = null , $attributes = array(
                                'class'         => 'form-control', 
                                'placeholder'   => '',
                                'ng-model'      => 'search',
                                'ng-change'     => 'getParentRecords(search)',
                                )) }}
                            </fieldset>
                            <div >
                                <!-- <p ng-if="parents.length==0 && showSearch">Nhập tên cần tìm</p> -->
                                <table ng-if="parents.length>0" class="table table-striped">
                                    <thead>
                                        <th>Họ tên</th>
                                        <th>Email</th>
                                        <th>SĐT</th>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="item in parents" ng-click="setAsCurrentItem(item)">
                                            <td>@{{item.name}}</td>
                                            <td>@{{item.email}}</td>
                                            <td>@{{item.phone}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6" ng-show="userDetails" >
                            <fieldset class="form-group ">
                                {{ Form::label('parent_name', 'Họ tên') }}
                                <span class="text-red" >*</span>
                                {{ Form::text('parent_name', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '', 'ng-model'=>'parent_name')) }}
                            </fieldset>
                            <fieldset class="form-group ">
                                {{ Form::label('parent_user_name', 'Username') }}
                                <span class="text-red" >*</span>
                                {{ Form::text('parent_user_name', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '', 'ng-model'=>'parent_user_name')) }}
                            </fieldset>
                            <fieldset class="form-group ">
                                {{ Form::label('parent_email', 'Email') }}
                                {{ Form::text('parent_email', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '', 'ng-model'=>'parent_email')) }}
                            </fieldset>
                            <fieldset class="form-group ">
                                {{ Form::label('parent_password', getphrase('password')) }}
                                {{ Form::password('parent_password',$attributes = array('class'=>'form-control')) }}
                            </fieldset>
                            <fieldset class="form-group ">
                                {{ Form::label('money', 'Số tiền (VNĐ)') }}
                                <span class="text-red" >*</span>
                                {{ Form::text('money', $value = null , $attributes = array('class'=>'form-control', 'placeholder' => '', 'required'=>'required')) }}
                            </fieldset>
                            
                            <div class="col-md-12 clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="buttons text-center">
                    <button type="submit" class="btn btn-lg btn-success button">Cập nhật</button>
                </div>
                {!! Form::close() !!}
                <!-- Show table list hoc viên  -->
                <div class="panel panel-custom">
                    <div class="panel-heading">
                        <h1>Danh sách học viên thanh toán online</h1>
                    </div>
                    <div class="panel-body packages">
                        <div> 
                            <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Tên học viên</th>
                                        <th>Số tiền (VNĐ)</th>
                                        <th>Số xu</th>
                                        <th>Ngày nạp</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
@endsection
@section('footer_scripts')
@include('admin.common.validations')
@include('admin.common.alertify')
@include('admin.classes.scripts.js-scripts')
<script src="{{JS}}bootstrap-toggle.min.js"></script>
<script src="{{JS}}jquery.dataTables.min.js"></script>
<script src="{{JS}}dataTables.bootstrap.min.js"></script>
@include('admin.common.datatables', array('route'=> PREFIX.'payments-report/index-offline', 'route_as_url' => 'TRUE', 'excel'=>'0,1,2,3'))
@include('admin.common.deletescript', array('route'=> URL_CLASSES_USER_DELETE))
@stop