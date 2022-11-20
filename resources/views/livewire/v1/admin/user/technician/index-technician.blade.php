@if($view_header??true)
    <div class="login">
        @section("header")
            {{--extended app.blade--}}

        @endsection

        @include("partials.v1.title",[
              "second_title"=>"de tecnicos",
              "first_title"=>"Listado"
          ])

        @endif

        @include("partials.v1.table_nav",
               [
               "nav_options"=>[
                          [
                              "permission"=>[\App\Http\Resources\V1\Permissions::TECHNICIAN_CREATE],
                              "button_align"=>"right",
                              "click_action"=>"",
                              "button_content"=>"Crear nuevo",
                              "button_icon"=>"fas fa-plus",
                              "target_route"=>"administrar.v1.usuarios.tecnicos.agregar",
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
                           "col_name" =>"Identificacion",
                           "col_data" =>"identification",
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
                                                                       "route"=>"administrar.v1.usuarios.tecnicos.detalles",
                                                                       "binding"=>"technician"
                                                                 ],
                                                               "icon"=>"fas fa-search",
                                                               "tooltip_title"=>"Detalles",
                                                               "permission"=>[\App\Http\Resources\V1\Permissions::TECHNICIAN_SHOW],
                                                         ],
                                                        [
                                                           "redirect"=>[
                                                                       "route"=>"administrar.v1.usuarios.tecnicos.editar",
                                                                       "binding"=>"technician"
                                                                 ],
                                                               "icon"=>"fas fa-pencil",
                                                               "tooltip_title"=>"Editar",
                                                               "permission"=>[\App\Http\Resources\V1\Permissions::TECHNICIAN_EDIT],
                                                         ],


                                                        [
                                                           "redirect"=>[
                                                                       "route"=>"administrar.v1.usuarios.tecnicos.agregar_clientes",
                                                                       "binding"=>"technician"
                                                                 ],
                                                               "icon"=>"fas fa-users",
                                                               "tooltip_title"=>"Asociar clientes",
                                                               "conditional" => "conditionalLinkClientsTechnician",
                                                               "permission"=>[\App\Http\Resources\V1\Permissions::TECHNICIAN_LINK_CLIENT],
                                                         ],
                                                        [
                                                           "redirect"=>[
                                                                       "route"=>"administrar.v1.usuarios.tecnicos.agregar_equipos",
                                                                       "binding"=>"technician"
                                                                 ],
                                                               "icon"=>"fas fa-laptop-medical",
                                                               "tooltip_title"=>"Asociar equipos",
                                                               "conditional" => "conditionalLinkEquipmentTechnician",
                                                               "permission"=>[\App\Http\Resources\V1\Permissions::TECHNICIAN_LINK_EQUIPMENT],
                                                         ],
                                                         [
                                                            "permission" => [\App\Http\Resources\V1\Permissions::TECHNICIAN_ENABLED],
                                                            "conditional" => "getEnabledTechnician",
                                                            "function"=>"disableTechnician",
                                                            "icon"=>"fa-solid fa-user-xmark",
                                                            "tooltip_title"=>"Desactivar"
                                                        ],
                                                        [
                                                            "permission" => [\App\Http\Resources\V1\Permissions::TECHNICIAN_ENABLED],
                                                            "conditional" => "getEnabledAuxTechnician",
                                                            "function"=>"disableTechnician",
                                                            "icon"=>"fa-solid fa-user-check",
                                                            "tooltip_title"=>"Activar"
                                                        ],
                                                [
                                                    "permission"=>[\App\Http\Resources\V1\Permissions::TECHNICIAN_DELETE],
                                                        "function"=>"deleteTechnician",
                                                        "conditional"=>"conditionalDeleteTechnician",
                                                        "icon"=>"fas fa-trash",
                                                        "tooltip_title"=>"Eliminar"
                                                ],

                                                    ],

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

