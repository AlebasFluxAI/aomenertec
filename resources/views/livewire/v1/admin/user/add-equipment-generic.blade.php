<div class="login">
    @section("header")
        {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Editar",
            "second_title"=>$this->getPageTitle()
        ])
    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=> $this->getNavOptions()

        ])

    @include("partials.v1.primary-card",[
            'card_title'=>$this->getCardTitle(),
            'card_subtitle'=>$model->id,
            'card_body'=>[
                            [
                                   "name"=>"Nombre",
                                   "value"=>$model->name
                            ]   ,
                             [
                                   "name"=>"Identificacion",
                                   "value"=>$model->identification
                            ] ,
                                     [
                                   "name"=>"Correo",
                                   "value"=>$model->email
                            ] ,
                         ]
        ])


    <div class="divider-1 mt-4"></div>

    @include("partials.v1.tab.v1.tab",[
                            "tab_titles"=>[
                                            [
                                               "title"=>"Agregar equipos"
                                             ],
                                             [
                                                "title"=>"Listado de equipos"
                                             ]
                                        ],
                                            "tab_contents"=>[
                                                [
                                                "view_name"=>"partials.v1.equipmentAssignation.equipment_assignation_v2",
                                                "view_values"=>[]
                                               ],
                                             [
                                                  "view_name"=>"partials.v1.table.primary-table",
                                                   "view_values"=>[
                                                                       "table_pageable"=>false,
                                                                      "table_headers"=>["ID"=>"id",
                                                                                        "Nombre"=>"name",
                                                                                        "Serial"=>"serial",
                                                                                        "Tipo"=>"equipmentType.type",
                                                                                        "Descripcion"=>"description",
                                                                       ],
                                                                      "table_actions"=>[
                                                                                    "customs"=>[
                                                                                             [
                                                                                                    "redirect"=>[
                                                                                                            "route"=>"administrar.v1.equipos.detalle",
                                                                                                            "binding"=>"equipment"
                                                                                                      ],
                                                                                                    "icon"=>"fas fa-search",
                                                                                                     "tooltip_title"=>"Detalles",
                                                                                               ],
                                                                                              [
                                                                                             "function"=>"deleteEquipmentAssigned",
                                                                                             "icon"=>"fas fa-trash",
                                                                                             "model_id"=>"id"
                                                                                            ],
                                                                                        ]
                                                                                    ],
                                                                      "table_rows"=>$model->equipments

                                                                  ]
                                               ],
                ]

                            ])


</div>
