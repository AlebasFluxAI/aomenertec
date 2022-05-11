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
                series: [],
                xaxis: {
                    categories: []
                },
                noData: {
                    text: 'Loading...'
                },
                stroke: {
                    curve: 'smooth'
                }


            }

            var chart_line = new ApexCharts(document.querySelector("#chart_line"), options);

            chart_line.render();
            ApexCharts.exec('line_chart', "updateOptions", {
                series: @js($series),
                xaxis: {
                    categories: @js($x_axis)
                }
            });
            @this.on('changeAxis',(e) =>{

                ApexCharts.exec('line_chart', "updateOptions", {
                    series: e.series,
                    xaxis: {
                        categories: e.x_axis
                    }
                });
            })
            @this.on('loading',(e) =>{
                ApexCharts.exec('line_chart', "updateOptions", {
                    series: [],
                    xaxis: {
                        categories: []
                    },
                    noData: {
                        text: 'Datos no encontrados'
                    }
                });
            })
        })
    </script>
</div>



