@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Detalles de",
            "second_title"=>"tipo de alarma"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.equipos.alertas.tipos.listado",
                    ],

                ]
        ])
    @include("partials.v1.tab.v1.tab",[
                            "edit_function"=>"edit",
                            "tab_titles"=>[
                                                [
                                                    "title"=>"Detalles",

                                                ],

                                           ],

                            "tab_contents"=>[
                                                [
                                                    "view_name"=>"partials.v1.table.primary-details-table",
                                                    "view_values"=>  [
                                                                        "table_info"=>[
                                                                         [
                                                                             "key"=>"Id",
                                                                             "value"=>$alertType->id
                                                                         ],
                                                                         [
                                                                             "key"=>"Nombre",
                                                                             "value"=>$alertType->name
                                                                         ],
                                                                         [
                                                                             "key"=>"Valor interno",
                                                                             "value"=>$alertType->value
                                                                         ],
                                                                         [
                                                                             "key"=>"Unidad de medida",
                                                                             "value"=>$alertType->unit
                                                                         ],

                                                                     ]
                                                            ]
                                                ],

                                          ]
         ])


</div>
