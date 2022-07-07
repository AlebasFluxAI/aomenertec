@if($view_header??true)
    <div class="login">
        @section("header") {{--extended app.blade--}}

        @endsection

        @include("partials.v1.title",[
              "second_title"=>"de administradores",
              "first_title"=>"Listado"
          ])
        @endif
        @include("partials.v1.table_nav",
               [
                    "mt"=>2,
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
                           "col_data" =>"phone",
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
                                                    "function"=>"details",
                                                    "icon"=>"fas fa-search",
                                                    "tooltip_title"=>"Detalles",
                                                    "permission"=>[\App\Http\Resources\V1\Permissions::ADMIN_SHOW],
                                            ],
                                            [
                                                    "function"=>"edit",
                                                    "icon"=>"fas fa-pencil",
                                                    "tooltip_title"=>"Editar",
                                                    "permission"=>[\App\Http\Resources\V1\Permissions::ADMIN_EDIT],
                                            ],
                                            [

                                                "redirect"=>[
                                                        "route"=>"administrar.v1.usuarios.admin.editar_precios",
                                                        "binding"=>""
                                                  ],
                                                "icon"=>"fa-solid fa-money-bill-wave",
                                                "tooltip_title"=>"Precios",
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
                                                    "function"=>"delete",
                                                    "conditional"=>$admin_conditional_delete??"conditionalDelete",
                                                    "icon"=>"fas fa-trash",
                                                    "tooltip_title"=>"Eliminar",
                                                    "permission"=>[\App\Http\Resources\V1\Permissions::ADMIN_DELETE],
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
