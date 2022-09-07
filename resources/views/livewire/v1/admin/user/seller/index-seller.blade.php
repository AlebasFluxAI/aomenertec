@if($view_header??true)
    <div class="login">
        @section("header")
            {{--extended app.blade--}}

        @endsection

        @include("partials.v1.title",[
              "second_title"=>"de vendedores",
              "first_title"=>"Listado"
          ])
        @endif


        @include("partials.v1.table_nav",
               [
               "nav_options"=>[
                          ["button_align"=>"right",
                           "permission"=>[\App\Http\Resources\V1\Permissions::SELLER_CREATE],
                          "click_action"=>"",
                          "button_content"=>"Crear nuevo",
                          "icon"=>"fa-solid fa-plus",
                        "target_route"=>"administrar.v1.usuarios.vendedores.agregar",
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
                           "col_name" =>"Identificacion",
                           "col_data" =>"identification",
                           "col_filter"=>true
                       ],
                       [
                           "col_name" =>"Telefono",
                           "col_data" =>"phone",
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
                                                                "permission"=>[\App\Http\Resources\V1\Permissions::SELLER_SHOW],
                                                                "redirect"=>[
                                                                       "route"=>"administrar.v1.usuarios.vendedores.detalles",
                                                                       "binding"=>"seller"
                                                                 ],
                                                                "icon"=>"fas fa-search",
                                                                "tooltip_title"=>"Detalles"
                                                        ],
                                                        [

                                                                 "permission"=>[\App\Http\Resources\V1\Permissions::SELLER_EDIT],
                                                                 "redirect"=>[
                                                                       "route"=>"administrar.v1.usuarios.vendedores.editar",
                                                                       "binding"=>"seller"
                                                                 ],
                                                                "icon"=>"fas fa-pencil",
                                                                "tooltip_title"=>"Editar"
                                                        ],
                                                        [
                                                                 "permission"=>[\App\Http\Resources\V1\Permissions::SELLER_LINK_CLIENT],
                                                                 "redirect"=>[
                                                                        "route"=>"administrar.v1.usuarios.vendedores.agregar_clientes",
                                                                        "binding"=>"seller"
                                                                  ],
                                                                 "icon"=>"fas fa-users",
                                                                  "tooltip_title"=>"Gestionar clientes"
                                                         ],
                                                         [
                                                                 "permission"=>[\App\Http\Resources\V1\Permissions::SELLER_MANAGE_PURCHASE],
                                                                 "redirect"=>[
                                                                        "route"=>"administrar.v1.usuarios.vendedores.recargas.historico",
                                                                        "binding"=>"seller"
                                                                  ],
                                                                 "icon"=>"fas fa-money-check",
                                                                  "tooltip_title"=>"Historial de ventas"
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
