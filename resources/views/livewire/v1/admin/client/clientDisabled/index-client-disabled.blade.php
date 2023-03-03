@if($view_header??true)
    <div class="login">
        @section("header")
            {{--extended app.blade--}}

        @endsection


        @include("partials.v1.title",[
              "second_title"=>(isset($clientType)?"de clientes desactivados tipo ".$clientType:"de clientes"),
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
                                                               "route"=>"v1.admin.client-disabled.detail.client",
                                                               "binding"=>"client"
                                                         ],
                                                       "icon"=>"fas fa-search",
                                                       "tooltip_title"=>"Detalles",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_SHOW],
                                                 ],
                                                [
                                                        "function"=>"enableClient",
                                                        "icon"=>"fas fa-user",
                                                        "tooltip_title"=>"Activar cliente",
                                                        "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_ACTION_ENABLE],
                                                ],
                                                 [
                                                        "function"=>"createActivationWorkOrder",
                                                        "icon"=>"fas fa-clipboard-list",
                                                        "tooltip_title"=>"Orden de trabajo para activacion",
                                                        "conditional"=>"createActivationWorkOrderConditional",
                                                        "modal"=>[
                                                                "header"=>"Crear oden de trabajo de activacion",
                                                                "body"=>"Esta seguro de crear una orden de trabajo para reactivar cliente ?",
                                                        ],
                                                        "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_WORK_ACTIVATION_ORDER],
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
