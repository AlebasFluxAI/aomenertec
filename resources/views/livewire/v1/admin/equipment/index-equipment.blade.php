@if($view_header??true)
    <div class="login">

        @section("header")
            {{--extended app.blade--}}

        @endsection

        @include("partials.v1.title",[
              "second_title"=>"de equipos",
              "first_title"=>"Listado"
          ])

        @endif
        <div>
            @include("partials.v1.table_nav",
                   ["nav_options"=>$tab_options?? [
                                [
                                   "permission"=>[\App\Http\Resources\V1\Permissions::EQUIPMENT_CREATE],
                                   "button_align"=>"right",
                                   "click_action"=>"",
                                   "button_content"=>"Crear nuevo",
                                   "icon"=>"fa-solid fa-plus",
                                   "target_route"=>"administrar.v1.equipos.agregar",
                              ]
                              ],
                  ])

            @include("partials.v2.table.primary-table",[
        "class_container"=>$table_class_container??null,
                       "table_pageable"=>$table_pageable??true,
                       "table_headers"=>[
                               [
                                   "col_name" =>"ID",
                                   "col_data" =>"id",
                                   "col_filter"=>$col_filter??true
                               ],
                               [
                                   "col_name" =>"Nombre",
                                   "col_data" =>"name",
                                   "col_filter"=>$col_filter??true
                               ],
                                 [
                                   "col_name" =>"Serial",
                                   "col_data" =>"serial",
                                   "col_filter"=>$col_filter??true
                               ],
                                 [
                                   "col_name" =>"Tipo",
                                   "col_data" =>"equipmentType.type",
                                   "col_filter"=>false
                               ],
                               [
                                   "col_name" =>"Descripcion",
                                   "col_data" =>"description",
                                   "col_filter"=>$col_filter??true
                               ],
                               [
                                   "col_name" =>"Disponible",
                                   "col_data" =>"available",
                                   "col_filter"=>$col_filter??true,
                                   "col_type"=>\App\Http\Resources\V1\ColTypeEnum::COL_TYPE_BOOLEAN
                               ],
                               [
                                   "col_name" =>"Estado del equipo",
                                   "col_data" =>"status",
                                   "col_filter"=>false,
                                   "col_translate"=>"equipment"
                               ],
                               [
                                   "col_name" =>"Tecnico",
                                   "col_data" =>"technician.name",
                                   "col_filter"=>true,

                               ],
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
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::EQUIPMENT_SHOW],
                                                 ],
                                                [
                                                   "redirect"=>[
                                                               "route"=>"administrar.v1.equipos.editar",
                                                               "binding"=>"equipment"
                                                         ],
                                                       "icon"=>"fas fa-pencil",
                                                       "tooltip_title"=>"Editar",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::EQUIPMENT_EDIT],
                                                 ],
                                                    [
                                                            "permission" => $permissionRemove,
                                                            "conditional" => $conditionalRemoveEquipment,
                                                            "function"=>$functionRemoveEquipment,
                                                            "icon"=>"fa-solid fa-square-minus",
                                                            "tooltip_title"=>"Desvincular equipo"
                                                        ],
                                                    [
                                                        "function"=>"deleteEquipment",
                                                        "conditional"=>"conditionalDeleteEquipment",
                                                        "icon"=>"fas fa-trash",
                                                        "tooltip_title"=>"Eliminar",
                                                        "permission"=>[\App\Http\Resources\V1\Permissions::EQUIPMENT_DELETE],
                                                      ],
                                                      [
                                                            "function"=>"deprecateEquipment",
                                                            "conditional"=>"conditionalEquipmentDeprecate",
                                                            "icon"=>"fas fa-link-slash",
                                                            "tooltip_title"=>"Dar de baja",
                                                            "permission"=>[\App\Http\Resources\V1\Permissions::EQUIPMENT_DELETE],
                                                      ],
                                                        [
                                                            "function"=>"repairEquipment",
                                                            "conditional"=>"conditionalEquipmentRepaired",
                                                            "icon"=>"fas fa-clipboard-check",
                                                            "tooltip_title"=>"Marcar como reparado",
                                                            "permission"=>[\App\Http\Resources\V1\Permissions::EQUIPMENT_REPAIR],
                                                      ]
                                                ],
                                            ],

                                                        /* Le dice al componente tabla las acciones que tendra la columna de acciones en la tabla [
                                                        _edit_button=>{ruta para redireccionar a edicion}
                                                        _delete_button => {boton de borrado, siempre tomando como identificador la primera colunma de la tabla - ID}
                                                          ]*/
                       "table_rows"=>$data

                   ])
        </div>
        @if($view_header??true)
    </div>
@endif
