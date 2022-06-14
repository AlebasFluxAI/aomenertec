@section("header") {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Monitoreo",
            "second_title"=>$client->name
        ])

    {{--optiones de cabecera de formulario--}}



    @include("partials.v1.tab.v1.tab",[

                            "tab_titles"=>[
                                                [
                                                    "title"=>"Historico",
                                                    "action" => "emit('selectHistory')"

                                                ],
                                                [
                                                    "title"=>"Tiempo Real",
                                                    "action" => "emit('selectRealTime')"

                                                ],

                                                [
                                                    "title"=>"Reactivos",
                                                    "action" => "emit('selectReactive')"

                                                ],

                                                [
                                                    "title"=>"HeatMap",
                                                    "action" => "emit('selectHeatMap')"

                                                ],
                                                [
                                                    "title"=>"Reportes",
                                                    "action" => "emit('selectReport')"

                                                ],
                                                [
                                                    "title"=>"ON/OFF",
                                                    "action" => "emit('selectControl')"

                                                ],
                                                [
                                                    "title"=>"Alertas",
                                                    "action" => "emit('selectAlert')"
                                                ],

                                           ],

                            "tab_contents"=>[
                                                [
                                                    "view_name"=>"partials.v1.chart.client_monitoring",
                                                    "view_values"=>  [
                                                                        "type" => "history_data",
                                                                        "variables"=>$variables,
                                                                        "client"=>$client,
                                                                        "data_frame"=>$data_frame,
                                                                        "data_chart" => $data_chart,
                                                                        "time" => $time

                                                                     ]
                                                ],
                                                [
                                                    "view_name"=>"partials.v1.chart.client_monitoring",
                                                    "view_values"=>  [
                                                                        "type" => "real_time_data",
                                                                        "variables"=>$variables,
                                                                        "client"=>$client,
                                                                        "data_frame"=>$data_frame,
                                                                     ]
                                                ],

                                                [
                                                    "view_name"=>"partials.v1.chart.client_monitoring",
                                                    "view_values"=>  [
                                                                        "type" => "reactive_data",
                                                                        "variables"=>$reactive_variables,
                                                                        "client"=>$client,
                                                                        "data_chart"=>$data_chart,
                                                                        "time" => $time

                                                                     ]
                                                ],
                                                [
                                                    "view_name"=>"partials.v1.chart.client_monitoring",
                                                    "view_values"=>  [
                                                                        "type" => "heatmap_data",
                                                                        "variables"=>$reactive_variables,
                                                                        "client"=>$client,
                                                                        "data_chart"=>$data_chart

                                                                     ]
                                                ],
                                                [
                                                    "view_name"=>"partials.v1.chart.client_monitoring",
                                                    "view_values"=>  [
                                                                        "type" => "report_data",
                                                                        "variables"=>$variables,
                                                                        "client"=>$client,
                                                                        "data_frame"=>$data_frame

                                                                     ]
                                                ],
                                                [
                                                    "view_name"=>"partials.v1.chart.client_monitoring",
                                                    "view_values"=>  [
                                                                        "type" => "control_data",
                                                                        "client"=>$client,
                                                                     ]
                                                ],
                                                [
                                                    "view_name"=>"partials.v1.table.primary-table",
                                                    "view_values"=>  [
                                                                        "table_pageable"=>false,
                                                                        "table_headers"=>[
                                                                                            "ID"=>'id',
                                                                                            "Variable"=>'flag_index',
                                                                                            "Valor"=>'value',
                                                                                            "Fecha"=>'created_at'

                                                                                        ],
                                                                        "table_rows"=>$client->alerts

                                                                     ]
                                                ],

                                            ],



         ])
    <script>
        /*window.onblur = function() {
            console.log("cambio")
        }
        window.onfocus = function() {
            console.log("vuelve")
        }*/
        window.onbeforeunload = function(e) {
        @this.emit('tabChange')
        };
    </script>
</div>

