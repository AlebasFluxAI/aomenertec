<div>
    @section("header")
        {{-- extended app.blade --}}
    @endsection

    {{-- FluxAI Home — dashboard operacional (ver partials.v1.home.flux-shell) --}}
    @include("partials.v1.home.flux-shell", [
        "welcome_title"     => $dashboard["welcome_title"]     ?? ("Bienvenido, " . ($model->name ?? '')),
        "welcome_subtitle"  => $dashboard["welcome_subtitle"]  ?? "Panel de control FluxAI",
        "welcome_role_chip" => $dashboard["welcome_role_chip"] ?? "Super administrador",
        "kpis"              => $dashboard["kpis"]              ?? [],
        "quick_actions"     => $dashboard["quick_actions"]     ?? [],
        "activity_panels"   => $dashboard["activity_panels"]   ?? [],
    ])

    {{-- ===== Detalles del perfil + listados heredados (no romper accesos) ===== --}}
    <section class="flux-section flux-legacy-tabs">
        <div class="flux-section__head">
            <h2 class="flux-section__title">Detalles del perfil</h2>
        </div>

        @include("partials.v1.tab.v1.tab",[
            "tab_titles"=>[
                [ "title"=>"Mis datos" ],
                [ "title"=>"Administradores" ],
                [ "title"=>"Operadores de red" ],
            ],
            "tab_contents"=>[
                [
                    "view_name"=>"partials.v1.table.primary-details-table",
                    "view_values"=>  [
                        "table_info"=>[
                            [ "key"=>"Id", "value"=>$model->id ],
                            [ "key"=>"Nombre", "value"=>$model->name ],
                            [ "key"=>"Apellido", "value"=>$model->last_name ],
                            [ "key"=>"Correo electronico", "value"=>$model->email ],
                            [ "key"=>"Telefono", "value"=>$model->phone ],
                        ]
                    ],
                ],
                [
                    "view_name"=>"livewire.v1.admin.user.admin.index-admin",
                    "view_values"=>[
                        "data"=>$admins,
                        "table_pageable"=>false,
                        "table_class_container"=>"",
                        "view_header"=>false,
                        "col_filter"=>false
                    ],
                ],
                [
                    "view_name"=>"livewire.v1.admin.user.network-operator.index-network-operator",
                    "view_values"=>[
                        "data"=>$network_operators,
                        "table_pageable"=>false,
                        "table_class_container"=>"",
                        "view_header"=>false,
                        "col_filter"=>false
                    ],
                ],
            ],
            "logout_button" => true,
        ])
    </section>
</div>
