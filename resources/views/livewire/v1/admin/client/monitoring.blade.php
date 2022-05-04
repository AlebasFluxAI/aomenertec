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
                                                            ]
                                                ],



         ])

</div>

