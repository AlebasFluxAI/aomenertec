<div class="row pt-3">
    @include("partials.v1.form.form_input_icon_button",[
                    "mt"=>4,
                    "input_model"=>"date_range_reference",
                    "icon_class"=>"fas fa-calendar",
                    "updated_input"=>"",
                    "placeholder"=>"Seleccione rango de fechas",
                    "col_with"=>6,
                    "input_type"=>"text",
                    "input_label"=>"Datos de referencia",
                    "input_name"=>"datetimes_baseline_reference",
                    "autocomplete"=> "off",
                    "button_name" => "Borrar",
                    "button_action"=> "restartDateRange"
           ])
    @include("partials.v1.form.form_input_icon_button",[
                    "mt"=>4,
                    "input_model"=>"date_range_result",
                    "icon_class"=>"fas fa-calendar",
                    "updated_input"=>"",
                    "placeholder"=>"Seleccione rango de fechas",
                    "col_with"=>6,
                    "input_type"=>"text",
                    "input_label"=>"Datos a comparar",
                    "input_name"=>"datetimes_baseline_result",
                    "autocomplete"=> "off",
                    "button_name" => "Borrar",
                    "button_action"=> "restartDateRange"
           ])

    @include("partials.v1.form.form_list",[
                         "col_with"=>4,
                         "mt"=> 4,
                         "mb"=>0,
                         "input_type"=>"text",
                         "list_model" => "variable_chart_id",
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
    @include("partials.v1.form.form_list",[
                         "col_with"=>2,
                         "mt"=>4,
                         "mb"=>0,
                         "input_type"=>"text",
                         "list_model" => "time_id_baseline",
                         "list_default" => "Muestreo...",
                         "list_options" => [
                                            ['id'=>2, 'display_name'=> 'Hora'],
                                            ['id'=>3, 'display_name'=> 'Dia'],
                                            ['id'=>4, 'display_name'=> 'Mes'],
                                           ],
                         "list_option_value"=>"id",
                         "list_option_view"=>"display_name",
                         "list_option_title"=>"",
                ])
    <div class="col-12 mt-0">
        <div class="box shadow mt-4">
            <div wire:loading>
                Actualizando Grafica...
            </div>
            <div id="chart_baseline">

            </div>
        </div>
    </div>
    <script>

        $(function () {
            $('input[name="datetimes_baseline_reference"]').daterangepicker({
                applyButtonClasses: 'text-primary',
                timePicker: true,
                timePicker24Hour: true,
                locale: {
                    format: 'YYYY-MM-DD HH:mm'
                }
            });

        });
        $(function () {
            $('input[name="datetimes_baseline_result"]').daterangepicker({
                applyButtonClasses: 'text-primary',
                timePicker: true,
                timePicker24Hour: true,
                locale: {
                    format: 'YYYY-MM-DD HH:mm'
                }
            });

        });


        document.addEventListener('livewire:load', function () {
            var options = {
                chart: {
                    id: 'baseline_chart',
                    type: @js($chart_type),
                    height: '550px',
                    animations: {
                        enabled: false
                    },
                    events: {
                        click: function (event, chartContext, config) {
                            if (((config.config.series[0].data[config.dataPointIndex]) - (config.config.series[1].data[config.dataPointIndex])).toFixed(2) >= 0) {
                                var text = 'Ahorro: ' + ((config.config.series[0].data[config.dataPointIndex]) - (config.config.series[1].data[config.dataPointIndex])).toFixed(2)
                                var color = '#4CAF50'
                            } else {
                                var text = 'Extra consumo: ' + ((config.config.series[0].data[config.dataPointIndex]) - (config.config.series[1].data[config.dataPointIndex])).toFixed(2)
                                var color = '#D4526E'
                            }
                            chart_baseline.addXaxisAnnotation({
                                x: config.globals.categoryLabels[config.dataPointIndex],
                                borderColor: color,
                                label: {
                                    text: text,
                                    borderColor: color,
                                    style: {
                                        color: '#fff',
                                        background: color
                                    },
                                },
                            }, false)
                            // The last parameter config contains additional information like `seriesIndex` and `dataPointIndex` for cartesian charts
                        }
                    }


                },
                legend: {
                    show: true,
                    position: 'top',
                },
                colors: ['#2E93fA', '#546E7A'],
                series: [],
                xaxis: {
                    categories: []
                },
                noData: {
                    text: 'Sin datos'
                },
                stroke: {
                    curve: 'smooth'
                },


            }

            var chart_baseline = new ApexCharts(document.querySelector("#chart_baseline"), options);

            chart_baseline.render();
            var pos1 = 0;
            var pos2 = @js($series)[0].data[0];

                @js($series)
            [0].data.forEach(function (element) {
                if (pos1 < element) {
                    pos1 = element
                }
            });
                @js($series)
            [1].data.forEach(function (element) {
                if (pos2 > element) {
                    pos2 = element
                }
            });
            ApexCharts.exec('baseline_chart', "updateOptions", {
                series: @js($series),
                xaxis: {
                    categories: @js($x_axis)
                },
                title: {
                    text: @js($chart_title),
                    align: 'center',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        fontFamily: 'sans-serif',
                        color: '#000'
                    },
                },
                annotations: {
                    yaxis: [
                        {
                            y: pos1,
                            borderColor: '#2E93fA',
                            label: {
                                borderColor: '#2E93fA',
                                style: {
                                    color: '#fff',
                                    background: '#2E93fA',
                                    padding: {
                                        left: 5,
                                        right: 5,
                                        top: 3,
                                        bottom: 3,
                                    }
                                },
                                text: (@js($series)[0].data.reduce((a, b) => a + b, 0)).toFixed(2)
                            }
                        },
                        {
                            y: (pos1 + pos2) / 2,
                            borderColor: ((@js($series)[0].data.reduce((a, b) => a + b, 0)) >= (@js($series)[1].data.reduce((a, b) => a + b, 0))) ? '#4CAF50' : '#D4526E',
                            label: {
                                borderColor: ((@js($series)[0].data.reduce((a, b) => a + b, 0)) >= (@js($series)[1].data.reduce((a, b) => a + b, 0))) ? '#4CAF50' : '#D4526E',
                                style: {
                                    color: '#fff',
                                    background: ((@js($series)[0].data.reduce((a, b) => a + b, 0)) >= (@js($series)[1].data.reduce((a, b) => a + b, 0))) ? '#4CAF50' : '#D4526E',
                                    padding: {
                                        left: 5,
                                        right: 5,
                                        top: 3,
                                        bottom: 3,
                                    }
                                },
                                text: ((@js($series)[0].data.reduce((a, b) => a + b, 0)) >= (@js($series)[1].data.reduce((a, b) => a + b, 0))) ? 'Ahorro: ' + (100 - ((@js($series)[1].data.reduce((a, b) => a + b, 0)) * 100 / (@js($series)[0].data.reduce((a, b) => a + b, 0)))).toFixed(3) + "%" : 'Extra consumo: ' + (100 - ((@js($series)[1].data.reduce((a, b) => a + b, 0)) * 100 / (@js($series)[0].data.reduce((a, b) => a + b, 0)))).toFixed(3) + "%"
                            }
                        },
                        {
                            y: pos2,
                            borderColor: '#546E7A',
                            label: {
                                borderColor: '#546E7A',
                                style: {
                                    color: '#fff',
                                    background: '#546E7A',
                                    padding: {
                                        left: 5,
                                        right: 5,
                                        top: 3,
                                        bottom: 3,
                                    }
                                },
                                text: (@js($series)[1].data.reduce((a, b) => a + b, 0)).toFixed(2)
                            }
                        },
                    ]
                }

            });
        @this.on('changeAxis', (e) => {
            var pos1 = 0;
            var pos2 = e.series[1].data[0];
            var value_pos2 = (e.accumulated_result[1] - e.accumulated_result[0]).toFixed(2)
            var value_pos1 = (e.accumulated_reference[1] - e.accumulated_reference[0]).toFixed(2)
            e.series[0].data.forEach(function (element) {
                if (pos1 < element) {
                    pos1 = element
                }
            });
            e.series[1].data.forEach(function (element) {
                if (pos2 > element) {
                    pos2 = element
                }
            });


            ApexCharts.exec('baseline_chart', "updateOptions", {
                series: e.series,
                xaxis: {
                    categories: e.x_axis
                },
                title: {
                    text: e.title,
                },
                annotations: {
                    yaxis: [
                        {
                            y: pos1,
                            borderColor: '#2E93fA',
                            label: {
                                borderColor: '#2E93fA',
                                style: {
                                    color: '#fff',
                                    background: '#2E93fA',
                                    padding: {
                                        left: 5,
                                        right: 5,
                                        top: 3,
                                        bottom: 3,
                                    }
                                },
                                text: value_pos1
                            }
                        },

                        {
                            y: (pos1 + pos2) / 2,
                            borderColor: ((value_pos1) >= (value_pos2)) ? '#4CAF50' : '#D4526E',
                            label: {
                                borderColor: ((value_pos1) >= (value_pos2)) ? '#4CAF50' : '#D4526E',
                                style: {
                                    color: '#fff',
                                    background: ((value_pos1) >= (value_pos2)) ? '#4CAF50' : '#D4526E',
                                    padding: {
                                        left: 5,
                                        right: 5,
                                        top: 3,
                                        bottom: 3,
                                    }
                                },
                                text: ((value_pos1) >= (value_pos2)) ? 'Ahorro: ' + (100 - (value_pos2 * 100 / (value_pos1))).toFixed(3) + "%" : 'Extra consumo: ' + (100 - (value_pos2 * 100 / (value_pos1))).toFixed(3) + "%"
                            }
                        },
                        {
                            y: pos2,
                            borderColor: '#546E7A',
                            label: {
                                borderColor: '#546E7A',
                                style: {
                                    color: '#fff',
                                    background: '#546E7A',
                                    padding: {
                                        left: 5,
                                        right: 5,
                                        top: 3,
                                        bottom: 3,
                                    }
                                },
                                text: value_pos2
                            }
                        },
                    ]
                }
            });
        })
        @this.on('loading', (e) => {
            ApexCharts.exec('baseline_chart', "updateOptions", {
                series: [],
                xaxis: {
                    categories: []
                },
                noData: {
                    text: 'Datos no encontrados'
                }
            });
        })
            $('input[name="datetimes_baseline_result"]').on('apply.daterangepicker', function (ev, picker) {
            @this.emit('changeDateRangeResult', picker.startDate.format('YYYY-MM-DD 00:00:00'), picker.endDate.format('YYYY-MM-DD 23:59:59'))
            });
            $('input[name="datetimes_baseline_reference"]').on('apply.daterangepicker', function (ev, picker) {
            @this.emit('changeDateRangeReference', picker.startDate.format('YYYY-MM-DD 00:00:00'), picker.endDate.format('YYYY-MM-DD 23:59:59'))
            });
        })
    </script>
</div>








