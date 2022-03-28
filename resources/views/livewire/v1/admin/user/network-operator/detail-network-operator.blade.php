@section("header") {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Detalles de",
            "second_title"=>"operador de red"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.usuarios.operadores.listado",
                    ],

                ]
        ])
    @include("partials.v1.tab.v1.tab",[

                            "tab_titles"=>[
                                                [
                                                    "title"=>"Detalles",

                                                ],

                                                [
                                                    "title"=>"Vendedores",

                                                ],
                                                [
                                                    "title"=>"Supervisores",

                                                ],
                                           ],

                            "tab_contents"=>[
                                                [
                                                    "view_name"=>"partials.v1.table.primary-details-table",
                                                    "view_values"=>  [
                                                                        "table_info"=>[
                                                                         [
                                                                             "key"=>"Id",
                                                                             "value"=>$model->id
                                                                         ],
                                                                         [
                                                                             "key"=>"Nombre",
                                                                             "value"=>$model->name
                                                                         ],
                                                                         [
                                                                             "key"=>"Apellido",
                                                                             "value"=>$model->last_name
                                                                         ],
                                                                         [
                                                                             "key"=>"Correo electronico",
                                                                             "value"=>$model->email
                                                                         ],
                                                                         [
                                                                             "key"=>"Telefono",
                                                                             "value"=>$model->phone
                                                                         ],
                                                                          [
                                                                             "key"=>"Identificacion",
                                                                             "value"=>$model->identification
                                                                         ],
                                                                     ]
                                                            ]
                                                ],
                                                [
                                                   "view_name"=>"partials.v1.table.primary-table",
                                                    "view_values"=>[
                                                                        "table_pageable"=>false,
                                                                       "table_headers"=>["ID"=>"id",
                                                                                         "Nombre"=>"name",
                                                                                          "Apellido"=>"last_name",
                                                                                          "Telefono"=>"phone",
                                                                        ],

                                                                       "table_rows"=>$model->sellers

                                                                   ]
                                                ],
                                                [
                                                   "view_name"=>"partials.v1.table.primary-table",
                                                    "view_values"=>[
                                                                        "table_pageable"=>false,
                                                                       "table_headers"=>["ID"=>"id",
                                                                                         "Nombre"=>"name",
                                                                                          "Apellido"=>"last_name",
                                                                                          "Telefono"=>"phone",
                                                                        ],

                                                                       "table_rows"=>$model->supervisors

                                                                   ]
                                                ]

                                                                                        ]
         ])


</div>
