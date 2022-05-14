<div class="login">
    @section("header") {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Perfil",
            "second_title"=>"Super Administrador"
        ])


    {{----------------------------------Formulario--------------------------}}
    @include("partials.v1.tab.v1.tab",[

                           "tab_titles"=>[
                                               [
                                                   "title"=>"Mis datos",

                                               ],
                                               [
                                                    "title"=>"Mis administradores",
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

                                                                    ]
                                                           ],


                                               ],
                                               [
                                                  "view_name"=>"partials.v1.table.primary-table",
                                                   "view_values"=>[
                                                                       "table_pageable"=>false,
                                                                      "table_headers"=>["ID"=>"id",
                                                                                        "Nombre"=>"name",
                                                                                        "Apellido"=>"last_name",
                                                                                        "Telefono"=>"phone",
                                                                                        "Correo electronico"=>"email",
                                                                                        "Identificacion"=>"identification",
                                                                       ],
                                                                      "table_actions"=>[
                                                                                    "customs"=>[
                                                                                           [
                                                                                                    "redirect"=>[
                                                                                                            "route"=>"administrar.v1.usuarios.admin.detalles",
                                                                                                            "binding"=>"admin"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-search",
                                                                                                    "tooltip_title"=>"Detalles",
                                                                                            ],
                                                                                             [
                                                                                                    "function"=>"delete",
                                                                                                    "conditional"=>"conditionalAdminDelete",
                                                                                                    "icon"=>"fas fa-trash",
                                                                                                    "tooltip_title"=>"Eliminar"
                                                                                            ],
                                                                                            [
                                                                                               "redirect"=>[
                                                                                                           "route"=>"administrar.v1.usuarios.admin.agregar_tipos_equipo",
                                                                                                           "binding"=>"admin"
                                                                                                     ],
                                                                                                   "icon"=>"fas fa-computer",
                                                                                                   "tooltip_title"=>"Asociar tipos de equipos",
                                                                                             ],
                                                                                             [
                                                                                               "redirect"=>[
                                                                                                           "route"=>"administrar.v1.usuarios.admin.agregar_equipos",
                                                                                                           "binding"=>"admin"
                                                                                                     ],
                                                                                                   "icon"=>"fas fa-laptop-medical",
                                                                                                   "tooltip_title"=>"Asociar equipos",
                                                                                             ],
                                                                                        ]
                                                                                    ],
                                                                      "table_rows"=>$model->admins()

                                                                  ]
                                               ],
                                   ]
        ])

    @include("partials.v1.table_nav",
      ["nav_options"=>[
                 ["button_align"=>"right",
                 "click_action"=>"",
                 "button_content" => "Cerrar sesión",
                 "button_icon"=>"fa-solid fa-right-from-bracket",
                 "target_route"=>"logout",
                 ],
             ]
     ])


</div>
