<div class="login">
    @section("header")
        {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Perfil",
            "second_title"=>"vendedor"
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
                                                   "title"=>"Mis ventas",

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

                                                                                            ]
                                                                                        ],
                                                                                        [
                                                                                                    "redirect"=>[
                                                                                                                "route"=>"v1.admin.client.monitoring",
                                                                                                                "binding"=>"client"
                                                                                                          ],
                                                                                                        "icon"=>"fa fa-connectdevelop",
                                                                                                        "tooltip_title"=>"Monitoreo",
                                                                                                        "conditional" => "conditionalMonitoring",

                                                                                                ],
                                                                                    ],
                                                                      "table_rows"=>$model->clients

                                                                  ]
                                               ],
                                               [
                                                  "view_name"=>"livewire.v1.admin.purchase.historical-purchase",
                                                   "view_values"=>[
                                                                       "data"=>$model->clientRecharges()->get(),
                                                                       "table_class_container"=>"",
                                                                       "view_header"=>false,
                                                                       "is_filtered"=>false,
                                                                       "col_filter"=>false,
                                                                  ]
                                               ],



                                ],
                           "logout_button"=>true,
        ])

</div>
