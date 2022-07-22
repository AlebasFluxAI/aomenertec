<div class="login">
    @section("header")
        {{--extended app.blade--}}
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
                                                                                        "Nombre"=>"name",
                                                                                        "Identificacion"=>"identification",
                                                                                        "Telefono"=>"phone",
                                                                                        "Correo electronico"=>"email",
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
                                                                                                            "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_SHOW_MONITORING],
                                                                                                            "redirect"=>[
                                                                                                                        "route"=>"v1.admin.client.monitoring",
                                                                                                                        "binding"=>"client"
                                                                                                                  ],
                                                                                                                "icon"=>"fa fa-connectdevelop",
                                                                                                                "tooltip_title"=>"Monitoreo",
                                                                                                                "conditional" => "conditionalMonitoring",
                                                                                                        ],
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
                                                  "view_name"=>"livewire.v1.admin.user.supervisor.index-supervisor",
                                                   "view_values"=>[
                                                                       "data"=>$model->supervisors()->get(),
                                                                       "table_class_container"=>"",
                                                                       "view_header"=>false,
                                                                       "col_filter"=>false,
                                                                       "network_operator_conditional_delete"=>"conditionalDeleteSupervisor",
                                                                  ]
                                               ],
                                               [
                                                  "view_name"=>"livewire.v1.admin.user.technician.index-technician",
                                                   "view_values"=>[
                                                                       "data"=>$model->technicians()->get(),
                                                                       "table_class_container"=>"",
                                                                       "view_header"=>false,
                                                                       "col_filter"=>false,
                                                                       "network_operator_conditional_delete"=>"conditionalDeleteTechnician",
                                                                  ]
                                               ],



                                ]
        ])

    @include("partials.v1.table_nav",
       ["mt"=>2,
           "nav_options"=>[
                  ["button_align"=>"right",
                  "click_action"=>"",
                  "button_content"=>"Cerrar sesión",
                  "button_icon"=>"fa-solid fa-right-from-bracket",
                  "target_route"=>"logout",
                  ],

              ]
      ])
</div>
