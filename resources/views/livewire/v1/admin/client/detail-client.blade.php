@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Detalles de",
            "second_title"=>"Cliente"
        ])

    {{--optiones de cabecera de formulario--}}
    @include("partials.v1.table_nav",
         ["mt"=>2,
         "nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"v1.admin.client.list.client",
                    ],
                    [
                    "button_align"=>"right",
                    "button_type"=>"dropdown",
                    "button_icon"=>"fas fa-gear",
                    "button_content"=>"Acciones",
                    "button_options"=>$client->navigatorDropdownOptions()
                    ]
                 ]
        ])


    @include("partials.v1.tab.v1.tab",[

                            "tab_titles"=>[
                                                [
                                                    "title"=>"Detalles",

                                                ],
                                                 [
                                                    "title"=>"Datos de facturacion",
                                                ],
                                                [
                                                    "title"=>"Dirección",
                                                ],
                                                [
                                                    "title"=>"Tecnico",
                                                ],
                                                [
                                                    "title"=>"Supervisores",
                                                ],
                                                [
                                                    "title"=>"Equipos",
                                                ]
                                           ],

                            "tab_contents"=>[
                                                [
                                                    "view_name"=>"partials.v1.table.primary-details-table",
                                                    "view_values"=>  [
                                                                        "table_info"=>[
                                                                         [
                                                                             "key"=>"Id",
                                                                             "value"=>$client->id
                                                                         ],
                                                                         [
                                                                             "key"=>"Codigo",
                                                                             "value"=>$client->code
                                                                         ],
                                                                         [
                                                                             "key"=>"Nombre",
                                                                             "value"=>$client->name
                                                                         ],
                                                                         [
                                                                             "key"=>"Alias de cliente",
                                                                             "value"=>$client->alias
                                                                         ],
                                                                         [
                                                                             "key"=>"Email",
                                                                             "value"=>$client->email
                                                                         ],
                                                                         [
                                                                             "key"=>"Telefono",
                                                                             "value"=>$client->phonePlusIndicative
                                                                         ],
                                                                         [
                                                                             "key"=>"Identificación",
                                                                             "value"=>$client->identification
                                                                         ],
                                                                         [
                                                                             "key"=>"Operador de red",
                                                                             "value"=>$client->networkOperator?($client->networkOperator->id. "- ". $client->networkOperator->name):""
                                                                         ],


                                                                     ]
                                                            ]
                                                ],
                                          [
                                                   "view_name"=>"partials.v2.table.primary-table",
                                                   "view_values"=>[
                                                                        "class_container"=>"",
                                                                        "table_pageable"=>false,
                                                                        "table_headers"=>[
                                                                           [
                                                                               "col_name" =>"ID",
                                                                               "col_data" =>"id",
                                                                               "col_filter"=>false
                                                                           ],
                                                                           [
                                                                               "col_name" =>"Razon social",
                                                                               "col_data" =>"name",
                                                                               "col_filter"=>false
                                                                           ],
                                                                           [
                                                                               "col_name" =>"Tipo de documento",
                                                                               "col_data" =>"identification_type",
                                                                               "col_filter"=>false
                                                                           ],
                                                                           [
                                                                               "col_name" =>"Numero de documento",
                                                                               "col_data" =>"identification",
                                                                               "col_filter"=>false
                                                                           ],
                                                                           [
                                                                               "col_name" =>"Direccion de facturacion",
                                                                               "col_data" =>"address",
                                                                               "col_filter"=>false
                                                                           ],
                                                                            [
                                                                               "col_name" =>"Telefono",
                                                                               "col_data" =>"phone",
                                                                               "col_filter"=>false
                                                                           ],[
                                                                               "col_name" =>"Por Defecto",
                                                                               "col_data" =>"default",
                                                                               "col_filter"=>false,
                                                                               "col_type"=>\App\Http\Resources\V1\ColTypeEnum::COL_TYPE_BOOLEAN
                                                                           ],

                                                                                                                    ],

                                                                       "table_rows"=>$client->billingInformation,
                                                                   ],

                                                ],
                                          [
                                                   "view_name"=>"partials.v1.table.primary-table",
                                                   "view_values"=>[
                                                                        "table_pageable"=>false,
                                                                       "table_headers"=>[
                                                                                         "ID"=>"id",
                                                                                         "Direccion"=>"address",
                                                                                         "Pais"=>"country",
                                                                                         "Departamento"=>"state",
                                                                                         "Ciudad"=>"city",
                                                                                         "Latitude"=>"latitude",
                                                                                         "Longitude"=>"longitude",
                                                                                         "Codigo postal"=>"postal_code",
                                                                                         "Detalles"=>"details"

                                                                        ],
                                                                        "table_actions"=>[
                                                                            "customs"=>[
                                                                                [
                                                                                   "popup"=>[
                                                                                               "modal_title"=>"Ubicación del cliente",
                                                                                               "view_name"=>"partials.v1.map_pin",
                                                                                               "view_data"=>[
                                                                                                   "latitude"=>$client->addresses->first()?$client->addresses->first()->latitude:null,
                                                                                                   "longitude"=>$client->addresses->first()?$client->addresses->first()->longitude:null,
                                                                                             ],
                                                                                   ],
                                                                                ]
                                                                            ],
                                                                         ],

                                                                       "table_rows"=>$client->addresses,
                                                                   ],


                                                ],
                                                  [
                                                  "view_name"=>"livewire.v1.admin.user.technician.index-technician",
                                                   "view_values"=>[
                                                                       "data"=>$client->clientTechnician()->get(),
                                                                       "table_class_container"=>"",
                                                                       "view_header"=>false,
                                                                       "is_filtered"=>false,
                                                                       "col_filter"=>false,
                                                                       "network_operator_conditional_delete"=>"conditionalDeleteTechnician",
                                                                  ]
                                               ],
                                               [
                                                  "view_name"=>"livewire.v1.admin.user.supervisor.index-supervisor",
                                                   "view_values"=>[
                                                                       "data"=>$client->supervisors()->get(),
                                                                       "table_class_container"=>"",
                                                                       "view_header"=>false,
                                                                       "is_filtered"=>false,
                                                                       "col_filter"=>false,
                                                                       "network_operator_conditional_delete"=>"conditionalDeleteSupervisor",
                                                                  ]
                                               ],
                                               [
                                                  "view_name"=>"livewire.v1.admin.equipment.index-equipment",
                                                  "view_values"=>[
                                                      "tab_options"=>[
                                                           [
                                                               "permission"=>[\App\Http\Resources\V1\Permissions::EQUIPMENT_CREATE],
                                                               "button_align"=>"right",
                                                               "click_action"=>"",
                                                               "button_content"=>"Crear nuevo",
                                                               "icon"=>"fa-solid fa-plus",
                                                               "target_route"=>"administrar.v1.equipos.agregar",
                                                          ],
                                                          [
                                                              "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_ADD_EQUIPMENT],
                                                              "button_align"=>"right",
                                                              "click_action"=>"",
                                                              "button_content"=>"Asociar equipo a cliente",
                                                              "icon"=>"fa-solid fa-computer",
                                                              "target_route"=>"v1.admin.client.add.equipment",
                                                              "target_binding"=>"client",
                                                              "target_binding_value"=>$client->id
                                                          ]

                                                       ],
                                                      "data"=>$client->equipments()->get(),
                                                      "permissionRemove" => [\App\Http\Resources\V1\Permissions::ADMIN_REMOVE_EQUIPMENT],
                                                      "functionRemoveEquipment" => "removeEquipmentAdmin",
                                                      "conditionalRemoveEquipment" => "conditionalRemoveEquipmentAdmin",
                                                      "availableFlag" => "has_clients",
                                                      "table_class_container"=>"",
                                                      "view_header"=>false,
                                                      "col_filter"=>false
                                                   ],
                                               ],

                                        ]
         ])


</div>

