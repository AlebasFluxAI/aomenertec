<div>
    @section("header")
        {{--extended app.blade--}}
    @endsection

    {{-- FluxAI Home — dashboard operacional (ver partials.v1.home.flux-shell) --}}
    @include("partials.v1.home.flux-shell", [
        "welcome_title"     => $dashboard["welcome_title"]     ?? ("Hola, " . ($model->name ?? '')),
        "welcome_subtitle"  => $dashboard["welcome_subtitle"]  ?? "Panel FluxAI",
        "welcome_role_chip" => $dashboard["welcome_role_chip"] ?? "Supervisor",
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
                                                  "view_name"=>"livewire.v1.admin.client.index-client",
                                                  "view_values"=>[
                                                      "data"=>$model->clients()->get(),
                                                      "table_pageable"=>false,
                                                      "table_class_container"=>"",
                                                      "view_header"=>false,
                                                      "col_filter"=>false
                                                   ],
                                               ]




                                ],
                           "logout_button"=>true,
        ])
    </section>
</div>
