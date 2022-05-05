<div class="contenedor-grande">
    <div wire:ignore class="row pt-3">


        <div  class="col-12 mt-0">
            <div class="box shadow mt-4">
                <div id="chart_reactive">

                </div>

            </div>
        </div>
    </div>
    <script>



        document.addEventListener('livewire:load', function () {

            var options_reactive = {
                chart: {
                    id: 'reactive_chart',
                    type: 'bar',
                    height: '800px',
                    stacked: true,

                },
                plotOptions: {
                    bar: {
                        horizontal: true
                    }
                },
                series: [],
                xaxis: {
                    categories: [],
                },
                noData: {
                    text: 'Loading...'
                },


            }

            var chart_reactive = new ApexCharts(document.querySelector("#chart_reactive"), options_reactive);

            chart_reactive.render();

             @this.on('changeAxisReactive',(e) =>{

                 ApexCharts.exec('reactive_chart', "updateOptions", {
                     series: e.series_reactive,
                     xaxis: {
                         categories: e.x_axis_reactive
                     }
                 });
             })

            @this.on('loading8',(e) =>{
                ApexCharts.exec('reactive_chart', "updateOptions", {
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



