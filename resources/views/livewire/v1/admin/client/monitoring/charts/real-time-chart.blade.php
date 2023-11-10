<div>
    <div class="row pt-3">
        @foreach($cards_real_time as $index => $item)

            @include('partials.v1.chart.variable-card', [
                        "icon_class" => $item['icon'],
                        "color"=>$item['color'],
                        "list_variable_options" => $variables_rt,
                        "list_model_variable" => 'cards_real_time.'.$index.'.list_model_variable',
                        "data" => $item['variables_selected'],
                        "id"=>$index,
                        "real_time_flag" => true
                ])

        @endforeach
        @include("partials.v1.form.form_list",[
                             "col_with"=>12,
                             "mt"=> 4,
                             "mb"=>0,
                             "input_type"=>"text",
                             "list_model" => "variable_chart_id",
                             "list_default" => "Variable...",
                             "list_options" => $variables_rt,
                             "list_option_value"=>"id",
                             "list_option_view"=>"display_name",
                             "list_option_title"=>"",
                    ])
        <div class="col-12 mt-0">
            <div class="box shadow mt-4">
                <div wire:ignore id="chart_real_time">

                </div>
                @if($select_data)
                    <div class="row mt-0">
                        <div class="col-md-4 col-sm-12 mt-0">
                            <div class="p-4" id="phasor_rt"></div>
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
        </div>
    </div>
    <script>
        document.addEventListener('livewire:load', function () {
            const elements = document.querySelectorAll('.animated-element');
            @this.on('animatedRealTime', (e) => {
                elements.forEach(function (element, index) {
                    element.classList.add('animate__animated', 'animate__pulse', 'animate__repeat-2');
                    element.addEventListener('animationend', () => {
                        element.classList.remove('animate__animated', 'animate__pulse', 'animate__repeat-2');
                    });
                });
            })
            var options_real_time = {

                series: [],
                xaxis: {
                    type: 'text'
                },
                chart: {
                    id: 'real_time_chart',
                    type: 'line',
                    height: '450px',
                    animations: {
                        enabled: true,
                        easing: 'linear',

                        dynamicAnimation: {
                            enabled: true,
                            speed: 3000
                        }
                    },
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    }

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
                dataLabels: {
                    enabled: false
                },
                noData: {
                    text: 'Loading...'
                },
                stroke: {
                    curve: 'smooth'
                },
                legend: {
                    show: false
                }
            }

            var chart_real_time = new ApexCharts(document.querySelector("#chart_real_time"), options_real_time);
            chart_real_time.render();

            var phasor;
            var wfSet;
            var sampleData = {
                title: "Sample Data",
                lineFrequency: 100,
                samplesPerCycle: 132,
                data: [
                    {
                        label: "",
                        unit: "Voltage",
                        phase: "1",
                        angle: 0,
                        magnitude: 0,
                    },
                    {
                        label: "",
                        unit: "Voltage",
                        phase: "2",
                        angle: 0,
                        magnitude: 0,
                    },
                    {
                        label: "",
                        unit: "Voltage",
                        phase: "3",
                        angle: 0,
                        magnitude: 0,
                    },
                    {
                        label: "",
                        unit: "Current",
                        phase: "1",
                        angle: 0,
                        magnitude: 0,
                    },
                    {
                        label: "",
                        unit: "Current",
                        phase: "2",
                        angle: 0,
                        magnitude: 0,
                    },
                    {
                        label: "",
                        unit: "Current",
                        phase: "3",
                        angle: 0,
                        magnitude: 0,
                    }
                ]
            };
        @this.on('addPointRealTime', (e) => {
            chart_real_time.updateOptions({
                series: e.series,
                title: {
                    text: e.title,
                },
                noData: {
                    text: e.no_data
                },
            }, true)
            phasor = new ACWF.PhasorDiagram("phasor_rt")
            // wfSet = ACWF.WaveformSet.create(sampleData);
            //phasor.plotWaveformSet(wfSet, 0);
            wfSet = ACWF.WaveformSet.create(e.data);
            phasor.plotWaveformSet(wfSet, 0);

        })
        })
    </script>
</div>








