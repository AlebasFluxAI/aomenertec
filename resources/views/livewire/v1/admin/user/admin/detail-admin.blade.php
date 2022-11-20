@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Detalles de",
            "second_title"=>"administrador"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.usuarios.admin.listado",
                    ],
                ]
        ])

    @include("partials.v1.tab.v1.tab",[

                            "tab_titles"=>[
                                                [
                                                    "title"=>"Detalles",

                                                ],
                                                  [
                                                    "title"=>"Dirección",
                                                ],
                                                  [
                                                    "title"=>"Operadores de red",

                                                ],
                                                [
                                                    "title"=>"Equipos",

                                                ],
                                                [
                                                    "title"=>"Clientes",

                                                ],

                                           ],

                            "tab_contents"=>[
                                                [
                                                    "view_name"=>"partials.v1.table.primary-details-table",
                                                    "view_values"=>  [
                                                                        "table_info"=>[
                                                                         [
                                                                             "key"=>"Id",

                                                                             "value"=>$admin->id
                                                                         ],
                                                                         [
                                                                             "key"=>"Nombre",

                                                                             "value"=>$admin->name
                                                                         ],
                                                                         [
                                                                             "key"=>"Apellido",

                                                                             "value"=>$admin->last_name
                                                                         ],
                                                                         [
                                                                             "key"=>"Correo electronico",

                                                                             "value"=>$admin->email
                                                                         ],
                                                                          [
                                                                             "key"=>"Identificacion",
                                                                             "value"=>$admin->identification
                                                                         ],
                                                                         [
                                                                             "key"=>"Telefono",

                                                                             "value"=>$admin->phonePlusIndicative
                                                                         ],
                                                                          [
                                                                             "key"=>"Direccion",

                                                                             "value"=>$admin->address
                                                                         ],
                                                                                  [
                                                                             "key"=>"Archivo de estilos",

                                                                             "value"=>$admin->css_file_name
                                                                         ],
                                                                           [
                                                                             "key"=>"Logo",
                                                                             "type"=>"image",
                                                                             "value"=>$admin->icon?$admin->icon->url:"https://enertedevops.s3.us-east-2.amazonaws.com/images/logotipo-enerteclatam.png"
                                                                         ],
                                                                     ]
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
                                                                                         "Detalles"=>"address_details"

                                                                        ],
                                                                        "table_actions"=>[
                                                                            "customs"=>[
                                                                                [
                                                                                   "popup"=>[
                                                                                               "modal_title"=>"Ubicación del usuario",
                                                                                               "view_name"=>"partials.v1.map_pin",
                                                                                               "view_data"=>[
                                                                                                   "latitude"=>$admin->latitude,
                                                                                                   "longitude"=>$admin->longitude,
                                                                                             ],
                                                                                   ],
                                                                                ],
                                                                            ],
                                                                         ],

                                                                       "table_rows"=>[$admin],
                                                                   ],


                                                ],
                                                [
                                                  "view_name"=>"livewire.v1.admin.user.network-operator.index-network-operator",
                                                   "view_values"=>[
                                                                       "data"=>$admin->networkOperators()->get(),
                                                                       "table_class_container"=>"",
                                                                       "view_header"=>false,
                                                                       "col_filter"=>false,
                                                                       "network_operator_conditional_delete"=>"conditionalDeleteNetworkOperator",
                                                                  ]
                                               ],
                                                  [
                                                  "view_name"=>"livewire.v1.admin.equipment.index-equipment",
                                                  "view_values"=>[
                                                      "data"=>$admin->equipments()->get(),
                                                      "permissionRemove" => [\App\Http\Resources\V1\Permissions::ADMIN_REMOVE_EQUIPMENT],
                                                      "functionRemoveEquipment" => "removeEquipmentAdmin",
                                                      "conditionalRemoveEquipment" => "conditionalRemoveEquipmentAdmin",
                                                      "availableFlag" => "has_network_operator",
                                                      "table_pageable"=>false,
                                                      "table_class_container"=>"",
                                                      "view_header"=>false,
                                                      "col_filter"=>false
                                                   ],
                                               ],
                                                  [
                                                  "view_name"=>"livewire.v1.admin.client.index-client",
                                                  "view_values"=>[
                                                      "data"=>$admin->getClientsAttribute(),
                                                      "table_pageable"=>false,
                                                      "table_class_container"=>"",
                                                      "view_header"=>false,
                                                      "col_filter"=>false
                                                   ],
                                               ],





                                                                                        ]
         ])


</div>
