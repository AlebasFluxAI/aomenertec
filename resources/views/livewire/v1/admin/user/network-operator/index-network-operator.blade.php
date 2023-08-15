@if($view_header??true)
    <div class="login">
        @section("header")
            {{--extended app.blade--}}

        @endsection

        @include("partials.v1.title",[
              "second_title"=>"de operadores de red",
              "first_title"=>"Listado"
          ])


        @endif
        @include("partials.v1.table_nav",
               [
                "nav_options"=>[
                          ["button_align"=>"right",
                          "click_action"=>"",
                          "button_content"=>"Crear nuevo",
                          "button_icon"=>"fa-solid fa-plus",
                          "target_route"=>"administrar.v1.usuarios.operadores.agregar",
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
                           "col_filter"=>$col_filter??true
                       ],
                       [
                           "col_name" =>"Nombre",
                           "col_data" =>"name",
                           "col_filter"=>$col_filter??true
                       ],
                       [
                           "col_name" =>"Apellido",
                           "col_data" =>"last_name",
                           "col_filter"=>$col_filter??true
                       ],
                       [
                           "col_name" =>"Correo electronico",
                           "col_data" =>"email",
                           "col_filter"=>$col_filter??true
                       ],
                       [
                           "col_name" =>"Telefono",
                           "col_data" =>"phonePlusIndicative",
                           "col_filter"=>$col_filter??true
                       ],
                       [
                           "col_name" =>"Identificacion",
                           "col_data" =>"identification",
                           "col_filter"=>$col_filter??true
                       ],
                       [
                           "col_name" =>"admin",
                           "col_data" =>"admin.name",
                           "col_filter"=>$col_filter??true
                       ],

                    ],
                     "table_actions"=>[

                                        "customs"=>[
                                                    [
                                                       "redirect"=>[
                                                                   "route"=>"administrar.v1.usuarios.operadores.detalles",
                                                                   "binding"=>"networkOperator"
                                                             ],
                                                           "icon"=>"fas fa-search",
                                                           "tooltip_title"=>"Detalles",
                                                           "permission"=>[\App\Http\Resources\V1\Permissions::NETWORK_OPERATOR_SHOW],
                                                     ],
                                                    [
                                                       "redirect"=>[
                                                                   "route"=>"administrar.v1.usuarios.operadores.editar",
                                                                   "binding"=>"networkOperator"
                                                             ],
                                                           "icon"=>"fas fa-pencil",
                                                           "tooltip_title"=>"Editar",
                                                           "permission"=>[\App\Http\Resources\V1\Permissions::NETWORK_OPERATOR_EDIT],
                                                     ],
                                                    [
                                                       "redirect"=>[
                                                                   "route"=>"administrar.v1.usuarios.operadores.agregar_equipos",
                                                                   "binding"=>"networkOperator"
                                                             ],
                                                           "icon"=>"fas fa-laptop-medical",
                                                           "tooltip_title"=>"Asociar equipos",
                                                           "conditional" => "conditionalLinkEquipmentNetworkOperator",
                                                           "permission"=>[\App\Http\Resources\V1\Permissions::NETWORK_OPERATOR_LINK_EQUIPMENT],
                                                     ],
                                                     [
                                                        "permission" => [\App\Http\Resources\V1\Permissions::NETWORK_OPERATOR_ENABLED],
                                                        "conditional" => "getEnabledNetworkOperator",
                                                        "function"=>"disableNetworkOperator",
                                                        "icon"=>"fa-solid fa-user-xmark",
                                                        "tooltip_title"=>"Desactivar"
                                                    ],
                                                    [
                                                        "permission" => [\App\Http\Resources\V1\Permissions::NETWORK_OPERATOR_ENABLED],
                                                        "conditional" => "getEnabledAuxNetworkOperator",
                                                        "function"=>"disableNetworkOperator",
                                                        "icon"=>"fa-solid fa-user-check",
                                                        "tooltip_title"=>"Activar"
                                                    ],
                                                    [
                                                        "permission"=>[\App\Http\Resources\V1\Permissions::NETWORK_OPERATOR_DELETE],
                                                            "function"=>"deleteNetworkOperator",
                                                            "conditional"=>"conditionalDeleteNetworkOperator",
                                                            "icon"=>"fas fa-trash",
                                                            "tooltip_title"=>"Eliminar"
                                                    ],
                                                    //[
                                                    //   "redirect"=>[
                                                    //               "route"=>"administrar.v1.usuarios.operadores.configurar_precios",
                                                    //               "binding"=>"networkOperator"
                                                    //         ],
                                                    //       "icon"=>"fas fa-money-bill-wave",
                                                    //       "tooltip_title"=>"Configurar precios",
                                                    //       "permission"=>[\App\Http\Resources\V1\Permissions::NETWORK_OPERATOR_PRICE_CONFIGURATION],
                                                    // ],
                                                     [
                                                       "redirect"=>[
                                                                   "route"=>"administrar.v1.usuarios.operadores.configurar_bolsa_servicios",
                                                                   "binding"=>"networkOperator"
                                                             ],
                                                           "icon"=>"fas fa-cash-register",
                                                           "tooltip_title"=>"Bolsa de servicios",
                                                           "permission"=>[\App\Http\Resources\V1\Permissions::NETWORK_OPERATOR_SERVICE_BAG_CONFIGURATION],
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
