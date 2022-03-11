@section("header") {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Detalles de",
            "second_title"=>"Equipo"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.equipos.listado",
                    ],

                ]
        ])
    @include("partials.v1.tab.v1.tab",[

                            "tab_titles"=>[
                                                [
                                                    "title"=>"Detalles",

                                                ],
                                                [
                                                    "title"=>"Alarmas",
                                                ]
                                           ],

                            "tab_contents"=>[
                                                [
                                                    "view_name"=>"partials.v1.table.primary-details-table",
                                                    "view_values"=>  [
                                                                        "table_info"=>[
                                                                         [
                                                                             "key"=>"Id",
                                                                             "value"=>$equipment->id
                                                                         ],
                                                                         [
                                                                             "key"=>"Nombre",
                                                                             "value"=>$equipment->name
                                                                         ],
                                                                         [
                                                                             "key"=>"Descripción",
                                                                             "value"=>$equipment->description
                                                                         ],
                                                                         [
                                                                             "key"=>"Serial",
                                                                             "value"=>$equipment->serial
                                                                         ],
                                                                         [
                                                                             "key"=>"Tipo de equipo",
                                                                             "value"=>$equipment->equipment_type->type
                                                                         ],
                                                                     ]
                                                            ]
                                                ],
                                                [
                                                   "view_name"=>"partials.v1.table.primary-table",
                                                    "view_values"=>[
                                                                        "table_pageable"=>false,
                                                                       "table_headers"=>["ID"=>"id",
                                                                                         "Tipo de alarma"=>"alertType.name",
                                                                                         "Valor a alarmar"=>"value",



                                                                        ],

                                                                       "table_rows"=>$equipment->alerts

                                                                   ]
                                                ]
                                          ]
         ])


</div>
