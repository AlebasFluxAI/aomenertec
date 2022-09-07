@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Super administrador",
            "second_title"=>$model->name
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
                                                [
                                                   "title"=>"Administradores",

                                               ],
                                                [
                                                   "title"=>"Operadores de red",
                                               ],
                                                [
                                                   "title"=>"Equipos",
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
                                                                             "key"=>"Identificacion",
                                                                             "value"=>$model->identification
                                                                         ],
                                                                         [
                                                                             "key"=>"Telefono",
                                                                             "value"=>$model->phone
                                                                         ],

                                                                     ]
                                                            ]
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
                                               [
                                                  "view_name"=>"livewire.v1.admin.equipment.index-equipment",
                                                  "view_values"=>[
                                                      "data"=>$equipment,
                                                      "permissionRemove" => [\App\Http\Resources\V1\Permissions::ADMIN_REMOVE_EQUIPMENT],
                                                      "functionRemoveEquipment" => "removeEquipmentAdmin",
                                                      "conditionalRemoveEquipment" => "conditionalRemoveEquipmentAdmin",
                                                      "availableFlag" => "has_admin",
                                                      "table_pageable"=>false,
                                                      "table_class_container"=>"",
                                                      "view_header"=>false,
                                                      "col_filter"=>false
                                                   ],
                                               ],
                                                                                        ]
         ])


</div>
