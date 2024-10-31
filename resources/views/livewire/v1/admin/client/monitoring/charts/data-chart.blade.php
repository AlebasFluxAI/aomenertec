<div class="row pt-3">

    @include("partials.v1.form.form_list",[
                         "col_with"=>4,
                         "mt"=> 4,
                         "mb"=>0,
                         "input_type"=>"text",
                         "list_model" => "variable_chart_id",
                         "list_default" => "Variable...",
                         "list_options" => $variables,
                         "list_option_value"=>"id",
                         "list_option_view"=>"display_name",
                         "list_option_title"=>"",
                ])
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
                                            ['id'=>3, 'display_name'=> 'Dia'],
                                            ['id'=>4, 'display_name'=> 'Mes'],


                                           ],
                         "list_option_value"=>"id",
                         "list_option_view"=>"display_name",
                         "list_option_title"=>"",
                ])
    @include("partials.v1.form.form_input_icon_button",[
                    "mt"=>4,
                    "input_model"=>"date_range",
                    "icon_class"=>"fas fa-calendar",
                    "updated_input"=>"",
                    "placeholder"=>"Seleccione rango de fechas",
                    "col_with"=>6,
                    "input_type"=>"text",
                    "input_name"=>"datetimes",
                    "autocomplete"=> "off",
                    "button_name" => "Borrar",
                    "button_action"=> "restartDateRange"
           ])
    <div class="col-12 mt-0">
        <div class="box shadow mt-4">
            <div wire:loading>
                Actualizando Grafica...
            </div>
            <div wire:ignore id="chart_line">

            </div>

        </div>
    </div>


    <div wire:ignore.self class="modal fade" id="modal_phasor" tabindex="-1" role="dialog"
         aria-labelledby="ModalLabel_phasor" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel_phasor">Diagrama Fasorial</h5>
                    <a onclick="$('#modal_phasor').modal('hide');" type="button"
                       class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></a>
                </div>
                <div class="modal-body">
                    @if($select_data)
                        <div class="row mt-0">
                            <div class="col-md-4 col-sm-12 mt-0">
                                <div class="p-4" id="phasor"></div>
                            </div>
                            <div class="p-4 col-md-8 col-sm-12 mt-0 align-items-center">
                                <table class="table table-sm text-center">
                                    <thead>
                                    <tr>
                                        <th scope="col">UNIDAD</th>
                                        <th class="table-warning" scope="col">L1</th>
                                        <th class="table-primary" scope="col">L2</th>
                                        <th class="table-danger" scope="col">L3</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th class="text-bold" scope="row">VOLTAJE (V)</th>
                                        <td class="table-warning">{{ ($select_data['data'][0])['magnitude'] }}</td>
                                        <td class="table-primary">{{ ($select_data['data'][1])['magnitude'] }}</td>
                                        <td class="table-danger">{{ ($select_data['data'][2])['magnitude'] }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-bold" scope="row">ANGULO (°)</th>
                                        <td class="table-warning">{{ ($select_data['data'][0])['degrees'] }}</td>
                                        <td class="table-primary">{{ ($select_data['data'][1])['degrees'] }}</td>
                                        <td class="table-danger">{{ ($select_data['data'][2])['degrees'] }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-bold" scope="row">CORRIENTE (A)</th>
                                        <td class="table-warning">{{ ($select_data['data'][3])['magnitude'] }}</td>
                                        <td class="table-primary">{{ ($select_data['data'][4])['magnitude'] }}</td>
                                        <td class="table-danger">{{ ($select_data['data'][5])['magnitude'] }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-bold" scope="row">ANGULO (°)</th>
                                        <td class="table-warning">{{ ($select_data['data'][3])['degrees'] }} </td>
                                        <td class="table-primary">{{ ($select_data['data'][4])['degrees'] }}</td>
                                        <td class="table-danger">{{ ($select_data['data'][5])['degrees'] }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-bold" scope="row">V1 - I1 (°)</th>
                                        <td class="table-warning">{{ ($select_data['data'][3])['relationship_degrees'] }} </td>
                                        <td class="table-primary">{{ ($select_data['data'][4])['relationship_degrees'] }}</td>
                                        <td class="table-danger">{{ ($select_data['data'][5])['relationship_degrees'] }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-bold" scope="row">TIPO SISTEMA</th>
                                        <td class="table-warning">{{ ($select_data['data'][0])['system_type'] }}</td>
                                        <td class="table-primary">{{ ($select_data['data'][1])['system_type'] }}</td>
                                        <td class="table-danger">{{ ($select_data['data'][2])['system_type']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="table-active text-bold" scope="row" colspan="4">DESEQUILIBRIO</th>
                                    </tr>
                                    <tr>
                                        <th class="text-bold" scope="row" colspan="2">VOLTAJE (V2/V1)</th>
                                        <td>%</td>
                                        <td>{{ $select_data['percent_volt'] }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-bold" scope="row" colspan="2">CORRIENTE (I2/I1)</th>
                                        <td>%</td>
                                        <td>{{ $select_data['percent_curr'] }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>


    <script>

        $(function () {
            $('input[name="datetimes"]').daterangepicker({
                applyButtonClasses: 'text-primary',
                timePicker: true,
                timePicker24Hour: true,
                locale: {
                    format: 'YYYY-MM-DD HH:mm'
                }
            });

        });


        document.addEventListener('livewire:load', function () {

            @this.on('chartPhasor', (e) => {
                var phasor = new ACWF.PhasorDiagram("phasor");
                var wfSet = ACWF.WaveformSet.create(e.data);
                phasor.plotWaveformSet(wfSet, 0);
                $('#modal_phasor').modal('show');


            })


            var options = {
                chart: {
                    id: 'line_chart',
                    type: @js($chart_type),
                    height: '450px',
                    width: '100%',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 300
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    },
                    events: {
                        dataPointSelection: function (event, chartContext, config) {
                            // The last parameter config contains additional information like `seriesIndex` and `dataPointIndex` for cartesian charts

                            if (config.dataPointIndex > 0) {
                            @this.emitSelf('setPointPhasor', config.dataPointIndex)
                            }


                        }
                    },

                },
                tooltip: {
                    enabled: true,
                    shared: true,
                    intersect: false,
                },
                markers: {
                    size: 1,

                },
                colors: [function ({value, seriesIndex, w}) {
                    if ((w.config.series).length > 1) {
                        if (seriesIndex == 0) {
                            return '#FFF000';
                        } else if (seriesIndex == 1) {
                            return '#000FFF';
                        } else {
                            return '#FF0000'
                        }
                    } else {
                        return '#2D45BD';
                    }

                }],
                series: [],
                xaxis: {
                    categories: []
                },
                noData: {
                    text: 'Sin datos'
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
                dataLabels: {
                    enabled: (@js($chart_type)== 'column') ? true : false
        },
        })
            ;


        @this.on('changeAxis', (e) => {

            ApexCharts.exec('line_chart', "updateOptions", {
                series: e.series,
                xaxis: {
                    categories: e.x_axis
                },
                title: {
                    text: e.title,
                },
                dataLabels: {
                    enabled: (e.type == 'column') ? true : false
                },
            });
        })
        @this.on('loading', (e) => {
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
            $('input[name="datetimes"]').on('apply.daterangepicker', function (ev, picker) {
            @this.emit('changeDateRange', picker.startDate.format('YYYY-MM-DD HH:mm:00'), picker.endDate.format('YYYY-MM-DD HH:mm:00'))
            });
        })
    </script>


</div>




