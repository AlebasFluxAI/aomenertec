@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Detalles de",
            "second_title"=>"Equipo"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["mt"=>4,
         "nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.equipos.listado",
                    ],
                    [
                    "button_align"=>"right",
                    "button_type"=>"dropdown",
                    "button_icon"=>"fas fa-gear",
                    "button_content"=>"Acciones",
                    "button_options"=>$equipment->navigatorDropdownOptions()
                  ]

                ]
        ])
    @include("partials.v1.tab.v1.tab",[

                            "tab_titles"=>[
                                                [
                                                    "title"=>"Detalles",

                                                ],
                                                [
                                                    "title"=>"Cliente",

                                                ],

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
                                                                             "key"=>"Estado",
                                                                             "value"=>__("equipment.".$equipment->status)
                                                                         ],
                                                                         [
                                                                             "key"=>"Tipo de equipo",
                                                                             "value"=>$equipment->equipmentType->type
                                                                         ],
                                                                         [
                                                                             "key"=>"Administrador",
                                                                             "value"=>($equipment->admin?$equipment->admin->id:"") ." - ".($equipment->admin?$equipment->admin->name:"")
                                                                         ],
                                                                         [
                                                                             "key"=>"Operador de red",
                                                                             "value"=>($equipment->networkOperator?$equipment->networkOperator->id:"") ." - ".($equipment->networkOperator?$equipment->networkOperator->name:"")
                                                                         ],
                                                                         [
                                                                             "key"=>"Equipo con multiple conexion?",
                                                                             "value"=>__("boolean.".$equipment->has_multiple_connection)
                                                                         ],

                                                                     ]
                                                            ]
                                                ],
                                               [
                                                  "view_name"=>"partials.v2.table.primary-table",
                                                  "view_values"=>[
                                                             "class_container"=>null,
                                                                "table_pageable"=>true,
                                                                "table_rows"=>$equipment->clients,
                                                                "table_headers"=>[
                                                                          [
                                                                               "col_name" =>"ID",
                                                                               "col_data" =>"id",
                                                                               "col_filter"=>true
                                                                           ],
                                                                           [
                                                                               "col_name" =>"Codigo",
                                                                               "col_data" =>"code",
                                                                               "col_filter"=>true
                                                                           ],
                                                                           [
                                                                               "col_name" =>"Nombre",
                                                                               "col_data" =>"name",
                                                                               "col_filter"=>true
                                                                           ],
                                                                           [
                                                                               "col_name" =>"Apellido",
                                                                               "col_data" =>"last_name",
                                                                               "col_filter"=>true
                                                                           ],
                                                                           [
                                                                               "col_name" =>"Correo electronico",
                                                                               "col_data" =>"email",
                                                                               "col_filter"=>true
                                                                           ],
                                                                           [
                                                                               "col_name" =>"Telefono",
                                                                               "col_data" =>"phone",
                                                                               "col_filter"=>true
                                                                           ],
                                                                            [
                                                                                   "col_name" =>"Operador de red",
                                                                                   "col_data" =>"networkOperator.name",
                                                                                   "col_filter"=>false
                                                                               ],

                                                                            ],
                                                   ],
                                               ],

                                          ]
         ])


</div>
