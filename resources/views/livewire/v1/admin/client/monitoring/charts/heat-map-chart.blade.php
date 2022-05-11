<div class="contenedor-grande">
    <div wire:ignore class="row pt-3">
        @include("partials.v1.form.form_list",[
                                         "col_with"=>4,
                                         "mt"=> 4,
                                         "mb"=>0,
                                         "input_type"=>"text",
                                         "list_model" => "variable_heat_map_id",
                                         "list_default" => "Variable...",
                                         "list_options" => [
                                                ['id'=>2, 'display_name'=>'Activa (kWh)'],
                                                ['id'=>14, 'display_name'=>'Reactiva Inductiva (kVArLh)'],
                                                ['id'=>10, 'display_name'=>'Reactiva Capacitiva (kVArCh)'],

                                                         ],
                                         "list_option_value"=>"id",
                                         "list_option_view"=>"display_name",
                                         "list_option_title"=>"",
                                ])
        @include("partials.v1.form.form_input_icon_button",[
                        "mt"=>4,
                        "input_model"=>"date_range_heat_map",
                        "icon_class"=>"fas fa-calendar",
                        "placeholder"=>"Seleccione rango de fechas",
                        "col_with"=>6,
                        "input_type"=>"text",
                        "input_name"=>"datetime_heat_map",
                        "autocomplete"=> "off",
                        "button_name" => "Borrar",
                        "button_action"=> "editAxisHeatMap"
               ])


        <div  class="col-12 mt-0">
            <div class="box shadow mt-4">
                <div id="chart_heat_map">

                </div>

            </div>
        </div>
    </div>
    <script>



        document.addEventListener('livewire:load', function () {
            $(function() {
                $('input[name="datetime_heat_map"]').daterangepicker({
                    timePicker: false,
                    maxSpan:{
                        days: 15,
                    },

                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                });

            });

            var options_heat_map = {
                chart: {
                    id: 'heat_map_chart',
                    type: 'heatmap',
                    height: '450px',
                    stacked: true,

                },

                series: [],
                xaxis: {
                    type: 'category',
                    categories: [],
                },
                noData: {
                    text: 'Loading...'
                },
                dataLabels: {
                    enabled: false
                },
                plotOptions: {
                    heatmap: {
                        colorScale: {
                            ranges: [
                                {
                                from: 0,
                                to: 1,
                                color: '#00A100',
                                name: 'Bajo',
                                },
                                {
                                    from: 1.1,
                                    to: 2,
                                    color: '#128FD9',
                                    name: 'Medio',
                                },
                                {
                                    from: 2.1,
                                    to: 3,
                                    color: '#FFB200',
                                    name: 'Alto',
                                },
                                {
                                    from: 3.1,
                                    to: 200,
                                    color: '#FFB200',
                                    name: 'Extremo',
                                }
                            ]
                        }
                    }
                }
            }

            var chart_heat_map = new ApexCharts(document.querySelector("#chart_heat_map"), options_heat_map);

            chart_heat_map.render();

        @this.on('changeAxisHeatMap',(e) =>{
            console.log(e.max_value)

            ApexCharts.exec('heat_map_chart', "updateOptions", {
                series: e.series_heat_map,
                xaxis: {
                    categories: ["00h", "01h", "02h", "03h", "04h", "05h", "06h", "07h", "08h", "09h", "10h", "11h", "12h", "13h", "14h", "15h", "16h", "17h", "18h", "19h", "20h", "21h", "22h", "23h"],
                },
                plotOptions: {
                    heatmap: {
                        colorScale: {
                            ranges: [
                                {
                                    from: 0,
                                    to: (e.max_value)*0.25,
                                    color: '#00A100',
                                    name: 'Bajo',
                                },
                                {
                                    from: ((e.max_value)*0.25),
                                    to: (e.max_value)*0.5,
                                    color: '#128FD9',
                                    name: 'Medio',
                                },
                                {
                                    from: ((e.max_value)*0.5),
                                    to: (e.max_value)*0.75,
                                    color: '#FFB200',
                                    name: 'Alto',
                                },
                                {
                                    from: ((e.max_value)*0.75),
                                    to: e.max_value,
                                    color: '#ff0000',
                                    name: 'Extremo',
                                }
                            ]
                        }
                    }
                }
            });
        })

        @this.on('loading8',(e) =>{
            ApexCharts.exec('heat_map_chart', "updateOptions", {
                series: [],
                xaxis: {
                    categories: []
                },
                noData: {
                    text: 'Datos no encontrados'
                }
            });
        })
            $('input[name="datetime_heat_map"]').on('apply.daterangepicker', function(ev, picker) {
            @this.emit('dateRangeHeatMap', picker.startDate.format('YYYY-MM-DD'),picker.endDate.format('YYYY-MM-DD'))
            });
        })
    </script>
</div>



