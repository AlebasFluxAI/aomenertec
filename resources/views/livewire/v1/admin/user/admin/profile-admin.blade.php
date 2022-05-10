<div class="login">
    @section("header") {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Perfil",
            "second_title"=>"Administrador"
        ])


    {{----------------------------------Formulario--------------------------}}
    @include("partials.v1.tab.v1.tab",[

                           "tab_titles"=>[
                                               [
                                                   "title"=>"Mis datos",

                                               ],
                                                 [
                                                   "title"=>"Mis operadores de red",

                                               ],
                                               [
                                                   "title"=>"Clientes de mis operadores",

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
                                                                                        "Correo"=>"email"
                                                                       ],
                                                                      "table_actions"=>[
                                                                                    "customs"=>[
                                                                                           [
                                                                                                    "redirect"=>[
                                                                                                            "route"=>"administrar.v1.usuarios.operadores.detalles",
                                                                                                            "binding"=>"networkOperator"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-search",
                                                                                                    "tooltip_title"=>"Detalles",
                                                                                            ],
                                                                                            [
                                                                                                    "redirect"=>[
                                                                                                            "route"=>"administrar.v1.usuarios.operadores.editar",
                                                                                                            "binding"=>"networkOperator"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-pencil",
                                                                                                    "tooltip_title"=>"Editar",
                                                                                            ],
                                                                                               [
                                                                                                        "function"=>"deleteNetworkOperator",
                                                                                                        "conditional"=>"conditionalNetworkOperatorDelete",
                                                                                                        "icon"=>"fas fa-trash",
                                                                                                        "tooltip_title"=>"Eliminar"
                                                                                                ]
                                                                                        ]
                                                                                    ],
                                                                      "table_rows"=>$model->networkOperators

                                                                  ]
                                               ],
                                                [
                                                  "view_name"=>"partials.v1.table.primary-table",
                                                   "view_values"=>[
                                                                       "table_pageable"=>false,
                                                                      "table_headers"=>["ID"=>"id",
                                                                                        "Nombre"=>"name",
                                                                                        "Correo"=>"email"
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
                                                                                            [
                                                                                                    "redirect"=>[
                                                                                                            "route"=>"v1.admin.client.edit.client",
                                                                                                            "binding"=>"client"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-pencil",
                                                                                                    "tooltip_title"=>"Editar",
                                                                                            ],
                                                                                            [
                                                                                                    "redirect"=>[
                                                                                                                "route"=>"v1.admin.client.monitoring",
                                                                                                                "binding"=>"client"
                                                                                                          ],
                                                                                                        "icon"=>"fa fa-connectdevelop",
                                                                                                        "tooltip_title"=>"Monitoreo",
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
                                                                                        "Nombre"=>"equipmentType.type",
                                                                                        "Serial"=>"serial",
                                                                       ],
                                                                      "table_actions"=>[
                                                                                    "customs"=>[
                                                                                           [
                                                                                                    "redirect"=>[
                                                                                                            "route"=>"administrar.v1.equipos.detalle",
                                                                                                            "binding"=>"equipment"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-search",
                                                                                                     "tooltip_title"=>"Detalles",
                                                                                            ]
                                                                                        ]
                                                                                    ],
                                                                      "table_rows"=>$model->equipments

                                                                  ]
                                               ],



                                                                                       ]
        ])
    @include("partials.v1.table_nav",
     ["nav_options"=>[
                ["button_align"=>"right",
                "click_action"=>"",
                "button_content"=>"Cerrar cesion",
                "button_icon"=>"fa-solid fa-right-from-bracket",
                "target_route"=>"logout",
                ],

            ]
    ])

</div>
