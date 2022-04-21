@section("header") {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Detalles de",
            "second_title"=>"administrador"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    [
                        "button_align"=>"right",
                        "click_action"=>"",
                        "button_icon"=>"fas fa-pencil",
                        "button_content"=>"Editar",
                        "target_route"=>"administrar.v1.usuarios.admin.editar",
                        "target_binding"=>"admin",
                        "target_binding_value"=>$model->id
                    ],
                    [
                        "button_align"=>"right",
                        "click_action"=>"",
                        "button_icon"=>"fas fa-list",
                        "button_content"=>"Ver listado",
                        "target_route"=>"administrar.v1.usuarios.admin.listado",
                    ],
                ]
        ])

    @include("partials.v1.tab.v1.tab",[

                            "tab_titles"=>[
                                                [
                                                    "title"=>"Detalles",

                                                ],
                                                  [
                                                    "title"=>"Operadores de red",

                                                ],
                                                [
                                                    "title"=>"Clientes",

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
                                                                             "key"=>"Nit",

                                                                             "value"=>$model->nit
                                                                         ],
                                                                                                [
                                                                             "key"=>"Direccion",

                                                                             "value"=>$model->address
                                                                         ],
                                                                                  [
                                                                             "key"=>"Archivo de estilos",

                                                                             "value"=>$model->css_file_name
                                                                         ],
                                                                           [
                                                                             "key"=>"Logo",
                                                                             "type"=>"image",
                                                                             "value"=>$model->icon?$model->icon->url:"https://aom.enerteclatam.com/images/logo-horizontal.svg"
                                                                         ],
                                                                     ]
                                                            ],


                                                ],
                                                [
                                                   "view_name"=>"partials.v1.table.primary-table",
                                                    "view_values"=>[
                                                                        "table_pageable"=>false,
                                                                       "table_headers"=>["ID"=>"id",
                                                                                         "Nombre"=>"name",
                                                                                         "Correo"=>"email",
                                                                                         "Identificacion"=>"identification",

                                                                        ],
                                                                       "table_rows"=>$model->networkOperators

                                                                   ]
                                                ],
                                                  [
                                                   "view_name"=>"partials.v1.table.primary-table",
                                                    "view_values"=>[
                                                                        "table_pageable"=>false,
                                                                       "table_headers"=>[
                                                                                          "ID"=>"id",
                                                                                         "Nombre"=>"name",
                                                                                         "Correo"=>"email",
                                                                                         "Identificacion"=>"identification",
                                                                        ],

                                                                       "table_rows"=>$model->clients

                                                                   ]
                                                ]



                                                                                        ]
         ])


</div>
