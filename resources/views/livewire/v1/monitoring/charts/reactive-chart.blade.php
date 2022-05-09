<div class="contenedor-grande">
    <div wire:ignore class="row pt-3">
        @include("partials.v1.form.form_list",[
                                         "col_with"=>2,
                                         "mt"=>4,
                                         "mb"=>0,
                                         "input_type"=>"text",
                                         "list_model" => "time_id",
                                         "list_default" => "Muestreo...",
                                         "list_options" => [
                                                            ['id'=>1, 'display_name'=> 'Minuto'],
                                                            ['id'=>2, 'display_name'=> 'Hora'],


                                                           ],
                                         "list_option_value"=>"id",
                                         "list_option_view"=>"display_name",
                                         "list_option_title"=>"",
                                ])
        @include("partials.v1.form.form_input_icon_button",[
                        "mt"=>4,
                        "input_model"=>"date_range",
                        "icon_class"=>"fas fa-calendar",
                        "placeholder"=>"Seleccione rango de fechas",
                        "col_with"=>6,
                        "input_type"=>"text",
                        "input_name"=>"datetimes",
                        "autocomplete"=> "off",
                        "button_name" => "Borrar",
                        "button_action"=> "restartDateRange"
               ])


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



