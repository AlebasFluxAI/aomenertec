<div class="box shadow mt-4">
    <div id="chart">

    </div>
    <script>


        document.addEventListener('livewire:load', function () {
            var series = []

            @js($variables_selected).forEach( function(item, index, array) {
                if (index == 0){
                    series[index] = {name: item.variable_name, data: @js($L1) }
                }
                if (index == 1){
                    series[index] = {name: item.variable_name, data: @js($L2) }
                }
                if (index == 2){
                    series[index] = {name: item.variable_name, data: @js($L3) }
                }
            });
            var options = {
                chart: {
                    id: 'line_chart',
                    type: 'line',
                    height: '450px'
                },
                series: series,
                xaxis: {
                    categories: @js($x_axis)
                }
            }

            var chart = new ApexCharts(document.querySelector("#chart"), options);

            chart.render();

            @this.on('changeAxis',(e) =>{
                series = []
                e.variables.forEach( function(item, index, array) {
                    if (index == 0){
                        series[index] = {name: item.variable_name, data: e.L1 }
                    }
                    if (index == 1){
                        series[index] = {name: item.variable_name, data: e.L2 }
                    }
                    if (index == 2){
                        series[index] = {name: item.variable_name, data: e.L3 }
                    }
                });
                chart.updateSeries(series)
                ApexCharts.exec('line_chart', "updateOptions", {
                    xaxis: {
                        categories: e.x_axis
                    }
                });
            })
        })
    </script>
</div>



