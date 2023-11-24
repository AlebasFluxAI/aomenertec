<div class="login">
    @section("header")
        {{--extended app.blade--}}

    @endsection

    <div style="background-color: #f6f6f6;
        padding: 2rem;
        border-radius: 10px;
        margin: 1rem">
        @include("partials.v1.title",[
              "second_title"=>"",
              "first_title"=>"Reemplazo de equipo"
          ])
        @include("partials.v1.table_nav",
                       [
                         "mt"=>2,
                         "nav_options"=>[
                                [
                                "permission"=>[\App\Http\Resources\V1\Permissions::PQR_SHOW],
                                "button_align"=>"right",
                                "click_action"=>"",
                                "button_content"=>"Ver listado",
                                "button_icon"=>"fa-solid fa-list",
                                "target_route"=>"administrar.v1.peticiones.listado",
                                ],
                                [
                                    "button_align"=>"right",
                                    "button_type"=>"dropdown",
                                    "button_icon"=>"fas fa-gear",
                                    "button_content"=>"Acciones",
                                    "button_options"=>$model->navigatorDropdownOptions()
                                    ]

                            ]
                        ])
        @include("partials.v1.divider_title",[
                    "title"=> "Equipos ". ($model->client?$model->client->name:"")
        ])

        <p>A continuación se presentara un listado con los equipos actualmente relacionados al cliente:</p>
        <br>
        <p><b>Seleccione uno o mas equipos que deben ser reemplazados</b></p>
        <br>
        @include("partials.v2.table.primary-table",[
                        "class_container"=> "",
                        "table_pageable"=>false,
                        "table_checkable"=>true,
                        "table_checkable_blank"=>true,
                        "table_empty_text"=>"No existen equipos disponibles de este tipo",
                        "table_headers"=>[
                                [
                                    "col_name" =>"ID",
                                    "col_data" =>"id",
                                    "col_filter"=>false
                                ],
                                [
                                    "col_name" =>"Nombre",
                                    "col_data" =>"name",
                                    "col_filter"=>false
                                ],
                                [
                                    "col_name" =>"Serial",
                                    "col_data" =>"serial",
                                    "col_filter"=>false
                                ],
                                [
                                    "col_name" =>"Tipo",
                                    "col_data" =>"equipmentType.type",
                                    "col_filter"=>false
                                ],
                                [
                                    "col_name" =>"Descripción",
                                    "col_data" =>"description",
                                    "col_filter"=>false
                                ],
                         ],
                        "table_rows"=>($model->client?$model->client->equipments:[])

                    ])
        <p><b>Cuando seleccione el equipo a ser reemplazado se desplegara un listado con los equipos candidatos</b></p>
    </div>
    @foreach($equipmentToChange as $equipment)
        <div
            style="background-color: #f6f6f6;
            padding: 2rem;
            margin: 1rem;
            border-bottom-color: teal;
            border-bottom-width: 2px;
            border-bottom-style: solid;
            border-top-color: teal;
            border-top-width: 2px;
            border-top-style: solid" ;>
            @include("partials.v1.divider_title",[
                        "title"=> "Cambiar equipo - ". ucfirst(strtolower($equipment->name))
            ])
            <p>A continuación se presentara un listado con los equipos cadidatos de reemplazo</p>
            <br>
            <p><b>Seleccione el equipo de reemplazo dentro del siguiente listado:</b></p>
            <br>
            @include("partials.v2.table.primary-table",[
                      "class_container"=> "",
                      "table_pageable"=>false,
                      "table_empty_text"=>"No existen equipos disponibles de este tipo",
                      "table_headers"=>[
                              [
                                  "col_name" =>"ID",
                                  "col_data" =>"id",
                                  "col_filter"=>false
                              ],
                              [
                                  "col_name" =>"Nombre",
                                  "col_data" =>"name",
                                  "col_filter"=>false
                              ],
                              [
                                  "col_name" =>"Serial",
                                  "col_data" =>"serial",
                                  "col_filter"=>false
                              ],
                              [
                                  "col_name" =>"Tipo",
                                  "col_data" =>"equipmentType.type",
                                  "col_filter"=>false
                              ],
                              [
                                  "col_name" =>"Descripción",
                                  "col_data" =>"description",
                                  "col_filter"=>false
                              ],
                       ],
                       "table_actions"=>[

                                    "customs"=>[
                                                     [
                                                             "function"=>"confirmEquipmentChange",
                                                             "icon"=>"fas fa-rotate",
                                                             "tooltip_title"=>"Confirmar equipo",
                                                             "permission"=>[\App\Http\Resources\V1\Permissions::PQR_EQUIPMENT_CHANGE_MANAGE],
                                                     ],
                          ],
                      ],
                      "table_rows"=>$this->equipmentByType($equipment->equipment_type_id)

                  ])

        </div>
    @endforeach

</div>
