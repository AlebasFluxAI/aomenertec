@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Detalles de",
            "second_title"=>"tecnico"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.usuarios.tecnicos.listado",
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
                                                                             "value"=>$model->phonePlusIndicative
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
                                                                             "key"=>"Operador de red",
                                                                             "value"=>($model->networkOperator?$model->networkOperator->id:null). "- ". ($model->networkOperator?$model->networkOperator->name:null)
                                                                         ],
                                                                          [
                                                                             "key"=>"Firma",
                                                                             "type"=>"image",
                                                                             "value"=>$model->sign?$model->sign->url:""
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
                                                      "data"=>$model->equipments()->get(),
                                                      "permissionRemove" => [\App\Http\Resources\V1\Permissions::TECHNICIAN_REMOVE_EQUIPMENT],
                                                      "functionRemoveEquipment" => "removeEquipmentTechnician",
                                                      "conditionalRemoveEquipment" => "conditionalRemoveEquipmentTechnician",
                                                      "availableFlag" => "has_clients",
                                                      "table_class_container"=>"",
                                                      "view_header"=>false,
                                                      "col_filter"=>false
                                                   ],
                                               ],
                                                ]

         ])


</div>
