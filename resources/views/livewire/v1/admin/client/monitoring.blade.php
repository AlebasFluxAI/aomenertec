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
                                                    "title"=>"Grafica",

                                                ],
                                                [
                                                    "title"=>"Reactivos",
                                                    "action" => "emit('editAxisReactive')"

                                                ],

                                                [
                                                    "title"=>"Heat Map",
                                                    "action" => "emit('editAxisHeatMap')"

                                                ],

                                           ],

                            "tab_contents"=>[
                                                [
                                                    "view_name"=>"partials.v1.chart.monitoring",
                                                    "view_values"=>  [
                                                                        "cards"=>$cards,
                                                                        "variables"=>$variables,
                                                                        "client"=>$client,
                                                                        "variables_selected"=>$variables_selected,
                                                                        "time_id"=>$time_id,
                                                                        "chart_type"=>$chart_type,
                                                                        "data_chart" => $data_chart

                                                                     ]
                                                ],
                                                [
                                                    "view_name"=>"partials.v1.chart.reactive_data",
                                                    "view_values"=>  [

                                                                        "variables"=>$reactive_variables,
                                                                        "client"=>$client,
                                                                        "data_chart"=>$data_chart

                                                                     ]
                                                ],
                                                [
                                                    "view_name"=>"partials.v1.chart.heatmap_data",
                                                    "view_values"=>  [

                                                                        "variables"=>$reactive_variables,
                                                                        "client"=>$client,
                                                                        "data_chart"=>$data_chart

                                                                     ]
                                                ],

                                            ],



         ])

</div>

