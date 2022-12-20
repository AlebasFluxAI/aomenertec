@if($view_header??true)
    <div class="login">
        @section("header")
            {{--extended app.blade--}}

        @endsection


        @include("partials.v1.title",[
              "second_title"=>"de clientes activos",
              "first_title"=>"Listado"
          ])
        @endif
        @include("partials.v1.table_nav",
               [
                   "nav_options"=>[
                          [
                          "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_CREATE],
                          "button_align"=>"right",
                          "click_action"=>"",
                          "button_content"=>"Crear nuevo",
                          "button_icon"=>"fa-solid fa-plus",
                          "target_route"=>"v1.admin.client.add.client",
                          ],

                      ]
              ])

        @include("partials.v2.table.primary-table",[
                "class_container"=>$table_class_container??null,
               "table_pageable"=>$table_pageable??true,
               "table_headers"=>[
              [
                   "col_name" =>"ID",
                   "col_data" =>"id",
                   "col_filter"=>true
               ],
               [
                   "col_name" =>"Codigo",
                   "col_data" =>"code",
                   "col_filter"=>true
               ],
               [
                   "col_name" =>"Nombre",
                   "col_data" =>"name",
                   "col_filter"=>true
               ],
               [
                   "col_name" =>"Apellido",
                   "col_data" =>"last_name",
                   "col_filter"=>true
               ],
               [
                   "col_name" =>"Alias",
                   "col_data" =>"alias",
                   "col_filter"=>true
               ],
               [
                   "col_name" =>"Correo electronico",
                   "col_data" =>"email",
                   "col_filter"=>true
               ],
               [
                   "col_name" =>"Telefono",
                   "col_data" =>"phonePlusIndicative",
                   "col_filter"=>true
               ],
                [
                       "col_name" =>"Operador de red",
                       "col_data" =>"networkOperator.name",
                       "col_filter"=>false
                   ],

                ],
                 "table_actions"=>[

                                    "customs"=>[
                                        [
                                                   "redirect"=>[
                                                               "route"=>"v1.admin.client.detail.client",
                                                               "binding"=>"client"
                                                         ],
                                                       "icon"=>"fas fa-search",
                                                       "tooltip_title"=>"Detalles",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_SHOW],
                                                 ],
                                                [
                                                   "redirect"=>[
                                                               "route"=>"v1.admin.client.edit.client",
                                                               "binding"=>"client"
                                                         ],
                                                       "icon"=>"fas fa-pencil",
                                                       "tooltip_title"=>"Editar",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_EDIT],
                                                 ],
                                                [
                                                   "redirect"=>[
                                                               "route"=>"v1.admin.client.settings",
                                                               "binding"=>"client"
                                                         ],
                                                       "icon"=>"fas fa-gear",
                                                       "tooltip_title"=>"Configuración de equipos",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_SETTINGS],
                                                 ],

                                                    [
                                                        "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_SHOW_MONITORING],
                                                        "redirect"=>[
                                                                    "route"=>"v1.admin.client.monitoring",
                                                                    "binding"=>"client"
                                                              ],
                                                            "icon"=>"fa fa-connectdevelop",
                                                            "tooltip_title"=>"Monitoreo",
                                                            "conditional" => "conditionalMonitoring",
                                                    ],
                                                    [
                                                        "function"=>"deleteClient",
                                                        "conditional"=>"conditionalDeleteClient",
                                                        "icon"=>"fas fa-trash",
                                                        "tooltip_title"=>"Eliminar",
                                                        "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_DELETE],
                                                ],
                                                [
                                                        "redirect"=>[
                                                                    "route"=>"v1.admin.client.add.equipment",
                                                                    "binding"=>"client"
                                                              ],
                                                        "icon"=>"fas fa-computer",
                                                        "tooltip_title"=>"Agregar equipos",
                                                        "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_ADD_EQUIPMENT],
                                                ],
                                                [
                                                        "redirect"=>[
                                                                    "route"=>"v1.admin.client.work_orders",
                                                                    "binding"=>"client"
                                                              ],
                                                        "icon"=>"fas fa-hammer",
                                                        "tooltip_title"=>"Ordenes de trabajo",
                                                        "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_WORK_ORDER],
                                                ],
                                                [
                                                        "redirect"=>[
                                                                    "route"=>"v1.admin.client.change_equipment.historical",
                                                                    "binding"=>"client"
                                                              ],
                                                        "icon"=>"fas fa-server",
                                                        "tooltip_title"=>"Historial de cambios de equipo",
                                                        "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_SHOW],
                                                ],
                                                [
                                                        "redirect"=>[
                                                                    "route"=>"v1.admin.client.add.alerts",
                                                                    "binding"=>"client"
                                                              ],
                                                        "icon"=>"fas fa-bell",
                                                        "tooltip_title"=>"Alertas",
                                                        "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_SHOW_ALERTS],
                                                ],
                                                [
                                                        "redirect"=>[
                                                                    "route"=>"v1.admin.client.monitoring.control",
                                                                    "binding"=>"client"
                                                              ],
                                                        "icon"=>"fas fa-toggle-on",
                                                        "tooltip_title"=>"On/Off",
                                                        "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_MONITORING_CONTROL],
                                                ],
                                                [
                                                        "redirect"=>[
                                                                    "route"=>"v1.admin.client.monitoring.control",
                                                                    "binding"=>"client"
                                                              ],
                                                        "icon"=>"fas fa-toggle-on",
                                                        "tooltip_title"=>"On/Off",
                                                        "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_MONITORING_CONTROL],
                                                ],
                                                 [
                                                        "function"=>"disableClient",
                                                        "icon"=>"fas fa-user-xmark",
                                                        "tooltip_title"=>"Desactivar cliente",
                                                        "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_ACTION_DISABLE],
                                                ],



                                    ]
                                    ],

                                                /* Le dice al componente tabla las acciones que tendra la columna de acciones en la tabla [
                                                _edit_button=>{ruta para redireccionar a edicion}
                                                _delete_button => {boton de borrado, siempre tomando como identificador la primera colunma de la tabla - ID}
                                                  ]*/
               "table_rows"=>$data



           ])
        @if($view_header??true)
    </div>
@endif
