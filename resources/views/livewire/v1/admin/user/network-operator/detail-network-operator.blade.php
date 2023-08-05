@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Detalles de",
            "second_title"=>"operador de red"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
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
                                                [
                                                    "title"=>"Equipos",

                                                ],
                                                [
                                                    "title"=>"PQRS",

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
                                                                             "value"=>$model->phonePlusIndicative
                                                                         ],
                                                                          [
                                                                             "key"=>"Identificacion",
                                                                             "value"=>$model->identification
                                                                         ],
                                                                          [
                                                                             "key"=>"admin",
                                                                             "value"=>$model->admin->name
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
                                                                          [
                                                                             "key"=>"Dia de facturacion",
                                                                             "value"=>$model->billing_day
                                                                         ],
                                                                          [
                                                                             "key"=>"Bolsa inicial de PQR gestionadas",
                                                                             "value"=>$model->pqr_initial_bag
                                                                         ],
                                                                         [
                                                                             "key"=>"Bolsa inicial de horas ordenes de trabajo gestionadas",
                                                                             "value"=>$model->work_order_initial_bag
                                                                        ],
                                                                     ]
                                                            ]
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
                                               ],
                                               [
                                                  "view_name"=>"livewire.v1.admin.user.seller.index-seller",
                                                   "view_values"=>[
                                                                       "data"=>$model->sellers()->get(),
                                                                       "table_class_container"=>"",
                                                                       "view_header"=>false,
                                                                       "is_filtered"=>false,
                                                                       "col_filter"=>false,
                                                                  ]
                                               ],
                                               [
                                                  "view_name"=>"livewire.v1.admin.user.supervisor.index-supervisor",
                                                   "view_values"=>[
                                                                       "data"=>$supervisors,
                                                                       "table_class_container"=>"",
                                                                       "view_header"=>false,
                                                                       "is_filtered"=>false,
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
                                                                       "is_filtered"=>false,
                                                                       "col_filter"=>false,
                                                                       "network_operator_conditional_delete"=>"conditionalDeleteTechnician",
                                                                  ]
                                               ],
                                               [
                                                  "view_name"=>"livewire.v1.admin.equipment.index-equipment",
                                                  "view_values"=>[
                                                      "data"=>$model->equipments()->get(),
                                                      "permissionRemove" => [\App\Http\Resources\V1\Permissions::NETWORK_OPERATOR_REMOVE_EQUIPMENT],
                                                      "functionRemoveEquipment" => "removeEquipmentNetworkOperator",
                                                      "conditionalRemoveEquipment" => "conditionalRemoveEquipmentNetworkOperator",
                                                      "availableFlag" => "has_technician",
                                                      "table_pageable"=>false,
                                                      "table_class_container"=>"",
                                                      "view_header"=>false,
                                                      "col_filter"=>false
                                                   ],
                                               ],
                                               [
                                                  "view_name"=>"livewire.v1.admin.pqr.index-pqr",
                                                  "view_values"=>[
                                                                       "data"=>$model->pqrs()->get(),
                                                                       "table_class_container"=>"",
                                                                       "view_header"=>false,
                                                                       "is_filtered"=>false,
                                                                       "col_filter"=>false,
                                                                       "network_operator_conditional_delete"=>"conditionalDeleteTechnician",
                                                                  ]
                                               ],

                                                                                        ]
         ])


</div>
