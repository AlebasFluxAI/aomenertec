<div class="box shadow mt-4">
    <div id="chart_line">

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
                    type: @js($chart_type),
                    height: '450px',

                },
                series: series,
                xaxis: {
                    categories: @js($x_axis)
                },
                stroke: {
                    curve: 'smooth'
                }

            }

            var chart_line = new ApexCharts(document.querySelector("#chart_line"), options);

            chart_line.render();

            @this.on('changeAxis',(e) =>{
                series = []
                console.log(@js($chart_type))
                e.variables.forEach( function(item, index, array) {
                    if (index == 0){
                        series[index] = {name: item.variable_name, type: e.chart_type, data: e.L1 }
                    }
                    if (index == 1){
                        series[index] = {name: item.variable_name, type: e.chart_type, data: e.L2 }
                    }
                    if (index == 2){
                        series[index] = {name: item.variable_name, type: e.chart_type, data: e.L3 }
                    }
                });

                ApexCharts.exec('line_chart', "updateOptions", {
                    series: series,
                    xaxis: {
                        categories: e.x_axis
                    }
                });
            })
        })
    </script>
</div>



