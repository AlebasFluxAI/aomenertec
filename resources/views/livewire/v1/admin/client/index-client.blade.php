
<div class="login">
    @section("header") {{--extended app.blade--}}

    @endsection


    @include("partials.v1.title",[
          "second_title"=>"de clientes",
          "first_title"=>"Listado"
      ])

    @if(\Illuminate\Support\Facades\Auth::user()->admin || \Illuminate\Support\Facades\Auth::user()->networkOperator)

        @include("partials.v1.table_nav",
               [
                   "mt"=>2,
                   "nav_options"=>[
                          ["button_align"=>"right",
                          "click_action"=>"",
                          "button_content"=>"Crear nuevo",
                          "button_icon"=>"fa-solid fa-plus",
                          "target_route"=>"v1.admin.client.add.client",
                          ],

                      ]
              ])
    @endif


        @include("partials.v2.table.primary-table",[
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
                       "col_name" =>"Operador de red",
                       "col_data" =>"networkOperator.name",
                       "col_filter"=>false
                   ],

                ],
                 "table_actions"=>[
                                    "details"=>"details",
                                    "edit"=>"edit",
                                    "delete"=>"delete",
                                    "customs"=>[
                                       [
                                        "function"=>"settings",
                                        "tooltip_title"=>"Configuración de equipos",
                                        "icon"=>"fas fa-gear"
                                        ],
                                        [
                                            "redirect"=>[
                                                        "route"=>"v1.admin.client.monitoring",
                                                        "binding"=>"client"
                                                  ],
                                                "icon"=>"fa fa-connectdevelop",
                                                "tooltip_title"=>"Monitoreo",
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
