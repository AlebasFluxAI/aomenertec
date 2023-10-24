@if($view_header??true)
    <div class="login">
        @section("header")
            {{--extended app.blade--}}

        @endsection


        @include("partials.v1.title",[
              "second_title"=>(isset($clientType)?"de clientes activos tipo ".$clientType:"de clientes"),
              "first_title"=>"Listado"
          ])
        @endif
        <span style="font-weight: bold">{{isset($clientType)?"Tipo de cliente:". $clientType :"" }}</span>
        @if(isset($clientType))
            @include("partials.v1.table_nav",
                   [
                       "nav_options"=>[
                              [
                              "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_CREATE],
                              "button_align"=>"right",
                              "click_action"=>"",
                              "button_content"=>"Importar clientes",
                              "button_icon"=>"fa-solid fa-file-excel",
                              "target_route"=>"v1.admin.client.import-index.client",
                              ],
                              [
                              "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_CREATE],
                              "button_align"=>"right",
                              "click_action"=>"",
                              "button_content"=>"Crear nuevo",
                              "button_icon"=>"fa-solid fa-plus",
                              "target_route"=>"v1.admin.client.add.client",
                              ],
                              [
                                    "button_align"=>"right",
                                    "button_type"=>"dropdown_filter",
                                    "button_icon"=>"fas fa-gear",
                                    "button_content"=>"Tipo de cliente",
                                    "button_options"=>[
                                             [
                                            "title" => "ZNI Sistema fotovoltaico",
                                            "actionable" => [
                                                "function" => [
                                                    "button_content" => "ZNI Sistema fotovoltaico",
                                                    "button_action" => "setFilter",
                                                    "binding" => "client",
                                                    "value" => "ZNI Sistema fotovoltaico"
                                                ],
                                                "icon" => "fas fa-search",
                                                "tooltip_title" => "ZNI Sistema fotovoltaico",
                                            ],
                                        ],
                                         [
                                            "title" => "ZNI Convencional",
                                            "actionable" => [
                                                "function" => [
                                                    "button_content" => "ZNI Convencional",
                                                    "button_action" => "setFilter",
                                                    "binding" => "client",
                                                    "value" => "ZNI Convencional"
                                                ],
                                                "icon" => "fas fa-search",
                                                "tooltip_title" => "ZNI Convencional",
                                            ],
                                        ],
                                         [
                                            "title" => "ZNI rural",
                                            "actionable" => [
                                                "function" => [
                                                    "button_content" => "ZNI rural",
                                                    "button_action" => "setFilter",
                                                    "binding" => "client",
                                                    "value" => "ZNI rural"
                                                ],
                                                "icon" => "fas fa-search",
                                                "tooltip_title" => "ZNI rural",
                                            ],
                                        ],
                                         [
                                            "title" => "SIN Convencional",
                                            "actionable" => [
                                                "function" => [
                                                    "button_content" => "SIN Convencional",
                                                    "button_action" => "setFilter",
                                                    "binding" => "client",
                                                    "value" => "SIN Convencional"
                                                ],
                                                "icon" => "fas fa-search",
                                                "tooltip_title" => "SIN Convencional",
                                            ],
                                        ],
                                         [
                                            "title" => "Monitoreo",
                                            "actionable" => [
                                                "function" => [
                                                    "button_content" => "Monitoreo",
                                                    "button_action" => "setFilter",
                                                    "binding" => "client",
                                                    "value" => "Monitoreo"
                                                ],
                                                "icon" => "fas fa-search",
                                                "tooltip_title" => "Monitoreo",
                                            ],
                                        ],
    ]
                               ],

                          ]
                  ])
        @else
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
        @endif
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
                                                       "tooltip_title"=>"Configuración",
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
                                                        "function"=>"disableClient",
                                                        "icon"=>"fas fa-user-xmark",
                                                        "tooltip_title"=>"Desactivar cliente",
                                                        "modal"=>[
                                                                "header"=>"Desactivar cliente",
                                                                "body"=>"Esta seguro de desactivar cliente ?",
                                                        ],
                                                        "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_ACTION_DISABLE],
                                                ],
                                                [
                                                        "redirect"=>[
                                                                    "route"=>"v1.admin.client.invoicing",
                                                                    "binding"=>"client"
                                                              ],
                                                        "icon"=>"fas fa-money-bill",
                                                        "tooltip_title"=>"Facturas",
                                                        "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_SHOW_INVOICING],
                                                ],
                                                [
                                                        "redirect"=>[
                                                                    "route"=>"v1.admin.client.hand_reading",
                                                                    "binding"=>"client"
                                                              ],
                                                        "icon"=>"fas fa-file-signature",
                                                        "tooltip_title"=>"Lecturas manuales",
                                                        "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_HAND_READING],
                                                ],
                                                [
                                                        "redirect"=>[
                                                                    "route"=>"v1.admin.client.invoice_generate",
                                                                    "binding"=>"client"
                                                              ],
                                                        "icon"=>"fas fa-receipt",
                                                        "tooltip_title"=>"Generar factura de prueba",
                                                        "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_INVOICE_GENERATE],
                                                ],
                                                 [
                                                        "redirect"=>[
                                                                    "route"=>"v1.admin.client.manual_payment",
                                                                    "binding"=>"client"
                                                              ],
                                                        "icon"=>"fas fa-dollar-sign",
                                                        "tooltip_title"=>"Registrar pagos",
                                                        "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_INVOICE_MANUAL_PAYMENT],
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
