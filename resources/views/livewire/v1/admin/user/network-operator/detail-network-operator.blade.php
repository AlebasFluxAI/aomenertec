@section("header") {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Detalles de",
            "second_title"=>"operador de red"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         [
             "nav_options"=>[
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
                                                    "title"=>"Clientes",

                                                ],
                                                [
                                                    "title"=>"Vendedores",

                                                ],
                                                [
                                                    "title"=>"Supervisores",

                                                ],
                                                [
                                                    "title"=>"Tecnicos",

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
                                                                         [
                                                                             "key"=>"Detalles de direccion",
                                                                             "value"=>$model->address_details
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
                                                                                        "Identificacion"=>"identification",
                                                                       ],
                                                                      "table_actions"=>[
                                                                                    "customs"=>[
                                                                                           [
                                                                                                    "redirect"=>[
                                                                                                            "route"=>"v1.admin.client.detail.client",
                                                                                                            "binding"=>"client"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-search"
                                                                                            ],
                                                                                        ]
                                                                                    ],
                                                                      "table_rows"=>$model->clients

                                                                  ]
                                               ],
                                               [
                                                  "view_name"=>"partials.v1.table.primary-table",
                                                   "view_values"=>[
                                                                       "table_pageable"=>false,
                                                                      "table_headers"=>["ID"=>"id",
                                                                                        "Nombre"=>"name",
                                                                                        "Identificacion"=>"identification",
                                                                       ],
                                                                      "table_actions"=>[
                                                                                    "customs"=>[
                                                                                           [
                                                                                                "redirect"=>[
                                                                                                            "route"=>"administrar.v1.usuarios.vendedores.detalles",
                                                                                                            "binding"=>"seller"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-search"
                                                                                            ],
                                                                                            [
                                                                                                "redirect"=>[
                                                                                                            "route"=>"administrar.v1.usuarios.vendedores.editar",
                                                                                                            "binding"=>"seller"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-pencil"
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
                                                                                        "Nombre"=>"name",
                                                                                        "Identificacion"=>"identification",
                                                                       ],
                                                                      "table_actions"=>[
                                                                                    "customs"=>[
                                                                                           [
                                                                                                    "redirect"=>[
                                                                                                            "route"=>"administrar.v1.usuarios.supervisores.detalles",
                                                                                                            "binding"=>"supervisor"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-search"
                                                                                            ],
                                                                                            [
                                                                                                    "redirect"=>[
                                                                                                            "route"=>"administrar.v1.usuarios.supervisores.editar",
                                                                                                            "binding"=>"supervisor"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-pencil"
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
                                                                                        "Identificacion"=>"identification",
                                                                       ],
                                                                      "table_actions"=>[
                                                                                    "customs"=>[
                                                                                           [
                                                                                                    "redirect"=>[
                                                                                                            "route"=>"administrar.v1.usuarios.tecnicos.detalles",
                                                                                                            "binding"=>"technician"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-search"
                                                                                            ],
                                                                                            [
                                                                                                    "redirect"=>[
                                                                                                            "route"=>"administrar.v1.usuarios.tecnicos.editar",
                                                                                                            "binding"=>"technician"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-pencil"
                                                                                            ]
                                                                                        ]
                                                                                    ],
                                                                      "table_rows"=>$model->technicians

                                                                  ]
                                               ]

                                                                                        ]
         ])


</div>
