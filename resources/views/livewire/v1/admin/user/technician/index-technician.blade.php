<div class="login">
    @section("header") {{--extended app.blade--}}

    @endsection

    @include("partials.v1.title",[
          "second_title"=>"de tecnicos",
          "first_title"=>"Listado"
      ])



    @include("partials.v1.table_nav",
           ["mt"=>2,
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
                       "col_data" =>"phone",
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
                                                        "permission"=>[\App\Http\Resources\V1\Permissions::TECHNICIAN_SHOW],
                                                        "function"=>"detail",
                                                        "icon"=>"fas fa-search",
                                                        "tooltip_title"=>"Detalles"
                                                    ],
                                                    [

                                                            "permission"=>[\App\Http\Resources\V1\Permissions::TECHNICIAN_EDIT],
                                                            "function"=>"edit",
                                                            "icon"=>"fas fa-pencil",
                                                            "tooltip_title"=>"Editar"
                                                    ],
                                                    [
                                                        "permission"=>[\App\Http\Resources\V1\Permissions::TECHNICIAN_LINK_CLIENT],
                                                        "function"=>"addClients",
                                                        "icon"=>"fas fa-users",
                                                        "tooltip_title"=>"Ver clientes"
                                                    ],
                                                    [
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::TECHNICIAN_LINK_EQUIPMENT],
                                                       "redirect"=>[
                                                                   "route"=>"administrar.v1.usuarios.tecnicos.agregar_equipos",
                                                                   "binding"=>"technician"
                                                             ],
                                                         "icon"=>"fas fa-laptop-medical",
                                                         "tooltip_title"=>"Asociar tipos de equipos",

                                                  ],
                                                ],

                                    ],
                                                /* Le dice al componente tabla las acciones que tendra la columna de acciones en la tabla [
                                                _edit_button=>{ruta para redireccionar a edicion}
                                                _delete_button => {boton de borrado, siempre tomando como identificador la primera colunma de la tabla - ID}
                                                  ]*/
               "table_rows"=>$data

           ])
</div>

