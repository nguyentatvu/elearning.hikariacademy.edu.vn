@extends('admin.layouts.sitelayout')
@section('content')
<?php
$current_theme = getDefaultTheme();
$page_content  = getThemeSetting($key,$current_theme);
?>
<div class="row">
  <div class="col-sm-12">
    
    <div style="padding: 20px 0px">
      
      
        @switch($key) 
          @case('privacy-policy')
            @include('admin.site.privacy')
            @break;
          @case('terms-conditions')
            @include('admin.site.termsconditions')
            @break;
          @case('courses')
            @include('admin.site.course')
            @break;
          @case ('study')
            @include('admin.site.study')
            @break;
          @case ('contact')
            @include('admin.site.contact')
            @break;
          @case ('payment-method')
            @include('admin.site.paymentmethod')
            @break;
          @case ('shop')
            @include('admin.site.price')
            @break;
          @default:
            @include('admin.site.course')
            @break;
        @endswitch

    @if ($key == 'my-courses')
    <div class="row pricing-table">
      <?php foreach ($mycourses as $mycourses_key => $mycourses_value) { ?>
      <div class="col-md-6 col-xl-4 grid-margin stretch-card pricing-card">
        <div class="card border-primary border pricing-card-body">
          <img src="{{ IMAGE_PATH_UPLOAD_LMS_SERIES.$mycourses_value->image}}" style="width: 100%; padding-bottom: 20px">
          <div class="text-center pricing-card-head">
            <h3 class="text-success"><?php echo $mycourses_value->title; ?></h3>
            <p>1 năm</p>
            <!-- <h1 class="font-weight-normal mb-4">Giá: <?php echo $mycourses_value->cost; ?> <img src="/Themes/themeone/assets/images/icon-bank.png" style="width: 30px;"></h1> -->
          </div>
          <?php echo $mycourses_value->short_description; ?>
          <div class="wrapper">
            
            <a href="{{'/learning-management/series/'.$mycourses_value->slug}}" class="btn btn-success btn-block">HỌC NGAY</a>
            
          </div>
          
        </div>
      </div>
      <?php } ?>
      
    </div>
    @endif


    
  </div>
  
</div>
</div>
@stop