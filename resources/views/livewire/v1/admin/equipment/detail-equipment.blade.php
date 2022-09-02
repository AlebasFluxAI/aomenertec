@section("header") {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Detalles de",
            "second_title"=>"Equipo"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["mt"=>4,"nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.equipos.listado",
                    ],

                ]
        ])
    @include("partials.v1.tab.v1.tab",[

                            "tab_titles"=>[
                                                [
                                                    "title"=>"Detalles",

                                                ],
                                                [
                                                    "title"=>"Administrador",

                                                ],
                                                [
                                                    "title"=>"Operador",

                                                ],
                                                [
                                                    "title"=>"Tecnico",

                                                ],
                                                [
                                                    "title"=>"Cliente",

                                                ],

                                           ],

                            "tab_contents"=>[
                                                [
                                                    "view_name"=>"partials.v1.table.primary-details-table",
                                                    "view_values"=>  [
                                                                        "table_info"=>[
                                                                         [
                                                                             "key"=>"Id",
                                                                             "value"=>$equipment->id
                                                                         ],
                                                                         [
                                                                             "key"=>"Nombre",
                                                                             "value"=>$equipment->name
                                                                         ],
                                                                         [
                                                                             "key"=>"Descripción",
                                                                             "value"=>$equipment->description
                                                                         ],
                                                                         [
                                                                             "key"=>"Serial",
                                                                             "value"=>$equipment->serial
                                                                         ],
                                                                         [
                                                                             "key"=>"Tipo de equipo",
                                                                             "value"=>$equipment->equipmentType->type
                                                                         ],


                                                                     ]
                                                            ]
                                                ],
                                                [
                                                  "view_name"=>"livewire.v1.admin.user.admin.index-admin",
                                                  "view_values"=>[
                                                      "data"=>$equipment->admin()->get(),
                                                      "table_pageable"=>false,
                                                      "table_class_container"=>"",
                                                      "view_header"=>false,
                                                      "col_filter"=>false
                                                   ],
                                               ],
                                               [
                                                  "view_name"=>"livewire.v1.admin.user.network-operator.index-network-operator",
                                                  "view_values"=>[
                                                      "data"=>$equipment->networkOperator()->get(),
                                                      "table_pageable"=>false,
                                                      "table_class_container"=>"",
                                                      "view_header"=>false,
                                                      "col_filter"=>false
                                                   ],
                                               ],
                                               [
                                                  "view_name"=>"livewire.v1.admin.user.technician.index-technician",
                                                   "view_values"=>[
                                                                       "data"=>$equipment->technicians()->get(),
                                                                       "table_class_container"=>"",
                                                                       "view_header"=>false,
                                                                       "is_filtered"=>false,
                                                                       "col_filter"=>false,
                                                                       "network_operator_conditional_delete"=>"conditionalDeleteTechnician",
                                                                  ]
                                               ],
                                               [
                                                  "view_name"=>"livewire.v1.admin.client.index-client",
                                                  "view_values"=>[
                                                      "data"=>$equipment->clients()->get(),
                                                      "table_pageable"=>false,
                                                      "table_class_container"=>"",
                                                      "view_header"=>false,
                                                      "col_filter"=>false
                                                   ],
                                               ],

                                          ]
         ])


</div>
