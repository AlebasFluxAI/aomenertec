<div class="login">
    @section("header") {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Perfil",
            "second_title"=>"operador de red"
        ])



    {{----------------------------------Formulario--------------------------}}
    @include("partials.v1.tab.v1.tab",[

                           "tab_titles"=>[
                                               [
                                                   "title"=>"Mis datos",

                                               ],
                                                [
                                                   "title"=>"Mis clientes",

                                               ],
                                               [
                                                   "title"=>"Mis Vendedores",

                                               ],
                                               [
                                                   "title"=>"Mis Supervisores",

                                               ],
                                                [
                                                   "title"=>"Mis tecnicos",

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
                                                                                        "Apellido"=>"last_name",
                                                                                        "Telefono"=>"phone",
                                                                                        "Correo electronico"=>"email",
                                                                                        "Identificacion"=>"identification",
                                                                       ],
                                                                      "table_actions"=>[
                                                                                    "customs"=>[
                                                                                           [
                                                                                                    "redirect"=>[
                                                                                                            "route"=>"v1.admin.client.detail.client",
                                                                                                            "binding"=>"client"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-search",
                                                                                                    "tooltip_title"=>"Detalles",
                                                                                            ],
                                                                                    ],
                                                                      "table_rows"=>$model->clients

                                                                  ]
                                               ],
                                               [
                                                  "view_name"=>"partials.v1.table.primary-table",
                                                   "view_values"=>[
                                                                       "table_pageable"=>false,
                                                                      "table_headers"=>["ID"=>"id",
                                                                                        "Apellido"=>"last_name",
                                                                                        "Telefono"=>"phone",
                                                                                        "Correo electronico"=>"email",
                                                                                        "Identificacion"=>"identification",
                                                                       ],
                                                                      "table_actions"=>[
                                                                                    "customs"=>[
                                                                                           [
                                                                                                "redirect"=>[
                                                                                                            "route"=>"administrar.v1.usuarios.vendedores.detalles",
                                                                                                            "binding"=>"seller"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-search",
                                                                                                    "tooltip_title"=>"Detalles",
                                                                                            ],
                                                                                            [
                                                                                                "redirect"=>[
                                                                                                            "route"=>"administrar.v1.usuarios.vendedores.editar",
                                                                                                            "binding"=>"seller"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-pencil",
                                                                                                    "tooltip_title"=>"Editar",
                                                                                            ],
                                                                                            [
                                                                                                "redirect"=>[
                                                                                                            "route"=>"administrar.v1.usuarios.vendedores.agregar_clientes",
                                                                                                            "binding"=>"seller"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-users",
                                                                                                    "tooltip_title"=>"Asociar clientes",
                                                                                            ]
                                                                                        ]
                                                                                    ],
                                                                      "table_rows" => $model->sellers

                                                                  ]
                                               ],
                                               [
                                                  "view_name"=>"partials.v1.table.primary-table",
                                                   "view_values"=>[
                                                                       "table_pageable"=>false,
                                                                      "table_headers"=>["ID"=>"id",
                                                                                        "Apellido"=>"last_name",
                                                                                        "Telefono"=>"phone",
                                                                                        "Correo electronico"=>"email",
                                                                                        "Identificacion"=>"identification",
                                                                       ],
                                                                      "table_actions"=>[
                                                                                    "customs"=>[
                                                                                           [
                                                                                                    "redirect"=>[
                                                                                                            "route"=>"administrar.v1.usuarios.supervisores.detalles",
                                                                                                            "binding"=>"supervisor"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-search",
                                                                                                    "tooltip_title"=>"Detalles",
                                                                                            ],
                                                                                            [
                                                                                                    "redirect"=>[
                                                                                                            "route"=>"administrar.v1.usuarios.supervisores.editar",
                                                                                                            "binding"=>"supervisor"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-pencil",
                                                                                                    "tooltip_title"=>"Editar",
                                                                                            ],
                                                                                            [
                                                                                                "redirect"=>[
                                                                                                            "route"=>"administrar.v1.usuarios.supervisores.agregar_clientes",
                                                                                                            "binding"=>"supervisor"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-users",
                                                                                                    "tooltip_title"=>"Asociar clientes",
                                                                                            ]
                                                                                        ]
                                                                                    ],
                                                                      "table_rows"=>$model->supervisors

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
                                                                                        "Correo electronico"=>"email",
                                                                                        "Identificacion"=>"identification",
                                                                       ],
                                                                      "table_actions"=>[
                                                                                    "customs"=>[
                                                                                           [
                                                                                                    "redirect"=>[
                                                                                                            "route"=>"administrar.v1.usuarios.tecnicos.detalles",
                                                                                                            "binding"=>"technician"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-search",
                                                                                                    "tooltip_title"=>"Detalles",

                                                                                            ],
                                                                                            [
                                                                                                    "redirect"=>[
                                                                                                            "route"=>"administrar.v1.usuarios.tecnicos.editar",
                                                                                                            "binding"=>"technician"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-pencil",
                                                                                                    "tooltip_title"=>"Editar",
                                                                                            ],
                                                                                              [
                                                                                                "redirect"=>[
                                                                                                            "route"=>"administrar.v1.usuarios.tecnicos.agregar_clientes",
                                                                                                            "binding"=>"technician"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-users",
                                                                                                    "tooltip_title"=>"Asociar clientes",
                                                                                            ],
                                                                                            [
                                                                                               "redirect"=>[
                                                                                                           "route"=>"administrar.v1.usuarios.tecnicos.agregar_equipos",
                                                                                                           "binding"=>"technician"
                                                                                                     ],
                                                                                                 "icon"=>"fas fa-laptop-medical",
                                                                                                 "tooltip_title"=>"Asociar equipos",
                                                                                                 "limit_roles"=>\App\Http\Resources\V1\PermissionUtil::getTechnicianEquipmentTypeRoles()
                                                                                              ],
                                                                                        ]
                                                                                    ],
                                                                      "table_rows"=>$model->technicians

                                                                  ]
                                               ]



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
