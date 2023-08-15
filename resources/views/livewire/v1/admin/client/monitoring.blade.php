@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Monitoreo",
            "second_title"=>($model->alias??$model->name),
        ])

    {{--optiones de cabecera de formulario--}}
    @include("partials.v1.table_nav",
            [ "nav_options"=>[
                       ["button_align"=>"right",
                       "click_action"=>"",
                       "button_icon"=>"fas fa-list",
                       "button_content"=>"Listado de clientes",
                       "target_route"=>"v1.admin.client.list.client",
                       ],
                       [
                        "button_align"=>"right",
                        "button_type"=>"dropdown",
                        "button_icon"=>"fas fa-gear",
                        "button_content"=>"Acciones",
                        "button_options"=>$model->navigatorDropdownOptions()
                        ]

                   ]
           ])


    @include("partials.v1.tab.v1.tab",[

                            "tab_titles"=>[
                                                [
                                                    "title"=>"Historico",
                                                    "action" => "emit('selectHistory')"

                                                ],
                                                [
                                                    "title"=>"Tiempo Real",
                                                    "action" => "emit('selectRealTime')",
                                                    "conditionable"=>true,
                                                    "permissions"=>[
                                                        \App\Models\V1\Admin::class=>\App\Models\V1\TabPermission::CLIENT_MONITORING_REAL_TIME,
                                                        \App\Models\V1\NetworkOperator::class=>\App\Models\V1\TabPermission::CLIENT_MONITORING_REAL_TIME,
                                                        \App\Models\V1\Technician::class=>\App\Models\V1\TabPermission::CLIENT_MONITORING_REAL_TIME,
                                                        ],

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
                                                    "title"=>"BaseLine",
                                                    "action" => "emit('selectBaseLine')"

                                                ],
                                                [
                                                    "title"=>"Reportes y tarifas",
                                                    "action" => "emit('selectReport')"

                                                ],
                                            //    [
                                            //          "title"=>"ON/OFF",
                                            //        "action" => "emit('selectControl')"

                                             //   ],
                                               // [
                                               //     "title"=>"Alertas",
                                               //     "action" => "emit('selectAlert')"
                                               // ],

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
                                                                        "type" => "baseline_data",
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
                                                                        "type" => "report_data",
                                                                        "variables"=>$variables,
                                                                        "client"=>$client,
                                                                        "data_frame"=>$data_frame

                                                                     ]
                                                ],
                                             //   [
                                             //       "view_name"=>"partials.v1.chart.client_monitoring",
                                             //       "view_values"=>  [
                                             //                          "type" => "control_data",
                                             //                           "client"=>$client,
                                             //                        ]
                                             //  ],
                                             //  [
                                             //      "view_name"=>"partials.v1.table.primary-table",
                                             //      "view_values"=>  [
                                             //                          "table_pageable"=>false,
                                             //                          "table_headers"=>[
                                             //                                              "ID"=>'id',
                                             //                                              "Variable"=>'name',
                                             //                                              "Limite min" => "clientAlertConfiguration.min_alert",
                                             //                                              "Limite max" => "clientAlertConfiguration.max_alert",
                                             //                                              "Valor alerta"=>'value',
                                             //                                              "Tipo" => "type",
                                             //                                              "Actualizacion limites"=>'clientAlertConfiguration.updated_at',
                                             //                                              "Fecha"=>'created_at'

                                             //                                          ],
                                             //                          "table_rows"=> $clientAlerts

                                             //                       ]
                                             //  ],

                                            ],



         ])
    <script>
        /*window.onblur = function() {
            console.log("cambio")
        }
        window.onfocus = function() {
            console.log("vuelve")
        }*/
        window.onbeforeunload = function (e) {
            console.log("exit");
        @this.emit('tabChange')
        };
    </script>
</div>

