@extends($layout)
@section('content')
<div id="page-wrapper">
  <div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
      <div class="col-lg-12">
        <ol class="breadcrumb">
          <li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
          <li> <a href="{{URL_STUDENT_EXAM_SERIES_LIST}}">Bộ đề thi</a> </li>
          <li class="active"><?php echo $title; ?></li>
        </ol>
      </div>
    </div>
    <div class="panel panel-custom">
      <div class="panel-body">
        
        <div class="row">
            <div class="col-md-12"> 
              <div class="series-details">
                <h3><?php echo $title; ?> </h3>
              </div>
             </div>

        </div>
        <div class="row">


            


            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

            <script src="https://www.google.com/jsapi?ext.js"></script>

            <script>

                function drawChart() {

                    var data = new google.visualization.DataTable();

                    data.addColumn('number', 'Điểm');

                    data.addColumn('number', 'Phân phối bình thường');

                    function NormalDensityZx(x, Mean, StdDev) {

                        var a = x - Mean;

                        return Math.exp(-(a * a) / (2 * StdDev * StdDev)) / (Math.sqrt(2 * Math.PI) * StdDev);

                    }

                    var chartData = new Array([]);

                    var index = 0;

                    var i = [<?php echo $diem_str; ?>];
                    i.forEach(function(element){
                        chartData[index] = new Array(2);

                      chartData[index][0] = element;

                      chartData[index][1] = NormalDensityZx(element, <?php echo $total_marks_avg; ?>, <?php echo $stdev ?>);

                      index++;
                    });

                    /*for (var i = -4; i < 4.1; i += 0.1) {

                      chartData[index] = new Array(2);

                      chartData[index][0] = i;

                      chartData[index][1] = NormalDensityZx(i, 0, 37.37360773);

                      index++;

                    }*/

                    data.addRows(chartData);

                    options = { height: 600, width: 900, legend: 'none' };

                    options.hAxis = {};

                    options.hAxis.minorGridlines = {};

                    options.hAxis.minorGridlines.count = 12;

                    var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));

                    chart.draw(data, options);

                }

                google.load('visualization', '1', { packages: ['corechart'], callback: drawChart });

                </script>


                <div id="chart_div"></div>


        </div>
       
        <Br>
        
      </div>
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
@stop
@section('footer_scripts')
<script>
  function showInstructions(url) {
    var popup = window.open(url, "_blank", "type=fullWindow,fullscreen,minimizable=no,scrollbars=no,titlebar=no,location=no,dialog=yes,resizable=no");
  //window.open(url, "_blank", ',type=fullWindow,fullscreen,scrollbars=yes');
  if (popup.outerWidth < screen.availWidth || popup.outerHeight < screen.availHeight)
  {
    popup.moveTo(0,0);
    popup.resizeTo(screen.availWidth, screen.availHeight);
  }
  localStorage.clear();
  runner();
}
function runner()
{
  url = localStorage.getItem('redirect_url');
  if(url) {
    localStorage.clear();
    window.location = url;
  }
  setTimeout(function() {
    runner();
  }, 500);
}
</script>
@stop