@section("header") {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Detalles de",
            "second_title"=>"super usuario"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.usuarios.superadmin.listado",
                    ],

                ]
        ])
    @include("partials.v1.tab.v1.tab",[

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
                                                                             "key"=>"Identificacion",
                                                                             "value"=>$model->identification
                                                                         ],
                                                                         [
                                                                             "key"=>"Telefono",
                                                                             "value"=>$model->user->phone
                                                                         ],
                                                                     ]
                                                            ]
                                                ],
                                                                                        ]
         ])


</div>
