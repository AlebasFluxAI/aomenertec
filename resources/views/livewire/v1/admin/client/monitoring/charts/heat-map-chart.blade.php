<div>
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

                       "updated_input"=>"",
                        "placeholder"=>"Seleccione rango de fechas",
                        "col_with"=>6,
                        "input_type"=>"text",
                        "input_name"=>"datetime_heat_map",
                        "autocomplete"=> "off",
                        "button_name" => "Borrar",
                        "button_action"=> "selectHeatMap"
               ])


        <div class="col-12 mt-0">
            <div class="box shadow mt-4">
                <div wire:loading>
                    Actualizando Grafica...
                </div>
                <div id="chart_heat_map">

                </div>

            </div>
        </div>
    </div>
    <script>


        document.addEventListener('livewire:load', function () {
            $(function () {
                $('input[name="datetime_heat_map"]').daterangepicker({
                    applyButtonClasses: 'text-primary',
                    timePicker: false,
                    maxSpan: {
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
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    }

                },

                series: [],
                xaxis: {
                    type: 'category',
                    categories: [],
                },
                legend: {
                    show: true,
                    position: 'bottom',
                },
                title: {
                    text: 'Activa (kWh)',
                    align: 'center',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        fontFamily: 'sans-serif',
                        color: '#000'
                    },
                },
                noData: {
                    text: 'Loading...'
                },
                dataLabels: {
                    enabled: true
                },
            }

            var chart_heat_map = new ApexCharts(document.querySelector("#chart_heat_map"), options_heat_map);

            chart_heat_map.render();

        @this.on('changeAxisHeatMap', (e) => {
            ApexCharts.exec('heat_map_chart', "updateOptions", {
                series: e.series_heat_map,
                xaxis: {
                    categories: ["00h", "01h", "02h", "03h", "04h", "05h", "06h", "07h", "08h", "09h", "10h", "11h", "12h", "13h", "14h", "15h", "16h", "17h", "18h", "19h", "20h", "21h", "22h", "23h"],
                },
                title: {
                    text: e.title,

                },
                plotOptions: {
                    heatmap: {
                        colorScale: {
                            ranges: [
                                {
                                    from: 0,
                                    to: (e.max_value) * 0.25,
                                    color: '#00A100',
                                    name: 'Bajo(>0)',
                                },
                                {
                                    from: ((e.max_value) * 0.25),
                                    to: (e.max_value) * 0.5,
                                    color: '#ffcf63',
                                    name: 'Medio(>' + ((e.max_value) * 0.25) + ')',
                                },
                                {
                                    from: ((e.max_value) * 0.5),
                                    to: (e.max_value) * 0.75,
                                    color: '#ff9100',
                                    name: 'Alto(>' + ((e.max_value) * 0.5) + ')',
                                },
                                {
                                    from: ((e.max_value) * 0.75),
                                    to: e.max_value,
                                    color: '#ff0000',
                                    name: 'Extremo(>' + ((e.max_value) * 0.75) + ')',
                                }
                            ]
                        }
                    }
                }
            });
        })

        @this.on('loading8', (e) => {
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
            $('input[name="datetime_heat_map"]').on('apply.daterangepicker', function (ev, picker) {
            @this.emit('dateRangeHeatMap', picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'))
            });
        })
    </script>
</div>



