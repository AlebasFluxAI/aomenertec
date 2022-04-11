@section("header") {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Detalles de",
            "second_title"=>"supervisor"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.usuarios.supervisores.listado",
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
                                                                             "value"=>$model->user->id
                                                                         ],
                                                                         [
                                                                             "key"=>"Nombre",
                                                                             "value"=>$model->user->name
                                                                         ],
                                                                         [
                                                                             "key"=>"Apellido",
                                                                             "value"=>$model->user->last_name
                                                                         ],
                                                                         [
                                                                             "key"=>"Correo electronico",
                                                                             "value"=>$model->user->email
                                                                         ],
                                                                         [
                                                                             "key"=>"Telefono",
                                                                             "value"=>$model->user->phone
                                                                         ],
                                                                         [
                                                                             "key"=>"Operador de red",
                                                                             "value"=>$model->networkOperator->id." - ".$model->networkOperator->name
                                                                         ],
                                                                     ]
                                                            ]
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
                                                                               "table_rows"=>$model->clientSupervisors,
                                                                                  ]
                                                                            ]



                                                ]
         ])


</div>
