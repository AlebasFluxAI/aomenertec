<div class="box shadow mt-4">
    <div id="chart_line">

    </div>
    <script>


        document.addEventListener('livewire:load', function () {
            var options = {
                chart: {
                    id: 'line_chart',
                    type: @js($chart_type),
                    height: '450px',

                },
                series: @js($series),
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

                ApexCharts.exec('line_chart', "updateOptions", {
                    series: e.series,
                    xaxis: {
                        categories: e.x_axis
                    }
                });
            })
        })
    </script>
</div>



