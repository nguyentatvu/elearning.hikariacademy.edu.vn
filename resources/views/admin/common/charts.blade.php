<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script type="text/javascript">
 
<?php
    $index = -1;
?>

@foreach($ids as $id)
    <?php
        $index++;
    ?>
        var ctx = document.getElementById("{{$id}}").getContext("2d");
    <?php
        $cdata = $chart_data;
        if(is_array($chart_data))
        if(isset($chart_data[$index]))
            $cdata = $chart_data[$index]; 
        
        $dataset = $cdata->data;
    ?>
    var myChart = new Chart(ctx, {
        type: '{{$cdata->type}}',
        animation: {
            animateScale: true,
        },
        data: {
            labels: {!! json_encode($dataset->labels) !!},
            datasets: [
                @if(isset($cdata->stack))
                    @isset($cdata->type)
                        {
                            label: {!! json_encode($dataset->dataset_label) !!}, 
                            data: {!! json_encode($dataset->data_view) !!},
                            backgroundColor: {!! json_encode($dataset->bgcolor) !!},
                            borderColor: {!! json_encode($dataset->border_color) !!},
                            borderWidth: 1,
                            stack: 'Stack 0',
                        },
                        {
                            label: {!! json_encode($dataset->dataset_label_default) !!}, 
                            data: {!! json_encode($dataset->data_not_view) !!},
                            backgroundColor: {!! json_encode($dataset->border_color_default) !!},
                            borderColor: {!! json_encode($dataset->border_color_default) !!},
                            borderWidth: 1,
                            stack: 'Stack 0',
                        },
                    @endisset
                @else
                    @isset($cdata->type)
                        {
                            label: {!! json_encode($dataset->dataset_label) !!}, 
                            data: {!! json_encode($dataset->dataset) !!},
                            backgroundColor: {!! json_encode($dataset->bgcolor) !!},
                            borderColor: {!! json_encode($dataset->border_color) !!},
                            borderWidth: 1,
                            stack: 'Stack 0',
                        },
                    @endisset
                @endif
            ],
        },
        options: {
            scales: {
                xAxes: [{
                    stacked: true,
                    gridLines: {
                        display:false
                    },
                }],
                yAxes: [{
                    stacked: true,
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            return value + @if ($id == 'number_of_courses') ' Courses' @elseif($id == 'percentage_of_course') ' Lesson' @else '' @endif;
                        }
                    }
                }]
            },
            title: {
                display: true,
                text: '{{ isset($cdata->title) ? $cdata->title : '' }}'
            },
            plugins: {
                datalabels: {
                    @isset($cdata->stack)
                        formatter: function(value, context) {
                            var dataset = context.chart.data.datasets[context.datasetIndex];
                            var xAxisLabel = context.dataIndex;
                            
                            // Calculate total for the current x-axis label
                            var total = context.chart.data.datasets.reduce(function(sum, dataset) {
                                return sum + (dataset.data[xAxisLabel] || 0);
                            }, 0);

                            // Handle cases where total is 0
                            if (total === 0) {
                                return '0%';
                            }

                            // Calculate percentage
                            var percentage = (value / total * 100).toFixed(1) + '%';
                            return percentage;
                        },
                    @else
                        formatter: function(value, context) {
                            var dataset = context.chart.data.datasets[context.datasetIndex];
                            var total = dataset.data.reduce((acc, val) => acc + val, 0);
                            var percentage = (value / total * 100).toFixed(1) + '%';
                            return percentage;
                        },
                    @endisset
                    color: '#337ab7',
                },
            },
            legend: {
                display: false,
            }
        }
    });
@endforeach

</script>
