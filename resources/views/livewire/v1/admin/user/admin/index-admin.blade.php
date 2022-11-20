@if($view_header??true)
    <div class="login">
        @section("header")
            {{--extended app.blade--}}

        @endsection

        @include("partials.v1.title",[
              "second_title"=>"de administradores",
              "first_title"=>"Listado"
          ])
        @endif
        @include("partials.v1.table_nav",
               [
                    "nav_options"=>[
                          [
                              "permission"=>[\App\Http\Resources\V1\Permissions::ADMIN_CREATE],
                              "button_align"=>"right",
                              "click_action"=>"",
                              "button_content"=>"Crear nuevo",
                              "button_icon"=>"fa-solid fa-plus",
                              "target_route"=>"administrar.v1.usuarios.admin.agregar",
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
                           "col_filter"=>$col_filter??true,
                       ],
                       [
                           "col_name" =>"Nombre",
                           "col_data" =>"name",
                           "col_filter"=>$col_filter??true,
                       ],
                       [
                           "col_name" =>"Apellido",
                           "col_data" =>"last_name",
                           "col_filter"=>$col_filter??true,
                       ],
                       [
                           "col_name" =>"Correo electronico",
                           "col_data" =>"email",
                           "col_filter"=>$col_filter??true,
                       ],
                       [
                           "col_name" =>"Telefono",
                           "col_data" =>"phonePlusIndicative",
                           "col_filter"=>$col_filter??true,
                       ],
                       [
                           "col_name" =>"Identificacion",
                           "col_data" =>"identification",
                           "col_filter"=>$col_filter??true,
                       ],


                    ],

                     "table_actions"=>[
                                        "customs"=>[
                                            [
                                               "redirect"=>[
                                                           "route"=>"administrar.v1.usuarios.admin.detalles",
                                                           "binding"=>"admin"
                                                     ],
                                                   "icon"=>"fas fa-search",
                                                   "tooltip_title"=>"Detalles",
                                                   "permission"=>[\App\Http\Resources\V1\Permissions::ADMIN_SHOW],
                                             ],
                                            [
                                               "redirect"=>[
                                                           "route"=>"administrar.v1.usuarios.admin.editar",
                                                           "binding"=>"admin"
                                                     ],
                                                   "icon"=>"fas fa-pencil",
                                                   "tooltip_title"=>"Editar",
                                                   "permission"=>[\App\Http\Resources\V1\Permissions::ADMIN_EDIT],
                                             ],

                                            [

                                                "redirect"=>[
                                                        "route"=>"administrar.v1.usuarios.admin.editar_precios",
                                                        "binding"=>""
                                                  ],
                                                "icon"=>"fas fa-gear",
                                                "tooltip_title"=>"Configuración",
                                                "permission"=>[\App\Http\Resources\V1\Permissions::EQUIPMENT_CONFIG],
                                            ],
                                            [
                                               "redirect"=>[
                                                           "route"=>"administrar.v1.usuarios.admin.agregar_tipos_equipo",
                                                           "binding"=>"admin"
                                                     ],
                                                   "icon"=>"fas fa-computer",
                                                   "tooltip_title"=>"Asociar tipos de equipos",
                                                   "permission"=>[\App\Http\Resources\V1\Permissions::ADMIN_LINK_EQUIPMENT_TYPE],
                                             ],
                                             [
                                               "redirect"=>[
                                                           "route"=>"administrar.v1.usuarios.admin.agregar_equipos",
                                                           "binding"=>"admin"
                                                     ],
                                                   "icon"=>"fas fa-laptop-medical",
                                                   "tooltip_title"=>"Asociar equipos",
                                                   "permission"=>[\App\Http\Resources\V1\Permissions::ADMIN_LINK_EQUIPMENT],
                                             ],
                                             [
                                                        "permission" => [\App\Http\Resources\V1\Permissions::ADMIN_ENABLED],
                                                        "conditional" => "getEnabledAdmin",
                                                        "function"=>"disableAdmin",
                                                        "icon"=>"fa-solid fa-user-xmark",
                                                        "tooltip_title"=>"Desactivar"
                                              ],
                                              [
                                                        "permission" => [\App\Http\Resources\V1\Permissions::ADMIN_ENABLED],
                                                        "conditional" => "getEnabledAuxAdmin",
                                                        "function"=>"disableAdmin",
                                                        "icon"=>"fa-solid fa-user-check",
                                                        "tooltip_title"=>"Activar"
                                              ],
                                              [
                                                    "function"=>"deleteAdmin",
                                                    "conditional"=>"conditionalDeleteAdmin",
                                                    "icon"=>"fas fa-trash",
                                                    "tooltip_title"=>"Eliminar",
                                                    "permission"=>[\App\Http\Resources\V1\Permissions::ADMIN_DELETE],
                                              ],
                                              [
                                               "redirect"=>[
                                                           "route"=>"administrar.v1.permisos.pestanas",
                                                           "binding"=>"id",
                                                           "extra_params"=>[
                                                                "user_type"=>\App\Models\V1\Admin::class,
                                                               ]
                                                     ],
                                                   "icon"=>"fas fa-lock",
                                                   "tooltip_title"=>"Permisos de pestanas",
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
