@extends('admin.layouts.sitelayout')

@section('header_scripts')
<style>
	.error {
      color: red;
   }

</style>
@stop
@section('content')

    <!-- Contact Us Section -->
    <div class="container" style="margin-top: 140px">
      
        <div class="row">
                <div class="col-md-12">
                    <h2>Xác thực tài khoản</h2>
                </div>
        </div>
        <br>
        <div class="row btm50">
            <div class="col-md-12 cs-right-pad-lg">
                <?php echo $status; ?>
            </div>
            <?php if ($key == 'confirm') { ?>
              <div class="col-md-12 cs-right-pad-lg">
                  Vui lòng click vào đây để: <a href="{{URL_USERS_LOGIN}}"> Đăng nhập</a>
              </div>
            <?php } ?>


        </div>
    </div>
    <!-- /Contact Us Section -->

@stop

@section('footer_scripts')



@stop