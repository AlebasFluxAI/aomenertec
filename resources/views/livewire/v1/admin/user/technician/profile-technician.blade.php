<div class="login">
    @section("header")
        {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Perfil",
            "second_title"=>"tecnico"
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
                                                   "title"=>"Mis equipos",

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
                                                                            "key"=>"Identificacion",

                                                                            "value"=>$model->identification
                                                                        ],
                                                                        [
                                                                            "key"=>"Correo electronico",

                                                                            "value"=>$model->email
                                                                        ],
                                                                        [
                                                                            "key"=>"Telefono",

                                                                            "value"=>$model->phone
                                                                        ],
                                                                        [
                                                                            "key"=>"Mi operador de red",
                                                                            "value"=>($model->networkOperator?$model->networkOperator->name." ".$model->networkOperator->last_name:"")
                                                                        ],
                                                                        [
                                                                            "key"=>"Correo de mi operador de red",
                                                                            "value"=>($model->networkOperator?$model->networkOperator->email:"")
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
                                                  "view_name"=>"livewire.v1.admin.equipment.index-equipment",
                                                  "view_values"=>[
                                                      "data"=>$model->allEquipments(),
                                                      "permissionRemove" => [\App\Http\Resources\V1\Permissions::TECHNICIAN_REMOVE_EQUIPMENT],
                                                      "functionRemoveEquipment" => "removeEquipmentTechnician",
                                                      "conditionalRemoveEquipment" => "conditionalRemoveEquipmentTechnician",
                                                      "availableFlag" => "has_technician",
                                                      "table_class_container"=>"",
                                                      "view_header"=>false,
                                                      "col_filter"=>false
                                                   ],
                                               ],



                                ],
                           "logout_button"=>true
        ])

</div>
