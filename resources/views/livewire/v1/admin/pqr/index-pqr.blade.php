<div class="login">
    @section("header")
        {{--extended app.blade--}}

    @endsection


    @include("partials.v1.title",[
          "second_title"=>"",
          "first_title"=>"PQRS"
      ])
    @include("partials.v1.table_nav",
            [
                "mt"=>2,
                "nav_options"=>[
                       [
                       "permission"=>[\App\Http\Resources\V1\Permissions::PQR_CREATE],
                       "button_align"=>"right",
                       "click_action"=>"",
                       "button_content"=>"Crear PQR",
                       "button_icon"=>"fa-solid fa-plus",
                       "target_route"=>"administrar.v1.peticiones.supervisor.crear",
                       ],
                       [
                       "permission"=>[\App\Http\Resources\V1\Permissions::PQR_CREATE_NETWORK_OPERATOR],
                       "button_align"=>"right",
                       "click_action"=>"",
                       "button_content"=>"Crear PQR",
                       "button_icon"=>"fa-solid fa-plus",
                       "target_route"=>"administrar.v1.peticiones.operador.crear",
                       ],

                   ]
           ])

    @include("partials.v2.table.primary-table",[
           "table_headers"=>[
            [
               "col_name" =>"ID",
               "col_data" =>"id",
               "col_filter"=>false
           ],
            [
               "col_name" =>"Codigo",
               "col_data" =>"code",
               "col_filter"=>false
           ],
           [
               "col_name" =>"Asunto",
               "col_data" =>"subject",
               "col_filter"=>false
           ],
           [
               "col_name" =>"Tipo",
               "col_data" =>"type",
               "col_filter"=>false,
               "col_translate"=>"pqr"
           ],
           [
                   "col_name" =>"Categoria",
               "col_data" =>"sub_type",
               "col_filter"=>false,
               "col_translate"=>"pqr"
           ],
            [
                "col_name" =>"Cliente",
               "col_data" =>"client.name",
               "col_filter"=>false,

           ],

           [
                "col_name" =>"Estado",
               "col_data" =>"status",
               "col_filter"=>false,
               "col_translate"=>"pqr"

           ],
           [
                "col_name" =>"Nivel",
               "col_data" =>"level",
               "col_filter"=>false,
               "col_translate"=>"pqr"

           ],
           [
               "col_name" =>"Prioridad",
               "col_data" =>"severity",
               "col_filter"=>false,
               "col_translate"=>"pqr"
           ],


            ],
             "table_actions"=>[

                                "customs"=>[
                                                [
                                                   "redirect"=>[
                                                               "route"=>"administrar.v1.peticiones.detalles",
                                                               "binding"=>"pqr"
                                                         ],
                                                       "icon"=>"fas fa-search",
                                                       "tooltip_title"=>"Detalles",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::PQR_SHOW],
                                                 ],

                                                [

                                                       "permission"=>[\App\Http\Resources\V1\Permissions::PQR_REPLY],
                                                        "redirect"=>[
                                                                "route"=>"administrar.v1.peticiones.respuesta",
                                                                "binding"=>"pqr"
                                                          ],
                                                        "icon"=>"fa fa-comment-dots",
                                                        "tooltip_title"=>"Responder ticket",
                                                        "conditional"=>"openTicked"
                                                ],
                                                  [

                                                       "permission"=>[\App\Http\Resources\V1\Permissions::PQR_REPLY],
                                                        "redirect"=>[
                                                                "route"=>"administrar.v1.peticiones.historial-mensajes",
                                                                "binding"=>"pqr"
                                                          ],
                                                        "icon"=>"fa fa-list",
                                                        "tooltip_title"=>"Historial de mensajes",
                                                ],
                                                [

                                                       "permission"=>[\App\Http\Resources\V1\Permissions::PQR_EQUIPMENT_CHANGE_MANAGE],
                                                        "redirect"=>[
                                                                "route"=>"administrar.v1.peticiones.cambio-equipo-historico",
                                                                "binding"=>"pqr"
                                                          ],
                                                        "icon"=>"fa fa-server",
                                                        "tooltip_title"=>"Historial de cambios de equipo",
                                                        "conditional"=>"closedTicked"
                                                ],
                                                 [

                                                       "permission"=>[\App\Http\Resources\V1\Permissions::PQR_LINK_CLIENT],
                                                        "redirect"=>[
                                                                "route"=>"administrar.v1.peticiones.relacionar_cliente",
                                                                "binding"=>"pqr"
                                                          ],
                                                        "icon"=>"fa fa-user-plus",
                                                        "tooltip_title"=>"Relacionar cliente",
                                                ],
                                                [

                                                        "permission"=>[\App\Http\Resources\V1\Permissions::PQR_EQUIPMENT_CHANGE_MANAGE],
                                                        "function"=>"requestEquipment",
                                                        "icon"=>"fas fa-rotate",
                                                        "tooltip_title"=>"Gestionar cambio de equipo",
                                                        "conditional"=>"equipmentRequest",
                                                        "redirect"=>[
                                                                "route"=>"administrar.v1.peticiones.cambio-equipo",
                                                                "binding"=>"pqr"
                                                          ],
                                                ],

                                              // [
                                              //         "permission"=>[\App\Http\Resources\V1\Permissions::PQR_REQUEST_CLOSE],
                                              //         "function"=>"closePqr",
                                              //         "icon"=>"fas fa-file-circle-question",
                                              //         "tooltip_title"=>"Solicitar cierre de ticket",
                                              //         "conditional"=>"openTicked"
                                              // ],
                                                [

                                                        "permission"=>[\App\Http\Resources\V1\Permissions::PQR_CLOSE],
                                                             "redirect"=>[
                                                                "route"=>"administrar.v1.peticiones.cierre",
                                                                "binding"=>"pqr"
                                                          ],
                                                        "icon"=>"fas fa-check",
                                                        "tooltip_title"=>"Resolver ticket",
                                                        "conditional"=>"openTicked"
                                                ],
                                                    [

                                                        "permission"=>[\App\Http\Resources\V1\Permissions::PQR_EQUIPMENT_CHANGE],
                                                        "function"=>"requestEquipment",
                                                        "icon"=>"fas fa-computer",
                                                        "tooltip_title"=>"Solicitar cambio de equipo",
                                                        "conditional"=>"equipmentNotRequest"
                                                ],
                                                  [

                                                        "permission"=>[\App\Http\Resources\V1\Permissions::PQR_TO_WORK_ORDER],
                                                        "function"=>"convertToWorkOrder",
                                                        "icon"=>"fas fa-arrows-spin",
                                                        "tooltip_title"=>"Convertir en orden de trabajo",
                                                        "conditional"=>"canConvertToOrder"
                                                ],
                                                 [
                                                        "function"=>"downloadReport",
                                                        "icon"=>"fas fa-file-download",
                                                        "tooltip_title"=>"Reporte de PQR",
                                                        "conditional"=>"canDownloadReport"
                                                ],
                                                    [

                                                        "permission"=>[\App\Http\Resources\V1\Permissions::PQR_CHANGE_LEVEL],
                                                        "function"=>"changeLevel",
                                                        "icon"=>"fas fa-arrow-turn-up",
                                                        "tooltip_title"=>"Escalar ticket",
                                                        "conditional"=>"openTicked",
                                                        "modal_content"=>"Esta seguro que quiere cambiar el nivel del Pqrs ?"
                                                ],
                                    ]
                                ],

                                            /* Le dice al componente tabla las acciones que tendra la columna de acciones en la tabla [
                                            _edit_button=>{ruta para redireccionar a edicion}
                                            _delete_button => {boton de borrado, siempre tomando como identificador la primera colunma de la tabla - ID}
                                              ]*/
           "table_rows"=>$data



       ])

</div>
