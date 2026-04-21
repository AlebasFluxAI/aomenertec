<div>
    @section("header")
        {{--extended app.blade--}}
    @endsection

    {{-- FluxAI Home — dashboard operacional (ver partials.v1.home.flux-shell) --}}
    @include("partials.v1.home.flux-shell", [
        "welcome_title"     => $dashboard["welcome_title"]     ?? ("Hola, " . ($model->name ?? '')),
        "welcome_subtitle"  => $dashboard["welcome_subtitle"]  ?? "Panel FluxAI",
        "welcome_role_chip" => $dashboard["welcome_role_chip"] ?? "Vendedor",
        "kpis"              => $dashboard["kpis"]              ?? [],
        "quick_actions"     => $dashboard["quick_actions"]     ?? [],
        "activity_panels"   => $dashboard["activity_panels"]   ?? [],
    ])

    <section class="flux-section flux-legacy-tabs">
        <div class="flux-section__head">
            <h2 class="flux-section__title">Detalles del perfil</h2>
        </div>

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
    </section>
</div>
