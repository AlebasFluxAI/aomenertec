<div class="login">
    @section("header")
        {{--extended app.blade--}}

    @endsection

    @include("partials.v1.title",[
          "second_title"=>"de usuarios de soporte",
          "first_title"=>"Listado"
      ])



    @include("partials.v1.table_nav",
           ["nav_options"=>[
                      [
                      "permission"=>[\App\Http\Resources\V1\Permissions::SUPPORT_CREATE],
                      "button_align"=>"right",
                      "click_action"=>"",
                      "button_content"=>"Crear nuevo",
                      "button_icon"=>"fa-solid fa-plus",
                      "target_route"=>"administrar.v1.usuarios.soporte.agregar",
                      ],

                  ]
          ])

    @include("partials.v2.table.primary-table",[
               "table_headers"=>[ [
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
                   [
                       "col_name" =>"Activo para Gestionar Pqr",
                       "col_data" =>"pqr_available",
                       "col_filter"=>false,
                       "col_type"=>\App\Http\Resources\V1\ColTypeEnum::COL_TYPE_BOOLEAN
                   ],
                ],
                 "table_actions"=>[
                                    "customs"=>[
                                                     [
                                                            "function"=>"details",
                                                            "icon"=>"fas fa-search",
                                                            "tooltip_title"=>"Detalles",
                                                            "permission"=>[\App\Http\Resources\V1\Permissions::SUPPORT_SHOW],
                                                    ],
                                                    [
                                                            "function"=>"edit",
                                                            "icon"=>"fas fa-pencil",
                                                            "tooltip_title"=>"Editar",
                                                            "permission"=>[\App\Http\Resources\V1\Permissions::SUPPORT_EDIT],
                                                    ],
                                                  //  [
                                                  //          "permission"=>[\App\Http\Resources\V1\Permissions::SUPPORT_LINK_CLIENT],
                                                  //          "function"=>"addClients",
                                                  //          "icon"=> "fas fa-users",
                                                  //          "tooltip_title"=>"Ver clientes"
                                                  //  ],
                                                    [
                                                            "permission"=>[\App\Http\Resources\V1\Permissions::SUPPORT_ENABLE_PQR],
                                                            "function"=>"enablePqrSupport",
                                                            "icon"=> "fas fa-bell",
                                                            "tooltip_title"=>"Activar usuario para Pqr",
                                                            "conditional"=>"supportPqrDisabled"
                                                    ],
                                                     [
                                                            "function"=>"delete",
                                                            "icon"=> "fas fa-trash",
                                                            "tooltip_title"=>"Eliminar usuario",
                                                    ],
                                                    [
                                                            "permission"=>[\App\Http\Resources\V1\Permissions::SUPPORT_ENABLE_PQR],
                                                            "function"=>"disablePqrSupport",
                                                            "icon"=> "fas fa-bell-slash",
                                                            "tooltip_title"=>"Activar usuario para Pqr",
                                                            "conditional"=>"supportPqrEnabled"
                                                    ],
                                                ]
                                    ],
               "table_rows"=>$data

           ])
</div>

