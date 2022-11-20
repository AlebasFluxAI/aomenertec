@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Detalles de",
            "second_title"=>"usuario de soporte"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.usuarios.soporte.listado",
                    ],

                ]
        ])
    @include("partials.v1.tab.v1.tab",[

                            "tab_titles"=>[
                                                [
                                                    "title"=>"Detalles",

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
                                                                             "key"=>"Identificación",
                                                                             "value"=>$model->identification
                                                                         ],
                                                                         [
                                                                             "key"=>"Telefono",
                                                                             "value"=>$model->phonePlusIndicative
                                                                         ],

                                                                         [
                                                                             "key"=>"Pais",
                                                                             "value"=>$model->country
                                                                         ],
                                                                          [
                                                                             "key"=>"Departamento",
                                                                             "value"=>$model->state
                                                                         ],
                                                                          [
                                                                             "key"=>"Ciudad",
                                                                             "value"=>$model->city
                                                                         ],
                                                                         [
                                                                             "key"=>"Direccion",
                                                                             "value"=>$model->address
                                                                         ],

                                                                     ]
                                                            ],


                                                ],
                                                [
                                                    "view_name"=>"partials.v1.table.primary-table",
                                                    "view_values"=>  [

                                                                              "table_pageable"=>false,
                                                                               "table_headers"=>[
                                                                                        "ID"=>"client.id",
                                                                                        "Nombre"=>"client.name",
                                                                                        "Apellido"=>"client.last_name",
                                                                                        "Correo electronico"=>"client.email",
                                                                                        "Telefono"=>"client.phone",
                                                                                 ],
                                                                               "table_rows"=>$model->clientSupports,
                                                                                  ]
                                                                            ]


                                                ],


         ])


</div>
