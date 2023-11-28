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
                           "wire_ignore"=>true,
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
                                                   "title"=>"Mis tecnicos",

                                               ],
                                               [
                                                   "title"=>"Mis Equipos",

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
                                                      "data"=>(\App\Models\V1\Equipment::whereNetworkOperatorId($model->id)
                                                                ->where(function ($query)  {
                                                                    $this->applyFilter($query, $this);
                                                                })->get()),
                                                      "permissionRemove" => [\App\Http\Resources\V1\Permissions::TECHNICIAN_REMOVE_EQUIPMENT],
                                                      "functionRemoveEquipment" => "removeEquipmentTechnician",
                                                      "conditionalRemoveEquipment" => "conditionalRemoveEquipmentTechnician",
                                                      "availableFlag" => "has_technician",
                                                      "table_pageable"=>false,
                                                      "table_class_container"=>"",
                                                      "view_header"=>false,
                                                      "col_filter"=>false
                                                   ],
                                               ],




                                ],
                           "logout_button"=>true,
        ])

</div>
